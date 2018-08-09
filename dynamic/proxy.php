<?php

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
elseif ($Page == "spbMetroBlockDiagDetails" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/blockdetails.php?rnd='.$rnd.'&Server='.$params[0]); }
elseif ($Page == "spbMetroBlockErrorsDetails" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/blockerrordetails.php?rnd='.$rnd.'&Server='.$params[0]); }
elseif ($Page == "spbMetroBlockErrorsDetailsShowLog" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/blockerrordetailsshowlog.php?rnd='.$rnd.'&id='.$params[0]); }


?>