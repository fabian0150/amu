using AMU.Dto;
using AMU.Windows;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
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

        private void Suche_Gruppen_Clicked(object sender, RoutedEventArgs e)
        {
            lstbx_gruppen_verfuegbar.Items.Clear();
            //Hier werden die Bands gesucht und angezeigt sobald die Abfrage fertig ist.
            LoadAvailableBands();
        }
        private void LoadAvailableBands()//Lädt Bands, die den Kriterien entsprechen (Anzahl BandMembers & freier Termin)
        {
            List<Band> bandList = new List<Band>();
            JArray arrayJSON = GET_Request($"https://amu.tkg.ovh/json/band/_getBands.php", "");

            //JArray arrayJSON = GET_Request($"https://amu.tkg.ovh/json/band/_getAvailableBands.php?members_cnt="+1 +"&date=2019-03-02", "");
            Band band;
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
                    Record_Date = (DateTime)item.GetValue("record_date"),
                    ID = item.Value<int?>("ID") ?? -1,
                    Leader_ID = item.Value<int?>("leader_id") ?? -1
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
            lbl_bandmembers.Content = 0;
            lbl_contact_person_name.Content = user.Name;
            lbl_phone_number.Content = user.Phone_Number;
            lbl_email.Content = user.Mail;
            lbl_address.Content = user.Address;
            txtblck_notes.Text = user.Notes;


        }

        private void AngebotErstellen(object sender, RoutedEventArgs e)
        {
            bandList.Clear();
            foreach (var item in lstbx_gruppen_verfuegbar.SelectedItems)
            {
                bandList.Add((Band)item);
            }
            AngebotErstellenWindow angebotErstellenWindow = new AngebotErstellenWindow(bandList, session_key, session_user);
            angebotErstellenWindow.Show();
        }
    }
}
