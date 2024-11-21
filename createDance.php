<?php 
include('config/db.php'); 
session_start(); 

$admin_user_id = $_SESSION['admin_user_id'];
if (!isset($admin_user_id)) {
    header('location:login.php');
    exit;
}

if (isset($_POST['addDance'])) {
    $dance_name = mysqli_real_escape_string($conn, $_POST['dance_name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image = $_FILES['dance_image']['name'];
    $image_size = $_FILES['dance_image']['size'];
    $tmp_name = $_FILES['dance_image']['tmp_name'];
    $img_path = 'uploads/' . $image;

    // Validate fields
    if (empty($dance_name) || empty($category_id) || empty($price)) {
        $message[] = 'All fields are required!';
    } elseif (!empty($image)) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES['dance_image']['type'], $allowed_types)) {
            $message[] = 'Invalid image format! Only JPG, JPEG, and PNG are allowed.';
        } elseif ($image_size > 2000000) {
            $message[] = 'Image file size is too large.';
        } else {
            // Check for duplicate dance form
            $checkDance = "SELECT * FROM tbl_dance_forms WHERE dance_name='$dance_name'";
            $result = mysqli_query($conn, $checkDance);
            if (mysqli_num_rows($result) > 0) {
                $message[] = 'Dance form already exists!';
            } else {
                // Insert into the database
                $insertDance = "INSERT INTO tbl_dance_forms(dance_name, category_id, price, dance_image) VALUES ('$dance_name', '$category_id', '$price', '$img_path')";
                if (mysqli_query($conn, $insertDance)) {
                    move_uploaded_file($tmp_name, $img_path);
                    header('location:createDance.php?updatedDance=1');
                    exit;
                }
            }
        }
    }
}

if (isset($_GET['updatedDance'])) {
    echo '<script>alert("Updated Successfully!");</script>';
}

?>

<?php include('inc/header.php'); ?>
<div class="container">
    <div class="col-md-3">
        <?php include('sidebar/adminSidebar.php'); ?>
    </div>
    <div class="col-md-9">
        <div class="row" style="margin-left:1px;margin-top:18px;">
            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#addDanceModal">Add Dance Forms</a>
        </div>

        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Dance Name</th>
                    <th scope="col">Category Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Image</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tbl_dance_forms JOIN tbl_dance_categories ON tbl_dance_categories.category_id=tbl_dance_forms.category_id";
                $getDances = mysqli_query($conn, $sql);
                if (mysqli_num_rows($getDances) > 0) {
                    while ($row = mysqli_fetch_assoc($getDances)) {
                ?>
                <tr>
                    <td style="width:20%;font-size:14px;"><?php echo $row['dance_name']; ?></td>
                    <td style="width:20%;font-size:14px;"><?php echo $row['category_name']; ?></td>
                    <td style="width:20%;font-size:14px;"><?php echo $row['price']; ?></td>
                    <td style="width:20%;font-size:14px;" id="dance_image">
                        <img src="<?php echo $row['dance_image']; ?>" style="width:30%;" alt="Dance">
                    </td>
                    <td style="width:25%;" class="actions">
                        <a href="#" class="btn-sm btn-primary editDance" data-val="<?php echo $row['dance_id']; ?>" data-toggle="modal" data-target="#editDanceModal"><i class="fa fa-edit"></i>Edit</a>
                        <a href="#" class="btn-sm btn-danger deleteDance" data-val="<?php echo $row['dance_id']; ?>"><i class="fa fa-trash"></i>Delete</a>
                    </td>
                </tr>
            <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">No Dance Forms added yet!</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal to add dance -->
    <div class="modal fade" id="addDanceModal" tabindex="-1" role="dialog" aria-labelledby="addDanceModalLabel" aria-hidden="true">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Dance Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Dance Name</label>
                            <input type="text" name="dance_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select</option>
                                <?php
                                $sql = "SELECT * FROM tbl_dance_categories";
                                $getCategories = mysqli_query($conn, $sql);
                                while ($category = mysqli_fetch_assoc($getCategories)) {
                                    echo '<option value="' . $category['category_id'] . '">' . $category['category_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Upload Image</label>
                            <input type="file" name="dance_image" accept="image/*" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addDance" class="btn btn-primary">Add Dance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal to update Dance -->
    <div class="modal fade" id="editDanceModal" tabindex="-1" role="dialog" aria-labelledby="editDanceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="UpdateDance.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Dance Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="dance_id" id="dance_id">
                        <div id="messageContainer"></div>
                        <div class="form-group">
                            <label>Dance Name</label>
                            <input type="text" name="dance_name" id="dance_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Select</option>
                                <?php
                                $sql = "SELECT * FROM tbl_dance_categories";
                                $getCategories = mysqli_query($conn, $sql);
                                while ($category = mysqli_fetch_assoc($getCategories)) {
                                    echo '<option value="' . $category['category_id'] . '">' . $category['category_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" id="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Upload Image (Leave blank if you don't want to change)</label>
                            <input type="file" name="dance_image" accept="image/jpg,image/jpeg,image/png" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" name="updateDance" value="Update changes" class="btn btn-primary">
                        </div>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>


<script>



   // Handling edit dance form action
    $(document).on('click', '.editDance', function() {
        var id = $(this).data('val');  // Retrieve the dance ID from data attribute
        $.ajax({
        url: 'updateDance.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            var data = JSON.parse(response);
            $('#dance_id').val(data.dance_id);
            $('#dance_name').val(data.dance_name);
            $('#category_id').val(data.category_id);
            $('#price').val(data.price);
            $('#dance_image').html('<img src="' + data.dance_image + '" style="width:30%;" alt="Dance">');
        },
        error: function() {
            $('#messageContainer').html('<div class="alert alert-danger">Dance Forms loaded successfully!</div>');
        },
        error:function(){
            $('#messageContainer').html('<div class="alert alert-danger">Failed to load.</div>'); 
        }
    });
});




    $(document).on('click', '.deleteDance', function() {
        var id = $(this).data('val');
        if(confirm('Are you sure you want to delete this dance form?')){
        $.ajax({
            url: 'deleteDance.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
            alert(response);
            location.reload();
            },
            error: function() {
                alert("Error: Could not delete dance form.");
            }
        });
    }
});

</script>
<?php include('inc/footer.php'); ?>