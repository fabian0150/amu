<?php

    require_once '../vendor/autoload.php';

    require_once('../scripts/config.php');

    $mpdf = new \Mpdf\Mpdf(['tempDir' => '../pdf/data']);

    if(isset($_GET['id'])) {
		$id = $_GET['id'];
        $id = mysqli_real_escape_string($db, $id);
        
        $offer_date = "";
        $offer_time = "";
        $location_id = "";
        $location_name = "";
        $user_id = "";
        $user_name = "";
        $user_address = "";
        $record_date = "";
        $invoice_number = "";
        $invoice_date = "";

        $band_str = '';
        $date_location_str = '';
        $band_price = 0;

		$query = "SELECT location_id, user_id, offer_date, invoice_number, DATE(invoice_date) as 'invoice_date', DATE(record_date) as 'record_date' FROM TBL_OFFER WHERE ID=" . $id . " LIMIT 1;"; 
		if ($result = mysqli_query($db, $query)){
			while ($row = mysqli_fetch_assoc($result)) {
                $location_id = $row['location_id'];
                $user_id = $row['user_id'];
                $offer_date = $row['offer_date'];
                $offer_time = strstr($offer_date, ' ');
                $offer_date = strstr($offer_date, ' ', true);
                $offer_date = date("d.m.Y", strtotime($offer_date));
                $record_date = $row['record_date'];
                $record_date = date("d.m.Y", strtotime($record_date));


                $invoice_number = $row['invoice_number'];
                $invoice_date = $row['invoice_date'];
                $invoice_date = date("d.m.Y", strtotime($invoice_date));
                break;
            }
        }

        $query = "SELECT name FROM TBL_LOCATIONS WHERE ID=" . $location_id . " LIMIT 1;";
		if ($result = mysqli_query($db, $query)){
			while ($row = mysqli_fetch_assoc($result)) {
                $location_name = $row['name'];
                break;
            }
        }

        $query = "SELECT name, address FROM TBL_USERS WHERE ID=" . $user_id . " LIMIT 1;";
		if ($result = mysqli_query($db, $query)){
			while ($row = mysqli_fetch_assoc($result)) {
            
                $user_name = $row['name'];
                $user_address = $row['address'];
                break;
            }
        }

        $query = "SELECT ob.band_id, ob.price, b.website_url, b.name, b.logo_path FROM TBL_OFFER_BANDS ob JOIN TBL_BANDINFO b ON b.ID = ob.band_id WHERE offer_id=" . $id . " AND offer_band_chosen = 1 LIMIT 1;";
		if ($result = mysqli_query($db, $query)){

            
			while ($row = mysqli_fetch_assoc($result)) {

                $band_id = $row['band_id'];
                $band_homepage = $row['website_url'];
                $band_name = $row['name'];
                $band_logo = $row['logo_path'];
                $band_price = $row['price'];


              
            }
        }


       
        $fees = $band_price * 0.2;
    


        echo "FEES: " . $fees;
        echo " PRICE: " . $band_price;
        $date_location_str = $offer_date . ' ' . $offer_time . ' - ' . $location_name;

        $add_arr = preg_split('/\r\n|\r|\n/', $user_address);

        
     
 

        $address_str = '<table style="height: 72px;" width="347">
                            <tbody>
                            <tr style="height: 13px;">
                            <td style="width: 337px; height: 13px;"><strong>' . $user_name . '</strong></td>
                            </tr>
                            <tr style="height: 13px;">
                            <td style="width: 337px; height: 13px;">' . $add_arr[0] . '</td>
                            </tr>
                            <tr style="height: 13px;">
                            <td style="width: 337px; height: 13px;">' . $add_arr[1] . '</td>
                            </tr>
                            <tr style="height: 13px;">
                            <td style="width: 337px; height: 13px;">' . $add_arr[2] . '</td>
                            </tr>
                            </tbody>
                        </table>';
        
        $document = '<p>Bankverbindung: Raiffeisenbank Peuerbach, IBAN: AT93 3444 2000 0003, BIC: RZOOAT2L442, UID-Nr.: ATU45177204, Gerichtsstand: Grieskirchen</p>' .
            $address_str . 
            '<h1>Rechnung Nr. ' . $invoice_number . '</h1>
            <p>Für das Engagement am ' . $offer_date . ' stelle ich folgenden Betrag in Rechnung:</p>
          
            <table style="margin-left: auto; margin-right: auto; width: 479px; height: 38px;">
                <tbody>
                <tr style="height: 13px;">
                <td style="width: 152px; height: 13px;">Netto</td>
                <td style="width: 152px; text-align: right; height: 13px;">&euro;</td>
                <td style="width: 153px; height: 13px; text-align: right;">' . $band_price . '</td>
                </tr>
                <tr style="height: 13px;">
                <td style="width: 152px; height: 13px;">Ust. 20%</td>
                <td style="width: 152px; text-align: right; height: 13px;">&euro;</td>
                <td style="width: 153px; height: 13px; text-align: right;">' . $fees . '</td>
                </tr>
                <tr style="height: 13px;">
                <td style="width: 152px; height: 13px;">Brutto</td>
                <td style="width: 152px; text-align: right; height: 13px;">&euro;</td>
                <td style="width: 153px; height: 13px; text-align: right;">' . ($band_price + $fees) . '</td>
                </tr>
                </tbody>
            </table>
            
        
            
          
            
           
            <p>&nbsp;</p>
            <p>Ich bitte Sie diesen Betrag innerhalb der nächsten 8 Tage, mittels beiliegendem Zahlschein, zur Einzahlung zu bringen.</p>
            <p>&nbsp;</p>
            <p>In der Hoffnung auf weitere, gute Zusammenarbeit verbleibe ich</p>
          
            <p>&nbsp;</p>
            <p>Mit freundlichen Gr&uuml;&szlig;en,</p>
            <table style="height: 140px;" width="280">
            <tbody>
            <tr>
            <td style="width: 270px;">Music-Live K&uuml;nstleragentur</td>
            </tr>
            <tr>
            <td style="width: 270px;">Manfred Stehrlein</td>
            </tr>
            <tr>
            <td style="width: 270px;">A-4722 Peuerbach, Steindlbachweg 4</td>
            </tr>
            <tr>
            <td style="width: 270px;">Tel.: 0664 161 334 0</td>
            </tr>
            <tr>
            <td style="width: 270px;">E-Mail:&nbsp;<a href="mailto:office@music-live.at">office@music-live.at</a></td>
            </tr>
            <tr>
            <td style="width: 270px;">Homepage:&nbsp;<a href="http://www.music-live.at/">http://www.music-live.at/</a></td>
            </tr>
            <tr>
            <td style="width: 270px;">UID-Nr.: ATU5177204</td>
            </tr>
            </tbody>
            </table>
            
            <p>Peuerbach, am ' . $invoice_date . '</p>';

  



        $mpdf->SetTitle('Rechnung - ' . $location_name . ' -' . $invoice_date);
        $mpdf->WriteHTML($document);

        
        $mpdf->Output();

    } else {
        echo "false";
    }




   

   

    

?>