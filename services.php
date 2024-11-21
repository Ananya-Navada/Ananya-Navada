<?php include('inc/header.php'); ?>

<div class="container" style="padding: 0px;">
    <img src="assets/img/services.jpg" alt="Our Services" style="width:100%; height:400px;">
</div>

<div class="row">
    <h2 style="text-align:center;">OUR SERVICES</h2>
</div>

<div class="row">
    <div class="wrapper">
        <?php
        include('config/db.php');
        $sql = "SELECT * FROM tbl_dance_categories";
        $getCategories = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($getCategories) > 0) {
            while ($row = mysqli_fetch_assoc($getCategories)) {
                $category_id = $row['category_id'];
        ?>
                <div class="col-md-4" style="margin-top:10px;">
                    <div class="category-box">
                        <img src="<?php echo $row['category_image']; ?>" style="width:85%; margin-left:14px;" alt="<?php echo $row['category_name']; ?>">
                        
                        <div class="category_name">
                            <span>
                                <?php echo $row['category_name']; ?>
                            </span>
                        </div>
                        <a href="viewDanceForms.php?id=<?php echo $category_id; ?>" class="btn btn-warning" style="background:#922bc0; border-color:#922bc0;">View Dance Forms</a>
                    </div>
                </div>
        <?php 
            }
        } else {
            echo '<div class="col-md-12"><p>No categories found.</p></div>';
        }
        ?>
    </div>
</div>

<?php include('inc/footer.php'); ?>
