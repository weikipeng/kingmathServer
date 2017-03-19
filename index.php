<?php
require_once("license/License.php");
require_once("db/db.php");

header('Content-Type: application/json; charset=utf-8');
$tempLicense = new License();
$tempLicense->cellPhone = $_POST["c"];
$tempLicense->licenseCode = $_POST["l"];
$tempLicense->sign = $_POST["s"];
echo json_encode($tempLicense);
?>


