

<?php



$hostname='localhost';

$user='root';
$password='';

$dbname='etemar_transaction_backup';




$conn=mysqli_connect($hostname,$user,$password,$dbname);


if($conn->error){


    echo "errror ocurred".$conn->error;
}





?>