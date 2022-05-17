<!doctype html>
<html lang="en">


<head>
    <?php
    $pageIdentifier = "home";
    require('includes/database.php');
    require('includes/nav.php');


    if (isset($_SESSION['username'])) {
        $dialogMessage = array("You are already logged in.");
        header('Location: ' . 'index.php?error=true&dialog=' . json_encode($dialogMessage));
        die();
    }

    ?>

</head>

<body class="d-flex flex-column min-vh-100">

    <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
        <img class='card-img' src='https://craig.software/webflix/img/logo.png' alt='Logo' style='width:20%'>
    </div>

    <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">

        <form action="mail/reset-password.php" class="row g-3" method="post" class="alert-dismissible fade show" role="alert">

            <div class="form-group row">
                <label for="email" class="col-2 col-form-label">Email</label>
                <div class="col-10">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-envelope"></i>
                            </div>
                        </div>
                        <input id="email" name="email" placeholder="example@webflix.com" type="text" aria-describedby="emailHelpBlock" required="required" class="form-control">
                    </div>
                    <span id="emailHelpBlock" class="form-text text-muted">Please insert the email associated with your account. You will recieve a new password</span>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-2 col-10">
                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>


    <?php
    require('includes/footer.php');
    ?>