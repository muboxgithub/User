<?php


$host='localhost';

$user='root';
$password='';


$database='moodle';



$conn2=mysqli_connect($host,$user,$password,$database);


if(!$conn2){

    echo "eror ocurred";
}



?>