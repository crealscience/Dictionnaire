 <?php
	if (preg_match_all('#DFSM/(..)/([^\.]*)$#', $_SERVER['REDIRECT_URL'], $match)) {
 	 //modification du code retour
 	 header("Status: 200 OK", false, 200);
 	 //alimentation du paramètre GET
 	 $_GET['langue'] = $match[1][0];
 	 $_REQUEST['langue'] = $match[1][0];
	 $_GET['type'] = $match[2][0];
	 $_REQUEST['type'] = $match[2][0];
  	 include('index.php');
	} elseif (preg_match_all('#DFSM/consult/(.*)$#', $_SERVER['REDIRECT_URL'], $match)) { 
	  $_GET['mot'] = $match[1][0];
          $_REQUEST['mot'] = $match[1][0];
	  include('index.php');
	} elseif (preg_match_all('#DFSM/AND/(.*)$#', $_SERVER['REDIRECT_URL'], $match)) { 
	  $_GET['motand'] = $match[1][0];
          $_REQUEST['motand'] = $match[1][0];
          include('index.php');
	 } elseif (preg_match_all('#Cahier/(..)/(.*)$#', $_SERVER['REDIRECT_URL'], $match)) {
          $_GET['cahier'] = $match[2][0];
          $_REQUEST['cahier'] = $match[2][0];
          $_GET['langue'] = $match[1][0];
          $_REQUEST['langue'] = $match[1][0];
	  include('index.php');
	} else {
	 $_GET['langue'] = 'fr';
         $_REQUEST['langue'] = 'fr';
         $_GET['type'] = '404';
         $_REQUEST['type'] = '404';
	  include('index.php');
	}
?> 
