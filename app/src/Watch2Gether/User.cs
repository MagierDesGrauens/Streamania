using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Watch2Gether
{
    public static class User
    {
        public static string GetIdFromPHPSession(string PHPSESSID)
        {
            QueryResult r = Sql.squery("SELECT users_id FROM users WHERE session_id = '" + PHPSESSID + "'");

            return r.get("user_id");
        }

        public static string GetName(string id)
        {
            QueryResult qr = Sql.squery("SELECT username FROM users WHERE users_id = '" + id + "'");

            return qr.get("username");
        }

        public static bool IsInRoom(string roomid, string userid)
        {
            QueryResult qr = Sql.squery("SELECT users_id FROM rooms_users WHERE room_id='" + roomid + "' AND users_id='" + userid + "'");
            return qr.get("user_id").Length > 0;
        }

        public static void AddToRoom(string roomid, string userid)
        {
            Sql.query("INSERT INTO rooms_users (room_id, users_id) VALUES ('" + roomid + "', '" + userid + "')");
        }
    }
}
