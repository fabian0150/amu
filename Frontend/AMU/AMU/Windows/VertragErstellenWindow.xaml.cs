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
        List<string> textListe = new List<string>();

        public VertragErstellenWindow(Offer offerParam, string sessionKey, string sessionUser)
        {
            InitializeComponent();
            session_key = sessionKey;
            session_user = sessionUser;
            offer = offerParam;
            LoadTexte();
            LoadBands(offer.ID.ToString());
            txtbxUhrzeit.Text = offer.OfferDate.Remove(0,11);
            lblVeranstaltung.Content = offer.VeranstaltungName;
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

        private void LoadTexte()
        {
            lstbxTexte.Items.Clear();
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
            using (WebClient webClient = new WebClient())
            {
                string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/contract/secure_addContract.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                    {"offer_id", offer.ID.ToString()},
                    {"offer_band_id", ((BandGage)lstbxBands.SelectedItem).Band.ID.ToString()},
                    {"price", txtbxGage.Text},
                    { "text_gage",textListe[0] },
                    { "text_paytype",textListe[1] },
                    { "text_more_hours",textListe[2] },
                    { "text_breakfast",textListe[3] },
                    { "text_food",textListe[4] },
                    { "text_punitive",textListe[5] },
                    { "text_fees",textListe[6] },
                    { "text_replacement",textListe[7] },
                    { "text_other",textListe[8] }
                }));
                Console.WriteLine();
            }

            using (WebClient webClient = new WebClient())
            {
                string response = Encoding.UTF8.GetString(webClient.UploadValues("https://amu.tkg.ovh/scripts/contract/secure_addContract.php?session_key=" + session_key + "&session_user=" + session_user, new NameValueCollection() {
                    {"offer_id", offer.ID.ToString()},
                    {"offer_band_id", ((BandGage)lstbxBands.SelectedItem).Band.ID.ToString()}, 
                    {"price", txtbxGage.Text},
                    { "text_gage",textListe[0] },
                    { "text_paytype",textListe[1] },
                    { "text_more_hours",textListe[2] },
                    { "text_breakfast",textListe[3] },
                    { "text_food",textListe[4] },
                    { "text_punitive",textListe[5] },
                    { "text_fees",textListe[6] },
                    { "text_replacement",textListe[7] },
                    { "text_other",textListe[8] }
                }));
                Console.WriteLine();
            }
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


        private void TextSpeichern(object sender, RoutedEventArgs e)
        {
            int index = (int)lstbxTexte.SelectedIndex;
            textListe[index] = txtbxVertragsinfos.Text;
            lstbxTexte.Items.Clear();
            textListe.ForEach(x => lstbxTexte.Items.Add(x));
        }
    }
}
