<?php
$mysqli = new mysqli("localhost", "root", "", "e_archive");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
} else {
    echo "Connected successfully!";
}
