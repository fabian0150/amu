using AMU.Dto;
using AMU.Windows;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;

namespace AMU
{
    /// <summary>
    /// Interaktionslogik für AngebotErstellenWindow.xaml
    /// </summary>
    public partial class TerminAnfrageWindow : Window
    {
        private string session_key = "-1";
        private string session_user = "-1";
        List<Band> bandList = new List<Band>();
        public TerminAnfrageWindow(string sessionKey, string sessionUser)
        {
            InitializeComponent();
            session_key = sessionKey;
            session_user = sessionUser;
            lstbx_gruppen_verfuegbar.SelectionMode = SelectionMode.Extended;
        }

        private void GruppenSuche(object sender, RoutedEventArgs e)
        {
            lstbx_gruppen_verfuegbar.Items.Clear();

            if (datePickerAnfrage.SelectedDate == null)
            {
                string message = "Bitte ein Datum auswählen";
                string caption = "Fehlende Daten";
                System.Windows.Forms.MessageBoxButtons buttons = System.Windows.Forms.MessageBoxButtons.OK;
                System.Windows.Forms.DialogResult result = System.Windows.Forms.MessageBox.Show(message, caption, buttons);
                return;
            }
            else
            {
                //Hier werden die Bands gesucht und angezeigt sobald die Abfrage fertig ist.
                if (chckbxAlleinunterhalter.IsChecked == true)
                {
                    string date = DateTime.ParseExact(datePickerAnfrage.Text, "dd.MM.yyyy",
                                CultureInfo.InvariantCulture
                                ).ToString("yyyy-MM-dd");
                    LoadAvailableBands(1, date);
                }
                if (chckbxDuo.IsChecked == true)
                {
                    string date = DateTime.ParseExact(datePickerAnfrage.Text, "dd.MM.yyyy",
                                CultureInfo.InvariantCulture
                                ).ToString("yyyy-MM-dd");
                    LoadAvailableBands(2, date);
                }
                if (chckbxTrio.IsChecked == true)
                {
                    string date = DateTime.ParseExact(datePickerAnfrage.Text, "dd.MM.yyyy",
                                CultureInfo.InvariantCulture
                                ).ToString("yyyy-MM-dd");
                    LoadAvailableBands(3, date);
                }
                if (chckbxQuartett.IsChecked == true)
                {
                    string date = DateTime.ParseExact(datePickerAnfrage.Text, "dd.MM.yyyy",
                                CultureInfo.InvariantCulture
                                ).ToString("yyyy-MM-dd");
                    LoadAvailableBands(4, date);
                }
                if (chckbxQuintettUndMehr.IsChecked == true)
                {
                    string date = DateTime.ParseExact(datePickerAnfrage.Text, "dd.MM.yyyy",
                                CultureInfo.InvariantCulture
                                ).ToString("yyyy-MM-dd");
                    LoadAvailableBands(5, date);
                }
            }



        }
        private void LoadAvailableBands(int bandMembersNr, string date)//Lädt Bands, die den Kriterien entsprechen (Anzahl BandMembers & freier Termin)
        {
            JArray arrayJSON = GET_Request($"https://amu.tkg.ovh/json/band/_getAvailableBands.php?members_cnt=" + bandMembersNr + "&date=", date);
            if (((string)((JObject)arrayJSON[0]).GetValue("code")).Equals("1"))
            {
                for (int i = 0; i < arrayJSON.Count; i++)
                {
                    JObject item = (JObject)arrayJSON[i];
                    LoadBand(item);
                }
            }
        }

        private void LoadBand(JObject item)
        {
            JArray arrayJSON = GET_Request($"https://amu.tkg.ovh/json/band/_getBand.php?id=", (string)item.GetValue("ID"));
            JObject bandItem = (JObject)arrayJSON[0];
            if (((string)bandItem.GetValue("code")).Equals("1"))
            {
                Band band = new Band
                {
                    Name = (string)bandItem.GetValue("name"),
                    Logo_Path = (string)bandItem.GetValue("logo_path"),
                    Website_Url = (string)bandItem.GetValue("website_url"),
                    Notes = (string)bandItem.GetValue("notes"),
                    Leader_Username = (string)bandItem.GetValue("leader_username"),
                    Record_Date = (DateTime)bandItem.GetValue("record_date"),
                    ID = bandItem.Value<int?>("ID") ?? -1,
                    Leader_ID = bandItem.Value<int?>("leader_id") ?? -1
                };
                bandList.Add(band);
                lstbx_gruppen_verfuegbar.Items.Add(band);
            }
        }

        private JArray GET_Request(string url, string parameter)
        {
            var rawJSON = new WebClient().DownloadString(url + parameter);
            //var resultObjects = JsonConvert.DeserializeObject(rawJSON);
            return JArray.Parse(rawJSON);
        }

        private void Lstbx_gruppen_verfuegbar_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if (!(lstbx_gruppen_verfuegbar.SelectedItem == null))
            {
                lbl_bandname.Content = "";
                lbl_bandmembers.Content = "";
                lbl_contact_person_name.Content = "";
                lbl_phone_number.Content = "";
                lbl_email.Content = "";
                lbl_address.Content = "";
                txtblck_notes.Text = "";
                Band band = (Band)lstbx_gruppen_verfuegbar.SelectedItem;
                JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/user/_getUser.php?id=", band.Leader_ID.ToString());
                User user;

                JObject item = (JObject)arrayJSON[0];
                user = new User
                {
                    ID = item.Value<int?>("ID") ?? -1,
                    Name = (string)item.GetValue("name"),
                    Mail = (string)item.GetValue("mail"),
                    Phone_Number = (string)item.GetValue("phone_number"),
                    Address = (string)item.GetValue("address"),
                    User_Description = (string)item.GetValue("user_description"),
                    Username = (string)item.GetValue("username"),
                    User_Type = item.Value<int?>("user_type") ?? -1,
                    Notes = (string)item.GetValue("notes"),
                    Record_Date = (DateTime)item.GetValue("record_date")
                };

                lbl_bandname.Content = band.Name;
                lbl_bandmembers.Content = GetBandMembersCount(band.ID);
                lbl_contact_person_name.Content = user.Name;
                lbl_phone_number.Content = user.Phone_Number;
                lbl_email.Content = user.Mail;
                lbl_address.Content = user.Address;
                txtblck_notes.Text = user.Notes;
            }

        }

        private void AngebotErstellen(object sender, RoutedEventArgs e)
        {
            bandList.Clear();
            foreach (var item in lstbx_gruppen_verfuegbar.SelectedItems)
            {
                bandList.Add((Band)item);
            }
            string date = DateTime.ParseExact(datePickerAnfrage.Text, "dd.MM.yyyy",
                                CultureInfo.InvariantCulture
                                ).ToString("yyyy-MM-dd");
            
            AngebotErstellenWindow angebotErstellenWindow = new AngebotErstellenWindow(date, bandList, session_key, session_user);
            angebotErstellenWindow.Show();
        }
        private int GetBandMembersCount(int id)
        {
            var rawJSONBandMembers = new WebClient().DownloadString("https://amu.tkg.ovh/json/band/_getBandMember.php?id=" + id);
            var resultObjectsBandMembers = JsonConvert.DeserializeObject(rawJSONBandMembers);
            JArray arrayJSONBandMembers = JArray.Parse(rawJSONBandMembers);
            return arrayJSONBandMembers.Count;
        }
    }
}
