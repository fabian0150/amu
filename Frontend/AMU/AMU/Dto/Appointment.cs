using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AMU.Dto
{
    class Appointment
    {
        public int ID { get; set; }
        public int Band_Id { get; set; }
        public string Band_Name { get; set; }
        public int Location_ID { get; set; }
        public string Location_Adresse { get; set; }
        public string Location_Name { get; set; }
        public DateTime Appointment_Date { get; set; }
        public DateTime Record_Date { get; set; }
    }
}
