using AMU.Dto;
using System;
using System.Collections.Generic;
using System.Collections.Specialized;
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
    /// Interaktionslogik für VeranstaltungsortHinzufügenWindow.xaml
    /// </summary>
    public partial class VeranstaltungsortHinzufuegenWindow : Window
    {
        private int contactPersonID = -1;
        private string session_key = "-1";
        private string session_user = "-1";
        public VeranstaltungsortHinzufuegenWindow(int contactID, string sessionKey, string sessionUser)
        {
            InitializeComponent();
            session_key = sessionKey;
            session_user = sessionUser;
            contactPersonID = contactID;
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        {
            Location location = new Location {
                Address = txtbxAdresse.Text,
                Name = txtbxVeranstaltungsname.Text,
                Contact_Person_ID = contactPersonID
            };
            POSTLocation(location);
        }

        private void POSTLocation(Location location)
        {

            using (WebClient webClient = new WebClient())
            {
                string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/location/secure_addLocation.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                    {"name", location.Name},
                    {"address", location.Address},
                    {"contact_person_id", location.Contact_Person_ID.ToString() }
                }));
                Console.WriteLine(response + "--------");
            }
        }
    }
}
