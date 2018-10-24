<?php # Script 8.2 - mysqli_connect.php

//This file contains the database access information.
//This file also establishes a connection to MySQL and selects the database.

//Set the database access information as constants:

/*
DEFINE ('DB_USER', 'capitabs_dcb');
DEFINE ('DB_PASSWORD', '!Cabs2019!');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'capitabs_upfrontedgewater');
 */


DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', '');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'upfrontedgewater');


$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR 

die ('Could not connect through connect.php to MySQL: '.mysqli_connect_error());
?>