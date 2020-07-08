<?php
                             
function MSSQLconnect( $Server, $DBname )
{
	global $SQL_SERVER, $CONFIG_SHTURMAN; //Server, SQL_DataBase, SQL_UserName, SQL_Password

	if ( $Server == "Config" and $DBname == "Config" )
	{
		$SQL_Server = $SQL_SERVER["Config"]["Server"];
		$SQL_DataBase = $SQL_SERVER["Config"]["DataBase"];
		$SQL_UserName = $SQL_SERVER["Config"]["UserName"];
		$SQL_Password = $SQL_SERVER["Config"]["Password"];
		
		//$Server = $SQL_SERVER[$Server]["DataBase"][$DBname];
	}
	else
	{
/*
		$SQL_Server = $SQL_SERVER[$Server]["Server"];
		if ( $Server == "SpbMetro-Anal" or $Server == "Anal" ) { $SQL_Server = $CONFIG_SHTURMAN["SQL_Server_Shturman_Anal"]; }
		elseif ( $Server == "SpbMetro-sRoot" or $Server == "Root" ) { $SQL_Server = $CONFIG_SHTURMAN["SQL_Server_Shturman_Root"]; }
		elseif ( $Server == "Diag" ) { $SQL_Server = $CONFIG_SHTURMAN["SQL_Server_Shturman_Root"]; }
		elseif ( $Server == "DiagCentral" ) { $SQL_Server = $CONFIG_SHTURMAN["SQL_Server_Shturman_Root"]; }
*/
		$xServer = $Server;
		if ( $Server == "SpbMetro-Anal" ) { $Server = "Shturman_Anal"; }
		elseif ( $Server == "SpbMetro-sRoot" ) { $Server = "Shturman_Root"; }
		$SQL_Server = $CONFIG_SHTURMAN["SQL_Server_".$Server];
		
		//$SQL_DataBase = $SQL_SERVER[$xServer]["DataBase"][$DBname];
		if ( $DBname == "Shturman" ) { $DBname = $Server; }
		elseif ( $DBname == "Block" ) { $DBname = "Diag"; }
		$SQL_DataBase = $CONFIG_SHTURMAN["SQL_DB_".$DBname];

		$SQL_UserName = $CONFIG_SHTURMAN["SQL_UserName_ShturmanDB"];
		$SQL_Password = $CONFIG_SHTURMAN["SQL_UserPass_ShturmanDB"];
		//$SQL_UserName = $SQL_SERVER[$Server]["UserName"];
		//$SQL_Password = $SQL_SERVER[$Server]["Password"];
	}

	//$SQL_UserName = $CONFIG_SHTURMAN["SQL_UserName_ShturmanDB"];
	//$SQL_UserPass = $CONFIG_SHTURMAN["SQL_UserPass_ShturmanDB"];
	

	$serverName = "localhost"; //serverName\instanceName
//	$connectionInfo = array( "Database"=>$SQL_SERVER[$Server]["DataBase"][$DBname], "UID"=>$SQL_SERVER[$Server]["UserName"], "PWD"=>$SQL_SERVER[$Server]["Password"]);
	$connectionInfo = array( "Database"=>$SQL_DataBase, "UID"=>$SQL_UserName, "PWD"=>$SQL_Password);
	$conn = sqlsrv_connect( $SQL_Server, $connectionInfo);

	if( $conn ) {
	     //echo "Connection established.<br />";
	}else{
	     echo "Connection could not be established.<br />";
	     die( print_r( sqlsrv_errors(), true));
	}

	return $conn;

}

function MSSQLsiletnQuery($sql)
{
	global $conn;

        // Just Execute query with out any answer
	$stmt = sqlsrv_query( $conn, $sql);
	if( $stmt === false ) 
	{
		echo "<pre>\nMSSQLsiletnQuery: can not execute query\n"; 
		die( print_r( (sqlsrv_errors()), true)); 
		echo "\n</pre>"; 
		return false;
	}
}

?>