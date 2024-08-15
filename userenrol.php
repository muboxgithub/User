<?php




/**
 * function for enrolling the user when the button accept click
 * @param $conn this connection from connection2.php
 * @param $userid this user id is from moodle 
 * @param $couseids $this course ids is in array formar
 */
function enrollUserInCourses($conn, $userid, $courseids) {
    $response = array();

    foreach ($courseids as $courseid) {
        // Fetch the enrolment method (manual) for the course
        $sqlEnrol = "SELECT id FROM `mdl_enrol` WHERE `courseid` = ? AND enrol = 'manual'";
        $stmtEnrol = $conn->prepare($sqlEnrol);
        $stmtEnrol->bind_param('i', $courseid);
        $stmtEnrol->execute();
        $resultEnrol = $stmtEnrol->get_result();

        if ($resultEnrol->num_rows > 0) {
            while ($rowEnrol = $resultEnrol->fetch_assoc()) {
                $enrolid = $rowEnrol['id'];
                $timecreated = time();
                $timemodified = time();
                $timestart = time();

                // Insert user enrolment
                $sqlInsertEnrolment = "INSERT INTO `mdl_user_enrolments` (`enrolid`, `userid`, `timestart`, `timecreated`, `timemodified`) VALUES (?, ?, ?, ?, ?)";
                $stmtInsertEnrolment = $conn->prepare($sqlInsertEnrolment);
                $stmtInsertEnrolment->bind_param('iiiii', $enrolid, $userid, $timestart, $timecreated, $timemodified);
                
                if ($stmtInsertEnrolment->execute()) {
                    // Fetch contextid for the course
                    $sqlContextid = "SELECT id FROM `mdl_context` WHERE `contextlevel` = 50 AND `instanceid` = ?";
                    $stmtContextid = $conn->prepare($sqlContextid);
                    $stmtContextid->bind_param('i', $courseid);
                    $stmtContextid->execute();
                    $resultContextid = $stmtContextid->get_result();

                    if ($resultContextid->num_rows > 0) {
                        while ($rowContextid = $resultContextid->fetch_assoc()) {
                            $contextid = $rowContextid['id'];
                            $roleid = 5; // Assuming 5 is the role ID for students
                            $modifierid = 2; // Assuming 2 is the modifier ID
                            $timemodified = time();

                            // Insert the user in role assignment
                            $sqlInsertRoleassignment = "INSERT INTO `mdl_role_assignments` (`roleid`, `contextid`, `userid`, `timemodified`, `modifierid`) VALUES (?, ?, ?, ?, ?)";
                            $stmtRoleassign = $conn->prepare($sqlInsertRoleassignment);
                            $stmtRoleassign->bind_param('iiiii', $roleid, $contextid, $userid, $timemodified, $modifierid);

                            if ($stmtRoleassign->execute()) {
                                $response[] = array(
                                    'message' => "User enrolled successfully in course ID $courseid and assigned role",
                                );
                            } else {
                                $response[] = array(
                                    'message' => "Role assignment failed for course ID $courseid",
                                );
                            }
                        }
                    } else {
                        $response[] = array(
                            'message' => "Context not found for course ID $courseid",
                        );
                    }
                } else {
                    $response[] = array(
                        'message' => "Enrolment failed for course ID $courseid",
                    );
                }
            }
        } else {
            $response[] = array(
                'message' => "No manual enrolment found for course ID $courseid",
            );
        }
    }

    return $response;
}

// Example usage




/**
 * 
 * 
 * method for unreol
 * user when rejeccted button is clciked
 * so only delete the user id fron mdl-userenrolmetn and mdl_role assignment table
 * @param mysqli $connection
 * @param  userid of moodle
 * @param return a message
 */

//the user is id the moodle user id
 function  deleteUserEnrollmentsAndRoles ($conn, $userid){



    $sqlDuserenr="DELETE FROM `mdl_user_enrolments` WHERE `userid`=?";

    $stmtDuserenr=$conn->prepare($sqlDuserenr);

    $stmtDuserenr->bind_param('i',$userid);
    $resultDuserenr=$stmtDuserenr->execute();

    if($resultDuserenr=== true){




        $sqlDroleassign="DELETE FROM `mdl_role_assignments` WHERE `userid`=?";
        $stmtDroleassign=$conn->prepare($sqlDroleassign);

        $stmtDroleassign->bind_param('i',$userid);

       
        $resultDrolassign=  $stmtDroleassign->execute();
        if($resultDrolassign=== true){
            $stmtDuserenr->close();
            $stmtDroleassign->close();
          


            return "successfully delated the user from usr enrolemnt and role assignment table";
        }
        else{
            $stmtDuserenr->close();
            $stmtDroleassign->close();
            return "eror ocurred for delating the user";


        }

    }
    else{
        $stmtDuserenr->close();
        return "error ocurred for delating the user from enrolemtn table";
    }




 }


 /**
  * method for enroling multiple selected user 
  * @param moodleuserid of multiple user is needed like he number of totla selected row is[{"userId":46,"courseId":[2,4,5]},{"userId":1004,"courseId":"B-"},{"userId":10067,"courseId":"F"}]
  * @param  courseid for each user $name
  * @param  $conn connection is neeeded
  * 
  */


  function EnrolMultipleUsersInCourse($conn,$userCourses){


    $response=[];




    foreach($userCourses as $usercoursedata){


        $userid=$usercoursedata['userId'];
        $courseids=$usercoursedata['courseId'];

        foreach ($courseids as $courseid) {
            // Fetch the enrolment method (manual) for the course
            $sqlEnrol = "SELECT id FROM `mdl_enrol` WHERE `courseid` = ? AND enrol = 'manual'";
            $stmtEnrol = $conn->prepare($sqlEnrol);
            $stmtEnrol->bind_param('i', $courseid);
            $stmtEnrol->execute();
            $resultEnrol = $stmtEnrol->get_result();
    
            if ($resultEnrol->num_rows > 0) {
                while ($rowEnrol = $resultEnrol->fetch_assoc()) {
                    $enrolid = $rowEnrol['id'];
                    $timecreated = time();
                    $timemodified = time();
                    $timestart = time();
    
                    // Insert user enrolment
                    $sqlInsertEnrolment = "INSERT INTO `mdl_user_enrolments` (`enrolid`, `userid`, `timestart`, `timecreated`, `timemodified`) VALUES (?, ?, ?, ?, ?)";
                    $stmtInsertEnrolment = $conn->prepare($sqlInsertEnrolment);
                    $stmtInsertEnrolment->bind_param('iiiii', $enrolid, $userid, $timestart, $timecreated, $timemodified);
                    
                    if ($stmtInsertEnrolment->execute()) {
                        // Fetch contextid for the course
                        $sqlContextid = "SELECT id FROM `mdl_context` WHERE `contextlevel` = 50 AND `instanceid` = ?";
                        $stmtContextid = $conn->prepare($sqlContextid);
                        $stmtContextid->bind_param('i', $courseid);
                        $stmtContextid->execute();
                        $resultContextid = $stmtContextid->get_result();
    
                        if ($resultContextid->num_rows > 0) {
                            while ($rowContextid = $resultContextid->fetch_assoc()) {
                                $contextid = $rowContextid['id'];
                                $roleid = 5; // Assuming 5 is the role ID for students
                                $modifierid = 2; // Assuming 2 is the modifier ID
                                $timemodified = time();
    
                                // Insert the user in role assignment
                                $sqlInsertRoleassignment = "INSERT INTO `mdl_role_assignments` (`roleid`, `contextid`, `userid`, `timemodified`, `modifierid`) VALUES (?, ?, ?, ?, ?)";
                                $stmtRoleassign = $conn->prepare($sqlInsertRoleassignment);
                                $stmtRoleassign->bind_param('iiiii', $roleid, $contextid, $userid, $timemodified, $modifierid);
    
                                if ($stmtRoleassign->execute()) {
                                    $response[] = array(
                                        'message' => "User enrolled successfully in course ID $courseid and assigned role",
                                    );
                                } else {
                                    $response[] = array(
                                        'message' => "Role assignment failed for course ID $courseid",
                                    );
                                }
                            }
                        } else {
                            $response[] = array(
                                'message' => "Context not found for course ID $courseid",
                            );
                        }
                    } else {
                        $response[] = array(
                            'message' => "Enrolment failed for course ID $courseid",
                        );
                    }
                }
            } else {
                $response[] = array(
                    'message' => "No manual enrolment found for course ID $courseid",
                );
            }
        }
    
    
    }

    return $response;


  }


?>
