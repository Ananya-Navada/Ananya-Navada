<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script type="text/javascript" src="assets/js/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.js"></script>

    <title>Indian Dance Academy | Home Page</title>
    <style>
        .logout-link {
            margin-left: 15px; /* Adjust spacing as needed */
            font-weight: bold; /* Make it bold */
            color: red; /* Change color to differentiate */
        }
    </style>
    <script>
    $(document).ready(function(){
        $('#myCarousel').carousel();
        
        $('.dropdown-toggle').on('click', function(event) {
            event.preventDefault();
            $(this).parent().find('.dropdown-menu').toggle();
        });
    });
</script>

</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid" style="padding-left:25px;">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>   
                <a class="navbar-brand" href="index.php">Amazing Dance Academy</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="services.php">Our Services</a></li>
                    <li><a href="staff.php">Our Instructors</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if (isset($_SESSION['student_user_id']) || isset($_SESSION['instructor_user_id']) || isset($_SESSION['admin_user_id'])): ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dashboard<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php if (isset($_SESSION['student_user_id'])): ?>
                                    <li><a href="student_dashboard.php">Student</a></li>
                                <?php elseif (isset($_SESSION['instructor_user_id'])): ?>
                                    <li><a href="instructor_dashboard.php">Instructor</a></li>
                                <?php elseif (isset($_SESSION['admin_user_id'])): ?>
                                    <li><a href="admin_dashboard.php">Admin</a></li>
                                <?php endif; ?>
                             
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                    
                    <!-- Separate Logout Link -->
                    <li class="logout-link"><a href="logout.php">Logout</a></li> 
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>