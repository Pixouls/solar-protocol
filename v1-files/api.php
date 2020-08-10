<?php
#comment out these lines for production version
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

$servername = "localhost";

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// If you change this value, the ESP32 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F9";

$api_key= $stamp = $ip = $mac = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fileName = "/home/pi/distributed-dynamic-IP-exchanger-API/v1-files/deviceList.json";

    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $stamp = test_input($_POST["stamp"]);
        $ip = test_input($_POST["ip"]);
        $mac = test_input($_POST["mac"]);
        
        // Read the file contents into a string variable,
        // and parse the string into a data structure
        $str_data = file_get_contents($fileName);
        $data = json_decode($str_data,true);
        
        var_dump($data);

        //loop through to check if entry with mac address exists
        $newMac = true;
        for ($i = 0; $i < sizeof($data['deviceList']);$i++){
          if($data[0][$i]['mac']==$mac){
              $data[0][$i]['ip']= $ip;
              $data[0][$i]['time stamp']= $stamp;
              $newMac = false;
              break;
          }
        }
        if ($newMac == true){
          $newEntry = [
            "mac" => $mac,
            "ip" => $ip,
            "time stamp" => $stamp
          ];
          array_push($data['deviceList'], $newEntry);
        }

        var_dump($data);

         
 /*        
        $fh = fopen("ipList.json", 'w')
              or die("Error opening output file");
        fwrite($fh, json_encode($data,JSON_UNESCAPED_UNICODE));
        fclose($fh);*/
        
    }
    else {
        echo "Wrong API Key provided.";
    }
}

else if ($_SERVER["REQUEST_METHOD"] == "GET") {

    //read the value of the query string, replce - with spaces
    //echo $_GET["value"];
    $qValue = str_replace("-"," ",$_GET["value"]);
    //echo $qValue;

    $fileDate = date("Y-m-d");
    $fileName = "/home/pi/EPSolar_Tracer/data/tracerData" . $fileDate . ".csv";
    $rawDataArray = [];

    if (($h = fopen("{$fileName}", "r")) !== FALSE) 
    {
      // Each line in the file is converted into an individual array that we call $data
      // The items of the array are comma separated
      while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
      {
        // Each individual array is being pushed into the nested array
        $rawDataArray[] = $data;        
      }

      // Close the file
      fclose($h);
    
      //return most recent voltage
        //foreach($rawDataArray[0] as $valueName){
        for ($v = 0; $v < sizeof($rawDataArray[0]);$v++){
            if($rawDataArray[0][$v]==$qValue){
                echo $rawDataArray[count($rawDataArray)-1][$v];
                break;
            }
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
