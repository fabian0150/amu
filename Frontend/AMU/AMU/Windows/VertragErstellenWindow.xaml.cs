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
            lstbxTexte.Items.Add(textListe);
        }

        private void VertragErstellen(object sender, RoutedEventArgs e)
        {
            //https://amu.tkg.ovh/scripts/offer/secure_addOfferband.php 
            //https://amu.tkg.ovh/json/offer/_getOfferBands.php?id=ID
            Contract contract = new Contract {
                //BandID = 
            };
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
    }
}
