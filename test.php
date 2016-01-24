<?php 
$mysqli = new mysqli('localhost', 'root', '', 'attwizard');
$result = $mysqli->query("SELECT * FROM jos_users");
echo $result->num_rows;