<?php
  //password protection
  require_once '/home/pi/solar-protocol/backend/protect/protect.php';
  Protect\with('/home/pi/solar-protocol/backend/protect/form.php','admin');


  if(isset($_POST['hash']) && isset($_POST['rehash']) && $_POST['hash'] != "" ) {

    if(testInput()){
      updateUserInfo($_SESSION["username"], password_hash($_POST['hash'], PASSWORD_DEFAULT));
    }
  } 



function updateUserInfo($un, $pwHash){
  $fileName = '/home/pi/local/access.json';

  try{
    $f = json_decode(file_get_contents($fileName),true);
    
    //var_dump($f);

    $f['users'][$un]['hash']=$pwHash;
    //var_dump($f);
    file_put_contents($fileName, json_encode($f, JSON_PRETTY_PRINT));

    echo "Password for user " . $_SESSION["username"] . " has been successfully changed.<br>";
  }
  catch(Exception $e) {

    echo "Error";

  }
}

//what are the criteria to test the password?
function testInput(){

  //check that passwords match
  if($_POST['hash'] != $_POST['rehash']){
    echo "Passwords do not match";
    return false;
  }

  //check for white spaces
  if(strpos($_POST['hash'],' ')){
    return false;
  }
  
  //check for characters
  /*if ($_POST['hash']) {
    echo "Passwords can only contain letters, numbers, etc.";
    return false; 
  }*/

  return true;
}

?>


<html>
<body>
<p>
  Submtting this form will change the password for the current user (<?php echo $_SESSION["username"]; ?>) only on this server. If you have forgotten your previous password, contact Alex, Benedetta, or Tega for instructions to manually reset the password. If you have access to multiple servers, you will need to manually update your password on each one.
</p>

<form method="POST" onsubmit="return confirm('Are you sure you want to change your password?');">

  <p>Enter new password to hash:</p>
  <input type="hash" name="hash" required>

  <p>Re-enter new password to hash:</p>
  <input type="hash" name="rehash" required>

  <button type="submit">Submit</button>
</form>

</body>
</html>