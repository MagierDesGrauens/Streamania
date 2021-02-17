using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Watch2Gether
{
    class IniReader
    {
        private List<IniReaderGroup> groups;

        public IniReader()
        {
            groups = new List<IniReaderGroup>();
        }

        public bool parse(string file)
        {
            if (System.IO.File.Exists(file))
            {
                string[] lines = System.IO.File.ReadAllLines(file);
                string newGroup = "";
                string currentGroup = "";

                for (int i = 0; i < lines.Length; i++)
                {
                    if (lines[i].StartsWith("["))
                    {
                        newGroup = lines[i].Substring(1, lines[i].Length - 2);
                    }

                    if (newGroup != "")
                    {
                        groups.Add(new IniReaderGroup(newGroup));
                        currentGroup = newGroup;
                        newGroup = "";
                    }
                    else
                    {
                        IniReaderGroup g = groups.Where(group => group.Group == currentGroup).FirstOrDefault();

                        if (g != null)
                        {
                            int equalSignPos = lines[i].IndexOf("=");

                            if (equalSignPos > -1)
                            {
                                string key = lines[i].Substring(0, equalSignPos).Trim();
                                string value = lines[i].Substring(equalSignPos + 1, lines[i].Length - equalSignPos - 1).Trim();

                                // Trim " at start
                                if (value.IndexOf((char)34) == 0)
                                {
                                    value = value.Substring(1, value.Length - 1);
                                }

                                // Trim " at end
                                if (value.IndexOf((char)34) == value.Length - 1)
                                {
                                    value = value.Substring(0, value.Length - 1);
                                }

                                g.Add(key, value);
                            }
                        }
                    }
                }

                return true;
            }

            return false;
        }

        public string get(string group, string key)
        {
            IniReaderGroup g = groups.Where(gr => gr.Group == group).FirstOrDefault();

            if (g != null)
            {
                return g.Get(key);
            }

            return "";
        }
    }

    class IniReaderGroup
    {
        public string Group;
        List<IniReaderKey> keys;

        public IniReaderGroup(string group)
        {
            keys = new List<IniReaderKey>();
            Group = group;
        }

        public void Add(string key, string value)
        {
            keys.Add(new IniReaderKey(key, value));
        }

        public string Get(string key)
        {
            IniReaderKey readerKeyValue = keys.Where(readerKey => readerKey.Key == key).FirstOrDefault();

            if (readerKeyValue != null)
            {
                return readerKeyValue.Value;
            }

            return "";
        }
    }

    class IniReaderKey
    {
        public string Key;
        public string Value;

        public IniReaderKey(string key, string value)
        {
            Key = key;
            Value = value;
        }
    }
}
