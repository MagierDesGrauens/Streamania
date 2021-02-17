using MySql.Data.MySqlClient;
using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;

namespace Watch2Gether
{
    public static class Sql
    {
        public static MySqlConnection con;

        public static bool init()
        {

            string configPath = "../../../../../bin/config.ini";

            IniReader ini = new IniReader();
            ini.parse(configPath);
            string server = ini.get("Database", "host");
            string database = ini.get("Database", "db");
            string user = ini.get("Database", "user");
            string password = ini.get("Database", "password");


            con = new MySqlConnection("SERVER=" + server + ";DATABASE=" + database + ";UID=" + user + ";PASSWORD=" + password + ";");

            return OpenConnection();
        }

        private static bool OpenConnection()
        {
            try
            {
                con.Open();
                return true;
            }
            catch (MySqlException ex)
            {
                //When handling errors, you can your application's response based 
                //on the error number.
                //The two most common error numbers when connecting are as follows:
                //0: Cannot connect to server.
                //1045: Invalid user name and/or password.
                //1049: Invalid Database
                switch (ex.Number)
                {
                    case 0:
                        Console.WriteLine("Cannot connect to server");
                        break;

                    case 1045:
                        Console.WriteLine("Invalid username/password, please try again");
                        break;

                    case 1049:
                        Console.WriteLine("Invalid database, please try again");
                        break;

                    default:
                        Console.WriteLine("An error appeard. Num: " + ex.Number);
                        break;
                }
                return false;
            }
        }

        public static bool CloseConnection()
        {
            try
            {
                con.Close();
                return true;
            }
            catch (MySqlException ex)
            {
                Console.WriteLine(ex.Message);
                return false;
            }
        }
        
        public static void UpdateConnection()
        {
            bool forceReconnect = false;

            //Test SQL
            try
            {
                MySqlCommand cmd = new MySqlCommand("SELECT version()", con);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Helper.Log("[Test Connection Error] " + ex.Message);
                forceReconnect = true; //If closing connection fails

                CloseConnection();
            }

            if (con.State != ConnectionState.Open || forceReconnect)
            {
                Helper.Log(
                    "SQL",
                    ConsoleColor.White,
                    "Reconnecting to MYSQL"
                );

                bool init = Sql.init(); //Connect allow from other IP: http://stackoverflow.com/a/3507278
                
                if (!init)
                {
                    Helper.Log("SQL", ConsoleColor.Red, "Mysql couldn't be initialized. [FAILED]");
                    Console.ReadKey();
                    return;
                }

                Helper.Log("Connection reopned\n", false);
            }
        }

        public static void KeepAlive()
        {
            try
            {
                MySqlCommand cmd = new MySqlCommand("SELECT version()", con);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Console.WriteLine("\n" + DateTime.Now.Hour.ToString().PadLeft(2, '0') + ":" + DateTime.Now.Minute.ToString().PadLeft(2, '0') + ":" + DateTime.Now.Second.ToString().PadLeft(2, '0') + " [Keep Alive Error] " + ex.Message);
            }
        }

        //No Query Execution
        public static void nquery(string strQuery)
        {
            try
            {
                UpdateConnection();

                MySqlCommand cmd = new MySqlCommand(strQuery, con);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Helper.Log("NoExecutionQuery Error", ConsoleColor.Red, ex.Message + " | " + strQuery);
            }
        }
        public static List<QueryResult> query(string strQuery)
        {
            List<QueryResult> list = new List<QueryResult>();

            try
            {
                UpdateConnection();

                MySqlCommand cmd = new MySqlCommand(strQuery, con);
                MySqlDataReader dataReader = cmd.ExecuteReader();
                
                while (dataReader.Read())
                {
                    QueryResult qrAdd = new QueryResult();
                    for (int i = 0; i < dataReader.FieldCount; i++)
                        qrAdd.setData(dataReader.GetName(i), SafeGetString(dataReader, i));
                    list.Add(qrAdd);
                }

                dataReader.Close();
            }
            catch (Exception ex)
            {
                Helper.Log("Query Error", ConsoleColor.Red, ex.Message + " | " + strQuery);
            }

            return list;
        }
        //Single Query
        public static QueryResult squery(string strQuery)
        {
            QueryResult qrAdd = new QueryResult();
            try
            {
                UpdateConnection();

                MySqlCommand cmd = new MySqlCommand(strQuery, con);
                MySqlDataReader dataReader = cmd.ExecuteReader();

                while (dataReader.Read())
                {
                    for (int i = 0; i < dataReader.FieldCount; i++)
                        qrAdd.setData(dataReader.GetName(i), SafeGetString(dataReader, i));
                }

                dataReader.Close();
            }
            catch (Exception ex)
            {
                Helper.Log("SingleQuery Error", ConsoleColor.Red, ex.Message + " | " + strQuery);
            }

            return qrAdd;
        }
        public static string SafeGetString(MySqlDataReader reader, int colIndex)
        {
            if (!reader.IsDBNull(colIndex))
            {
                string ret = reader.GetString(colIndex);
                if (ret == "False") ret = "0"; //For tinyint(1)
                else if (ret == "True") ret = reader.GetByte(colIndex).ToString();

                return ret;
            }
            else
                return string.Empty;
        }

        public static long Insert(string strCmd)
        {
            UpdateConnection();

            string q = "INSERT INTO " + strCmd;
            MySqlCommand cmd = new MySqlCommand(q, con);
            cmd.ExecuteNonQuery();

            return cmd.LastInsertedId;
        }
        public static void Update(string strCmd)
        {
            string q = "UPDATE " + strCmd;
            nquery(q);
        }
        public static void Delete(string strCmd)
        {
            string q = "DELETE FROM " + strCmd;
            nquery(q);
        }
    }
}
