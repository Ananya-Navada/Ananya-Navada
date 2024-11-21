<?php include('inc/header.php'); ?>

<div class="container" style="padding:0px;">
    <img src="assets/img/view-dance.jpg" alt="" style="width:100%;height:400px;">
</div>
<div class="row">
    <?php
    include('config/db.php');
    $id = intval($_GET['id']); // Sanitize the input to prevent SQL injection
    $sql = "SELECT category_name FROM tbl_dance_categories WHERE category_id = $id";
    $getCategory = mysqli_query($conn, $sql);

    if (mysqli_num_rows($getCategory) > 0) {
        while ($row = mysqli_fetch_assoc($getCategory)) { ?>
            <h2 style="text-align:center;margin-top:30px;text-transform:uppercase;">
                <?php echo htmlspecialchars($row['category_name']); ?></h2>
        <?php }
    } else {
        echo '<h2 style="text-align:center;margin-top:30px;">Category not found.</h2>';
    }
    ?>
</div>

<div class="container" style="background:#F7F7F7; margin-top:30px; padding-bottom:30px;">
    <div class="wrapper">
        <div class="row">
            <h2 class="text-center mt-3">Dance Forms</h2>
        </div>
        <div class="row logo-slider">
            <?php
            $sql = "SELECT * FROM tbl_dance_forms WHERE category_id = $id"; // Fetch dance forms for the category
            $getDanceForms = mysqli_query($conn, $sql);

            if ($getDanceForms && mysqli_num_rows($getDanceForms) > 0) {
                while ($row = mysqli_fetch_assoc($getDanceForms)) {
                    ?>
                    <div class="col-md-4 mt-2">
                        <div class="category-box">
                            <img src="<?php echo htmlspecialchars($row['dance_image']); ?>" class="img-responsive" style="width:100%; height:auto;" alt="Dance Form">
                            <div class="category_name">
                                <span><?php echo htmlspecialchars($row['dance_name']); ?></span>
                            </div>
                            <a href="enroll.php?cat_id=<?php echo $row['category_id']; ?>&did=<?php echo $row['dance_id']; ?>" class="btn btn-warning" style="background:#922bc0; border-color:#922bc0;">Enroll Now</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<h3 style="text-align:center;">No dance forms available for this category.</h3>';
            }
            ?>
        </div>
    </div>
</div>
<?php include('inc/footer.php'); ?>
