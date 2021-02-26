class ListenerPlugin
{
    constructor(action, content) {
        this.cleared = false;
        this.action = action;
        this.content = content;
    }

    getAction() {
        return this.action;
    }

    getContent() {
        return this.content;
    }

    clear() {
        this.cleared = true;
        this.action = '';
    }

    isCleared() {
        return this.cleared;
    }
}
