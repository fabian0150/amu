using System;
using System.Collections.Generic;
using System.Globalization;
using System.Windows;
using System.Linq;
using Jarloo.Calendar;
using AMU.Windows;
using static Jarloo.Calendar.Calendar;
using AMU.Dto;
using System.Net;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using System.Windows.Controls;

namespace AMU_WPF
{
    /// <summary>
    /// Interaktionslogik für MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        public List<Band> bandList;
        public MainWindow()
        {
            InitializeComponent();
            List<string> months = new List<string> { "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" };
            cboMonth.ItemsSource = months;
            //GruppeHinzufuegenWindow asdf = new GruppeHinzufuegenWindow();
            //asdf.Show();

            for (int i = -50; i < 50; i++)
            {
                cboYear.Items.Add(DateTime.Today.AddYears(i).Year);
            }

            cboMonth.SelectedItem = months.FirstOrDefault(w => w == DateTime.Today.ToString("MMMM"));
            cboYear.SelectedItem = DateTime.Today.Year;

            cboMonth.SelectionChanged += (o, e) => RefreshCalendar();
            cboYear.SelectionChanged += (o, e) => RefreshCalendar();
            
            LoadBands();
        }

        private void LoadBands()
        {
            bandList = new List<Band>();
            var rawJSON = new WebClient().DownloadString("https://amu.tkg.ovh/json/band/_getBands.php");
            var resultObjects = JsonConvert.DeserializeObject(rawJSON);
            JArray asdf = JArray.Parse(rawJSON);

            Band band;
            Console.WriteLine("--------------");
            //AppointmentCollection appointmentCollection = JsonConvert.DeserializeObject<AppointmentCollection>(obj);
            for (int i = 0; i < asdf.Count; i++)
            {
                JObject item = (JObject)asdf[i];

                band = new Band
                {


                    Name = (string)item.GetValue("name"),
                    Logo_Path = (string)item.GetValue("logo_path"),
                    Website_Url = (string)item.GetValue("website_url"),
                    Leader_Username = (string)item.GetValue("leader_username"),
                    Record_Date = (DateTime)item.GetValue("record_date")

                };
                if (item.GetValue("ID") != null)
                {
                    band.ID = item.GetValue("ID").ToObject<int>();
                }
                else
                {
                    band.ID = -1;
                }
                if (item.GetValue("leader_id").Equals(null))
                {
                    band.Leader_ID = item.GetValue("leader_id").ToObject<int>();
                }
                else
                {
                    band.Leader_ID = -1;
                }


                bandList.Add(band);
                gruppen_listbox.Items.Add(band);
            }

        }

        private void RefreshCalendar()
        {
            if (cboYear.SelectedItem == null) return;
            if (cboMonth.SelectedItem == null) return;

            int year = (int)cboYear.SelectedItem;

            int month = cboMonth.SelectedIndex + 1;

            DateTime targetDate = new DateTime(year, month, 1);

            Calendar.BuildCalendar(targetDate);

        }

        private void Calendar_DayChanged(object sender, DayChangedEventArgs e)
        {

            //save the text edits to persistant storage
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        {

        }

   

        private void Gruppen_listbox_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            Console.WriteLine();
            Band band = (Band)gruppen_listbox.SelectedItem;
            txtbx_bandname.Text = band.Name;
            txtbx_website.Text = band.Website_Url;
        }
    }
}
