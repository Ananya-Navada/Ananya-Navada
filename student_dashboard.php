<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['student_user_id'])) {
    header('location:login.php');
    exit(); // It's good practice to exit after a header redirect.
}

include('inc/header.php');
?>

<div class="container">
    <div class="col-md-3">
        <?php include('sidebar/studentSidebar.php'); ?>
    </div>
    <div class="col-md-9">
        <h3>DASHBOARD</h3>
        <div class="jumbotron" style="margin-top:10px;background-color:#f9f9f9;border:1px solid #ccc;border-radius:unset;padding-right:30px;padding-left:30px;">
            <div class="row">
                <div class="col-md-5">
                    <?php
                    $user_id = $_SESSION['student_user_id'];
                    $sql = "SELECT * FROM tbl_students WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        ?>
                        <p style="text-align:center;">
                        <img src="<?php echo htmlspecialchars($row['student_image'] ? $row['student_image'] : 'uploads/student_image.jpg'); ?>" style="width:30%;border-radius:50%;border:1px solid #FFF;" alt="Student Image">
                        </p>
                        <h2 style="text-align:center;text-transform:uppercase;font-size:20px;"><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                    <?php
                    } else { ?>
                       <p style="text-align:center;">
                            <img src="<?php echo htmlspecialchars($row['student_image'] ? $row['student_image'] : 'uploads/student_image.jpg'); ?>" style="width:30%;border-radius:50%;border:1px solid #FFF;" alt="Student Image">
                        </p>

                    <?php } ?>
                </div>
                <div class="col-md-7">
                    <ul class="list-group">
                        <?php
                        if ($result->num_rows > 0) {
                            // Reuse $row fetched above
                            ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Age
                                <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($row['age']); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Gender
                                <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($row['gender']); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Date of Join
                                <span class="badge bg-primary rounded-pill"><?php echo date('d/m/Y'); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Address
                                <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($row['address']); ?></span>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('inc/footer.php'); ?>
