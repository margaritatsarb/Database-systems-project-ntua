<?php
$serverName = "YOUR SERVER NAME"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array( "Database"=>"YOUR DATABASE NAME");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn );
else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>
