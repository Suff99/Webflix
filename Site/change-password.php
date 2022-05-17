<?php
# Access session.



# Check form submitted.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require('includes/database.php');
    require('includes/util.php');


    session();

    $userId = $_SESSION['user_id'];

    # Connect to the database.

    # Initialize an error array.
    $errors = array();

    # Check for a password and matching input passwords.
    if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Passwords do not match.';
        } else {
            $p = mysqli_real_escape_string($link, trim($_POST['pass1']));
        }
    } else {
        $errors[] = 'Enter your password.';
    }


    # On success new password into 'users' database table.
    if (empty($errors)) {
        $q = "UPDATE wf_users SET `password`= SHA2('$p',256) WHERE user_id='$userId'";
        $r = @mysqli_query($link, $q);
        echo $q;
        if ($r) {
            header("Location: user.php");
        } else {
            echo "Error updating record: " . $link->error;
        }


        # Close database connection.

        mysqli_close($link);
        exit();
    }

    # Or report errors.
    else {
        echo ' <div class="container"><div class="alert alert-dark alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
	<h1><strong>Error!</strong></h1><p>The following error(s) occurred:<br>';
        foreach ($errors as $msg) {
            echo " - $msg<br>";
        }
        echo 'Please try again.</div></div>';
        # Close database connection.
        mysqli_close($link);
    }
}
