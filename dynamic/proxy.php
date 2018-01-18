<?php

$Page = substr($_GET["page"],0,50);
$Parameters = substr($_GET["params"],0,200);

$params = explode(";",$Parameters);
//var_dump($params);

if ($Page == "spbMetro3sBlock" )     { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/spbMetro3sBlocks.php'); }
elseif ($Page == "spbMetro4sBlock" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/spbMetro4sBlocks.php'); }
elseif ($Page == "spbMetroServersState" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/serversstate.php'); }
elseif ($Page == "spbMetroServersStateCompact" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/serversstatecompact.php'); }
elseif ($Page == "spbMetroServersInfo" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/serversinfo.php'); }
elseif ($Page == "spbMetroServersDiagDetails" ) { echo $homepage = file_get_contents('http://192.168.51.92/dynamic/serversdiagdetails.php?Server='.$params[0].'&MetricGroup='.$params[1].'&Metric='.$params[2]); }





?>