using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AMU.Dto
{
    public class Appointment
    {
        public int ID { get; set; }
        public int Band_ID { get; set; }
        public string Band_Name { get; set; }
        public int Location_ID { get; set; }
        public string Location_Address { get; set; }
        public string Location_Name { get; set; }
        public DateTime Appointment_Date { get; set; }
        public DateTime Record_Date { get; set; }

        public override string ToString()
        {
            return Appointment_Date.ToString("dd.MM.yyyy") + ": " + Location_Name;
        }

    }
}
