using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AMU.Dto
{
    public class Location
    {
        public int ID { get; set; }
        public string Name { get; set; }
        public DateTime Record_Date { get; set; }
        public string Address { get; set; }
        public int Contact_Person_ID { get; set; }

        public override string ToString()
        {
            return Name;
        }
    }
}
