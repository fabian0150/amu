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
using Day = Jarloo.Calendar.Day;

namespace AMU_WPF
{
    /// <summary>
    /// Interaktionslogik für MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        User user;
        //Band band;
        //Appointment appointment;
        //Location location;
        public string session_key = "-1";
        public string session_user = "-1";

        public MainWindow()
        {
            InitializeComponent();
            LoginUser();
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
                    session_user = (string)item.GetValue("user_id");
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
                Location location = new Location
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
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/band/_getBands.php", "");

            for (int i = 0; i < arrayJSON.Count; i++)
            {
                JObject item = (JObject)arrayJSON[i];

                Band band = new Band
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
                gruppen_listbox.Items.Add(band);
            }



        } //Gruppen Tab

        private void LoadAppointments() //lstbx_appointments
        {
            lstbx_appointments.Items.Clear();
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/appointment/_getAppointments.php?band_id=", ((Band)gruppen_listbox.SelectedItem).ID.ToString());

            for (int i = 0; i < arrayJSON.Count; i++)
            {
                JObject item = (JObject)arrayJSON[i];
                if (item.Value<int?>("code") == 1)
                {
                    Appointment appointment = new Appointment
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

        private Day currentDay = null;
        private void Calendar_DayChanged(object sender, DayChangedEventArgs e)
        {
            //if ( currentDay!=null) currentDay.PropertyChanged -= Day_PropertyChanged;
            currentDay = e.Day;
            currentDay.PropertyChanged += Day_PropertyChanged;
        }

        private void Day_PropertyChanged(object sender, System.ComponentModel.PropertyChangedEventArgs e)
        {
            if (e.PropertyName != "Notes") return;
            var notes = (sender as Day).Notes;
            Console.WriteLine();
        }

        private void Termin_Anfrage_Button_Clicked(object sender, RoutedEventArgs e)
        {
            TerminAnfrageWindow taw = new TerminAnfrageWindow(session_key, session_user);
            taw.Show();

        }

        private void Gruppen_listbox_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            lstbx_appointments.Items.Clear();
            Band band = (Band)gruppen_listbox.SelectedItem;
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
                    ID = item.Value<int?>("ID") ?? -1,
                    User_Type = item.Value<int?>("user_type") ?? -1,
                    Record_Date = (DateTime)item.GetValue("record_date"),
                };

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
            Appointment appointment = (Appointment)veranstaltungen_listbox.SelectedItem;
            JArray arrayJSONLocation = GET_Request("https://amu.tkg.ovh/json/location/_getLocation.php?id=", appointment.Location_ID.ToString());
            JObject itemLocation = (JObject)arrayJSONLocation[0];
            Location location = new Location
            {
                ID = itemLocation.Value<int>("ID"),
                Address = (string)itemLocation.GetValue("address"),
                Contact_Person_ID = itemLocation.Value<int>("contact_person_id"),
                Name = (string)itemLocation.GetValue("name")
            };

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
                txtbx_veranstaltung_adresse.Text = location.Address;
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
                    {"band_id", ((Band)gruppen_listbox.SelectedItem).ID.ToString()},
                    {"location_id", "5"}, //5 steht für externe Veranstaltung
                    {"appointment_date", date},
                    { "location_name","[EXT.]" } //Externe Veranstaltung -> Termin wurde von Gruppe selber vermittelt
                }));
                    Console.WriteLine(response);
                }
            }
        } //Externe Veranstaltung hinzufügen

        private void DeleteAppointmentClick(object sender, RoutedEventArgs e)
        {
            if (System.Windows.MessageBox.Show("Veranstaltung löschen?", "Question", MessageBoxButton.YesNo, MessageBoxImage.Warning) == MessageBoxResult.Yes)
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
            Band band = ((Band)gruppen_listbox.SelectedItem);
            band.Name = txtbx_bandname.Text;
            band.Website_Url = txtbx_website.Text;
            band.Notes = txtblock_notizen.Text;
            //Get Users
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/user/_getUser.php?id=", band.Leader_ID.ToString());
            JObject item = (JObject)arrayJSON[0];

            User user = new User
            {
                Name = (string)item.GetValue("name"),
                Mail = (string)item.GetValue("mail"),
                Phone_Number = (string)item.GetValue("phone_number"),
                Address = (string)item.GetValue("address"),
                Notes = (string)item.GetValue("notes"),
                User_Description = (string)item.GetValue("user_description"),
                ID = item.Value<int?>("ID") ?? -1,
                User_Type = item.Value<int?>("user_type") ?? -1,
                Record_Date = (DateTime)item.GetValue("record_date"),
            };
            user.Name = txtbx_ansprechperson.Text;
            user.Phone_Number = txtbx_telefon.Text;
            user.Mail = txtbx_email.Text;
            user.Notes = txtblock_ansprechperson_notizen.Text;

            using (WebClient webClient = new WebClient())
            {
                string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/band/secure_updateBand.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                    {"id", band.ID.ToString()},
                    {"name", band.Name},
                    {"logo_path", band.Logo_Path},
                    {"website_url", band.Website_Url},
                    {"notes", band.Notes},
                    {"leader_id", band.Leader_ID.ToString()}
                }));
            }

            using (WebClient webClient = new WebClient())
            {
                string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/user/secure_updateUser.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                    {"id", user.ID.ToString()},
                    {"name", user.Name},
                    {"phone_number", user.Phone_Number},
                    {"address", user.Address},
                    {"mail", user.Mail},
                    {"notes", user.Notes},
                    {"user_type", user.User_Type.ToString() },
                    {"user_description", user.User_Description.ToString() },
                    {"username", user.Username }
                }));
            }
        }

        private void VertragErstellen(object sender, RoutedEventArgs e)
        {
            if (!(lstbxOffeneAngebote.SelectedItem == null))
            {
                Offer offer = ((Offer)lstbxOffeneAngebote.SelectedItem);
                VertragErstellenWindow vertragErstellenWindow = new VertragErstellenWindow(offer, session_key, session_user);
                vertragErstellenWindow.Show();

            }
            else
            {
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
            angebotBrowser.Navigate("https://amu.tkg.ovh/pdf/offer_pdf.php?id=" + offer.ID);
        }

        private void LstbxVertraegeSelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            Contract contract = ((Contract)lstbxVertraege.SelectedItem);
            vertragBrowser.Navigate("https://amu.tkg.ovh/pdf/contract_pdf.php?id=" + contract.ID);
        }

        private void VeranstaltungLöschen(object sender, RoutedEventArgs e)
        {
            Appointment appointment = (Appointment)lstbx_appointments.SelectedItem;
            using (WebClient webClient = new WebClient())
            {
                string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/appointment/secure_deleteAppointment.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                    {"appointment_id", appointment.ID.ToString()}
                }));
            }
        }

        private void VeranstaltungBearbeiten(object sender, RoutedEventArgs e)
        {
            Appointment appointment = (Appointment)lstbx_appointments.SelectedItem;

            //GetLocation
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/location/_getLocation.php?id=", appointment.Location_ID.ToString());
            JObject item = (JObject)arrayJSON[0];
            Location location = new Location
            {
                ID = (int)item.GetValue("ID"),
                Name = (string)item.GetValue("name") ?? "Kein Bandname",
                Address = (string)item.GetValue("address"),
                Contact_Person_ID = item.Value<int?>("contact_person_id") ?? -1,
                Record_Date = (DateTime)item.GetValue("record_date")
            };




            //Get Users
            JArray arrayJSONUser = GET_Request("https://amu.tkg.ovh/json/user/_getUser.php?id=", location.Contact_Person_ID.ToString());
            JObject itemUser = (JObject)arrayJSONUser[0];
            User user = new User
            {
                Name = (string)itemUser.GetValue("name"),
                Mail = (string)itemUser.GetValue("mail"),
                Phone_Number = (string)itemUser.GetValue("phone_number"),
                Address = (string)itemUser.GetValue("address"),
                Notes = (string)itemUser.GetValue("notes"),
                User_Description = (string)itemUser.GetValue("user_description"),
                ID = itemUser.Value<int?>("ID") ?? -1,
                User_Type = itemUser.Value<int?>("user_type") ?? -1,
                Record_Date = (DateTime)itemUser.GetValue("record_date"),
            };

            //Update User
            using (WebClient webClient = new WebClient())
            {
                string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/user/secure_updateUser.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                    {"id", appointment.ID.ToString()},
                    {"name", txtbx_ansprechperson.Text },
                    {"phone_number",user.Phone_Number},
                    {"address",user.Address },
                    {"mail",user.Mail },
                    {"notes",user.Notes },
                    {"user_type",user.User_Type.ToString() },
                    {"user_description",user.User_Description },
                    {"username",user.Username }
                }));
            }

            //UpdateLocation


            //UpdateAppointment
            //using (WebClient webClient = new WebClient())
            //{
            //    string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/appointment/secure_updateAppointment.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
            //        {"id", appointment.ID.ToString()},
            //        {"appointment_date" }
            //    }));
            //}
        }

        private void GruppeLöschen(object sender, RoutedEventArgs e)
        {
            using (WebClient webClient = new WebClient())
            {
                string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/band/secure_deleteBand.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                    {"band_id", ((Band)gruppen_listbox.SelectedItem).ID.ToString()}
                }));
            }
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