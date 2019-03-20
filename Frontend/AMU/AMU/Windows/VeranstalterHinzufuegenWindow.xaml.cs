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
    /// Interaktionslogik für UserHinzufuegenWindow.xaml
    /// </summary>
    public partial class VeranstalterHinzufuegenWindow : Window
    {
        public VeranstalterHinzufuegenWindow()
        {
            InitializeComponent();
        }

        private void VeranstalterSpeichern(object sender, RoutedEventArgs e)
        {
            User user = new User {//Felder dürfen nicht leer sein
                Name = txtbxName.Text,
                Address = txtbxAdresse.Text,
                Mail = txtbxMail.Text,
                Notes = txtbxNotizen.Text,
                Phone_Number = txtbxTelefonnummer.Text,
                Username = "Veranstalter",
                User_Type = 2,
                User_Description = "In der Applikation erstellter Veranstalter"
            };

            using (WebClient webClient = new WebClient()) //name, phone_number, address, wird nicht gespeichert.
            {
                string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/user/secure_register.php", new NameValueCollection() {
                    {"name", user.Name},
                    {"address", user.Address },
                    {"phone_number",user.Phone_Number },
                    {"notes", user.Notes },
                    {"email", user.Mail},
                    {"username", user.Username + "_" + user.Mail},
                    {"user_type", user.User_Type.ToString() },
                    {"user_description", user.User_Description },
                    {"password_1", "t7TCbM%3Ja" },
                    {"password_2", "t7TCbM%3Ja"}
                }));
                Console.WriteLine(response+"----------------------------");
            }
        }
    }
}
