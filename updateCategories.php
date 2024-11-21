<?php
include('config/db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_user_id'])) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Update category if 'updDanceCategories' is set
if (isset($_POST['updDanceCategories'])) {
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $tag_name = mysqli_real_escape_string($conn, $_POST['tag_name']);
    $image = $_FILES['category_image']['name'];
    $image_size = $_FILES['category_image']['size'];
    $tmp_name = $_FILES['category_image']['tmp_name'];
    
    // Prepare the path
    $img_path = 'categories/' . $image;

    // Initialize the update query
    $updateCategory = "UPDATE tbl_dance_categories SET category_name='$category_name', tag_name='$tag_name'";

    // Check if an image was uploaded
    if (!empty($image)) {
        if ($image_size > 2000000) {
            echo json_encode(['error' => 'Image file size is too large']);
            exit();
        } else {
            $updateCategory .= ", category_image='$img_path'";
            move_uploaded_file($tmp_name, $img_path);
        }
    }

    // Complete the query with the condition
    $updateCategory .= " WHERE category_id='$category_id'";

    // Execute the update query
    if (mysqli_query($conn, $updateCategory)) {
        header('location:danceCategories.php?updatedCategory=true');
    } else {
        echo json_encode(['error' => 'Failed to update category: ' . mysqli_error($conn)]);
    }
    exit();
}

// Check if 'id' is set and is numeric in the POST request for fetching details
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $category_id = $_POST['id'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM tbl_dance_categories WHERE category_id = ?");
    
    if ($stmt) {
        $stmt->bind_param("i", $category_id); // Assuming category_id is an integer

        // Execute the statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            // Check if any category was found
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $data = array(
                    'category_id' => $row['category_id'],
                    'category_name' => $row['category_name'],
                    'tag_name' => $row['tag_name'],
                    'category_image' => $row['category_image'],
                );
                echo json_encode($data);
            } else {
                echo json_encode(['error' => 'Category not found']);
            }
        } else {
            echo json_encode(['error' => 'Query execution failed: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Statement preparation failed: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'No valid category ID provided']);
}

$conn->close();
?>
