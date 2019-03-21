using AMU.Dto;
using Newtonsoft.Json.Linq;
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
    /// Interaktionslogik für VertragErstellenWindow.xaml
    /// </summary>
    public partial class VertragErstellenWindow : Window
    {
        private string session_key;
        private string session_user;
        Offer offer;

        public VertragErstellenWindow(Offer offerParam, string sessionKey, string sessionUser)
        {
            InitializeComponent();
            session_key = sessionKey;
            session_user = sessionUser;
            offer = offerParam;
            LoadTexte(offer);
            LoadBands(offer.ID.ToString());
            txtbxUhrzeit.Text = offer.OfferDate;
        }

        private void LoadBands(string id)
        {
            JArray arrayJSON = GET_Request("https://amu.tkg.ovh/json/offer/_getOfferBands.php?id=", id);

            for (int i = 0; i < arrayJSON.Count; i++)
            {
                JObject item = (JObject)arrayJSON[i];
                JArray arrayJSONBand = GET_Request("https://amu.tkg.ovh/json/band/_getBand.php?id=", (string)item.GetValue("band_id"));
                JObject itemBand = (JObject)arrayJSONBand[0];
                BandGage band = new BandGage
                {
                    Band = new Band
                    {
                        Name = (string)itemBand.GetValue("name"),
                        Logo_Path = (string)itemBand.GetValue("logo_path"),
                        Website_Url = (string)itemBand.GetValue("website_url"),
                        Notes = (string)itemBand.GetValue("notes"),
                        Leader_Username = (string)itemBand.GetValue("leader_username"),
                        Record_Date = (DateTime)itemBand.GetValue("record_date"),
                        ID = itemBand.Value<int?>("ID") ?? -1,
                        Leader_ID = itemBand.Value<int?>("leader_id") ?? -1
                    },
                    Gage = (string)item.GetValue("price")
            };
                lstbxBands.Items.Add(band);
            }
            
        }

        private void LoadTexte(Offer offer)
        {
            List<string> textListe = new List<string>();
            textListe.Add("Die Gage beträgt \t € XXX in Worten: EURO/XXX x x x");
            textListe.Add("Die Gage wird spätestens nach Beendigung des Engagements in bar ausbezahlt.");
            textListe.Add("Die Spielvergütung beträgt für jede weitere Spielstunde € XXX inkl.13% Ust..");
            textListe.Add("Nächtigung für 0 Personen wird vom Veranstalter organisiert und bezahlt.");
            textListe.Add("Essen und Getränke werden vom Veranstalter kostenlos zur Verfügung gestellt.");
            textListe.Add("Bei schuldhafter Nichterfüllung des Vertrages wird für beide Vertragspartner eine Pönale in der Höhe der vereinbarten Gage festgesetzt(ausgenommen Gründe höherer Gewalt).");
            textListe.Add("Gebühren (AKM) und behördliche Abgaben werden vom Veranstalter bezahlt.");
            textListe.Add("Sollte der Künstler verhindert sein (ausgenommen Gründe höherer Gewalt), so hat er unter diesen Umständen für einen entsprechenden Ersatz zu sorgen.");
            textListe.Add("Sollte der Vertrag nicht innerhalb von 3 Wochen ab Ausstellungsdatum retourniert werden, behält sich die Musikgruppe das Recht vor, vom Vertrag zurückzutreten.");
            textListe.ForEach(x=>lstbxTexte.Items.Add(x));
        }

        private void VertragErstellen(object sender, RoutedEventArgs e)
        {
            string s = txtbxAdresse.Text;
            Console.WriteLine();
            //https://amu.tkg.ovh/scripts/offer/secure_addOfferband.php 
            //https://amu.tkg.ovh/json/offer/_getOfferBands.php?id=ID
            //Contract contract = new Contract {
            //    lstbx 
            //};
            //https://amu.tkg.ovh/scripts/contract/secure_addContract.php
            //offer_id offer_band_id price
            //using (WebClient webClient = new WebClient())
            //{
            //    string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/appointment/secure_addAppointment.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
            //        {"band_id", offer.},
            //        {"location_id", "5"}, //5 steht für externe Veranstaltung
            //        {"appointment_date", date},
            //        { "location_name","[EXT.]" } //Externe Veranstaltung -> Termin wurde von Gruppe selber vermittelt
            //    }));
            //}
        }

        private void LstbxTexteSelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            txtbxVertragsinfos.Text = (String)lstbxTexte.SelectedItem;
        }

        private JArray GET_Request(string url, string parameter)
        {
            var rawJSON = new WebClient().DownloadString(url + parameter);
            return JArray.Parse(rawJSON);
        }

        private void LstbxBandsSelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            txtbxGage.Text = ((BandGage)lstbxBands.SelectedItem).Gage;
        }

        private void DetailsSpeichern(object sender, RoutedEventArgs e)
        {

        }
    }
}
