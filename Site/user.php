<?php

$pageIdentifier = "user";
require('includes/nav.php');

session();
if (!isset($_SESSION['username'])) {
    header('Location: ' . 'login.php');
}


# Open database connection.
require('includes/database.php');
$userId = $_SESSION['user_id'];
$q = "SELECT * FROM `wf_users` WHERE `user_id` = $userId";
$r = mysqli_query($link, $q);
if (mysqli_num_rows($r) > 0) {
    echo '
<div class="container">
    <div class="row">
        ';
    while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        echo '
        <div class="col-sm">
            <div class="alert alert-dark" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>	
                <h1>' . $row['first_name'] . ' ' . $row['last_name'] . '<strong>  </h1> 
                <p><strong> User ID : ' . $row['user_id'] . ' </strong></p>
                <p><strong> Email : </strong> ' . $row['email'] . ' </p>
                <p><strong> Registration Date : </strong> ' . $row['registration'] . ' </p>
                <p><strong> Account Status : </strong> ' . $row['status'] . ' </p>
                <p><strong> Role : </strong> ' . $row['role'] . ' </p>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-link" data-toggle="modal" data-target="#password">
                    <i class="fa fa-edit"></i>  Change Password
                </button>
            </div>
        </div> 
        ';
    }
} else {
    echo '<h3>No user details.</h3>';
}



# Retrieve items from 'users' database table.
$q = "SELECT * FROM `wf_users` WHERE `user_id` = $userId";
$r = mysqli_query($link, $q);
if (mysqli_num_rows($r) > 0) {
    echo '<div class="col-sm">';

    while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
        echo '

            <div class="alert alert-secondary" role="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h1>Card Stored</h1>
                <p><strong> Card Holder : </strong> ' . $row['first_name'] . '  ' . $row['last_name'] . ' </p>
                <p><strong> Card Number : </strong> ' . $row['card_number'] . ' </p>
                <p><strong> Expire Date : </strong> ' . $row['card_exp'] . '</p>
                <p><strong> CVV Number : </strong> ' . $row['card_cvv'] . ' </p>
            </div>
        </div>
        ';
    }

    # Close database connection.
    mysqli_close($link);
} else {
    echo '<div class="alert alert-danger"  role="alert">
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h1>Card Stored</h1>
            <h3>No card stored.</h3>
        </div>

        ';
}
?>
<div class="modal fade" id="password" tabindex="-1" role="dialog" aria-labelledby="password" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="change-password.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <input type="password" name="pass1" class="form-control" placeholder="New Password" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="pass2" class="form-control" placeholder="Confirm New Password" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="submit" name="btnChangePassword" class="btn btn-dark btn-lg btn-block" value="Save Changes" />
                    </div>
                </div>
            </form>
        </div>
        <!--Close body-->
    </div>
    <!--Close modal-body-->
</div><!-- Close modal-fade-->

<a href="payment.php">
    <button name="buy" type="submit" class="btn btn-primary">Buy premium/Update card</button>
</a>

