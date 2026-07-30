<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";

try {
  $conn = new PDO("mysql:host=$servername;port=3306", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "CREATE DATABASE IF NOT EXISTS taller_mecanico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
  $conn->exec($sql);
  echo "Database created successfully\n";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  exit(1);
}
?>
