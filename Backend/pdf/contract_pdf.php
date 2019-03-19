<?php

    require_once '../vendor/autoload.php';

    require_once('../scripts/config.php');

    $mpdf = new \Mpdf\Mpdf(['tempDir' => '../pdf']);

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

        $text_gage = "NULL";
        $text_paytype = "NULL";
        $text_more_hours = "NULL";
        $text_breakfast = "NULL";
        $text_food = "NULL";
        $text_punitive = "NULL";
        $text_fees = "NULL";
        $text_replacement = "NULL";
        $text_other = "NULL";

        $band_str = '';
        $date_location_str = '';

		$query = "SELECT location_id, user_id, offer_date, DATE(record_date) as 'record_date', text_gage, text_paytype, text_more_hours, text_breakfast, text_food, text_punitive, text_fees, text_replacement, text_other FROM TBL_OFFER WHERE ID=" . $id . " LIMIT 1;"; 
       
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


                $text_gage = $row['text_gage'];
                $text_paytype = $row['text_paytype'];
                $text_more_hours = $row['text_more_hours'];
                $text_breakfast = $row['text_breakfast'];
                $text_food = $row['text_food'];
                $text_punitive = $row['text_punitive'];
                $text_fees = $row['text_fees'];
                $text_replacement = $row['text_replacement'];
                $text_other = $row['text_other'];
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
        
        $document = 
           
            '<p style="text-align: center;">Künstleragentur <strong>Music-Live</strong>, Manfred Stehrlein, Steindlbachweg 4 A-4722 Peuerbach, Tel.: 0664 161 334 0, Fax: 07276 930 59</p>
            <h1 style="text-align: center;">Engagementsvertrag</h1>
            <h3 style="text-align: center;"><strong>Veranstalter:</strong> ' . $user_name . '</h3>
            <h3 style="text-align: center;"><strong>Künstler:</strong> ' . $band_name . '</h3>
            <hr>

            <p>1) <strong>Veranstaltungsdatum:</strong> ' . $offer_date . '</p>
            <p>2) <strong>Veranstalltungsort:</strong> ' . $location_name . '</p>
            <p>3) <strong>Auftrittszeit:</strong> ' . $offer_time . '</p>
            <p>4) ' . $text_gage . '</p>
            <p>5) ' . $text_paytype . '</p>
            <p>6) ' . $text_more_hours . '</p>
            <p>7) ' . $text_breakfast . '</p>
            <p>8) ' . $text_food . '</p>
            <p>9) ' . $text_punitive . '</p>
            <p>10) ' . $text_fees . '</p>
            <p>11) ' . $text_replacement . '</p>
            <p>12) Für Rechtsstreitigkeiten aus diesem Vertrag wird als Gerichtsstand das örtlich zuständige Gericht des Künstlers vereinbart.</p>
            <p>13) ' . $text_other . '</p>


            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
           

          
            <table style="height: 11px;" width="1033">
                <tbody>
                <tr style="height: 13px;">
                <td style="width: 337px; height: 13px; text-align: center;">________________________________________</td>
                <td style="width: 337px; height: 13px; text-align: center;">________________________________________</td>
                <td style="width: 337px; height: 13px; text-align: center;">________________________________________</td>
                </tr>
                <tr style="height: 25px;">
                <td style="width: 337px; text-align: center; height: 25px;">Veranstalter</td>
                <td style="width: 337px; text-align: center; height: 25px;">Datum</td>
                <td style="width: 337px; text-align: center; height: 25px;">K&uuml;nstler</td>
                </tr>
                </tbody>
            </table>';

  



        $mpdf->SetTitle('Vertrag - ' . $location_name . ' - ' . $offer_date);
        $mpdf->WriteHTML($document);
        $mpdf->Output();

    } else {
        echo "false";
    }




   

   

    

?>