<?php include('config/db.php'); ?>
<?php session_start(); ?>
<?php
    $student_user_id = $_SESSION['student_user_id'];
    if (!isset($student_user_id)) {
        header('location:login.php');
    }
    
    if (isset($_POST['submitStudentData'])) {
        $user_id = $_SESSION['student_user_id'];
        $student_name = $_SESSION['username'];
        $age = mysqli_real_escape_string($conn, $_POST['age']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $student_role_id = $_SESSION['student_role_id'];
        $image = $_FILES['student_image']['name'];
        $image_size = $_FILES['student_image']['size']; // Fixed this line
        $tmp_name = $_FILES['student_image']['tmp_name'];
        $img_path = 'uploads/' . $image;
        
        if (!empty($image)) {
            if ($image_size > 2000000) {
                $message[] = 'Image size is too large.';
            } else {
                // Ensure the uploads directory exists
                if (!is_dir('uploads/')) {
                    mkdir('uploads/', 0775, true); // Create the directory if it doesn't exist
                }
    
                // Check if the upload was successful
                if (move_uploaded_file($tmp_name, $img_path)) {
                    $studentProfile = "INSERT INTO tbl_students (user_id, student_name, age, gender, user_role_id, address, student_image, doj) VALUES ('$user_id', '$student_name', '$age', '$gender', '$student_role_id', '$address', '$img_path', NOW())";
    
                    if (mysqli_query($conn, $studentProfile)) {
                        header('location:student_dashboard.php');
                        exit(); // Added exit after redirect
                    } else {
                        $message[] = 'Error: ' . mysqli_error($conn);
                    }
                } else {
                    $message[] = 'Failed to move uploaded file.';
                }
            }
        } else {
            $message[] = 'No image uploaded.';
        }
    }
    ?>

<?php include('inc/header.php'); ?>
<div class="container">
    <div class="col-md-3">
        <?php include('sidebar/studentSidebar.php'); ?>
    </div>
    <div class="col-md-9">
        <h3>UPLOAD PROFILE</h3>
        <form method="post" action="uploadStudentProfile.php" enctype="multipart/form-data">
            <div class="box">
                <div class="row" style="padding: 0px 30px;margin-bottom: 10px;">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control" placeholder="Age" required="">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control" required="">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding:0px 30px;">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control" placeholder="Address" required=""></textarea>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding:0px 30px;">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Upload Image</label>
                            <input type="file" name="student_image" class="form-control" required="">
                        </div>
                    </div>
                </div>
                <div class="row" style="padding:0px 30px;">
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="submit" name="submitStudentData" value="Upload Profile" class="btn btn-success">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include('inc/footer.php'); ?>
