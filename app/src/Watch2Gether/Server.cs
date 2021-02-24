using SuperSocket.SocketBase;
using SuperWebSocket;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;

namespace Watch2Gether
{
    class Server
    {
        private WebSocketServer appServer;
        public int Port = 0;
        public string Ip = "";
        List<Connection> connections;

        public void Connect(bool onPublicIp)
        {
            IniReader ini = new IniReader();
            ini.parse("../../../../../bin/config.ini");

            Port = Convert.ToInt32(ini.get("Watch2Gether", "port"));

            Helper.Log("===== STREAMNIA - WATCH2GETHER SERVER =====\n");
            Helper.Log("Starte Server auf Port " + Port + "...");

            appServer = new WebSocketServer();
            connections = new List<Connection>();

            //Setup the appServer
            if (!appServer.Setup(Port)) //Setup with listening port
            {
                Helper.Log("Server konte nicht auf Port " + Port + " eingerichtet werden.", ConsoleColor.Red);
                return;
            }

            appServer.NewMessageReceived += new SessionHandler<WebSocketSession, string>(appServer_NewMessageReceived);
            appServer.NewSessionConnected += new SessionHandler<WebSocketSession>(appServer_NewSessionConnected);
            appServer.SessionClosed += new SessionHandler<WebSocketSession, CloseReason>(appServer_SessionCLosed);

            //Try to start the appServer
            if (!appServer.Start())
            {
                Helper.Log("Server starten fehlgeschlagen!", ConsoleColor.Red);
                return;
            }
            else
            {
                //Setup MySQL
                Helper.Log("MySql Verbindung herstellen...");

                bool init = Sql.init(); //Connect allow from other IP: http://stackoverflow.com/a/3507278
                if (!init)
                {
                    Helper.Log("Error", ConsoleColor.Red, "Mysql konnte nicht initialisiert werden.");
                    Console.ReadKey();
                    return;
                }

                // User aus räumen löschen
                Sql.query("DELETE FROM rooms_users");

                Helper.Log("\n===== Setup erfolgreich! =====", true, ConsoleColor.Green);
                Helper.Log("\nDrücke ENTER um den Server zu beenden.");
            }
        }

        private void appServer_NewMessageReceived(WebSocketSession session, string message)
        {
            HandleMessage(message, session);
        }

        private void appServer_NewSessionConnected(WebSocketSession s)
        {
            Helper.Log("New session", s.SessionID);
            s.Send("connected");
        }

        private void appServer_SessionCLosed(WebSocketSession wss, CloseReason cr)
        {
            Helper.Log("Session closed", wss.SessionID);

            Connection con = getConnection(wss);

            // Wenn der Benutzer zu einem Raum gehörte
            if (con != null)
            {
                Sql.query("DELETE FROM rooms_users WHERE room_id='" + con.RoomId + "' AND users_id='" + con.UserId + "'");

                connections.ForEach(c =>
                {
                    if (c.RoomId == con.RoomId)
                    {
                        c.WebSocket.Send("users|leave|" + con.UserId);
                    }
                });
            }
        }

        private void HandleMessage(string message, WebSocketSession wss)
        {
            string[] strCommands = message.Split('|');
            Helper.Log("Message Received", message);

            string strCommand = strCommands[0];

            // room | connect | ROOM_ID | PHPSESSIONID
            if (strCommand == "room")
            {
                HandleRoom(strCommands, wss);
            }
            else if (strCommand == "video")
            {
                HandleVideo(strCommands, wss);
            }
        }

        private void HandleRoom(string[] commands, WebSocketSession wss)
        {
            if (commands[1] == "connect")
            {
                string roomid = commands[2];
                string userid = User.GetIdFromPHPSession(commands[3]);
                string username = "";
                string otherUsernames = "";

                if (userid != "")
                {
                    username = User.GetName(userid);

                    // Wenn der Nutzer nicht schon in der DB im Raum ist, hinzufügen
                    if (!User.IsInRoom(roomid, userid))
                    {
                        User.AddToRoom(roomid, userid);
                    }

                    // Alte Verbindungen entfernen
                    connections.RemoveAll(con => con.UserId == userid);

                    // Socket Connection zur Liste hinzufügen
                    connections.Add(new Connection(wss, userid, commands[2]));

                    // Anderen im Raum mitteilen, das ein Nutzer gejoined ist
                    connections.ForEach(con =>
                    {
                        if (con.RoomId == roomid)
                        {
                            con.WebSocket.Send("users|add|" + userid + "|" + username);

                            wss.Send("users|add|" + con.UserId + "|" + User.GetName(con.UserId));
                        }
                    });

                    wss.Send(GetVideoSrc(roomid));
                    wss.Send(GetVideoState(roomid));
                }
            }
        }

        private void HandleVideo(string[] commands, WebSocketSession wss)
        {
            Connection con = getConnection(wss);

            if (commands[1] == "load")
            {
                Sql.query(
                    "UPDATE rooms SET " +
                    "   video_src='" + commands[2] + "', " +
                    "   video_timestamp='0', " +
                    "   video_started_at='0', " +
                    "   video_state='stopped'" +
                    " WHERE rooms_id='" + con.RoomId + "'"
                );

                connections.ForEach(conn =>
                {
                    conn.WebSocket.Send(GetVideoSrc(con.RoomId));
                    conn.WebSocket.Send(GetVideoState(con.RoomId));
                });
            }

            if (commands[1] == "state")
            {
                string state = commands[2];

                Sql.query(
                    "UPDATE rooms SET " +
                        "video_state='" + commands[2] + "', " +
                        "video_timestamp='" + commands[3] + "', " +
                        "video_started_at='" + commands[4] + "' " +
                    "WHERE rooms_id='" + con.RoomId + "'"
                );

                connections.ForEach(conn =>
                {
                    if (conn.UserId != con.UserId)
                    {
                        conn.WebSocket.Send(GetVideoState(con.RoomId));
                    }
                });
            }
        }

        private string GetVideoState(string roomId)
        {
            // video|state|play / stop|TIMESTAMP_OF_VIDEO_SECONDS|TIMESTAMP_OF_VIDEO_STARTED
            QueryResult qr = Sql.squery("SELECT video_state, video_timestamp, video_started_at FROM rooms WHERE rooms_id='" + roomId + "'");

            return "video|state|" + qr.get("video_state") + "|" + qr.get("video_timestamp").Replace(',', '.') + "|" + qr.get("video_started_at");
        }

        private string GetVideoSrc(string roomId)
        {
            // video|state|play / stop|TIMESTAMP_OF_VIDEO_SECONDS|TIMESTAMP_OF_VIDEO_STARTED
            QueryResult qr = Sql.squery("SELECT video_src FROM rooms WHERE rooms_id='" + roomId + "'");

            return "video|src|" + qr.get("video_src");
        }













        private Connection getConnection(WebSocketSession wss)
        {
            Connection con = connections.Where(c => c.WebSocket.SessionID == wss.SessionID).FirstOrDefault();
            return con;
        }

        private string GetPublicIP()
        {
            string url = "http://checkip.dyndns.org";
            System.Net.WebRequest req = System.Net.WebRequest.Create(url);
            System.Net.WebResponse resp = req.GetResponse();
            System.IO.StreamReader sr = new System.IO.StreamReader(resp.GetResponseStream());
            string response = sr.ReadToEnd().Trim();
            string[] a = response.Split(':');
            string a2 = a[1].Substring(1);
            string[] a3 = a2.Split('<');
            string a4 = a3[0];
            return a4;
        }
    }

    class Connection
    {
        public WebSocketSession WebSocket;
        public string UserId;
        public string RoomId;

        public Connection(WebSocketSession websocket, string userid, string roomid)
        {
            WebSocket = websocket;
            UserId = userid;
            RoomId = roomid;
        }
    }
}
