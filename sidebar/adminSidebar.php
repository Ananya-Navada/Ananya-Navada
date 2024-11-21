<div class="sidebar-list">
    <a href="admin_dashboard.php?page=dashboard" class="nav-item nav-dashboard" aria-label="Go to Admin Dashboard">
        <span class="icon-field" style="text-transform: uppercase; font-weight: bold; text-decoration: none;">
        </span>Admin Dashboard [<?php echo htmlspecialchars($_SESSION['username']); ?>]
    </a>

    <a href="danceCategories.php?page=danceCategories" class="nav-item nav-danceCategories" aria-label="View Dance Categories">
        <span class="icon-field"><i class="fa fa-plus"></i>
        </span>Dance Categories
    </a>

    <a href="createDance.php?page=createDance" class="nav-item nav-createDance" aria-label="Create Dance Forms">
        <span class="icon-field"><i class="fa fa-plus"></i>
        </span>Dance Forms
    </a>

    <a href="instructors.php?page=instructors" class="nav-item nav-instructors" aria-label="View Instructors">
        <span class="icon-field"><i class="fa fa-user"></i>
        </span>Instructors
    </a>

    <a href="students.php?page=students" class="nav-item nav-students" aria-label="View Students">
        <span class="icon-field"><i class="fa fa-graduation-cap"></i>
        </span>Students
    </a>

    <a href="enrolledStudents.php" class="nav-item nav-enrolledStudents" aria-label="View Enrolled Students">
   <span class="icon-field"><i class="fa fa-list-alt"></i></span>Enrolled Students
    </a>

</div>
