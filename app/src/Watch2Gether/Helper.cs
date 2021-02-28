using System;
using System.Collections.Generic;
using System.Text;

namespace Watch2Gether
{
    class Helper
    {
        public static void Log(string text, bool newLine = true, ConsoleColor color = ConsoleColor.White)
        {
            ConsoleColor actualColor = Console.ForegroundColor;

            Console.ForegroundColor = color;

            Console.Write(text);

            if (newLine)
            {
                Console.Write("\n");
            }

            Console.ForegroundColor = actualColor;
        }

        public static void Log(string text, ConsoleColor color = ConsoleColor.White)
        {
            Log(text, true, color);
        }

        public static void Log(string text)
        {
            Log(text, true, ConsoleColor.White);
        }

        public static void Log(string title, string delimiter, ConsoleColor color = ConsoleColor.White, params string[] values)
        {
            Log(DateTime.Now.Hour.ToString().PadLeft(2, '0') + ":" + DateTime.Now.Minute.ToString().PadLeft(2, '0') + ":" + DateTime.Now.Second.ToString().PadLeft(2, '0') + " [" + title + "]", false, color);

            delimiter = " " + delimiter;

            for (int i = 0; i < values.Length; i++)
            {
                if (i - 1 == values.Length)
                {
                    delimiter = "";
                }

                if (i > 0)
                {
                    Log(" ", false, color);
                }

                Log(values[i] + delimiter, false, color);
            }

            Log("", true, color);
        }

        public static void Log(string title, ConsoleColor color = ConsoleColor.White, params string[] values)
        {
            Log(DateTime.Now.Hour.ToString().PadLeft(2, '0') + ":" + DateTime.Now.Minute.ToString().PadLeft(2, '0') + ":" + DateTime.Now.Second.ToString().PadLeft(2, '0') + " [" + title + "]", false, color);
            
            for (int i = 0; i < values.Length; i++)
            {
                Log(" " + values[i], false, color);
            }

            Log("", true, color);
        }

        public static void Log(string title, params string[] values)
        {
            Log(title, ConsoleColor.White, values);
        }
    }
}
