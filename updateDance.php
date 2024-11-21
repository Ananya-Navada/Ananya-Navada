<?php
include('config/db.php');
session_start();

if (!isset($_SESSION['admin_user_id'])) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

if (isset($_POST['updateDance'])) {
    $dance_id = mysqli_real_escape_string($conn, $_POST['dance_id']);
    $dance_name = mysqli_real_escape_string($conn, $_POST['dance_name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image = $_FILES['dance_image']['name'];
    $image_size = $_FILES['dance_image']['size'];
    $tmp_name = $_FILES['dance_image']['tmp_name'];
    $img_path = 'uploads/' . $image;

    // Begin query to update dance form
    $updateDance = "UPDATE tbl_dance_forms SET dance_name='$dance_name', category_id='$category_id', price='$price'";

    // If a new image is uploaded
    if (!empty($image)) {
        if ($image_size > 2000000) {
            echo json_encode(['error' => 'Image file size is too large']);
            exit();
        } else {
            $updateDance .= ", dance_image='$img_path'";
            move_uploaded_file($tmp_name, $img_path);
        }
    }

    // Complete the query to update the dance form
    $updateDance .= " WHERE dance_id='$dance_id'";

    // Execute the update query
    if (mysqli_query($conn, $updateDance)) {
        header('location:createDance.php?updateDance=true');
    } else {
        echo json_encode(['error' => 'Failed to update dance: ' . mysqli_error($conn)]);
    }
    exit();
}

// Fetch data for the edit modal
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $dance_id = $_POST['id'];

    // Prepare and execute a query to fetch dance form data
    $stmt = $conn->prepare("SELECT * FROM tbl_dance_forms WHERE dance_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $dance_id); // Bind the ID parameter

        // Execute the statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $data = array(
                    'dance_id' => $row['dance_id'],
                    'dance_name' => $row['dance_name'],
                    'category_id' => $row['category_id'],
                    'price' => $row['price'],
                    'dance_image' => $row['dance_image'],
                );
                echo json_encode($data); // Return the dance form data as JSON
            } else {
                echo json_encode(['error' => 'Dance form not found']);
            }
        } else {
            echo json_encode(['error' => 'Invalid request']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Statement preparation failed: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'No valid form ID provided']);
}

$conn->close();
?>
