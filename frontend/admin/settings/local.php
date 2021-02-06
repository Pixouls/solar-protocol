<?php
  require_once '/home/pi/solar-protocol/frontend/admin/protect/protect.php';
  Protect\with('/home/pi/solar-protocol/frontend/admin/protect/form.php','admin');
?>

<html>
<body>


<?php

//local www directory
$localWWW = '/home/pi/local/www/';

$totalDiskSpace = $availableDiskSpace = "";

diskSpace();

function diskSpace(){
  $totalDiskSpace = disk_total_space("/");
  $availableDiskSpace = disk_free_space("/");
}

/*
//read local file
$localFile = '/home/pi/local/local.json';
$localInfo = json_decode(getFile($localFile), true);

$apiErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  for ($k = 0; $k < count(array_keys($_POST));$k++){
    //echo array_keys($_POST)[$k];

    if(isset($_POST['apiKey'])){
      if(empty($_POST['apiKey'])){
        $apiErr = "No data entered.";
      } else {
        $localInfo[array_keys($_POST)[$k]]= test_input($_POST[array_keys($_POST)[$k]]);
      }
    }else {
      $localInfo[array_keys($_POST)[$k]]= test_input($_POST[array_keys($_POST)[$k]]);
    }
  }

  file_put_contents($localFile, json_encode($localInfo, JSON_PRETTY_PRINT));

}

//this will display the api key so DO NOT print directly except for debugging
//echo json_encode($localInfo);


$locName = $locDescription = $locLocation = $locCity = $locCountry = $locLat = $locLong = "";

if (isset($localInfo["name"])){
  $locName = $localInfo["name"];
}

if (isset($localInfo["description"])){
  $locDescription = $localInfo["description"];
}

if (isset($localInfo["location"])){
  $locLocation = $localInfo["location"];
}

if (isset($localInfo["city"])){
  $locCity = $localInfo["city"];
}

if (isset($localInfo["country"])){
  $locCountry = $localInfo["country"];
}

if (isset($localInfo["lat"])){
  $locLat = $localInfo["lat"];
}

if (isset($localInfo["long"])){
  $locLong = $localInfo["long"];
}*/

//from index
function test_input($data) {
 /* $data = str_replace("\r", " ", $data) //rm line breaks
  $data = str_replace("\n", " ", $data) //rm line breaks
  $data = str_replace("  ", " ", $data) //replace double spaces with single space*/
  $data = str_replace(array("\n", "\r", "  "), ' ', $data);
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//from index
function getFile($fileName){
  //echo $fileName;
  try{
    return file_get_contents($fileName);
  }
  catch(Exception $e) {
    echo $fileName;
    return FALSE;
  }
}

?>

<h1><a href="/">Solar Protocol</a> - Admin Console</h1>
<span>Logged in as <?php echo $_SESSION["username"]?> <a href="?logout">(Logout)</a></span>

<p><a href="/admin">Network Activity</a> | <a href="/admin/local.php">Local Data</a> | <a href="/admin/settings">Settings</a></p>

<h2>Local Content</h2>

<p><? php echo $availableDiskSpace . " of " . $totalDiskSpace ?> 
</p>
<form method="post" id="updateLocalInfo" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

  <button type="submit">Upload</button>
</form>


<div style="padding: 10px; border: 5px solid red">
  <h3>Danger Zone</h3>
  <form method="POST" onsubmit="return confirm('Are you sure you want to change the API key?');">
    <button type="submit">Update</button>
  </form>
</div>
</body>
</html>
