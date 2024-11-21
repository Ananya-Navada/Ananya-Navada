<?php include('inc/header.php'); ?>
<div class="container" style="padding: 0px;">
    <img src="assets/img/contact.jpg" alt="Contact Us" class="img-fluid">
</div>
<div class="row">
    <h2 style="text-align: center;">CONTACT US</h2>
</div>
<div class="row justify-content-center" style="background: #f4f4f4; padding: 30px;">
    <div class="col-md-4">
        <form action="process_contact.php" method="post" class="form-horizontal">
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="void-control" style="border-radius:0px;height:40px;" placeholder="Name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" class="form-control" style="border-radius:0px;height:40px;" required>
            </div> 

            <div class="form-group">
                <label for="request">Request</label>
                <textarea id="request" name="request" rows="8" cols="20" placeholder="Request" class="form-control" style="border-radius:0px;" required></textarea>
            </div>

            <div class="form-group">
                <input type="submit" value="Submit" class="btn btn-primary" style="width:100%">
            </div>

        </form>
    </div>
</div>

<?php include('inc/footer.php'); ?>
