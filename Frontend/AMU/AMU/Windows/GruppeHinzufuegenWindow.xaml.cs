using AMU.Dto;
using AMU_WPF;
using Microsoft.Win32;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Collections.Specialized;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Net.Http.Headers;
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
    /// Interaktionslogik für GruppeHinzufuegenWindow.xaml
    /// </summary>
    public partial class GruppeHinzufuegenWindow : Window
    {
        string session_key = "";
        string session_user = "";
        string logo_path = "Kein Pfad ausgewählt";

        public GruppeHinzufuegenWindow(string session_key_param, string session_user_param)
        {
            InitializeComponent();
            LoadUsers();
            session_user = session_user_param;
            session_key = session_key_param;
        }

        public void LoadUsers()
        {
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/user/_getUsers.php", "");
            JObject item;
            for (int i = 0; i < arrayJSON.Count; i++)
            {
                item = (JObject)arrayJSON[i];
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
                lstbx_gruppen_users.Items.Add(user);
            }

        }
        private JArray GET_Request(string url, string parameter)
        {
            var rawJSON = new WebClient().DownloadString(url + parameter);
            return JArray.Parse(rawJSON);
        }

        private void Button_Open_User_Hinzufuegen_Window(object sender, RoutedEventArgs e)
        {
            UserHinzufuegenWindow window = new UserHinzufuegenWindow();
            window.Show();
            window.Closed += OnWindowClosed;
        }

        private void OnWindowClosed(object sender, EventArgs e)
        {
            LoadUsers();
        }

        private void Button_Gruppe_Erstellen(object sender, RoutedEventArgs e)
        {
            //if () { } //auf Vollständigkeit der Daten prüfen https://amu.tkg.ovh/scripts/band/secure_addBand.php 
            Band band = new Band
            {
                Leader_ID = ((User)(lstbx_gruppen_users.SelectedItem)).ID,
                Leader_Username = ((User)(lstbx_gruppen_users.SelectedItem)).Name,
                Name = txtbx_bandname.Text,
                Website_Url = txtbx_website.Text,
                Notes = txtbx_notes.Text,
                Logo_Path = logo_path
            };
            //dummy Bandmembers adden
            POST_GruppeAsync(band);


            //using (WebClient webClient = new WebClient())
            //{
            //    string response = Encoding.ASCII.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/band/secure_addBand.php", new NameValueCollection() {
            //        {"name", band.Name},
            //        //{"logo_path", band.Logo_Path},
            //        {"website_url", band.Website_Url },
            //        //{"notes", band.Notes },
            //        {"leader_id", band.Leader_ID.ToString() },
            //        {"session_key", session_key },
            //        {"session_user", session_user }
            //    }));

            //    response.Replace("/" + '"', "");
            //    Console.WriteLine("---------"+response);
            //}
        }

        private void POST_GruppeAsync(Band band)
        {
            //IEnumerable<KeyValuePair<string, string>> queries = new List<KeyValuePair<string, string>>() {
            //    new KeyValuePair<string, string>("name",band.Name),
            //    new KeyValuePair<string, string>("website_url",band.Website_Url),
            //    new KeyValuePair<string, string>("notes",band.Notes),
            //    new KeyValuePair<string, string>("logo_path",band.Logo_Path),
            //    new KeyValuePair<string, string>("leader_id",band.Leader_ID.ToString()),
            //    new KeyValuePair<string, string>("session_key",session_key),
            //    new KeyValuePair<string, string>("session_user",session_user),
            //};
            //HttpContent q = new FormUrlEncodedContent(queries);
            //using (HttpClient client = new HttpClient())
            //{
            //    using (HttpResponseMessage response = await client.PostAsync("https://amu.tkg.ovh/scripts/band/secure_addBand.php", q))
            //    {
            //        using (HttpContent content = response.Content)
            //        {
            //            string mycontent = await content.ReadAsStringAsync();
            //            HttpContentHeaders headers = content.Headers;
            //            Console.WriteLine(mycontent);
            //        }
            //    }
            //}

            //var request = (HttpWebRequest)WebRequest.Create("https://amu.tkg.ovh/scripts/band/secure_addBand.php");

            //string stringData = "name="+band.Name+"&leader_id="+band.Leader_ID+"&session_key="+session_key+"&session_user="+session_user; //place body here
            //var data = Encoding.ASCII.GetBytes(stringData); // or UTF8

            //request.Method = "POST";
            //request.ContentType = "application/form-data";
            //request.ContentLength = data.Length;

            //var newStream = request.GetRequestStream();
            //newStream.Write(data, 0, data.Length);

            //WebResponse response = request.GetResponse();
            //newStream = response.GetResponseStream();

            //StreamReader sr = new StreamReader(newStream);
            //Console.WriteLine(sr.ReadToEnd());
            //sr.Close();
            //newStream.Close();

            //var url = "https://amu.tkg.ovh/scripts/band/secure_addBand.php?session_key="+session_key+"&session_user="+session_user;
            //var uriRequest = new Uri(url);

            //var content = new FormUrlEncodedContent(new[]
            //{
            //    new KeyValuePair<string, string>("name", band.Name),
            //    new KeyValuePair<string, string>("leader_id", band.Leader_ID.ToString()),
            //    new KeyValuePair<string, string>("website_url", band.Website_Url),
            //    new KeyValuePair<string, string>("notes", band.Notes),
            //    new KeyValuePair<string, string>("leader_username", band.Leader_Username)
            //});

            //using (var httpClient = new HttpClient())
            //{
            //    var httpResponse = httpClient.PostAsync(uriRequest, content);
            //}

            
            using (WebClient webClient = new WebClient())
            {
                string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/band/secure_addBand.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                    {"name", band.Name},
                    {"leader_id", band.Leader_ID.ToString()},
                    {"website_url", band.Website_Url },
                    {"notes", band.Notes },
                    {"leader_username", band.Leader_Username }
                }));
                Console.WriteLine(response + "--------");
                //Gruppen müssen neu geladen werden im MainWindow.
            }
        }

        private void Open_File_Dialog(object sender, RoutedEventArgs e)
        {
            OpenFileDialog opf = new OpenFileDialog();

            if (opf.ShowDialog() == true)
            {
                logo_path = opf.FileName;
            }
        }
    }
}
