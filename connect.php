<?php
$servername = "attendancemsystem.cxueocagesiq.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "October92002";

try {
  $conn = new PDO("mysql:host=$servername;dbname=attsystem", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>