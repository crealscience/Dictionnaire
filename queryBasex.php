<!-- Xavier-Laurent Salvador -->
<?php
function queryBasex($xq) {
        $ret = "";
        try {
                $session = new Session("localhost", xxx, "xxx", "xxx");
                //$session = new Session("www.i-def.fr", 1984, "admin", "admin");
                $mess0 = $session->info();
                $input = $xq ;
                $session->execute('open xxx');
                $mess1 =  $session->info();
                $query = $session->query($input);
                $ret = $query->execute();
                $query->close();
                $session->close();
        } catch (Exception $e) {
                $mess3 = $e->getMessage();
        }
        //return $ret.'coucou<br/>('.$mess0.") ".$mess1.$mess2.$mess3;
	if (preg_match_all('#<orth>#', $ret, $matches)>3 && preg_match_all('#<sense>#', $ret, $matches)>6) {$ret='dump';}
        return $ret;
        //return "coucou";
}

function queryBasexBavard($xq) {
        $ret = "";
        try {
                $session = new Session("localhost", xxx, "xxx", "xxx");
                $mess0 = $session->info();
                $input = $xq ;
                $session->execute('open xxx');
                $mess1 =  $session->info();
                $query = $session->query($input);
                $ret = $query->execute();
                $query->close();
                $session->close();
        } catch (Exception $e) {
                $mess3 = $e->getMessage();
        }
        return $ret.$mess0.$mess1.$mess2.$mess3;
	if (preg_match_all('#<orth>#', $ret, $matches)>3) {$ret='dump';}
        return $ret;
}

function registerDate($x) {
         $xq = 'for $x in db:open("utilisateur")//entry
               [./nom="'.$_SESSION["login"].'"]/journal 
                return (
		replace value of node $x/date[@n="'.$x.'"] 
                with current-dateTime(),
		replace value of node $x/ip with "'.$_SERVER['REMOTE_ADDR'].'"
		) ';
	 queryBasex($xq);
}

function lockUser($u, $st) {
	$xq = 'for $x in db:open("utilisateur")//entry
               [./nom="'.$u.'"]/journal 
                return replace value of node $x/lock with "'.$st.'"
		';
	queryBasex($xq);
}

function testLockIn($user) {
	$xq = '
                            for $x in db:open("utilisateur")//entry[./nom="'.$user.'"]/journal
                                return
                                 if ($x/lock = "IN") 
                                  then
                                   let $year := fn:year-from-dateTime($x/date[@n="out"])
                                   let $month := fn:month-from-dateTime($x/date[@n="out"])
                                   let $day := fn:day-from-dateTime($x/date[@n="out"])
                                   let $hours := fn:hours-from-dateTime($x/date[@n="out"])
                                   let $minute := fn:minutes-from-dateTime($x/date[@n="out"])
                                   let $currentyear := fn:year-from-dateTime(fn:current-dateTime())
                                   let $currentmonth := fn:month-from-dateTime(fn:current-dateTime())
                                   let $currentday := fn:day-from-dateTime(fn:current-dateTime())
                                   let $currenthours := fn:hours-from-dateTime(fn:current-dateTime())
                                   let $currentminute := fn:minutes-from-dateTime(fn:current-dateTime())
                                   return       
                                     if ($year = $currentyear) 
                                        then 
                                          if ($month = $currentmonth) 
                                          then 
                                              if ($day = $currentday)
                                              then 
                                                 if ($hours = $currenthours)
                                                        then
                                                                if ($minute + 8 < $currentminute)
                                                                        then "OK"
                                                                        else "XXX" 
                                                        else 
                                                                if ($currentminute > 8) then "OK"
                                                                else "XXX"
                                              else "OK"
                                          else "OK"
                                        else "OK"
                                  else "OK"
                        ';
	$ret = queryBasex($xq);
	return $ret;
}

function resetUser($user) {
	  lockUser($user, "OUT");
	  registerDate("in");
	}

function registerIncident($user, $msg) {
	$adminmailhere = split('#',file_get_contents('TXT_SITE/adm.islx'));
	$xq ='
	  if (db:open("incident")/bdd/entry[./login="'.$user.'"][./desc/mess="'.$msg.'"]) 
	   then
	     for $x in db:open("incident")/bdd/entry[./login="'.$user.'"][./desc/mess="'.$msg.'"]/desc
	     let $n := number(xs:integer(data($x/@f))) + 1
	     return replace value of node $x/@f with $n 
	   else
	  let $n := <entry><desc f="1"><date>{fn:current-dateTime()}</date><mess>'.$msg.'</mess></desc><login>'.$user.'</login><ip>'.$_SERVER['REMOTE_ADDR'].'</ip></entry>
	  return insert node $n into db:open("incident")/bdd
	';
	queryBasexBavard($xq);
        foreach ($adminmailhere as $maila) {
          courriel($maila,'[ Crealscience ] - Incident ', 'Ip: ('.$user.'),'.$_SERVER['REMOTE_ADDR'].' :: '.$msg);
         }
}

function registerIncidentMessage($user, $msg) {
        $adminmailhere = split('#',file_get_contents('TXT_SITE/adm.islx'));
	$xq ='
          let $n := <entry><desc f="1"><date>{fn:current-dateTime()}</date><mess>'.$msg.'</mess></desc><login>'.$user.'</login><ip>'.$_SERVER['REMOTE_ADDR'].'</ip></entry>
          return insert node $n into db:open("incident")/bdd
        ';
        queryBasexBavard($xq);
	foreach ($adminmailhere as $maila) {
          courriel($maila,'[ Crealscience ] - Incident -', 'Pour: ('.$user.'), :: '.$msg);
         }
}


function ban($ip) {
	$xq = '
	  let $n := <entry><ip>'.$ip.'</ip></entry>
	  return insert node $n as first into db:open("bannis")/bdd
	';
	queryBasex($xq);
}

function testBan($ip) {
        $xq = '
          if (db:open("bannis")/bdd/entry[./ip = "'.$ip.'"]) 
		then "ALERT"
		else "OK"
        ';
        $ret = queryBasex($xq);
	return $ret;
}
?>
