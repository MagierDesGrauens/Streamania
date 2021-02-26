class VideoPlugin
{
    constructor() {
        this.messageSelector = '.room__message';
        this.joinSelector = '.room__join';
        this.joinButtonSelector = '.room__join-button';
        this.formSelector = '.room__link-form';
        this.linkSelector = '.room__link';
        this.contentSelector = '.room__content';
        this.userListSelector = '.room__user-list';

        this.messageHiddenCls = 'room__message--hidden';
        this.joinVisibleCls = 'room__join--visible';
        this.contentVisibleCls = 'room__content--visible';

        this.listeners = [];
        this.roomId = 0;
        this.socket = null;
        this.player = null;
        this.playerReady = false;
        this.playerState = -1;
        this.playerLoadedVideo = false;
        this.canSendVideoState = true;
    }

    init(host, port, roomId) {
        this.roomId = roomId;
        this.startupVideoSrc = '';

        this.messageEl = document.querySelector(this.messageSelector);
        this.joinEl = document.querySelector(this.joinSelector);
        this.joinButtonEl = document.querySelector(this.joinButtonSelector);
        this.formEl = document.querySelector(this.formSelector);
        this.linkEl = document.querySelector(this.linkSelector);
        this.contentEl = document.querySelector(this.contentSelector);
        this.userListEl = document.querySelector(this.userListSelector);

        this.socket = new SocketPlugin(host, port);
        this.socket.onSocketOpen = this.onSocketOpen.bind(this);
        this.socket.onSocketMessage = this.onSocketMessage.bind(this);
        this.socket.onSocketClose = this.onSocketClose.bind(this);
        this.socket.init();

        this.showMessage('Verbinde zum Server...');

        this.registerEvents();
    }

    registerEvents() {
        this.joinButtonEl.addEventListener('click', this.onClickJoinRoom.bind(this));
        this.formEl.addEventListener('submit', this.onLinkChanged.bind(this));
    }

    onSocketOpen() {
        this.hideMessage();

        this.joinEl.classList.add(this.joinVisibleCls);

        this.socket.send(`room|connect|${this.roomId}|${CookiePlugin.get('PHPSESSID')}`);
    }

    onSocketClose() {
        this.showMessage('Verbindung zum Server fehlgeschlagen.');
        this.contentEl.remove();
        this.joinEl.remove();
    }

    onSocketMessage(event) {
        this.handleMessage(event.data);
    }

    onClickJoinRoom() {
        // Call listener for video actions
        setInterval(this.playerListener.bind(this), 100);

        this.joinEl.classList.remove(this.joinVisibleCls);
        this.contentEl.classList.add(this.contentVisibleCls);
    }

    initPlayer() {
        this.player = new YT.Player('watchtogehter__video-player', {
            height: '360',
            width: '640',
            videoId: this.startupVideoSrc,
            events: {
                'onReady': this.onPlayerReady.bind(this),
                'onStateChange': this.onPlayerStateChange.bind(this)
            }
        });
    }

    onPlayerReady() {
        console.log('YouTube Video-Player ready.');
        this.playerReady = true;
    }

    onPlayerStateChange(event) {
        this.playerState = event.data;

        if (!this.playerLoadedVideo) {
            // Block playing-state after a video was loaded
            if (this.playerState == YT.PlayerState.PLAYING) {
                this.playerLoadedVideo = true;
            }
        } else {
            if (this.playerState == YT.PlayerState.PLAYING && this.canSendVideoState) {
                this.socket.send(`video|state|playing|${this.player.getCurrentTime()}|${Date.now()}`);
            } else if (this.playerState == YT.PlayerState.PAUSED && this.canSendVideoState) {
                this.socket.send(`video|state|stopped|${this.player.getCurrentTime()}|${Date.now()}`);
            }
        }
    }

    playerListener() {
        let me = this;

        me.listeners.forEach(listener => {
            let done = false;
            let content = listener.getContent();

            if (me.playerReady) {
                if (listener.getAction() === 'setVideoSrc') {
                    me.playerState = -1;
                    me.playerLoadedVideo = false;

                    me.player.loadVideoById({
                        'videoId': content
                    });

                    done = true;
                } else if (
                    listener.getAction() === 'setVideoState'
                    && me.playerState !== YT.PlayerState.UNSTARTED
                    && (
                        me.playerState !== YT.PlayerState.BUFFERING
                        || me.playerLoadedVideo
                    )
                ) {
                    let videoTime = parseFloat(content[1]);

                    me.canSendVideoState = false;

                    if (content[0] === 'stopped') {
                        me.player.seekTo(videoTime);
                        me.player.pauseVideo();
                    } else if (content[0] === 'playing') {
                        let playerTime = videoTime + (Date.now() - parseFloat(content[2])) / 1000;
                        me.player.seekTo(playerTime);
                        me.player.playVideo();
                    }

                    setTimeout(() => { me.canSendVideoState = true; }, 1000);

                    done = true;
                }
            }

            // Clear for new actions
            if (done) {
                listener.clear();
            }
        });

        me.listeners = me.listeners.filter(listener => !listener.isCleared());
    }

    onLinkChanged(e) {
        e.preventDefault();

        let videoId = this.linkEl.value.split('v=')[1];
        let ampersandPosition = videoId.indexOf('&');

        if(ampersandPosition != -1) {
            videoId = videoId.substring(0, ampersandPosition);
        }

        this.socket.send(`video|load|${videoId}`);
    }

    handleMessage(message) {
        let commands = message.split('|');
        let command = commands[0];

        if (command === 'video') {
            this.handleVideoMessage(commands);
        } else if (command === 'users') {
            this.handleUserMessage(commands);
        }
    }

    handleVideoMessage(commands) {
        if (commands[1] === 'src') {
            this.listeners.push(new ListenerPlugin('setVideoSrc', commands[2]));
        } else if (commands[1] === 'state') {
            this.listeners.push(new ListenerPlugin('setVideoState', [
                commands[2],
                commands[3],
                commands[4]
            ]));
        }
    }

    handleUserMessage(commands) {
        let uid = commands[2],
            userList = this.userListEl.querySelectorAll('.room__user');
        
        if (commands[1] === 'add') {
            let uname = commands[3],
                userInList = false;

            userList.forEach(user => {
                if (user.dataset.userId === uid) {
                    userInList = true;
                }
            });

            if (!userInList) {
                this.userListEl.innerHTML += `<div class="room__user" data-user-id="${uid}">
                    ${uname}
                </div>`;
            }
        } else if (commands[1] === 'leave') {
            userList.forEach(user => {
                if (user.dataset.userId === uid) {
                    user.remove();
                }
            });
        }
    }

    showMessage(message) {
        this.messageEl.innerHTML = message;
        this.messageEl.classList.remove(this.messageHiddenCls);
    }

    hideMessage() {
        this.messageEl.classList.add(this.messageHiddenCls);
    }
}
