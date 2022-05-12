<!doctype html>
<html lang="en">

<head>
    <?php
    $pageIdentifier = "admin";
    require('includes/database.php');
    require('includes/nav.php');
    createMetaTags("Admin Panel", "Admin Panel", "");
    checkForAdmin();
    ?>

</head>
<h1>User Listing</h1>

<?php
$users = getAllUsers($link);
?>

<body>
<div class="form-group">
    <span id="text1">Test</span>
    <Input id="text1_input" class="form-control" style="display:none"/>
</div>

<div class="container">

    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Phone Number</th>
            <th scope="col">Registration</th>
            <th scope="col">DOB</th>
            <th scope="col">Account Status</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_array($users)) {
            echo '<tr>
                <th scope="row">' . $row['user_id'] . '</th>';
            echo '<td>' . $row['first_name'] . '</td>';
            echo '<td>' . $row['last_name'] . '</td>';
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['contact_no'] . '</td>';
            echo '<td>' . $row['registration'] . '</td>';
            echo '<td>' . $row['dob'] . '</td>';
            echo '<td>' . $row['status'] . '</td>
                </tr>';
        }
        ?>
        </tbody>
    </table>


</div>
</body>

<script>

</script>


<?php
require('includes/footer.php');
?>