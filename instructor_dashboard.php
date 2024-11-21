<?php
include('config/db.php');
session_start();

// Ensure user is logged in and has an instructor ID in session
if (!isset($_SESSION['instructor_user_id'])) {
    header('location:login.php');
    exit();
}

$instructor_user_id = $_SESSION['instructor_user_id'];

if(isset($_POST['submit'])){
    $dance_form = mysqli_real_escape_string($conn, $_POST['dance_name']);
    $sql = "UPDATE tbl_instructors SET dance_form = '$dance_form' WHERE user_id='$instructor_user_id'";
    echo $sql ;
    // echo $conn;
    

    if(mysqli_query($conn, $sql )){
    // if ($conn->query($updateDanceForm) === TRUE) {
        echo "Dance Form updated successfully!";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
<?php include('inc/header.php'); ?>

<div class="container">
    <div class="col-md-3">
        <?php include('sidebar/instructorSidebar.php'); ?>
    </div>

    <div class="col-md-9">
        <h3>DASHBOARD</h3>
        <div class="jumbotron" style="margin-top:10px;background-color:#f9f9f9;border:1px solid #ccc;border-radius:unset;padding-right:30px;padding-left:30px;">
            <div class="row">
                <div class="col-md-4">
                    <?php
                    // Fetch instructor profile data
                    $sql = "SELECT * FROM tbl_instructors WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("i", $instructor_user_id);
                        $stmt->execute();
                        $data = $stmt->get_result();
                        
                        if ($data->num_rows > 0) {
                            $row = $data->fetch_assoc();
                            $instructor_image = !empty($row['instructor_image']) ?  htmlspecialchars($row['instructor_image']) : 'assets/img/avatar.jpg';
                        ?>
                            <p style="text-align:center;">
                                <img src="<?php echo $instructor_image; ?>" style="width:50%; border-radius:50%; border:1px solid #FFF;" alt="Instructor Image">
                            </p>
                            <h2 style="text-align:center; text-transform:uppercase; font-size:20px;"><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                        <?php 
                        } else {
                            echo "<p style='text-align:center;'>No instructor data found.</p>";
                        }
                    } else {
                        echo "<p>Query error: " . $conn->error . "</p>";
                    }
                    ?>
                </div>

                <div class="col-md-4">
                    <!-- Additional Instructor Details -->
                    <ul class="list-group">
                        <?php
                        $sql = "SELECT age, gender, experience, address FROM tbl_instructors WHERE user_id = ?";
                        $stmt = $conn->prepare($sql);
                        if ($stmt) {
                            $stmt->bind_param("i", $instructor_user_id);
                            $stmt->execute();
                            $data = $stmt->get_result();
                            if ($data->num_rows > 0) {
                                while ($row = $data->fetch_assoc()) { ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Age
                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($row['age']); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Experience
                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($row['experience']); ?> Years</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Gender
                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($row['gender']); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Address
                                        <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($row['address']); ?></span>
                                    </li>
                                <?php }
                            } else {
                                echo "<li class='list-group-item'>No additional instructor details available.</li>";
                            }
                        } else {
                            echo "<p>Query error: " . $conn->error . "</p>";
                        }
                        ?>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <ul class="list-group skills">
                        <form action="instructor_dashboard.php" method="post">
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="display:flex;">
                                <select name="dance_name" class="form-control" style="width:90%;margin-right:10px;">
                                    <option value="">Select</option>
                                    <?php
                                    $sql = "SELECT dance_name FROM tbl_dance_forms";
                                    $dance_data = mysqli_query($conn, $sql);
                                    if ($dance_data) {
                                        while ($dance_row = mysqli_fetch_assoc($dance_data)) {
                                            echo '<option value="' . htmlspecialchars($dance_row['dance_name']) . '">' . htmlspecialchars($dance_row['dance_name']) . '</option>';
                                        }
                                    } else {
                                        echo "<option value=''>Error loading dance forms</option>";
                                    }
                                    ?>
                                </select>
                                <input type="submit" name="submit" value="ADD" class="btn btn-primary">
                            </li>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('inc/footer.php'); ?>
