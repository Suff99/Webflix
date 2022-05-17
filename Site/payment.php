<!doctype html>
<html lang="en">


<head>
    <?php
    $pageIdentifier = "home";
    require('includes/database.php');
    require('includes/nav.php');
    createMetaTags("Home", "Home", "");



    ?>
</head>

<?php

if (!isset($_SESSION['username'])) {
    header('Location: ' . 'login.php');
}

?>

<body class="d-flex flex-column min-vh-100">

    <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
        <h1>Purchase premium</h1><br>
    </div>

    <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
        <p>Purchasing will you a year of premium access to watch a large library of releases instantly!</p>
    </div>

    <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
        <img class='card-img' src='https://craig.software/webflix/img/logo.png' alt='Logo' style='width:20%'>


        <form action="includes/give_prenium.php" method="post">
            <div class="form-group row">
                <label for="card_number" class="col-4 col-form-label">Card Number</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-cc-visa"></i>
                            </div>
                        </div>
                        <input id="card_number" name="card_number" type="text" class="form-control" required="required">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="exp_date" class="col-4 col-form-label">Expiration Date</label>
                <div class="col-8">
                    <input id="exp_date" name="exp_date" pattern="^((0[1-9])|(1[0-2]))[\/\.\-]*((0[8-9])|(1[1-9]))$" placeholder="MM / YY" type="text" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label for="cvc" class="col-4 col-form-label">CVC Code</label>
                <div class="col-8">
                    <input id="cvc" name="cvc" pattern="^[0-9]{3,4}$" placeholder="CVC" type="text" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-4 col-8">
                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>

    </div>


    <?php
    require('includes/footer.php');
    ?>