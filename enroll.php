<?php
session_start();
include('config/db.php');

// Check if the student is logged in
if (!isset($_SESSION['student_user_id'])) {
    $_SESSION['redirect_after_login'] = "enroll.php?did=" . $_GET['did'];
    header('Location: login.php');
    exit();
}

$student_user_id = $_SESSION['student_user_id'];
$dance_id = $_GET['did'] ?? null;

// Check if dance_id is set
if (!$dance_id) {
    echo "Error: Dance ID is missing.";
    exit();
}

// Fetch dance and category information based on dance_id
$sql = "SELECT tbl_dance_forms.dance_name, tbl_dance_forms.dance_id,tbl_dance_forms.price, tbl_dance_categories.category_name, tbl_dance_categories.category_id
        FROM tbl_dance_forms
        JOIN tbl_dance_categories ON tbl_dance_forms.category_id = tbl_dance_categories.category_id
        WHERE tbl_dance_forms.dance_id = $dance_id";
$dance_info = mysqli_query($conn, $sql);

if (mysqli_num_rows($dance_info) == 0) {
    echo "Error: No dance form found for the provided dance ID.";
    exit();
}

$dance = mysqli_fetch_assoc($dance_info);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student_name'];
    $category_id = $_POST['category_id'];
    $dance_id = $_POST['dance_id'];
    $price = $_POST['price'];
    $address = $_POST['address'];
    $instructor_id = $_POST['instructor_id'];
    $shift = $_POST['shift'];
    $payment_method = $_POST['payment_method'];
    $doj = date('Y/m/d');

    $sql = "INSERT INTO tbl_enrollment (student_name, user_id, category_id, dance_id, price, address, instructor_id, shift, payment_method, payment_status, date_of_join)
            VALUES ('$student_name', $student_user_id, $category_id, $dance_id, '$price', '$address', '$instructor_id', '$shift', '$payment_method', 'pending', '$doj')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['enrollment_success'] = true; // Set success flag
        header('Location: enroll.php?did=' . $dance_id);
        exit();
    } else {
        echo 'Enrollment failed: ' . mysqli_error($conn);
    }
}

// Read and then clear the enrollment success flag
$enrollmentSuccess = $_SESSION['enrollment_success'] ?? false;
unset($_SESSION['enrollment_success']);

include('inc/header.php'); ?>
<div class="container">
<?php if ($enrollmentSuccess): ?>
    <div class="enrollment-success-overlay">
        <div class="success-animation">
            <span>✔️</span>
            <p>Enrollment Successful!</p>
        </div>
    </div>
    <script>
        setTimeout(() => {
            // Show a loading animation briefly
            document.querySelector('.success-animation').innerHTML = "<p>Redirecting to your dashboard...</p>";
            document.querySelector('.enrollment-success-overlay').style.opacity = "1";

            // Redirect to the student dashboard after 1 second
            setTimeout(() => {
                window.location.href = 'student_dashboard.php'; // Change to your dashboard page
            }, 1000); 
        }, 2000); // Delay before showing loading message
    </script>
<?php endif; ?>

    <h1>Enroll in <?php echo htmlspecialchars($dance['dance_name'] ?? ''); ?></h1>
    <form method="post">
        <label>Student Name</label>
        <input type="text" name="student_name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" class="form-control">

        <label>Category</label>
        <input type="text" value="<?php echo htmlspecialchars($dance['category_name'] ?? ''); ?>" class="form-control" readonly>
        <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($dance['category_id'] ?? ''); ?>">

        <label>Dance Form</label>
        <input type="text" value="<?php echo htmlspecialchars($dance['dance_name'] ?? ''); ?>" class="form-control" readonly>
        <input type="hidden" name="dance_id" value="<?php echo htmlspecialchars($dance['dance_id'] ?? ''); ?>">
        

        <label>Price</label>
        <input type="text" name="price" value="<?php echo htmlspecialchars($dance['price'] ?? ''); ?>" class="form-control" readonly>

        <label>Address</label>
        <textarea name="address" class="form-control"></textarea>

        <label>Instructor</label>
        <select name="instructor_id" class="form-control">
    <?php
    $instructor_sql = "SELECT * FROM tbl_instructors WHERE tbl_instructors.dance_form = '$dance_id'";
    $instructors_result = mysqli_query($conn, $instructor_sql);

    if ($instructors_result && mysqli_num_rows($instructors_result) > 0) {
        while ($instructor = mysqli_fetch_assoc($instructors_result)) {
            echo "<option value='{$instructor['user_id']}'>{$instructor['instructor_name']}</option>";
        }
    } else {
        echo "<option value=''>No instructors available for this dance form</option>";
    }
    ?>
</select>

        <label>Shift</label>
        <select name="shift" class="form-control">
            <option value="morning">Morning (09:00-12:00)</option>
            <option value="afternoon">Afternoon (12:00-03:00)</option>
            <option value="evening">Evening (03:00-06:00)</option>
        </select>

        <label>Payment Method</label>
        <select name="payment_method" class="form-control">
            <option value="cash">Cash</option>
            <option value="credit">Credit Card</option>
            <option value="debit">Debit Card</option>
            <option value="bank">Internet Banking</option>
        </select>
        <br/>
        <input type="submit" value="Enroll Now" class="btn btn-primary">
    </form>
</div>

<?php include('inc/footer.php'); ?>

<style>
.enrollment-success-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 1;
    transition: opacity 1s ease;
}

.success-animation {
    text-align: center;
    color: #28a745;
    font-size: 2em;
    animation: fadeOut 2s ease forwards;
}

@keyframes fadeOut {
    0% { opacity: 1; }
    100% { opacity: 0; }
}
</style>

<script>
document.querySelector("form").addEventListener("submit", function(event) {
    const requiredFields = [
        { name: "student_name", label: "Student Name" },
        { name: "address", label: "Address" },
        { name: "instructor_id", label: "Instructor" },
        { name: "shift", label: "Shift" },
        { name: "payment_method", label: "Payment Method" }
    ];

    let missingFields = [];  // Array to track missing fields

    requiredFields.forEach(function(field) {
        const input = document.querySelector(`[name="${field.name}"]`);

        // Check if the field is empty
        if (!input || input.value.trim() === "") {
            missingFields.push(field.label);  // Add label to missing fields
            input.style.border = "1px solid red";  // Highlight the field with red border
        } else {
            input.style.border = "";  // Reset the border color if field is filled
        }
    });

    if (missingFields.length > 0) {
        event.preventDefault();  // Prevent form submission
        alert("Please fill in the following fields: " + missingFields.join(", "));  // Show alert listing missing fields
    }
});
</script>
