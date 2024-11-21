<?php
session_start();
include('config/db.php');

// Check if the student is logged in
if (!isset($_SESSION['student_user_id'])) {
    die("<h3>Error: Unable to fetch student details. Please log in first.</h3>");
}

$student_user_id = $_SESSION['student_user_id'];

// Query to fetch the student's enrollment details
$query = "
    SELECT e.student_name,
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
    WHERE e.user_id = ?
    ORDER BY e.date_of_join DESC
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Debug: Query preparation failed - " . $conn->error);
}

// Bind student ID parameter
$stmt->bind_param("i", $student_user_id);
$stmt->execute();
$result = $stmt->get_result();

// Include header (unchanged)
include('inc/header.php');
?>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php include('sidebar/studentSidebar.php'); ?>
        </div>
        
        <div class="col-md-9">
            <?php
            // Check if there are enrollment details to display
            if ($result && $result->num_rows > 0) {
                // Fetch student name just once and display the welcome message
                $row = $result->fetch_assoc();
                echo "<h3>Welcome, " . htmlspecialchars($row['student_name']) . "!</h3>";
                
                // Display the enrollment details in a table
                echo "<table border='1' class='table'>
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Dance Form</th>
                                <th>Instructor</th>
                                <th>Shift</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Date of Joining</th>
                            </tr>
                        </thead>
                        <tbody>";
                
                // Move the pointer back to the start to fetch all rows
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['category_name']) . "</td>
                            <td>" . htmlspecialchars($row['dance_name']) . "</td>
                            <td>" . htmlspecialchars($row['instructor_name']) . "</td>
                            <td>" . htmlspecialchars($row['shift']) . "</td>
                            <td>" . htmlspecialchars($row['payment_method']) . "</td>
                            <td>" . htmlspecialchars($row['payment_status']) . "</td>
                            <td>" . htmlspecialchars($row['date_of_join']) . "</td>
                          </tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<h3>No enrollment details found for the student.</h3>";
            }
            ?>
        </div>
    </div>
</div>

<?php
// Close statement
$stmt->close();

// Include footer (unchanged)
include('inc/footer.php');
?>
