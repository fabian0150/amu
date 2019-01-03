using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AMU.Dto
{
    class Appointment
    {
        public int id { get; set; }
        public int band_id { get; set; }
        public string band_name { get; set; }
        public int location_id { get; set; }
        public string location_address { get; set; }
        public string location_name { get; set; }
        public DateTime appointment_date { get; set; }
        public DateTime record_date { get; set; }
    }
}
