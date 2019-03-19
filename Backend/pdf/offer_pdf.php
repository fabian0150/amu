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

        $band_str = '';
        $date_location_str = '';

		$query = "SELECT location_id, user_id, offer_date, DATE(record_date) as 'record_date' FROM TBL_OFFER WHERE ID=" . $id . " LIMIT 1;"; 
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

        $query = "SELECT ob.band_id, ob.price, b.website_url, b.name, b.logo_path FROM TBL_OFFER_BANDS ob JOIN TBL_BANDINFO b ON b.ID = ob.band_id WHERE offer_id=" . $id . ";";
		if ($result = mysqli_query($db, $query)){

            
			while ($row = mysqli_fetch_assoc($result)) {

                $band_id = $row['band_id'];
                $band_homepage = $row['website_url'];
                $band_name = $row['name'];
                $band_logo = $row['logo_path'];
                $band_price = $row['price'];

                if($band_homepage == "") {
                    $band_homepage = "Keine Homepage";
                }
                if($band_price <= 0) {
                    $band_price = 0.00;
                }

                $band_str .= '<tr style="height: 13px;">
                                <td style="width: 324px; height: 13px;">' . $band_name .'</td>
                                <td style="width: 324px; height: 13px;"><a href="' . $band_homepage . '">' . $band_homepage . '</a></td>
                                <td style="width: 325px; height: 13px;">' . $band_price . '&euro;</td>
                            </tr>';

              
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
            $address_str . 
            '<h1>Angebot</h1>
            <p>Folgende Gruppen sind an diesem Termin noch vorl&auml;ufig verf&uuml;gbar und w&uuml;rden sich freuen bei Ihrer Veranstaltung musikalisch dabei zu sein:</p>
            <p><span style="text-decoration: underline;"><em>' . $date_location_str . ' </em></span></p>
            
            <table style="height: 72px;" width="900">
                <tbody>
            
                ' . $band_str . '
               
                </tbody>
            </table>
            
            
           
            <p>&nbsp;</p>
            <p>N&auml;here Infos und Demos finden Sie auf den angef&uuml;hrten Hompages der Bands. Ton -und Lichttechnik ist im Preis inkludiert. Au&szlig;er Verk&ouml;stigung der Musiker kommen keine weiteren Kosten auf Sie zu!</p>
            <p>F&uuml;r genaue Ausk&uuml;nfte &uuml;ber die einzelnen Bands stehe ich jederzeit gerne unter der Nummer</p>
            <p><strong>0664 161&nbsp;334 0 </strong></p>
            <p>zur Verf&uuml;gung.</p>
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
            
            <p>Peuerbach, am ' . $record_date . '</p>';

  



        $mpdf->SetTitle('Angebot - ' . $location_name . ' -' . $offer_date);
        $mpdf->WriteHTML($document);
        $mpdf->Output();

    } else {
        echo "false";
    }




   

   

    

?>