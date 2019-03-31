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
    public partial class UserHinzufuegenWindow : Window
    {
        public UserHinzufuegenWindow()
        {
            InitializeComponent();
        }

        private void Btn_user_hinzufuegen_Click(object sender, RoutedEventArgs e)
        {
            User user = new User {//Felder dürfen nicht leer sein
                Name = txtbx_user_hinzufuegen_name.Text,
                Address = txtbx_user_hinzufuegen_adresse.Text,
                Mail = txtbx_user_hinzufuegen_email.Text,
                Notes = txtbx_user_hinzufuegen_notizen.Text,
                Phone_Number = txtbx_user_hinzufuegen_telefonnummer.Text,
                Username = txtbx_user_hinzufuegen_username.Text,
                User_Type = 1,
                User_Description = "In der Applikation erstellter User"
            };

            using (WebClient webClient = new WebClient()) //name, phone_number, address, wird nicht gespeichert.
            {
                string response = Encoding.UTF8.GetString(webClient
                    .UploadValues("https://amu.tkg.ovh/scripts/user/secure_register.php", 
                    new NameValueCollection() {
                    {"name", user.Name},
                    {"address", user.Address },
                    {"phone_number",user.Phone_Number },
                    {"notes", user.Notes },
                    {"email", user.Mail},
                    {"username", user.Username},
                    {"password_1", txtbx_user_hinzufuegen_passwort.Text},
                    {"password_2", txtbx_user_hinzufuegen_passwort.Text}
                }));
            }
        }
    }
}
