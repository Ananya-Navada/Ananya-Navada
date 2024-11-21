<div class="sidebar-list">
    <a href="student_dashboard.php" class="nav-item nav-dashboard" aria-label="Go to Student Dashboard">
        <span class="icon-field" style="text-transform:uppercase; font-weight:bold; text-decoration:none;">
        </span>Student Dashboard [<?php echo htmlspecialchars($_SESSION['username']); ?>]
    </a>

    <a href="uploadStudentProfile.php?page=uploadDanceProfile" class="nav-item nav-uploadDanceProfile" aria-label="Upload Dance Profile">
        <span class="icon-field"><i class="fa fa-upload"></i>
        </span>Upload Dance Profile
    </a>

    <a href="enrollDetails.php?page=enrollDetails" class="nav-item nav-enrollDetails" aria-label="Enrollment Details">
        <span class="icon-field"><i class="fa fa-list"></i>
        </span>Enrollment Details
    </a>
    
    <a href="feedback.php?page=feedback" class="nav-item nav-feedback" aria-label="Provide Feedback"> 
        <span class="icon-field"><i class="fa fa-comment"></i>
        </span>Provide Feedback
    </a>
</div>
