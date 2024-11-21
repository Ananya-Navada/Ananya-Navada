<?php include('inc/header.php'); ?>

<div class="container" style="padding: 0px;">
    <img src="assets/img/staff.jpg" alt="Our Staff and Instructors" style="width:100%; height:400px;">
</div>

<div class="row">
    <h2 style="text-align:center;">STAFF & INSTRUCTORS</h2>
</div>

<div class="row">
    <div class="wrapper">
        <?php
        include('config/db.php');
        $sql = "SELECT * FROM tbl_instructors";
        $getInstructors = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($getInstructors) > 0) {
            while ($row = mysqli_fetch_assoc($getInstructors)) {
        ?>
                <div class="col-md-4" style="margin-top:10px;">
                    <div class="category-box">
                        <img src="<?php echo $row['instructor_image']; ?>" style="width:50%; margin:0 auto; border-radius:50%;" alt="<?php echo $row['instructor_name']; ?>">
                        <div class="category_name" style="background:#922bc0;">
                            <span style="color:#FFF;"><?php echo $row['instructor_name']; ?> <br/> Experience: <?php echo $row['experience']; ?> Years</span>
                        </div>
                    </div>
                </div>
        <?php 
            }
        } else {
            echo '<div class="col-md-12"><p>No instructors found.</p></div>';
        }
        ?>
    </div>
</div>

<?php include('inc/footer.php'); ?>
