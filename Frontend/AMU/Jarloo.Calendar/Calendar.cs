/*
    Jarloo
    http://www.jarloo.com
 
    This work is licensed under a Creative Commons Attribution-ShareAlike 3.0 Unported License  
    http://creativecommons.org/licenses/by-sa/3.0/ 

*/
using AMU.Dto;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Net;
using System.Windows;
using System.Windows.Controls;

namespace Jarloo.Calendar
{
    public class Calendar : Control
    {
        public ObservableCollection<Day> Days { get; set; }
        public ObservableCollection<string> DayNames { get; set; }
        public static readonly DependencyProperty CurrentDateProperty = DependencyProperty.Register("CurrentDate", typeof(DateTime), typeof(Calendar));

        public event EventHandler<DayChangedEventArgs> DayChanged;
        List<Appointment> appointmentList;


        public DateTime CurrentDate
        {
            get { return (DateTime)GetValue(CurrentDateProperty); }
            set { SetValue(CurrentDateProperty, value); }
        }

        static Calendar()
        {
            DefaultStyleKeyProperty.OverrideMetadata(typeof(Calendar), new FrameworkPropertyMetadata(typeof(Calendar)));
        }

        public Calendar()
        {
            DataContext = this;
            CurrentDate = DateTime.Today;

            //this won't work in Australia where they start the week with Monday. So remember to test in other 
            //places if you plan on using it. 
            DayNames = new ObservableCollection<string> { "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat" };

            Days = new ObservableCollection<Day>();
            BuildCalendar(DateTime.Today);
        }

        public void BuildCalendar(DateTime targetDate)
        {
            Days.Clear();

            //Calculate when the first day of the month is and work out an 
            //offset so we can fill in any boxes before that.
            DateTime d = new DateTime(targetDate.Year, targetDate.Month, 1);
            int offset = DayOfWeekNumber(d.DayOfWeek);
            if (offset != 1) d = d.AddDays(-offset);
            GetAppointments();
            //Show 6 weeks each with 7 days = 42
            for (int box = 1; box <= 42; box++)
            {
                Day day = new Day { Date = d, Enabled = true, IsTargetMonth = targetDate.Month == d.Month };
                /* Abfrage nach den Terminen wird hier vorher eingebaut und hier auf das Datum geprüft */
                
                //if (day.Date == DateTime.Today.AddDays(-2))
                //{
                //    day.Notes = "Vorgestern";
                //}
                for (int i = 0; i < appointmentList.Count;i++) {
                    if (day.Date.Date == appointmentList[i].Appointment_Date.Date) {
                        day.Notes.Add(appointmentList[i]);
                    }
                }
                day.PropertyChanged += Day_Changed;
                
                day.IsToday = d == DateTime.Today;
                Days.Add(day);
                d = d.AddDays(1);
            }
            
        }

        

        private void Day_Changed(object sender, PropertyChangedEventArgs e)
        {
            if (e.PropertyName != "Notes") return;
            if (DayChanged == null) return;

            DayChanged(this, new DayChangedEventArgs(sender as Day));
        }

        private static int DayOfWeekNumber(DayOfWeek dow)
        {
            return Convert.ToInt32(dow.ToString("D"));
        }
        //get the JSON String from the URL
        public void GetAppointments()
        {
            appointmentList = new List<Appointment>();
            var rawJSON = new WebClient().DownloadString("https://amu.tkg.ovh/json/appointment/_getAppointments.php");
            var resultObjects = JsonConvert.DeserializeObject(rawJSON);
            JArray asdf = JArray.Parse(rawJSON);

            Appointment appointment;
            Console.WriteLine("--------------");
            //AppointmentCollection appointmentCollection = JsonConvert.DeserializeObject<AppointmentCollection>(obj);
            for(int i = 0; i < asdf.Count; i++)
            {
                JObject item = (JObject)asdf[i];
               
                appointment = new Appointment
                {
                    ID = (int)item.GetValue("ID"),
                    Band_ID = (int)item.GetValue("band_id"),
                    Band_Name = (string)item.GetValue("band_name"),
                    Location_ID = (int)item.GetValue("location_id"),
                    Location_Address = (string)item.GetValue("location_address"),
                    Location_Name = (string)item.GetValue("location_name"),
                    Appointment_Date = (DateTime)item.GetValue("appointment_date"),
                    Record_Date = (DateTime)item.GetValue("record_date")

                };
                appointmentList.Add(appointment);
            }



        }

        public class DayChangedEventArgs : EventArgs
        {
            public Day Day { get; private set; }

            public DayChangedEventArgs(Day day)
            {
                this.Day = day;
            }
        }


    }
}