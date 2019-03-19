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
using System.Text;
using System.Collections.Specialized;
using System.IO;
using System.Net.Http;
using System.Net.Http.Headers;
using AMU;
using System.Windows.Forms;
using System.Windows.Media;

namespace AMU_WPF
{
    /// <summary>
    /// Interaktionslogik für MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        User user;
        Band band;
        Appointment appointment;
        Location location;
        public string session_key = "-1";
        public string session_user = "-1";

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
            LoginUser();
            //Tabs
            LoadBands();
            LoadVeranstaltungen();
            LoadAngebote();
            LoadVertraege();
            //Tabs end

            
        }

        private void LoadVertraege()
        {
            //lstbxVertraege
            //https://amu.tkg.ovh/json/contract/_getContracts.php 
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/contract/_getContracts.php", "");
            Contract contract;
            for (int i = 0; i < arrayJSON.Count; i++)
            {
                JObject item = (JObject)arrayJSON[i];
                contract = new Contract
                {
                    ID = item.Value<int?>("contract") ?? -1,
                    BandID = item.Value<int?>("band_id") ?? -1,
                    LocationID = item.Value<int?>("location_id") ?? -1,
                    UserID = item.Value<int?>("user_id") ?? -1,
                    OfferState = item.Value<int?>("offer_state") ?? -1,
                    OfferDate = (string)item.GetValue("offer_date"),
                    Price = item.Value<double?>("price") ?? -1,
                    RecordDate = (DateTime)item.GetValue("record_date"),
                    VeranstaltungName = GetVeranstaltungName((string)item.GetValue("location_id"))
                };
                lstbxVertraege.Items.Add(contract);

            }
        }

        private void LoadAngebote()
        {
            //List<Offer> offerList = new List<Offer>();
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/offer/_getOffers.php?type=", "1");
            Offer offer;
            for (int i = 0; i < arrayJSON.Count; i++)
            {
                JObject item = (JObject)arrayJSON[i];

                offer = new Offer
                {
                    ID = item.Value<int?>("offer_id") ?? -1,
                    LocationID = item.Value<int?>("location_id") ?? -1,
                    UserID = item.Value<int?>("user_id") ?? -1,
                    OfferDate = (string)item.GetValue("offer_date"),
                    Record_Date = (DateTime)item.GetValue("record_date"),
                    VeranstaltungName = GetVeranstaltungName((string)item.GetValue("location_id")),
                };

                //offerList.Add(offer);
                lstbxOffeneAngebote.Items.Add(offer);
            }
        }

        private string GetVeranstaltungName(string v)
        {
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/location/_getLocation.php?id=", v);
            JObject item = (JObject)arrayJSON[0];
            return (string)item.GetValue("name");
        }

        private void LoginUser()
        {
            using (WebClient webClient = new WebClient())
            {
                string response = Encoding.ASCII.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/user/secure_login.php", new NameValueCollection() {
                    {"username", "robin"},
                    {"password", "1234"}
                }));
                //[{"code":1,"message":"Login erfolgreich","session_key":"Ti5zJ728kGFH9IzE466oiNTZTBHyBohvGn4qv7yQOexXW7C1dI","user_id":2,"user_name":"robin"}]
                response.Replace("/" + '"', "");
                JArray arrayJSON = JArray.Parse(response);
                JObject item;
                for (int i = 0; i < arrayJSON.Count; i++)
                {
                    item = (JObject)arrayJSON[i];
                    session_key = (string)item.GetValue("session_key");
                    session_user = (string)item.GetValue("user_id"); //username ist redundant, weil ja nur ein User Applikation hat
                }
            }
            Console.WriteLine("---");
        }

        private void LoadVeranstaltungen()
        {
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/location/_getLocations.php", "");
            JObject item;
            for (int i = 0; i < arrayJSON.Count; i++)
            {
                item = (JObject)arrayJSON[i];
                location = new Location
                {
                    ID = (int)item.GetValue("ID"),
                    Name = (string)item.GetValue("name") ?? "Kein Bandname",
                    Address = (string)item.GetValue("address"),
                    Contact_Person_ID = item.Value<int?>("contact_person_id") ?? -1,
                    Record_Date = (DateTime)item.GetValue("record_date")
                };
                veranstaltungen_listbox.Items.Add(location);
            }
        } //Veranstalter Tab

        private void LoadBands()
        {
            List<Band> bandList = new List<Band>();
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/band/_getBands.php", "");

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



        } //Gruppen Tab

        private void LoadAppointments() //lstbx_appointments
        {
            lstbx_appointments.Items.Clear();
            List<Appointment> appointmentList = new List<Appointment>();
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/appointment/_getAppointments.php?band_id=", band.ID.ToString());

            for (int i = 0; i < arrayJSON.Count; i++)
            {
                JObject item = (JObject)arrayJSON[i];
                if (item.Value<int?>("code") == 1)
                {
                    appointment = new Appointment
                    {
                        ID = item.Value<int?>("ID") ?? -1,
                        Appointment_Date = (DateTime)item.GetValue("appointment_date"),
                        Location_Name = (string)item.GetValue("location_name"),
                        Band_ID = item.Value<int?>("band_id") ?? -1,
                        Band_Name = (string)item.GetValue("band_name"),
                        Location_Address = (string)item.GetValue("location_address"),
                        Location_ID = item.Value<int?>("location_id") ?? -1,
                        Record_Date = (DateTime)item.GetValue("record_date")
                    };

                    appointmentList.Add(appointment);
                    lstbx_appointments.Items.Add(appointment);
                }
                else
                {
                    return;
                }
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

        } //Termine Tab

        private void Calendar_DayChanged(object sender, DayChangedEventArgs e)
        {

            //save the text edits to persistant storage
        }

        private void Termin_Anfrage_Button_Clicked(object sender, RoutedEventArgs e)
        {
            TerminAnfrageWindow taw = new TerminAnfrageWindow(session_key, session_user);
            taw.Show();

        }

        private void Gruppen_listbox_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            lstbx_appointments.Items.Clear();
            band = (Band)gruppen_listbox.SelectedItem;
            if (!(band.Leader_ID == -1))
            {
                //Get Users
                JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/user/_getUser.php?id=", band.Leader_ID.ToString());
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
            //Anzahl der Bandmembers
            txtbx_bandname.Text = band.Name;
            lbl_besetzung.Content = "Besetzung: " + GetBandMembersCount(band.ID);
            txtblock_notizen.Text = band.Notes;
            LoadAppointments(); //lstbx_appointments
        }

        private void Veranstaltungen_listbox_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            lstbx_veranstalter_gruppen.Items.Clear();
            //Zum Testen contact_person_id = 1 gesetzt, in der DB = null
            location.Contact_Person_ID = 1;
            if (!(location.Contact_Person_ID == -1))
            {
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

                //lstbx_veranstalter_gruppen Gruppen anzeigen
                arrayJSON = GET_Request("https://amu.tkg.ovh/json/appointment/_getLocationAppointments.php?id=", location.ID.ToString());
                JArray arrayJSONBands;
                JObject itemBand;
                for (int i = 0; i < arrayJSON.Count; i++)
                {
                    item = (JObject)arrayJSON[i];

                    arrayJSONBands = GET_Request("https://amu.tkg.ovh/json/band/_getBand.php?id=", (string)item.GetValue("band_id"));
                    itemBand = (JObject)arrayJSONBands[0];
                    if ((itemBand.Value<int?>("code") ?? -1) == 1)
                    {
                        Band bandPlayedAtAppointment = new Band
                        {
                            Name = (string)itemBand.GetValue("name"),
                            Logo_Path = (string)itemBand.GetValue("logo_path"),
                            Website_Url = (string)itemBand.GetValue("website_url"),
                            Notes = (string)itemBand.GetValue("notes"),
                            Leader_Username = (string)itemBand.GetValue("leader_username"),
                            Record_Date = (DateTime)itemBand.GetValue("record_date"),
                            ID = itemBand.Value<int?>("ID") ?? -1,
                            Leader_ID = itemBand.Value<int?>("leader_id") ?? -1
                        };
                        lstbx_veranstalter_gruppen.Items.Add(bandPlayedAtAppointment);
                    }
                }
            }
        }
        private JArray GET_Request(string url, string parameter)
        {
            var rawJSON = new WebClient().DownloadString(url + parameter);
            //var resultObjects = JsonConvert.DeserializeObject(rawJSON);
            return JArray.Parse(rawJSON);
        }

        private void Add_New_Band(object sender, RoutedEventArgs e)
        {
            GruppeHinzufuegenWindow gruppeHinzufuegenWindow = new GruppeHinzufuegenWindow(session_key, session_user);
            gruppeHinzufuegenWindow.Show();
        }

        private void Btn_add_default_appointment_Click(object sender, RoutedEventArgs e)
        {
            if (!datepicker_default_appointment.Text.Equals(""))
            {
                string date = DateTime.ParseExact(datepicker_default_appointment.Text, "dd.MM.yyyy",
                                CultureInfo.InvariantCulture
                                ).ToString("yyyy-MM-dd HH:mm:ss");
                using (WebClient webClient = new WebClient())
                {
                    string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/appointment/secure_addAppointment.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                    {"band_id", band.ID.ToString()},
                    {"location_id", "5"}, //5 steht für externe Veranstaltung
                    {"appointment_date", date},
                    { "location_name","[EXT.]" } //Externe Veranstaltung -> Termin wurde von Gruppe selber vermittelt
                }));
                    Console.WriteLine(response);
                }


                LoadAppointments();
            }
        } //Externe Veranstaltung hinzufügen

        private void Delete_Appointment_Click(object sender, RoutedEventArgs e)
        {
            if (System.Windows.MessageBox.Show("Veranstaltung löschen?", "Question", MessageBoxButton.YesNo, MessageBoxImage.Warning) == MessageBoxResult.No)
            {
                //nein, nichts tun
            }
            else
            {
                using (WebClient webClient = new WebClient())
                {
                    string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/appointment/secure_deleteAppointment.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                        {"appointment_id", ((Appointment)lstbx_appointments.SelectedItem).ID.ToString()}
                    }));
                }
            }

        }
        private int GetBandMembersCount(int id)
        {
            var rawJSONBandMembers = new WebClient().DownloadString("https://amu.tkg.ovh/json/band/_getBandMember.php?id=" + id);
            var resultObjectsBandMembers = JsonConvert.DeserializeObject(rawJSONBandMembers);
            JArray arrayJSONBandMembers = JArray.Parse(rawJSONBandMembers);
            return arrayJSONBandMembers.Count;
        }

        private void GruppeSpeichern(object sender, RoutedEventArgs e)
        {

        }

        private void VertragErstellen(object sender, RoutedEventArgs e)
        {
            if (!(lstbxOffeneAngebote.SelectedItem == null))
            {
                Offer offer = ((Offer)lstbxVertraege.SelectedItem);
                VertragErstellenWindow vertragErstellenWindow = new VertragErstellenWindow(offer, session_key, session_user);
                vertragErstellenWindow.Show();
                
            }
            else {
                string message = "Bitte ein Angebot auswählen";
                string caption = "Kein Angebot ausgewählt";
                MessageBoxButtons buttons = MessageBoxButtons.OK;
                DialogResult result = System.Windows.Forms.MessageBox.Show(message, caption, buttons);
                return;
            }
            
        }

        private void LstbxOffeneAngeboteSelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            Offer offer = ((Offer)lstbxOffeneAngebote.SelectedItem);
            angebotBrowser.Navigate("https://amu.tkg.ovh/pdf/offer_pdf.php?id="+offer.ID);
        }

        private void LstbxVertraegeSelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            Contract contract = ((Contract)lstbxVertraege.SelectedItem);
            vertragBrowser.Navigate("https://amu.tkg.ovh/pdf/offer_pdf.php?id=" + contract.ID);
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