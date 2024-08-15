<?php
include('connection.php');

include('userenrol.php');

include('connection2.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userids = $_POST['userIds'];
    $status = 'accepted';

    $userCourses=$_POST['userCourses'];
    

    $successCount=0;
    foreach($userids as $userid){
        

        $sqlupdate = "UPDATE `transaction_bank` SET `Status`=? WHERE `Id`=?";
        $stmtstatus = $conn->prepare($sqlupdate);
        $stmtstatus->bind_param('si', $status, $userid);
        $resultstmt = $stmtstatus->execute();
    
        if ($resultstmt === true) {
            $successCount++; 
        } else {
            echo "error";
        }
    }

    if($successCount=== count($userids)){
        
        EnrolMultipleUsersInCourse($conn2,$userCourses);
        echo "success";

    }



    $stmtstatus->close();
    $conn->close();
} else {
    echo "error";
}


?>
