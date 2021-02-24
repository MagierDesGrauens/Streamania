// Vendor Plugins
vendor/jquery.js
vendor/bootstrap.js


// Streamania Plugins
plugins/icons-plugin.js
plugins/cookie-plugin.js
plugins/socket-plugin.js

/*
*     <!--

    Test Verbindung zumWebsocket-Server (z. B. via JavaScript) herstellen.

Funktion zur Verwaltung der erhaltenen Coammds vom Server: handleComand(command) - damit kannst du dann Befehle simulieren und später an den Server anbinden.

Verbindung via raum-id (von der URL: ?site=room&id=RAUMID)

Send: room|connect|ROOM_ID|PHPSESSIONID
Returns:
video|state|play / stop|TIMESTAMP_OF_VIDEO_SECONDS|TIMESTAMP_OF_VIDEO_STARTED
video|src|VIDEO_SRC
users|add|USERNAME1|USERNAME2|...
Steuerbarer Videoplayer anbinden, der YouTube Videos abspielen kann über HTML-Tag <video> könnte das gehen (sofern YouTube VIdeos dort eingebunden werden können, ansonsten nehmen wir andere Videos von anderen Platformen) (evtl. direkt der embed YouTube Player, sofern er eine Steuerung von [Start/Stop, Zeit setzen] via JS zulässt)

Input feld für Video-Link + Button "Video laden"

Button "Video laden" klick:

Lädt das Video - Command: video|load|VIDEO_SRC_LINK
sendet dem Server: Neue Video-URL
Nutzer startet/pausiert das Video sendet dem Server:

Start: video|state|playing
Stop: video|state|stopped
Funktionen von Server-Antwort schreiben für:

Play/Stop Video -> Comands: video|state|play / stop|TIMESTAMP_OF_VIDEO_SECONDS|TIMESTAMP_OF_VIDEO_STARTED
Zeit setzen Video -> Command: video|state|play / stop|TIMESTAMP_OF_VIDEO_SECONDS|TIMESTAMP_OF_VIDEO_STARTED
Video setzen -> video|src|VIDEO_SRC
setzt video zeit auf 0 Sekunden
Anderer Nutzer joint dem Room:

Server sendet an dich: users|add|USERNAME
später:

Chat mit Links, die abgepsielt wurden
wird im Chat ein Link gepostet, wird das Video geladen

    -->
* */