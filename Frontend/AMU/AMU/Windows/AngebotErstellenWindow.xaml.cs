using AMU.Dto;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.ComponentModel;
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

namespace AMU.Windows
{
    /// <summary>
    /// Interaktionslogik für AngebotErstellenWindow.xaml
    /// </summary>
    public partial class AngebotErstellenWindow : Window
    {
        private string session_key = "-1";
        private string session_user = "-1";
        List<Band> bandList = new List<Band>();
        public AngebotErstellenWindow(List<Band> bands, string sessionKey, string sessionUser)
        {
            InitializeComponent();
            session_key = sessionKey;
            session_user = sessionUser;
            bandList = bands;
            LoadBands();
            LoadUsers();
            LoadVeranstaltungsorte();
        }

        private void LoadBands()
        {
            foreach (Band item in bandList)
            {
                lstbxBand.Items.Add(item);
            }
        }

        private void VeranstalterErstellen(object sender, RoutedEventArgs e)
        {
            VeranstalterHinzufuegenWindow veranstalterHinzufuegenWindow = new VeranstalterHinzufuegenWindow();
            veranstalterHinzufuegenWindow.Show();
            veranstalterHinzufuegenWindow.Closing += VeranstalterHinzufuegenWindowClosed;
        }
        private void VeranstalterHinzufuegenWindowClosed(object sender, EventArgs e)
        {
            LoadUsers();
        }

        private void VeranstaltungsortErstellen(object sender, RoutedEventArgs e)
        {
            int contactPersonID = ((User)lstbxVeranstalter.SelectedItem).ID;
            VeranstaltungsortHinzufuegenWindow veranstaltungsortHinzufuegenWindow = new VeranstaltungsortHinzufuegenWindow(contactPersonID, session_key, session_user);
            veranstaltungsortHinzufuegenWindow.Show();
            veranstaltungsortHinzufuegenWindow.Closing += VeranstaltungsortHinzufuegenWindowClosed;
        }

        private void VeranstaltungsortHinzufuegenWindowClosed(object sender, CancelEventArgs e)
        {
            LoadVeranstaltungsorte();
        }

        public void LoadUsers()
        {
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/user/_getUsers.php", "");
            JObject item;
            for (int i = 0; i < arrayJSON.Count; i++)
            {
                item = (JObject)arrayJSON[i];
                //if (item.Value<int?>("user_type") == 2) { //wieder auskommentieren
                    User user = new User
                    {
                        ID = (int)item.GetValue("ID"),
                        Name = (string)item.GetValue("name") ?? "Kein Bandname",
                        Address = (string)item.GetValue("address"),
                        Mail = (string)item.GetValue("mail"),
                        Notes = (string)item.GetValue("notes"),
                        Phone_Number = (string)item.GetValue("phone_number"),
                        Username = (string)item.GetValue("username"),
                        User_Description = (string)item.GetValue("user_description"),
                        User_Type = item.Value<int?>("user_type") ?? -1,
                        Record_Date = (DateTime)item.GetValue("record_date")
                    };
                    lstbxVeranstalter.Items.Add(user);
                //}
            }

        }
        private void LoadVeranstaltungsorte()
        {
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/location/_getLocations.php", "");
            JObject item;
            for (int i = 0; i < arrayJSON.Count; i++)
            {
                item = (JObject)arrayJSON[i];
                Location location = new Location
                {
                    ID = (int)item.GetValue("ID"),
                    Name = (string)item.GetValue("name") ?? "Kein Bandname",
                    Address = (string)item.GetValue("address"),
                    Contact_Person_ID = item.Value<int?>("contact_person_id") ?? -1,
                    Record_Date = (DateTime)item.GetValue("record_date")
                };
                lstbxVeranstaltungsort.Items.Add(location);
            }
        }
        private JArray GET_Request(string url, string parameter)
        {
            var rawJSON = new WebClient().DownloadString(url + parameter);
            return JArray.Parse(rawJSON);
        }

        private void LstbxBandSelectionChanged(object sender, SelectionChangedEventArgs e)
        {

        }
    }
}
