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
        public int Port = 2021;
        public string Ip = "";

        public void Connect(bool onPublicIp)
        {
            Helper.Log("Start server...");
            appServer = new WebSocketServer();

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
                Helper.Log("Setting up mysql...");

                bool init = Sql.init(); //Connect allow from other IP: http://stackoverflow.com/a/3507278
                if (!init)
                {
                    Console.ForegroundColor = ConsoleColor.Red;
                    Helper.Log("Error", ConsoleColor.Red, "Mysql couldn't be initialized.");
                    Console.ReadKey();
                    return;
                }

                Console.WriteLine("Server started.\n\nCommands:\nPress ENTER to quit the Server.\n");
                Console.ForegroundColor = ConsoleColor.White;
            }
        }

        private void appServer_NewMessageReceived(WebSocketSession session, string message)
        {
            string[] strCommands = message.Split('|');
            Helper.Log("Message Received", message);

            string strCommand = strCommands[0];

            // room | connect | ROOM_ID | PHPSESSIONID
            if (strCommand == "room")
            {
                HandleRoom(strCommands);
            }
        }

        static void appServer_NewSessionConnected(WebSocketSession s)
        {
            Helper.Log("New session", s.SessionID);
            s.Send("connected!");
        }

        static void appServer_SessionCLosed(WebSocketSession wss, CloseReason cr)
        {
            Helper.Log("Session closed,..");
            // Send request to all active users
        }


        static void HandleRoom(string[] commands)
        {

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
}
