<?php
include('connection.php');

include('userenrol.php');

include('connection2.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_POST['userId'];
    $status = $_POST['status'];

    $moodleid=$_POST['moodleId'];

    $courseid=$_POST['courseId'];
    
    $sqlupdate = "UPDATE `transaction_bank` SET `Status`=? WHERE `Id`=?";
    $stmtstatus = $conn->prepare($sqlupdate);
    $stmtstatus->bind_param('si', $status, $userid);
    $resultstmt = $stmtstatus->execute();

    if ($resultstmt === true) {
       
        if($status==='accepted'){
            enrollUserInCourses($conn2,$moodleid,$courseid);
            echo "success";
            exit;
        }
        else if($status=== 'rejected'){
            deleteUserEnrollmentsAndRoles($conn2,$moodleid);
            echo "success";
            exit;
        }
      


    } else {
        echo "error";
    }

    $stmtstatus->close();
    $conn->close();
} else {
    echo "error";
}
?>
