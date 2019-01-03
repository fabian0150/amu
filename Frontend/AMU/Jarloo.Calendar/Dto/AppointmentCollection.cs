using AMU.Dto;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Jarloo.Calendar.Dto
{
    class AppointmentCollection
    {
        private List<Appointment> appointments;
        public List<Appointment> Appointments { get; set; }
    }
}
