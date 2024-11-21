<?php
include('config/db.php');
session_start();

// Check if the admin is logged in
$admin_user_id = $_SESSION['admin_user_id'];
if (!isset($admin_user_id)) {
    header('location:login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connection.php'; // Include your database connection file

    $enrollment_id = intval($_POST['enrollment_id']);
    $payment_status = htmlspecialchars($_POST['payment_status']);

    $query = "UPDATE tbl_enrollment SET payment_status = ? WHERE id = ?";
    $stmt = $conn->prepare(query: $query);
    $stmt->bind_param("si", $payment_status, $enrollment_id);

    if ($stmt->execute()) {
        echo "Payment status updated successfully.";
        header("Location: enrolledStudents.php"); // Redirect to the table page
        exit();
    } else {
        echo "Error updating payment status: " . $stmt->error;
    }
}


include('inc/header.php');
?>


<div class="container">
    <div class="col-md-3">
        <?php include('sidebar/adminSidebar.php'); ?>
    </div>
    <div class="col-md-9">
        <h2>Enrolled Students</h2>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Student Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Dance Form</th>
                    <th scope="col">Instructor</th>
                    <th scope="col">Shift</th>
                    <th scope="col">Payment Method</th>
                    <th scope="col">Payment Status</th>
                    <th scope="col">Date of Joining</th>
                    <th scope="col">Change Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // SQL to fetch student details along with the dance form and instructor
                $sql = "
                    SELECT e.student_name,
                           e.id, 
                           c.category_name, 
                           d.dance_name, 
                           i.instructor_name, 
                           e.shift, 
                           e.payment_method, 
                           e.payment_status, 
                           e.date_of_join
                    FROM tbl_enrollment e
                    JOIN tbl_dance_categories c ON e.category_id = c.category_id
                    JOIN tbl_dance_forms d ON e.dance_id = d.dance_id
                    JOIN tbl_instructors i ON e.instructor_id = i.instructor_id
                    ORDER BY e.date_of_join DESC";
                    
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['dance_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['instructor_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['shift']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_of_join']); ?></td>
                            <td>
                                <form method="post" action="enrolledStudents.php">
                                    <input type="hidden" name="enrollment_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <select name="payment_status" class="form-select">
                                        <option value="Paid" <?php echo $row['payment_status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="Pending" <?php echo $row['payment_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php 
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">No students enrolled yet!</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>



<?php include('inc/footer.php'); ?>
