<?php
require_once("math/KingRandom.php");
//require_once("license/License.php");
//require_once("db/db.php");
//
//header('Content-Type: application/json; charset=utf-8');
//$tempLicense = new License();
//$tempLicense->cellPhone = $_POST["c"];
//$tempLicense->licenseCode = $_POST["l"];
//$tempLicense->sign = $_POST["s"];
//echo json_encode($tempLicense);

//echo KingRandom::randKeyString(30);


//---------
//$stuff = array(
//    array( 'label' => 'name 1', 'value' => 1 ),
//    array( 'label' => 'name 2', 'value' => 2 ),
//    array( 'label' => 'name 3', 'value' => 3 ),
//);
//
//array_push($stuff, array('label' => 'name 4', 'value' => 4));
//echo json_encode( $stuff );

//---------
$stuff = array(
    'label' => 'name 3', 'value' => 3
);

$stuff["name"] = "value";
echo json_encode($stuff);

?>


