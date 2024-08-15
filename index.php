

<?php

include('connection.php');
include('connection2.php');
include('functionclass.php');
require_once('../server/moodle/config.php');


// Get the session ID
require_login();


if(isloggedin() && !isguestuser()){


    // Query the mdl_session table to check if the session ID exists
    $sqlsession = "SELECT * FROM `mdl_sessions` WHERE `sid` = ?";
    $stmtsession = $conn2->prepare($sqlsession);
    $stmtsession->bind_param('s', $sessionID);
    $stmtsession->execute();
    $resultsession = $stmtsession->get_result();

    if($resultsession->num_rows == 0) {
        // Session ID not found in the database, show an error message
        ?>
        <html>
            <head>
                <title>Error page</title>
            </head>
            <body>
            </body>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script type="text/javascript">
                Swal.fire({
                    title: 'Error',
                    text: 'You have entered an invalid session',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 3000 // 3 seconds
                }).then(function() {
                    window.location.href = 'http://localhost/Green project/index.php'; // Redirect to a specific URL after showing the message
                });
            </script>
        </html>
        <?php
        exit;
    }

    DelateusersafterTime($conn);
    $sqlquery = "SELECT * FROM `transaction_bank` ";
    $stmt = $conn->prepare($sqlquery);
    $stmt->execute();
    $resultstmt = $stmt->get_result();
} else {
    // Session ID not provided in the Moodle config file, show an error message
    ?>
    <html>
        <head>
            <title>Error page</title>
        </head>
        <body>
        </body>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript">
            Swal.fire({
                title: 'Error',
                text: 'Session ID not provided. Access denied.',
                icon: 'error',
                showConfirmButton: false,
                timer: 3000 // 3 seconds
            }).then(function() {
                window.location.href = 'http://localhost/Green project/index.php'; // Redirect to a specific URL after showing the message
            });
        </script>
    </html>
    <?php
    exit;
}


  


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
      
 <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
 
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>


 body{
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
 }
 #ffff{
    
 }
</style>
</head>
<body>
    
    <!-- color of backgroun-color: #5db75d-->

<header>
    <!-- nabbar-responsive-->


<nav  class="navbar navbar-expand-sm shadow-sm  p-3" style="background-color: #5db75d;">


    <div class="container-fluid">
    
    
    
        <a class="navbar-brand">
            Green</a>    
            <button class="navbar-toggler" data-bs-target="#collapseones" data-bs-toggle="collapse">
                <span class="navbar-toggler-icon"></span>
            </button>  
        <div class="collapse navbar-collapse" id="collapseones">
    
    
    
            <ul class="navbar-nav ms-auto">
    
    
                <li class="nav-item">
                    <a href="#" class="nav-link text-dark text-capitalize fs-10">Home</a>
                </li>
    
                <li class="nav-item">
                    <a href="#" class="nav-link text-dark text-capitalize fs-10">About as</a>
                </li>
            </ul>
        </div>
    
    </div>
    
    
    
    
    </nav>
    
</header>


<div class="container mt-3 p-3" id="ffff">


    <p class="text-center mt-3 text-dark fs-6">Payed student table</p>
</div>


<div class="container mt-3 p-2 mb-5 shadow-sm table-responsive">

  <div class="container mt-2 p-2 mb-2">
    <div class="row ">


        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-4 mb-3 me-auto">
                    <select id="bankselect" class="form-select">
                        <option>Select Bank</option>
                        <option>CBE</option>
                        <option>Abyissiniya</option>
                        <option>Telebirr</option>
                    </select>
                    
                </div>
                
                <div class="col-sm-4  mb-3 me-auto">
                    <select id="dateselect" class="form-select">
                        <option>Select Date</option>
                        <option>Today</option>
                        <option>This Week</option>
                       
                    </select>
                    
                </div>

                <div class="col-sm-2  mb-2 me-auto">
                  
                    
                    
                </div>
                <div class="col-sm-2  mb-2 me-auto">
                   
                    
                </div>
            </div>
        </div>
        <div class="col-sm-6">
         

           
        </div>

    </div>

        
    
  </div>

    <table class="table table-hover" id="mytable">
        <thead>


            <tr>
                <th></th>
                <th>TTNo</th>
                <th>Bank</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Amount</th>
                <th>Courses</th>
                
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
           <?php

if($resultstmt->num_rows >0){



    while($row=$resultstmt->fetch_assoc()){


?>
<tr>

<td data-userid="<?php echo $row['Id']; ?>" data-moodleid="<?php echo $row['MoodleUserId']?>" data-courseid="<?php echo $row['Grade'] ?>">


<div class="form-check">

                        <input type="checkbox" class="form-check-input" id="check1" name="option1" />
                        <label for="label1" class="form-check-label"></label>
                      </div>
</td>
<td><?php  echo  $row['TTNumber']   ?></td>
<td><?php    echo $row['Bank']   ?></td>
<td><?php    echo $row['BankClientsName']  ?></td>
<td><?php  echo $row['Phone']?></td>
<td><?php   echo $row['Amount'] ?></td>
<td><?php  echo $row['Subject'] ?></td>

<td><?php   echo date('Y-m-d',strtotime( $row['Timestamp'])) ?></td>
<td data-userid="<?php echo $row['Id']; ?>">
    <?php
    $sts = $row['Status'];
    if ($sts === 'accepted') {
        echo '<span class="badge bg-primary">accepted</span>';
    } else if ($sts === 'rejected') {
        echo '<span class="badge bg-danger">rejected</span>';
    } else {
        echo '<span>null</span>';
    }
    ?>
</td>
<td>
    <button type="button" id="btnaccepted" data-userid="<?php echo $row['Id']; ?>" data-moodleid="<?php echo $row['MoodleUserId']?>" data-courseid="<?php echo $row['Grade'] ?>" class="btn btn-outline-success btn-sm timenewroman">Accept</button>
    <button type="button" id="btnrejected" data-userid="<?php echo $row['Id']; ?>" data-moodleid="<?php echo $row['MoodleUserId']?>" data-courseid="<?php echo $row['Grade'] ?>" class="btn btn-outline-danger btn-sm timenewroman">Reject</button>
</td>


</tr>




<?php


    }





    
}

?>
        </tbody>
    </table>
    <div class="container justify-content-start p-2">


        <button type="button" id="submitbutton" class="btn btn-success btn-sm">Enrol Select Users</button>
    </div>
</div>
    



   

<footer class="container-fluid mt-3 p-5 bg-dark">

<div class="container">
   <p class="text-white text-center">Green</p>
</div>


</footer>
</body>

<script type="text/javascript">
    $('document').ready(function(){





        $('#mytable').DataTable();

        var table=$('#mytable').DataTable();


        $('#bankselect').change(function(){


            var selectedbank=$(this).val();
table.search('').draw();
            table.column(2).search(selectedbank).draw();

        
            $(this).find('option:contains("Select Bank")').prop('disabled',true);
        });
        $('#dateselect').change(function(){
    var selectedDate = $(this).val();

    $(this).find('option:contains("Select Date")').prop('disabled', true);
    table.search('').draw();

    // Perform date-based filtering
  




  if(selectedDate === 'Today'){


    var today=new Date().toISOString().slice(0,10);

    table.column(7).search(today).draw();
  }

  else if(selectedDate === 'This Week'){
    var startDate=new Date();


    startDate.setDate(startDate.getDate()-startDate.getDay());

    var endDate=new Date();

    endDate.setDate(endDate.getDate()+ (6-endDate.getDay()));




    table.column(7).search(startDate.toISOString().slice(0,10) + ' to ' + endDate.toISOString().slice(0,10)).draw();
  }
});





/**
 * method fot updating the status with ajax request
 */












 //end of ajax ofor status updated




 var selectedCount = 0;
     
 // Event listener for checkbox clicks
$('#mytable').on('click','.form-check-input',function(){




    if($(this).prop('checked')){
        selectedCount++;
    }
    else{
        selectedCount--;
    }

    console.log('number of selected count is '+selectedCount);
});

    // Event listener for submit button

    var SelectedData=[];
    var SelectednormalId=[];
    $('#submitbutton').on('click',function(){


        SelectedData=[];
        SelectednormalId=[];

        $('#mytable').find('.form-check-input:checked').each(function(){

            var row=$(this).closest('tr');
            var userId=row.find('td').data('moodleid');
            var courseId=row.find('td').data('courseid');

            //lets add the user id for updaitng the status
            var userIdnormal=row.find('td').data('userid');
            SelectednormalId.push(userIdnormal);
            /**
             * create selectednormarl user id array and push the data in that array tanks
             */
            SelectedData.push({userId:userId,courseId:courseId});

        });


        console.log('the number of totla selected row is'+JSON.stringify(SelectedData));

        console.log('the ids selected of normal id is'+JSON.stringify(SelectednormalId));


            $.ajax({
            url:'Enrolselect.php',
            method:'POST',
            data:{userCourses:SelectedData,userIds:SelectednormalId},
            success:function(response){
                console.log('Response of multiple user:', response); // Log the response for debugging

if (response.trim() === 'success'){
            console.log('multiple users is enrolled usccessfully');
            Swal.fire({

            title:'Success',
            text:'You have successfully enrolled multiple users',
            width:'400px',
            height:'70px',
            icon:'success'
            });
            SelectednormalId.forEach(function(userIdnormal){

                $('#mytable').find('td:nth-child(9)').html('<span class="badge bg-primary">accepted</span>');
            });
            }
            else{
                console.log('the response erorr ocurred');
            }

            },
            error:function(xhr,status,error){

            console.log('AJAX ERRO for enrolling mutliple users');

            }
            });




    });




    });



    $(document).on('click', '#btnaccepted', function() {

    var moodleuserid=$(this).data('moodleid');
    var courseid=$(this).data('courseid');
    var userId = $(this).data('userid');
    var button = $(this);

    $.ajax({
        url: 'update_status.php',
        method: 'POST',
        data: { userId: userId, moodleId: moodleuserid, courseId:courseid, status: 'accepted' },
        success: function(response) {
            console.log('Response:', response); // Log the response for debugging

            if (response.trim() === 'success') { // Trim any extra whitespace
                // Navigate to the correct <td> and update the status
              button.closest('tr').find('td:nth-child(9)').html('<span class="badge bg-primary">accepted</span>');
            } else {
                console.log('Error updating status');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
        }
    });
});




  /**
     * ajax for updating the status for reject button
     */


     $(document).on('click','#btnrejected',function(){
        var moodleuserid=$(this).data('moodleid');
        var courseid=$(this).data('courseid');
var userid=$(this).data('userid');

var button=$(this);


Swal.fire({
icon: "warning",
title: "Alert",
width:"400px",
height:"70px",
text: "Are you want to reject",

showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, Reject!"
}).then((result)=>{
   if(result.isConfirmed){
   


    $.ajax({


        url:'update_status.php',
        method:'POST',
        data:{userId:userid, moodleId:moodleuserid, courseId:courseid, status:'rejected'},
        success:function(response){


            console.log('Response for rejected button',response);


            if(response.trim() === 'success'){
                console.log('sucessfully rejected the user fron enromet');
                button.closest('tr').find('td:nth-child(9)').html('<span class="badge bg-danger">rejected</span>');

                        Swal.fire({
                        title:'success',
                        text:'Successfully rejected',
                        icon:'success',
                        width:"400px"
                        });


            }
            else{
                console.log('Error ocurred during updating');
            }
        },
        error:function(xhr,status,error){
            console.log('Ajax erorr for Rejecte status'+error);
        }

    })
   }
});




});








</script>
</html>