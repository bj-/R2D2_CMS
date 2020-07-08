<?php

$SQL_SERVER["Config"]["Server"] = "localhost";
$SQL_SERVER["Config"]["DataBase"]["Config"] = "Shturman3Diag";
$SQL_SERVER["Config"]["DataBase"] = "Shturman3Diag";
$SQL_SERVER["Config"]["UserName"] = "USER-php";
$SQL_SERVER["Config"]["Password"] = "PASSWORD";

$SQL_SERVER["Server"] = "localhost";
$SQL_SERVER["DataBase"] = "Shturman3Block";
$SQL_SERVER["UserName"] = "USER-php";
$SQL_SERVER["Password"] = "PASSWORD";

//$SQL_SERVER["SpbMetro-Anal"]["Server"] = "10.200.24.92";
$SQL_SERVER["SpbMetro-Anal"]["Server"] = "localhost";
$SQL_SERVER["SpbMetro-Anal"]["DataBase"]["Shturman"] = "Shturman3";
//$SQL_SERVER["SpbMetro-Anal"]["DataBase"]["Block"] = "Shturman3Block";
$SQL_SERVER["SpbMetro-Anal"]["DataBase"]["Block"] = "Shturman3Diag";
$SQL_SERVER["SpbMetro-Anal"]["DataBase"]["Diag"] = "Shturman3Diag";
$SQL_SERVER["SpbMetro-Anal"]["UserName"] = "USER-php";
$SQL_SERVER["SpbMetro-Anal"]["Password"] = "PASSWORD";

//$SQL_SERVER["SpbMetro3s-Root3"]["Server"] = "10.200.24.88";
$SQL_SERVER["SpbMetro3s-Root3"]["Server"] = "HOST_ADDRESS";
$SQL_SERVER["SpbMetro3s-Root3"]["DataBase"]["Shturman"] = "Shturman3";
//$SQL_SERVER["SpbMetro3s-Root3"]["DataBase"]["Block"] = "Shturman3";
$SQL_SERVER["SpbMetro3s-Root3"]["UserName"] = "USER-php";
$SQL_SERVER["SpbMetro3s-Root3"]["Password"] = "PASSWORD";

$SQL_SERVER["SpbMetro4s-Root4"]["Server"] = "HOST_ADDRESS";
$SQL_SERVER["SpbMetro4s-Root4"]["DataBase"]["Shturman"] = "Shturman_Metro";
//$SQL_SERVER["SpbMetro4s-Root4"]["DataBase"]["Block"] = "Shturman3";
$SQL_SERVER["SpbMetro4s-Root4"]["UserName"] = "USER-php";
$SQL_SERVER["SpbMetro4s-Root4"]["Password"] = "PASSWORD";

$SQL_SERVER["SpbMetro-sRoot"]["Server"] = "HOST_ADDRESS";
$SQL_SERVER["SpbMetro-sRoot"]["DataBase"]["Shturman"] = "Shturman3";
//$SQL_SERVER["SpbMetro3s-sRoot"]["DataBase"]["Block"] = "Shturman3";
$SQL_SERVER["SpbMetro-sRoot"]["UserName"] = "USER-php";
$SQL_SERVER["SpbMetro-sRoot"]["Password"] = "PASSWORD";


$CONFIG_SHTURMAN["UploadedFilesDirPathLocal"] = "D:\\BlockUpload\\";

$CONFIG_SHTURMAN["BlocksFilter"] = "( [s].[Alias] LIKE 'STB%' OR [s].[Alias] LIKE 'BDL%' OR [s].[Alias] LIKE 'DBL%' )";
$CONFIG_SHTURMAN["BlocksFilterExclude"] = "'STB008626', 'STB2278'";

$CONFIG_SHTURMAN["Server_Name"] = "spbMetro";

$CONFIG_SHTURMAN["Block_Config_Report_Path"] = "D:\\BlockUpload";

$CONFIG_SHTURMAN["Block_Col_Route"] = "1";
$CONFIG_SHTURMAN["Block_Col_PositionLost"] = "1";


//******************************************
//                 Styles
//*****************************************/

$style["bg-lllll-purple"] = "background-color:#FFEEFF;";
$style["bg-llll-purple"] = "background-color:#FFCCFF;";
$style["bg-lll-purple"] = "background-color:#FFAAFF;";
$style["bg-ll-purple"] = "background-color:#FF88FF;";
$style["bg-l-purple"] = "background-color:#FF66FF;";
$style["bg-purple"] = "background-color:#FF00FF;";

$style["bg-lllll-puple"] = $style["bg-lllll-purple"];
$style["bg-llll-puple"] = $style["bg-llll-purple"];
$style["bg-lll-puple"] = $style["bg-lll-purple"];
$style["bg-ll-puple"] = $style["bg-ll-purple"];
$style["bg-l-puple"] = $style["bg-l-purple"];
$style["bg-puple"] = $style["bg-purple"];

$style["bg-lllll-blue"] = "background-color:#EEEEFF;";
$style["bg-llll-blue"] = "background-color:#CCCCFF;";
$style["bg-lll-blue"] = "background-color:#AAAAFF;";
$style["bg-ll-blue"] = "background-color:#8888FF;";
$style["bg-l-blue"] = "background-color:#6666FF;";
$style["bg-blue"] = "background-color:#4444FF;";

$style["bg-lllll-lightblue"] = "background-color:#CEFFF1;";
$style["bg-llll-lightblue"] = "background-color:#A6E7FF;";
$style["bg-lll-lightblue"] = "background-color:#6CD7FF;";
$style["bg-ll-lightblue"] = "background-color:#42CBFF;";
$style["bg-l-lightblue"] = "background-color:#0DBCFF;";
$style["bg-lightblue"] = "background-color:#003EDF;";



$style["bg-lllll-red"] = "background-color:#FFe9e9;";
$style["bg-llll-red"] = "background-color:#FFcccc;";
$style["bg-lll-red"] = "background-color:#FFaaaa;";
$style["bg-ll-red"] = "background-color:#FF8888;";
$style["bg-l-red"] = "background-color:#FF6666;";
$style["bg-red"] = "background-color:#FF0000;";

$style["bg-lllll-yellow"] = "background-color:#FFFFe9;";
$style["bg-llll-yellow"] = "background-color:#FFFFcc;";
$style["bg-lll-yellow"] = "background-color:#FFFFaa;";
$style["bg-ll-yellow"] = "background-color:#FFFF88;";
$style["bg-l-yellow"] = "background-color:#FFFF66;";
$style["bg-yellow"] = "background-color:#FFFF00;";


$style["bg-lllll-green"] = "background-color:#e9FFe9;";
$style["bg-llll-green"] = "background-color:#ccFFcc;";
$style["bg-lll-green"] = "background-color:#aaFFaa;";
$style["bg-ll-green"] = "background-color:#88FF88;";
$style["bg-l-green"] = "background-color:#66FF66;";
$style["bg-green"] = "background-color:#00FF00;";

$style["bg-lllll-gray"] = "background-color:#e0e0e0;";
$style["bg-llll-gray"] = "background-color:#d0d0d0;";
$style["bg-lll-gray"] = "background-color:#c0c0c0;";
$style["bg-ll-gray"] = "background-color:#B0B0B0;";
$style["bg-l-gray"] = "background-color:#A0A0A0;";
$style["bg-gray"] = "background-color:#555555;";


$style["bg-ll-beige"] = "background-color:#FFFFB0;";
$style["bg-l-beige"] = "background-color:#F2F2BB;";
$style["bg-beige"] = "background-color:#E2E2AB;";
$style["bg-lime"] = "background-color:#B5F8AF;";
$style["bg-limelight"] = "background-color:#D0FBD3;";

$style["color-lllll-gray"] = "color:#e0e0e0;";
$style["color-llll-gray"] = "color:#d0d0d0;";
$style["color-lll-gray"] = "color:#c0c0c0;";
$style["color-ll-gray"] = "color:#B0B0B0;";
$style["color-l-gray"] = "color:#A0A0A0;";
$style["color-gray"] = "color:#555555;";

$style["color-lllll-red"] = "color:#FFe0e0;";
$style["color-llll-red"] = "color:#FFd0d0;";
$style["color-lll-red"] = "color:#FFc0c0;";
$style["color-ll-red"] = "color:#FFB0B0;";
$style["color-l-red"] = "color:#FFA0A0;";
$style["color-red"] = "color:#FF0000;";

$style["color-lllll-darkred"] = "color:#E0e0e0;";
$style["color-llll-darkred"] = "color:#E0d0d0;";
$style["color-lll-darkred"] = "color:#E0c0c0;";
$style["color-ll-darkred"] = "color:#E0B0B0;";
$style["color-l-darkred"] = "color:#E0A0A0;";
$style["color-darkred"] = "color:#E00000;";

$style["color-lllll-darkgreen"] = "color:#e0B0e0;";
$style["color-llll-darkgreen"] = "color:#d0B0d0;";
$style["color-lll-darkgreen"] = "color:#c0B0c0;";
$style["color-ll-darkgreen"] = "color:#B0B0B0;";
$style["color-l-darkgreen"] = "color:#A0B0A0;";
$style["color-darkgreen"] = "color:#00B000;";


$style["bg-light-green"] = "background-color:#aaFFaa;";
$style["color-dark-red"] = "color:#BB0000;";

$style["bold"] = "font-weight:bold;";
                                     	
// To Remove
$style["bg-light-light-red"] = "background-color:#FFe9e9;";
$style["bg-light-red"] = "background-color:#FFaaaa;";
$style["bg-darklight-red"] = "background-color:#FF6666;";


?>