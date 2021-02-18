class SocketPlugin
{
    constructor(host, port) {
        this.host = host;
        this.port = port;
        this.socket = null;
    }

    init() {
        console.log('Connecting to the Server...');

        this.socket = new WebSocket(this.host + ':' + this.port);
        this.socket.onopen = this.onSocketOpen;
        this.socket.onmessage = this.onSocketMessage;
        this.socket.onclose = this.onSocketClose;
        this.socket.onerror = this.onSocketError;
    }

    onSocketOpen() {
        console.log('Connection to the Server established.');
    }

    onSocketMessage(event) {
        console.log(`Received message: ${event.data}`);
    }

    onSocketClose() {
        console.error('The connection to the server aborted.');
    }

    onSocketError(error) {
        console.error(`${error.message}`);
    }

    send(message) {
        this.socket.send(message);
    }
}
