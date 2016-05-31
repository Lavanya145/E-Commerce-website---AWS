<html>
<head>
    <title>Profile</title>
    <link href="/css/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<b align = "right" id="logout"><a href="index.php">Log Out</a></b>

<div id="profile">
    
	<h2>Google App Engine</h2>
<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
use google\appengine\api\cloud_storage\CloudStorageTools;

$uptime_start = microtime(true);

$options = [ 'gs_bucket_name' => 'lav_store' ];
$upload_url = CloudStorageTools::createUploadUrl('/prof', $options);

echo "<form method='post' action='".$upload_url."' enctype='multipart/form-data'>";
echo "<label>Upload File:</label>";
echo "<input type='file' name='image'><br><br>";
echo "<input type='submit' value='Upload' name='submit'>";
echo "</form>";
echo "</div>";
echo "</body>";
echo "</html>";

if(isset($_POST['submit'])){
	
$image_name = @$_FILES['image']['name'];
$image_temp = @$_FILES['image']['tmp_name'];
$filetype = @$FILES['image']['type'];

$handle = fopen(@$_FILES['image']['tmp_name'], "r");

$target_dir = 'gs://lav_store/'.$image_name;

move_uploaded_file($image_temp,$target_dir);
echo "The file has been uploaded.\n";

$uptime_end = microtime(true);
$uptime = $uptime_end - $uptime_start;
echo "\nTime to upload is:".$uptime;

$db = mysql_connect(':/cloudsql/valid-shine-93823:shine','lav','lav123') or die("Could not connect.");
if(!$db){
    die("No Database");
}
if(!mysql_select_db("lavdb",$db)){
    die("No database selected.");
}
$deleterecords = "TRUNCATE TABLE csvdata";
mysql_query($deleterecords);
$flag=true;

//$import="LOAD DATA LOCAL INFILE '".$image_temp."' INTO TABLE data1 FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n'IGNORE 1 LINES(station,elevation,latitude,longitude,date,hpcp)";
//mysql_query($import,$db) or die(mysql_error());


if($filetype == 'csv'){
while (($data = fgetcsv($handle, 100100, ",")) !== FALSE) {
        if($flag){
        $flag=false;
        continue;
        }
        $import="INSERT into Input csvdata('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]')";
        mysql_query($import) or die(mysql_error());
    }
    fclose($handle);
    print "Import done";

}
}   
?>
