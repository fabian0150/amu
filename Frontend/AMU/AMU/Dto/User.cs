using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AMU.Dto
{
    public class User
    {
        public int ID { get; set; }
        public string Name { get; set; }
        public string Phone_Number { get; set; }
        public string Address { get; set; }
        public string Mail { get; set; }
        public string Website_Url { get; set; }
        public string Notes { get; set; }
        public int User_Type { get; set; }
        public string User_Description { get; set; }
        public string Username { get; set; }
        public DateTime Record_Date { get; set; }

    }
}
