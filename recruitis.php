<?php

//@ini_set( 'upload_max_size' , '64M' );
//@ini_set( 'post_max_size', '64M');

//if (isset($_POST['submit'])) {

	$note = "Import z eMan stránek proběhl úspěšně.";		// poznámka na případné chyby při odesílání

	$newArray = array();
	/*foreach($_FILES['form_fields']['name'] as $key => $name){
    	$totalFiles = count($_FILES['form_fields']['name'][$key]);
    	print_r($totalFiles);
    	echo "<br>----<br>";
    	for ( $i=0 ; $i < $totalFiles ; $i++) { */
    		//print_r($_FILES['form_fields']['name'][$key][$i]);

    		/*if(array_key_exists('form_fields', $_FILES)){
			    if ($_FILES['form_fields']['error'][$key][$i] === UPLOAD_ERR_OK) {
			       echo 'upload was successful';
			    } else {
			       die("Upload failed with error code " . $_FILES['form_fields']['error'][$key][$i]);
			    }
			}*/

	/*		$fileName = $_FILES['form_fields']['name'][$key][$i];
			if ($fileName != "") {
				$fileTmpPath = $_FILES['form_fields']['tmp_name'][$key][$i];
				$fileSize = $_FILES['form_fields']['size'][$key][$i];
				echo "file<br>";
				print_r($fileName); echo "-<br>";
				print_r($fileTmpPath); echo "-<br>";
				print_r($fileSize);
				if ($fileSize > 3000000) {
					$note .= "Vložená příloha je větší než 3MB - bylo odesláno pouze na eMan email.";
					break;
				}
				$data_from_file = file_get_contents($fileTmpPath);
    			$base64_string = base64_encode($data_from_file);
    			echo $base64_string;
    			$fileName = str_replace(' ', '_', $fileName); // Recruitis požaduje název bez mezer
				$newArray[] = Array (
				//'path' => $fileTmpPath."/".$fileName,
				//'path' => $fileTmpPath,
				'base64' => $base64_string,
				'filename' => $fileName,
				'size' => $fileSize
				);	
			}
			else {
				$newArray = [];
			}
			
		} */
    //}
    
    //$message = htmlspecialchars($_POST['fields']['message']['value']);
    // Zaznamenej dnesni datum k GDPR souhlasu
    //if (isset($_POST['fields']['field_ff334f7']['value'])) {
    //	date_default_timezone_set('Europe/Prague');
    //	$today = date('Y-m-d h:i:s');
    //}

    $today = date('Y-m-d h:i:s');

    //$linkedinUrl = $_POST['fields']['field_bb883f3']['value'];
    /* if( $linkedinUrl != "" ) {
	    $last = explode("/", $linkedinUrl, 3);
		if(isset($last[2])) { 
			if ( $last[2] == "") {
				$linkedinUrl = "";
				$note .= "Uchazeč zadal neplatnou adresu Linkedin profilu. Nebylo naimportováno.";
			}
		}
		else  { 
			$linkedinUrl = "";
			$note .= "Uchazeč zadal neplatnou adresu Linkedin profilu. Nebylo naimportováno.";
		}
	} */
	//else { echo "empty linkedin "; }

	//print_r($note);
	/*
    $data = [
    'job_id' => $_POST['fields']['field_a6e5050']['value'],
    'source_id' => 2161, // eMan kariérní stránky
    'name' => $_POST['fields']['name']['value'],
    'email' => $_POST['fields']['email']['value'],
    'phone' => $_POST['fields']['field_e2149fe']['value'],
    'linkedin' => $linkedinUrl,
    'extra' => 
    	[
    		Array
    		(
    		'note' => $note
    		),
    		Array
    		(
    		'tag' => "Import z eMan stránek poznámky."
    		)
    	],
    'cover_letter' => $message,
    'gdpr_agreement' =>
    	Array
    	(
    	'date_created' => $today
    	),
    'attachments' => $newArray
    ];
    */

add_action( 'elementor_pro/forms/new_record',  'send_data_using_webhook' , 10, 2 );
    
function send_data_using_webhook( $record , $handler ) {

    $data = [
    'job_id' => 417033,
    'source_id' => 2161, // eMan kariérní stránky
    'name' => "Test Webovky2",
    'email' => "test1@test.cz",
    'phone' => "+420700100100",
    'linkedin' => "",
    'extra' => 
    	[
    		Array
    		(
    		'note' => $note
    		),
    		Array
    		(
    		'tag' => "Import z eMan stránek poznámky."
    		)
    	],
    'cover_letter' => "Test importu",
    'gdpr_agreement' =>
    	Array
    	(
    	'date_created' => $today
    	),
    'attachments' => $newArray
    ];

    $body = wp_json_encode( $data );
	$endpoint = "https://app.recruitis.io/api2/answers";
	$options = [
		'method' => 'POST',
		'body' => $body,
		'headers' => [
  			'Content-Type' => 'application/json',
  			'Authorization' => 'Bearer 504b610c0c335c6c7da97569aab00c8c79cfb1f4.c.9150.27e8c7ab8be495619508478fc3a29f00',
		],
		'httpversion' => '1.0',
		'redirection' => 10,
		'timeout' => 45,
		'sslverify' => false
	];


$response = wp_remote_post( $endpoint, $options );

}

//echo "<br>";
//$postdata = json_encode($data);
//echo $postdata;
//echo "<br>";
echo '<pre>'; print_r($data); echo '</pre>';

/*$data2 = [
    'job_id' => 417033,
    'source_id' => 2161, // eMan kariérní stránky
    'name' => "Test Web",
    'email' => "test@test.cz",
    'phone' => "+420700300100",
    'linkedin' => "",
    'extra' => 
    	[
    		Array
    		(
    		'note' => $note
    		),
    		Array
    		(
    		'tag' => "Import z eMan stránek poznámky."
    		)
    	],
    'cover_letter' => "Test importu",
    'gdpr_agreement' =>
    	Array
    	(
    	'date_created' => $today
    	),
    'attachments' => $newArray
    ];

$postdata = json_encode($data2);

// Reseni dle orig crm.php souboru
$ch = curl_init();
curl_setopt_array($ch, array(
	CURLOPT_URL => 'https://app.recruitis.io/api2/answers',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_HTTPHEADER => array(
  	"Content-Type: application/json",
  	"Authorization: Bearer 504b610c0c335c6c7da97569aab00c8c79cfb1f4.c.9150.27e8c7ab8be495619508478fc3a29f00"
		),
	CURLOPT_POSTFIELDS => $postdata
	)
); 
*/

// curl_setopt reseni
/*
curl_setopt($ch, CURLOPT_URL, "https://app.recruitis.io/api2/answers");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: Bearer 504b610c0c335c6c7da97569aab00c8c79cfb1f4.c.9150.27e8c7ab8be495619508478fc3a29f00"
));*/


/*$response = curl_exec($ch);
curl_close($ch);

var_dump($response);

if ($response === FALSE) {
    echo 'An error has occurred: ' . curl_error($ch) . PHP_EOL;
}
else {
    echo $response;
}*/

//}

?>