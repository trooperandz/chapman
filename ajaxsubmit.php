<?php
$connection = mysql_connect("localhost", "ajaxtest", "Trooper4#"); // Establishing Connection with Server..
$db = mysql_select_db("ajaxtest", $connection); // Selecting Database

//Fetching Values from URL
$name2=$_POST['name1'];
$email2=$_POST['email1'];
$password2=$_POST['password1'];
$contact2=$_POST['contact1'];

//Insert query
$query = mysql_query("insert into form_element(name, email, password, contact) values ('$name2', '$email2', '$password2','$contact2')");
echo "Form submitted successfully";
mysql_close($connection); // Connection Closed
?>