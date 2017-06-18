<!-- Xavier-Laurent Salvador, 2017 -->
<!-- Voir http://thisinterestsme.com/php-timer-countdown/ -->
<?php
	include('queryBsx.php');
	session_start();

	if (testBan($_SERVER['REMOTE_ADDR']) == 'ALERT') {exit ('Un problème VRU (utilisateur banni) est enregistre. Merci de contacter l\'administration.');}
	if ($_SESSION['authentification'] == 'OK') {	
	if (!isset($_SESSION['time'])) { // Check to see if variable is set
         $_SESSION['time'] = date('His'); // Format of time is 24hminsec (183412)
         //echo $_SESSION['time'];
        }
         else {
         // Is span less than 5 sec?
          if (abs($_SESSION['time'] - date('His')) < 2) {
          //echo "<div id='connect'>Vous devez attendre 25 secondes</div>";
           if ($_SESSION['login'] != 'xavier') {$_SESSION["kickOff"] ++;}
           //echo $_SESSION['kickOff'];
            if ($_SESSION['kickOff'] == 15) {
             header('location:http://www.crealscience.fr/DFSM/fr/TOE');
            } 
	     elseif ($_SESSION['kickOff'] > 40) {
		registerDate('out');
		registerIncident($_SESSION['login']."(".$_SERVER['REMOTE_ADDR'].")",'utilisation abusive');
		lockUser($_SESSION['login'],'IN');
		session_destroy();
	  }
         }
          else {
           $_SESSION['time'] = date('His'); // Update time of last request
         }
        }

	}

	else {
	 // Open/create a file using the user's IP as the file name.
	 // c+b flag opens new file if file doesn't exist.
	 // Allows for both read and write without resetting file size to 0.
	 $ipfh = fopen("logs/".$_SERVER['REMOTE_ADDR'].".txt", 'c+b');
 
	 // Look for time in file (read first 6 bytes/characters).
	 $storedtime = fread($ipfh, 6);
 
	 // If current time minus stored time is smaller than 5 seconds
	 if (abs(date('His') - $storedtime) < 2) {
	 fclose($ipfh); // Close file
	 registerIncident(strval($_SERVER['REMOTE_ADDR']),"Utilisation abusive");
	 $_SESSION['kickOff'] ++;
	 if ($_SESSION['kickOff'] > 12) {
	   ban($_SERVER['REMOTE_ADDR']);
	   exit ('violation des règles de sécurité');
	  }
	 //exit("You must wait 1 seconds between requests.");
	 header('location:http://www.crealscience.fr/DFSM/fr/TOE');
	 }
 
	 else {
	 fseek($ipfh, 0); // Reset pointer to beginning of file.
	 fwrite($ipfh, date('His')); // Write time
	 fclose($ipfh); // Close file
	 }
	}
?>
