<?php include('config/db.php'); ?>
<?php session_start(); ?>
<?php
$admin_user_id = $_SESSION['admin_user_id'];
if (!isset($admin_user_id)) {
    header('location:login.php');
}
?>
<?php include('inc/header.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php include('sidebar/adminSidebar.php'); ?>
        </div>
        <div class="col-md-9">

    <div class="admin" style="display: flex; margin-top: 10px; flex-wrap: wrap;">


    <div class="stat-card" style="flex: 1; margin-right: 10px; margin-top:50px;">
    <i class="fa fa-users" style="font-size: 24px; color: #007bff;"></i>
    <span>Instructors (<?php 
        $instructor_count_query = "SELECT COUNT(*) AS count FROM tbl_instructors"; 
        $instructor_count_result = mysqli_query($conn, $instructor_count_query); 
        $instructor_count = mysqli_fetch_assoc($instructor_count_result)['count']; 
        echo $instructor_count; 
    ?>)</span>
</div>


<div class="stat-card" style="flex: 1; margin-right: 10px;margin-top:50px;">
<i class="fa fa-graduation-cap" style="font-size: 24px; color: #28a745;"></i>
    <span>Students (<?php 
        $student_count_query = "SELECT COUNT(*) AS count FROM tbl_students"; 
        $student_count_result = mysqli_query($conn, $student_count_query); 
        $student_count = mysqli_fetch_assoc($student_count_result)['count']; 
        echo $student_count; 
    ?>)</span>
</div>



<div class="stat-card" style="flex: 1; margin-right: 10px;margin-top:50px;">
<i class="fa fa-user-plus" style="font-size: 24px; color: #ffc107;"></i>
    <span>Enrolled (<?php 
        // Query to count total enrollments
        $enrollment_count_query = "SELECT COUNT(*) AS count FROM tbl_enrollment"; 
        
        $enrollment_count_result = mysqli_query($conn, $enrollment_count_query); 
        
        // Check if the query was successful
        if ($enrollment_count_result) {
            $enrollment_count = mysqli_fetch_assoc($enrollment_count_result)['count']; 
            echo $enrollment_count; 
        } else {
            // Output the error message
            echo "Error: " . mysqli_error($conn);
        }
    ?>)</span>
</div>



<div class="stat-card" style="flex: 1;margin-top:50px;">
<i class="fa fa-film" style="font-size: 24px; color: #17a2b8;"></i>
    <span>Dance Forms (<?php 
        $dance_form_count_query = "SELECT COUNT(*) AS count FROM tbl_dance_forms"; 
        $dance_form_count_result = mysqli_query($conn, $dance_form_count_query); 
        $dance_form_count = mysqli_fetch_assoc($dance_form_count_result)['count']; 
        echo $dance_form_count; 
    ?>)</span>
</div>



    </div>
</div>

    </div>
</div>
<script>
    $('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active');
</script>
<?php include('inc/footer.php'); ?>
