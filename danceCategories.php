<?php include('config/db.php'); ?>
<?php session_start(); ?>
<?php
$admin_user_id = $_SESSION['admin_user_id'];
if (!isset($admin_user_id)) {
    header('location:login.php');
    exit; // Always good to exit after header redirection
}

if (isset($_POST['danceCategories'])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $tag_name = mysqli_real_escape_string($conn, $_POST['tag_name']);
    $image = $_FILES['category_image']['name'];
    $image_size = $_FILES['category_image']['size'];
    $tmp_name = $_FILES['category_image']['tmp_name'];
    $img_path = 'categories/' . $image; // Added a slash for the folder path

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'Image file size is too large';
        } else {
            $insertCategory = "INSERT INTO tbl_dance_categories (category_name, tag_name, category_image) VALUES ('$category_name', '$tag_name', '$img_path')";
            mysqli_query($conn, $insertCategory);
            move_uploaded_file($tmp_name, $img_path);
            header('location:danceCategories.php');
            exit; // Ensure the script stops executing after redirection
        }
    }
}

if( isset($_GET['updatedCategory'])) {
    echo '<script>alert("Success")</script>';
}

// if (isset($_POST['updDanceCategories'])) {
//     $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
//     $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
//     $tag_name = mysqli_real_escape_string($conn, $_POST['tag_name']);
//     $image = $_FILES['category_image']['name'];
//     $image_size = $_FILES['category_image']['size'];
//     $tmp_name = $_FILES['category_image']['tmp_name'];
//     $img_path = 'categories/' . $image; // Added a slash for the folder path

//     // Prepare SQL update statement
//     $updateCategory = "UPDATE tbl_dance_categories SET category_name='$category_name', tag_name='$tag_name'";

//     // Only update the image if a new one is uploaded
//     if (!empty($image)) {
//         if ($image_size > 2000000) {
//             $message[] = 'Image file size is too large';
//         } else {
//             $updateCategory .= ", category_image='$img_path'";
//             move_uploaded_file($tmp_name, $img_path);
//         }
//     }

//     $updateCategory .= " WHERE category_id='$category_id'";
//     mysqli_query($conn, $updateCategory);
//     header('location:danceCategories.php');
//     exit;
// }
?>

<?php include('inc/header.php'); ?>
<div class="container">
    <div class="col-md-3">
        <?php include('sidebar/adminSidebar.php'); ?>
    </div>
    <div class="col-md-9">
        <div class="row" style="margin-left:1px;margin-top:18px;">
            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#addCategoryModal">Add Category</a>
        </div>

        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Category Name</th>
                    <th scope="col">Tag</th>
                    <th scope="col">Image</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tbl_dance_categories";
                $getCategories = mysqli_query($conn, $sql);
                if (mysqli_num_rows($getCategories) > 0) {
                    while ($row = mysqli_fetch_assoc($getCategories)) {
                        ?>
                        <tr>
                            <td style="width:5%;font-size:14px;"><?php echo $row['category_id']; ?></td>
                            <td style="width:35%;font-size:14px;"><?php echo $row['category_name']; ?></td>
                            <td style="width:10%;font-size:14px;"><?php echo $row['tag_name']; ?></td>
                            <td style="width:20%;font-size:14px;" class="image">
                                <img src="<?php echo $row['category_image']; ?>" style="width:30%;" alt="Dance">
                            </td>
                            <td style="width:25%;" class="actions">
                                <a href="#" class="btn-sm btn-primary editCategory" data-val="<?php echo $row['category_id']; ?>" data-toggle="modal" data-target="#editCategoryModal"><i class="fa fa-edit"></i>Edit</a>
                                <a href="#" class="btn-sm btn-danger deleteCategory" data-val="<?php echo $row['category_id']; ?>"><i class="fa fa-trash"></i>Delete</a>
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="5">No categories added yet!</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal to add categories -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Dance Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body">
                    <form method="post" action="danceCategories.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category_name" class="form-control" required placeholder="Category">
                        </div>
                        <div class="form-group">
                            <label>Tag Name</label>
                            <input type="text" name="tag_name" class="form-control" placeholder="Tag Name" required>
                        </div>
                        <div class="form-group">
                            <label>Upload Image</label>
                            <input type="file" name="category_image" class="form-control" accept="image/jpg,image/jpeg,image/png" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();">Close</button>
                            <input type="submit" name="danceCategories" value="Save changes" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to update categories -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Dance Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.reload();">
                        <span aria-hidden="true">&times;</span>
                    </button>   
                </div>
                <div class="modal-body">
                    <form method="post" action="updateCategories.php" enctype="multipart/form-data">
                        <input type="hidden" name="category_id" id="category_id">
                        <div id="messageContainer"></div> <!-- Message container -->
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category_name" id="category_name" class="form-control" required placeholder="Category">
                        </div>
                        <div class="form-group">
                            <label>Tag Name</label>
                            <input type="text" name="tag_name" id="tag_name_edit" class="form-control" required placeholder="Tag Name">
                        </div>
                        <div class="form-group">
                            <div id="category_image"></div>
                            <label>Upload Image (Leave blank if you don't want to change)</label>
                            <input type="file" name="category_image" accept="image/jpg,image/jpeg,image/png" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.reload();">Close</button>
                            <input type="submit" name="updDanceCategories" value="Save changes" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>



     // Handling edit category action
    $(document).on('click', '.editCategory', function() {
    var id = $(this).data('val');
    $.ajax({
        url: 'updateCategories.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            var data = JSON.parse(response);
            $('#category_id').val(data.category_id);
            $('#category_name').val(data.category_name);
            $('#tag_name_edit').val(data.tag_name);
            $('#category_image').html('<img src="' + data.category_image + '" style="width:30%;" alt="Dance">');
        },
        error:function(){

            $('#messageContainer').html('<div class="alert alert-danger">Category details loaded successfully!</div>');
        },
        error: function() {
            // Display error message if the AJAX call fails
            $('#messageContainer').html('<div class="alert alert-danger">Failed to load category details. Please try again.</div>');
        }
    });
});


    

$(document).on('click', '.deleteCategory', function() {
    var category_id = $(this).data('val'); // Make sure `data-val` is set on the delete button

    if(confirm('Are you sure you want to delete this category?')) {
        $.ajax({
            url: 'deleteCategory.php',
            type: 'POST',
            data: { category_id: category_id },
            success: function(response) {
                alert(response);
                location.reload(); // Reload the page to update the table
            },
            error: function() {
                alert('Error: Could not delete category.');
            }
        });
    }
});

</script>
<?php include('inc/footer.php'); ?>
