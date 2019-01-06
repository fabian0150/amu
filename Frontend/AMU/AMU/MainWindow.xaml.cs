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
        Appointment appointment;
        Location location;
        public MainWindow()
        {
            InitializeComponent();
            List<string> months = new List<string> { "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" };
            cboMonth.ItemsSource = months;
            

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
            LoadVeranstalter();
            //Tabs end
        }

        private void LoadVeranstalter()
        {
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/location/_getLocations.php","");
            JObject item;
            for (int i = 0; i < arrayJSON.Count; i++)
            {
                item = (JObject)arrayJSON[i];
                location = new Location
                {
                    ID = (int)item.GetValue("ID"),
                    Name = (string)item.GetValue("name")??"Kein Bandname",
                    Address = (string)item.GetValue("address"),
                    Contact_Person_ID = item.Value<int?>("contact_person_id") ?? -1,
                    Record_Date = (DateTime)item.GetValue("record_date")
                };
                veranstaltungen_listbox.Items.Add(location);
            }
        }

        private void LoadBands()
        {
            bandList = new List<Band>();
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/band/_getBands.php","");

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
                
                band.ID = item.Value<int?>("ID") ?? -1;
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
            Band band = (Band)gruppen_listbox.SelectedItem;
            if (!(band.Leader_ID == -1))
            {
                JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/user/_getUser.php?id=",band.Leader_ID.ToString());
                JObject item = (JObject)arrayJSON[0];

                user = new User
                {
                    Name = (string)item.GetValue("name"),
                    Mail = (string)item.GetValue("mail"),
                    Phone_Number = (string)item.GetValue("phone_number"),
                    Address = (string)item.GetValue("address"),
                    Notes = (string)item.GetValue("notes"),
                    User_Description = (string)item.GetValue("user_description"),
                    Record_Date = (DateTime)item.GetValue("record_date")
                };
                
                user.ID = item.Value<int?>("ID") ?? -1;
                user.User_Type = item.Value<int?>("user_type") ?? -1;
                
                txtbx_email.Text = user.Mail;
                txtbx_telefon.Text = user.Phone_Number;
                txtbx_website.Text = band.Website_Url;
                txtbx_ansprechperson.Text = user.Name;
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
            lbl_besetzung.Content = "Besetzung: "+arrayJSONBandMembers.Count;
            txtblock_notizen.Text = band.Notes;

        }

        private void Veranstaltungen_listbox_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            //Zum Testen contact_person_id = 1 gesetzt, in der DB = null
            location.Contact_Person_ID = 1;
            if (!(location.Contact_Person_ID==-1)) { 
            User contactPerson;
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/user/_getUser.php?id=", location.Contact_Person_ID.ToString());

            JObject item = (JObject)arrayJSON[0];
            contactPerson = new User
            {
                ID = item.Value<int?>("ID") ?? -1,
                Name = (string)item.GetValue("name"),
                Mail = (string)item.GetValue("mail"),
                Phone_Number = (string)item.GetValue("phone_number"),
                Address = (string)item.GetValue("address"),
                Notes = (string)item.GetValue("notes"),
                User_Description = (string)item.GetValue("user_description"),
                Record_Date = (DateTime)item.GetValue("record_date"),
                User_Type = item.Value<int?>("user_type") ?? -1
            };
            txtbx_veranstalter_name.Text = contactPerson.Name;
            txtbx_veranstalter_adresse.Text = contactPerson.Address;
            txtbx_veranstalter_email.Text = contactPerson.Mail;
            txtbx_veranstalter_telefon.Text = contactPerson.Phone_Number;
            txtblck_veranstalter_notizen.Text = contactPerson.Notes;

            arrayJSON = GET_Request("https://amu.tkg.ovh/json/appointment/_getLocationAppointments.php?id=", location.ID.ToString());
                JArray arrayJSONBands;
                JObject itemBand;
                for (int i = 0;i<arrayJSON.Count;i++) {
                    item = (JObject)arrayJSON[i];
                    
                    arrayJSONBands = GET_Request("https://amu.tkg.ovh/json/band/_getBand.php?id=",(string)item.GetValue("band_id"));
                    itemBand = (JObject)arrayJSONBands[0];
                    if ((item.Value<int?>("code") ?? -1) == 1) { 
                    Band bandPlayedAtAppointment = new Band {
                        Name = (string)item.GetValue("name"),
                        Logo_Path = (string)item.GetValue("logo_path"),
                        Website_Url = (string)item.GetValue("website_url"),
                        Notes = (string)item.GetValue("notes"),
                        Leader_Username = (string)item.GetValue("leader_username"),
                        Record_Date = (DateTime)item.GetValue("record_date"),
                        ID = item.Value<int?>("ID") ?? -1,
                        Leader_ID = item.Value<int?>("leader_id") ?? -1
                    };
                    lstbx_veranstalter_gruppen.Items.Add(bandPlayedAtAppointment);
                    }
                }
            }
        }
        private JArray GET_Request(string url, string parameter) {
            var rawJSON = new WebClient().DownloadString(url + parameter);
            var resultObjects = JsonConvert.DeserializeObject(rawJSON);
            return JArray.Parse(rawJSON);
        }

        private void Add_New_Band(object sender, RoutedEventArgs e)
        {
            GruppeHinzufuegenWindow gruppeHinzufuegenWindow = new GruppeHinzufuegenWindow();
            gruppeHinzufuegenWindow.Show();
        }
    }
}
//POST REQUEST CODE
//using(WebClient webClient = new WebClient()) {
//    string response = Encoding.UTF8.GetString(webClient.UploadValues("…", new NameValueCollection() {
//        {"key1", "val1"},
//        {"key2", "val2"}
//    }));
//}