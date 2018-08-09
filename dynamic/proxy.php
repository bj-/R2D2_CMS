<?php
/***************************************************************************
 *                                proxy.php
 *                            -------------------
 *   begin                : Jun 13, 2010
 *   copyright            : (C) 2010 The R2D2 Group
 *
 *   $Id: proxy.php,v 0.1.1 (alfa) 2010/08/31 17:17:40 $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   License
 *
 ***************************************************************************/
$GLOBALS["ttt"]=microtime();
$GLOBALS["ttt"]=((double)strstr($GLOBALS["ttt"], ' ')+(double)substr($GLOBALS["ttt"],0,strpos($GLOBALS["ttt"],' ')));


define('IN_R2D2', true);
include("../includes/config.php");
//include($DRoot . '/includes/extension.inc');
include($DRoot . '/includes/common.'.$phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);

init_userprefs($userdata);
//
// End session management
//
//if ($userdata['session_logged_in'] && substr($userdata['user_right'], 2, 1) == 1) 
//
if ($userdata['session_logged_in']) 
//if (true) 
{

	$Page = substr($_GET["page"],0,50);
	$Parameters = substr($_GET["params"],0,200);

	$params = explode(";",$Parameters);
	//var_dump($params);

	$rnd = rand ( 0 , 1000000000 );

	//echo 'http://192.168.51.92/dynamic/blockerrordetailsshowlog.php?rnd='.$rnd.'&id='.$params[0];

	if ($Page == "spbMetro3sBlock" )     { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/spbMetro3sBlocks.php?rnd='.$rnd.''); }
	elseif ($Page == "spbMetro4sBlock" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/spbMetro4sBlocks.php?rnd='.$rnd.''); }
	elseif ($Page == "spbMetroBlocks" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/spbMetroBlocks.php?rnd='.$rnd.''); }
	elseif ($Page == "spbMetroServersState" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/serversstate.php?rnd='.$rnd.''); }
	elseif ($Page == "spbMetroServersStateCompact" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/serversstatecompact.php?rnd='.$rnd.''); }
	elseif ($Page == "spbMetroServersInfo" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/serversinfo.php?rnd='.$rnd.''); }
	elseif ($Page == "spbMetroServersDiagDetails" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/serversdiagdetails.php?rnd='.$rnd.'&Server='.$params[0].'&MetricGroup='.$params[1].'&Metric='.$params[2]); }
//	elseif ($Page == "spbMetroBlockDiagDetails" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/blockdetails.php?rnd='.$rnd.'&Server='.$params[0]); }
	elseif ($Page == "spbMetroBlockErrorsDetails" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/blockerrordetails.php?rnd='.$rnd.'&Server='.$params[0].'&Service='.$params[1]); }
	elseif ($Page == "spbMetroBlockErrorsDetailsShowLog" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/blockerrordetailsshowlog.php?rnd='.$rnd.'&id='.$params[0]); }
	elseif ($Page == "BlockHistory" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/blockconfighistory.php?rnd='.$rnd.'&id='.$params[0]); }
	elseif ($Page == "BlockStatHistory" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/blockstathistory.php?rnd='.$rnd.'&id='.$params[0]); }
	elseif ($Page == "BlockDetails" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/blockdetails.php?rnd='.$rnd.'&Server='.$params[0]); }

	elseif ($Page == "BlockLogErrorsBySrvcStat" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/blockerrorstat.php?rnd='.$rnd.'&mode='.$params[0].'&sort='.$params[1]); }

	elseif ($Page == "Persons" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/persons.php?rnd='.$rnd); }
	elseif ($Page == "persons_about" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/persons_about.php?rnd='.$rnd.'&params='.$Parameters); }
	elseif ($Page == "persons_usedwagons" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/persons_usedwagons.php?rnd='.$rnd.'&params='.$Parameters); }
	elseif ($Page == "persons_checkhidbydate" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/persons_checkhid.php?rnd='.$rnd.'&params='.$Parameters); }
	elseif ($Page == "persons_checkhidbywagon" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/persons_checkhid.php?rnd='.$rnd.'&params='.$Parameters); }

	elseif ($Page == "test" ) { echo "TEST PAGE<br><pre>". var_dump($params) ."</pre>"; }

}
else
{
	//echo "Access Denied ! <br /><br /> Logon and special user right required. Please register. <a href='/userp.php?mode=register'><b>Registration</b></a>";
	echo "Access Denied ! <br /><br /> <a href='/login.php'>Logon</a> required. Please register. <a href='/userp.php?mode=register'><b>Registration</b></a>";
}

?>