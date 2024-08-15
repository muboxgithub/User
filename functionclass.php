<?php


include('connection.php');


/**
 * @param $conn is only pass for delate the suer after time
 * so use belowe function
 */


function DelateusersafterTime($conn){

    $sqldeltereject="DELETE FROM `transaction_bank` WHERE `Timestamp` < ?";


$startdate=new DateTime();
$startdate->sub(new DateInterval('PT24H'));

$stmtdeletereject=$conn->prepare($sqldeltereject);


$formateddate=$startdate->format('Y-m-d H:i:s');
$stmtdeletereject->bind_param('s',$formateddate);

$resultstmtdeltereject=$stmtdeletereject->execute();


if($resultstmtdeltereject=== true){
    // echo "delated successfully";
}
else{

    echo "erro ocurre for delating";
}


$stmtdeletereject->close();

}







?>