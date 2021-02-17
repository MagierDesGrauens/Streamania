using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Watch2Gether
{
    public class QueryResult
    {
        List<string> Fields;
        List<string> Data;
        bool _isEmpty;

        public QueryResult()
        {
            Fields = new List<string>();
            Data = new List<string>();
            _isEmpty = true;
        }

        public string get(string field)
        {
            int i = -1;
            bool fieldFound = false;

            Fields.ForEach(delegate (string n)
            {
                if (!fieldFound) i++;
                if (n == field) fieldFound = true;
            });

            if (i == -1) return "";
            return Data[i];
        }

        public string get(int index)
        {
            return Data[index];
        }

        public void setData(string fieldname, string data)
        {
            _isEmpty = false;
            Fields.Add(fieldname);
            Data.Add(data);
        }

        public bool isEmpty()
        {
            return _isEmpty;
        }
    }
}
