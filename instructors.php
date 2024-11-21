<?php 
include('config/db.php'); 
session_start(); 

// Check if the session is set for admin
$admin_user_id = $_SESSION['admin_user_id'] ?? null;
if (!$admin_user_id) {
    header('location:login.php');
    exit();
}

include('inc/header.php'); 
?>

<div class="container">
    <div class="col-md-3">
        <?php include('sidebar/adminSidebar.php'); ?>
    </div>
    <div class="col-md-9">
        <h2>INSTRUCTORS</h2>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col" style="width:5%;">Image</th>
                    <th scope="col" style="width:25%;">Instructor Name</th>
                    <th scope="col" style="width:10%;">Age</th>
                    <th scope="col" style="width:10%;">Gender</th>
                    <th scope="col" style="width:10%;">Experience</th>
                    <th scope="col" style="width:30%;">Address</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $sql = "SELECT * FROM tbl_instructors";
                $instructors = mysqli_query($conn, $sql);
                
                if (!$instructors) {
                    echo "<p>Error fetching instructors: " . mysqli_error($conn) . "</p>";
                } elseif (mysqli_num_rows($instructors) > 0) {
                    while ($row = mysqli_fetch_assoc($instructors)) { ?>
                        <tr>
                           

                        <td style="width:10%;" class="image" id="instructor_image">
    <?php
        // Determine the correct path for the image
        $imagePath = !empty($row['instructor_image']) ? htmlspecialchars($row['instructor_image']) : 'assets/img/avatar.jpg';

        // Debugging line (remove this after verifying the path)
        echo "<!-- Image Path: $imagePath -->";

        // Display the image
        echo "<img src='" . $imagePath . "' style='width:80%; border-radius:30%; height:80px;' alt='Instructor'>";
    ?>
</td>




                            <td style="width:25%;font-size:14px;"><?php echo htmlspecialchars($row['instructor_name']); ?></td>
                            <td style="width:10%;font-size:14px;"><?php echo htmlspecialchars($row['age']); ?></td>
                            <td style="width:10%;font-size:14px;"><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td style="width:10%;font-size:14px;"><?php echo htmlspecialchars($row['experience']); ?></td>
                            <td style="width:30%;font-size:14px;"><?php echo htmlspecialchars($row['address']); ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No instructor registered yet!</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.nav-<?php echo isset($_GET['page']) ? htmlspecialchars($_GET['page']) : ''; ?>').addClass('active');
    });
</script>

<?php include('inc/footer.php'); ?>
