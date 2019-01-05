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
        User user;
        Band band;
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
            //Tabs
            LoadBands();
            //Tabs end
        }

        private void LoadBands()
        {
            bandList = new List<Band>();
            var rawJSON = new WebClient().DownloadString("https://amu.tkg.ovh/json/band/_getBands.php");
            var resultObjects = JsonConvert.DeserializeObject(rawJSON);
            JArray arrayJSON = JArray.Parse(rawJSON);

            for (int i = 0; i < arrayJSON.Count; i++)
            {
                JObject item = (JObject)arrayJSON[i];

                band = new Band
                {
                    Name = (string)item.GetValue("name"),
                    Logo_Path = (string)item.GetValue("logo_path"),
                    Website_Url = (string)item.GetValue("website_url"),
                    Notes = (string)item.GetValue("notes"),
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
                
                band.Leader_ID = item.Value<int?>("leader_id") ?? -1;

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
            if (!(band.Leader_ID == -1))
            {
                var rawJSON = new WebClient().DownloadString("https://amu.tkg.ovh/json/user/_getUser.php?id=" + band.Leader_ID);
                var resultObjects = JsonConvert.DeserializeObject(rawJSON);
                JArray arrayJSON = JArray.Parse(rawJSON);

                JObject item = (JObject)arrayJSON[0];

                user = new User
                {
                    Name = (string)item.GetValue("name"),
                    Mail = (string)item.GetValue("mail"),
                    Phone_Number = (string)item.GetValue("phone_number"),
                    Address = (string)item.GetValue("address"),
                    Website_Url = (string)item.GetValue("website_url"),
                    Notes = (string)item.GetValue("notes"),
                    User_Description = (string)item.GetValue("user_description"),
                    Record_Date = (DateTime)item.GetValue("record_date")
                };
                if (item.GetValue("ID") != null)
                {
                    user.ID = item.GetValue("ID").ToObject<int>();
                }
                else
                {
                    user.ID = -1;
                }
                if (item.GetValue("user_type").Equals(null))
                {
                    user.User_Type = item.GetValue("user_type").ToObject<int>();
                }
                else
                {
                    user.User_Type = -1;
                }

                
                txtbx_email.Text = user.Mail;
                txtbx_telefon.Text = user.Phone_Number;
                txtbx_website.Text = user.Website_Url;
                
                
                }
            else
            {
                txtbx_email.Text = "Keine Ansprechperson";
                txtbx_telefon.Text = "Keine Ansprechperson";
                txtbx_website.Text = "Keine Ansprechperson";
            }
            var rawJSONBandMembers = new WebClient().DownloadString("https://amu.tkg.ovh/json/band/_getBandMember.php?id=" + band.ID);
            var resultObjectsBandMembers = JsonConvert.DeserializeObject(rawJSONBandMembers);
            JArray arrayJSONBandMembers = JArray.Parse(rawJSONBandMembers);
            txtbx_bandname.Text = band.Name;
            lbl_besetzung.Content = arrayJSONBandMembers.Count;
            txtblock_notizen.Text = band.Notes;

        }

    }
}

