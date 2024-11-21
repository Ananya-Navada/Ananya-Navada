<?php
include('config/db.php');
session_start();

if (isset($_POST['id'])) {
    $dance_id = mysqli_real_escape_string($conn, $_POST['id']);

    // First, delete the image file associated with the dance form (if you want to remove it from the server)
    $getImageQuery = "SELECT dance_image FROM tbl_dance_forms WHERE dance_id = '$dance_id'";
    $result = mysqli_query($conn, $getImageQuery);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $imagePath = $row['dance_image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the image from the server
        }
    }

    // Now delete the dance form record from the database
    $deleteQuery = "DELETE FROM tbl_dance_forms WHERE dance_id = '$dance_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        echo 'Dance form deleted successfully.';
    } else {
        echo 'Error: Could not delete dance form.';
    }
} else {
    echo 'Invalid request.';
}

$conn->close();
?>
