<?php
#comment out these lines for production version
/*ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);*/

$servername = "localhost";

//CHANGE KEY AND USE ENVIRONMENTAL VARIABLES FOR LIVE VERSION!!! 
// If you change this value, the client keys need to match
$api_key_value = "tPmAT5Ab3j7F9";
//$api_key_value = getenv('SP_API_KEY'); //THIS LINE HASN'T BEEN TESTED

$api_key= $stamp = $ip = $mac = $name = $log = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $fileName = "/home/pi/solar-protocol/backend/api/v1/deviceList.json";

  $api_key = test_input($_POST["api_key"]);

  //check if key is correct
  if($api_key == $api_key_value) {

    //set variables to POST
    $stamp = test_input($_POST["stamp"]);
    $ip = test_input($_POST["ip"]);
    $mac = test_input($_POST["mac"]);
    $name = test_input($_POST["name"]);
    $log = test_input($_POST["log"]);

    // Read the file contents into a string variable,
    // and parse the string into a data structure
    $str_data = file_get_contents($fileName);
    $data = json_decode($str_data,true);
    
    //var_dump($data);

    $newEntry = [];

    //check if any content exists
    if (is_null($data)){
        $data = [[
          "mac" => $mac,
          "ip" => $ip,
          "time stamp" => $stamp,
          "name" => $name,
          "log" => $log
        ]];
    } else {
      //loop through to check if entry with mac address exists
      $newMac = true;
      for ($i = 0; $i < sizeof($data);$i++){
        if($data[$i]['mac']==$mac){
            $data[$i]['ip']= $ip;
            $data[$i]['time stamp']= $stamp;
            $data[$i]['name']= $name;
            $data[$i]['log']=$log;
            $newMac = false;
            break;
        }
      }
      //create a new entry if needed
      if ($newMac == true){
        $newEntry = [
          "mac" => $mac,
          "ip" => $ip,
          "time stamp" => $stamp,
          "name" => $name,
          "log" => $log
        ];
        array_push($data, $newEntry);
      }

      var_dump($data);
    }

    $fp = fopen($fileName, 'w') or die("Error opening output file");
    fwrite($fp, json_encode($data));
    fclose($fp);
  }
  else {
      echo "Wrong API Key provided.";
  }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {

  //read the value of the query string, replace "-" with " "
  //var_dump($_GET);

  //most recent PV Data queries
  if(array_key_exists("value", $_GET)){
    //echo "Key = Value";
  
    $qValue = str_replace("-"," ",$_GET["value"]);
    //echo $qValue;

    $readData = chargeControllerData();

    if ($readData != FALSE){    
      for ($v = 0; $v < sizeof($readData[0]);$v++){
          if($readData[0][$v]==$qValue){
              echo $readData[count($readData)-1][$v];
              break;
          }
      }
    }
  } 
  //get a line of current data file. "len" returns length of current file, "head" returns the column headers, "0" returns most recent line. Increments up for other lines.
  else if (array_key_exists("line", $_GET)) {
    //echo "Key = Line";
    
    $readData = chargeControllerData();

    if ($readData != FALSE){    
      
      if($_GET["line"] == "len"){//return the number of rows in the file
        echo count($readData);
      } if($_GET["line"] == "head"){//return the CSV data headers
        var_dump($readData[0]);
      } else {
        //returns raw line
//        var_dump($readData[count($readData)-1-$_GET["line"]]);

        $returnArray = array();
        //package line with headers
        for ($p = 0; $p<count($readData[0]);$p++){
          $returnArray[$readData[0][$p]] = $readData[count($readData)-1-$_GET["line"]][$p];
        }  
          $returnJSON = json_encode($returnArray);
          echo $returnJSON;
      }
    }
    //get a full file
  } /*else if (array_key_exists("file", $_GET)) {
    //echo "Key = File";
  }*/
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function chargeControllerData(){
  $fileDate = date("Y-m-d");
  $fileName = "/home/pi/solar-protocol/charge-controller/data/tracerData" . $fileDate . ".csv";
    
  $rawDataArray = [];

  if (($h = fopen("{$fileName}", "r")) !== FALSE) {
    // Each line in the file is converted into an individual array that we call $data
    // The items of the array are comma separated
    while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
    {
      // Each individual array is being pushed into the nested array
      $rawDataArray[] = $data;        
    }

    // Close the file
    fclose($h);

    return $rawDataArray;
  } else {
    return FALSE;
  }
}