using AMU.Dto;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Collections.Specialized;
using System.ComponentModel;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Forms;
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
        List<BandGage> bandGageList = new List<BandGage>();
        private string date;
        public AngebotErstellenWindow(string dateParam, List<Band> bands, string sessionKey, string sessionUser)
        {
            InitializeComponent();
            session_key = sessionKey;
            session_user = sessionUser;
            date = dateParam;
            FillBandList(bands);
            LoadBands();
            LoadUsers();
            LoadVeranstaltungsorte();
        }

        private void FillBandList(List<Band> bands)
        {
            foreach (Band item in bands)
            {
                bandGageList.Add(new BandGage
                {
                    Band = item
                });
            }
        }

        private void LoadBands()
        {
            foreach (BandGage item in bandGageList)
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
            if (lstbxVeranstalter.SelectedItem == null)
            {
                string message = "Es wurde kein Veranstalter ausgewählt";
                string caption = "Bitte einen Veranstalter auswählen";
                MessageBoxButtons buttons = MessageBoxButtons.OK;
                DialogResult result = System.Windows.Forms.MessageBox.Show(message, caption, buttons);
            }
            else
            {
                int contactPersonID = ((User)lstbxVeranstalter.SelectedItem).ID;
                VeranstaltungsortHinzufuegenWindow veranstaltungsortHinzufuegenWindow = new VeranstaltungsortHinzufuegenWindow(contactPersonID, session_key, session_user);
                veranstaltungsortHinzufuegenWindow.Show();
                veranstaltungsortHinzufuegenWindow.Closing += VeranstaltungsortHinzufuegenWindowClosed;
            }
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
            if (!(lstbxBand.SelectedItem == null))
            {
                BandGage bandGage = ((BandGage)lstbxBand.SelectedItem);
                LoadUser(bandGage.Band.Leader_ID);
                lblWebsite.Content = bandGage.Band.Website_Url;
                lblBesetzung.Content = "Besetzung: "+GetBandMembersCount(bandGage.Band.ID);
                txtbxGage.Text = bandGage.Gage ?? "";
            }
        }
        public void LoadUser(int userID)
        {
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/user/_getUser.php?id=", userID.ToString());
            JObject item;

            item = (JObject)arrayJSON[0];
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
            lblAnsprechperson.Content = user.Name;
            lblEmail.Content = user.Mail;
            lblTelefonnummer.Content = user.Phone_Number;

            //}


        }

        private int GetBandMembersCount(int id)
        {
            var rawJSONBandMembers = new WebClient().DownloadString("https://amu.tkg.ovh/json/band/_getBandMember.php?id=" + id);
            var resultObjectsBandMembers = JsonConvert.DeserializeObject(rawJSONBandMembers);
            JArray arrayJSONBandMembers = JArray.Parse(rawJSONBandMembers);
            return arrayJSONBandMembers.Count;
        }

        private void BandGageSpeichern(object sender, RoutedEventArgs e)
        {
            BandGage selectedBand = ((BandGage)lstbxBand.SelectedItem);
            for (int i = 0; i < bandGageList.Count; i++)
            {
                if (bandGageList[i].Band.ID.Equals(selectedBand.Band.ID))
                {
                    bandGageList[i].Gage = txtbxGage.Text;
                    lstbxBand.Items.Clear();
                    foreach (BandGage item in bandGageList)
                    {
                        lstbxBand.Items.Add(item);
                    }
                    txtbxGage.Text = "";
                }
            }
        }

        private void AngebotErstellen(object sender, RoutedEventArgs e)
        {
            bandGageList.ForEach(x => {
                if (x.Gage.Equals("")) {
                    string message = "Bitte Gage zu den Gruppen hinzufügen";
                    string caption = "Fehlende Daten";
                    MessageBoxButtons buttons = MessageBoxButtons.OK;
                    DialogResult result = System.Windows.Forms.MessageBox.Show(message, caption, buttons);
                    return;
                }
            });
            if (lstbxVeranstalter.SelectedItem == null | lstbxVeranstaltungsort.SelectedItem == null)
            {
                string message = "Bitte einen Veranstalter/Veranstaltungsort auswählen";
                string caption = "Fehlende Daten";
                MessageBoxButtons buttons = MessageBoxButtons.OK;
                DialogResult result = System.Windows.Forms.MessageBox.Show(message, caption, buttons);
            }
            else if (!(txtbxFußtext.Text != "" | txtbxKopftext.Text != "" | txtbxGage.Text != ""))
            {
                string message = "Bitte Uhrzeit/Kopftext/Fußtext eingeben";
                string caption = "Fehlende Daten";
                MessageBoxButtons buttons = MessageBoxButtons.OK;
                DialogResult result = System.Windows.Forms.MessageBox.Show(message, caption, buttons);
            }
            else {
                //https://amu.tkg.ovh/scripts/offer/secure_addOfferband.php 
                //https://amu.tkg.ovh/json/offer/_getOfferBands.php?id=ID

                Location location = (Location)(lstbxVeranstaltungsort.SelectedItem);
                User user = (User)(lstbxVeranstalter.SelectedItem);

                Offer offer = new Offer {
                    LocationID = location.ID,
                    OfferDate = date,
                    UserID = user.ID,
                    VeranstaltungName = location.Name
                };

                using (WebClient webClient = new WebClient())
                {
                    string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/offer/secure_addOffer.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                        {"offer_date", offer.OfferDate + " " + txtbxDauer.Text},
                        {"location_id", offer.LocationID.ToString()},
                        {"user_id", offer.UserID.ToString()},
                        {"offer_state", "0" }
                    }));
                    Console.ReadLine();
                }
            }
        }
    }
}
