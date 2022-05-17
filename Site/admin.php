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
<h1>Admin Panel</h1>


<body>
    <div class="container">
        <h1>Titles</h1>

        <div class="row text-center justify-content-center align-items-center mx-0 px-0 text-black">
            <div class="col-sm-15">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Title</h5>
                            <p class="card-text">Register a new title</p>
                            <a name="add_movie" href="addtitle.php" class="btn btn-primary">Go</a>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="col-sm-15">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Category</h5>
                            <p class="card-text">Register a new category</p>
                            <button name="add_category" data-toggle="modal" data-target="#category_modal" class="btn btn-primary">Go
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="col-sm-15">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <h5 class="card-title">List Categories</h5>
                            <p class="card-text">List categories and delete them</p>
                            <button name="list_categories" data-toggle="modal" data-target="#categories_modal" class="btn btn-primary">Go
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <h1>Users</h1>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">View Users</h5>
                        <p class="card-text">View Users</p>
                        <a name="list_users" href="users.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Category List Modal -->
    <div class="modal fade" id="categories_modal" tabindex="-1" role="dialog" aria-labelledby="category_modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Categories</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group text-black">
                        <?php
                        $queryCategories = "SELECT * FROM `wf_categories`;";
                        $result = @mysqli_query($link, $queryCategories);
                        while ($category = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            echo '<li class="list-group-item"><p class="card-text">' . $category['name'];
                            echo '<a name="delete_' . $category['name'] . '" href="includes/delete_category.php?category_id=' . $category['id'] . '">&nbsp;&nbsp;<i class="bi bi-trash-fill" style="color:black;"></i></p></a>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Add Modal -->
    <div class="modal fade" id="category_modal" tabindex="-1" role="dialog" aria-labelledby="category_modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="container">
                        <br>
                        <form name="category_form" id="category_form" action="includes/category.php" method="post" class="alert-dismissible fade show" role="alert">
                            <div class="form-group">
                                <label for="category">Category Name</label>
                                <input id="category" name="category" placeholder="Action" type="text" required="required" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="description">Category Description</label>
                                <textarea id="description" name="description" cols="40" rows="2" aria-describedby="descriptionHelpBlock" class="form-control"></textarea>
                                <span id="descriptionHelpBlock" class="form-text text-muted">(Optional) Provide a short
                                    description of the category</span>
                            </div>
                        </form>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" type="submit" form="form" name="btn_category" id="btn_category">Add
                    </button>
                </div>
            </div>
        </div>
    </div>


</body>


<script>
    $(document).on('click', '#btn_category', function() {
        $('#category_form').submit();
    });
</script>

<?php
require('includes/footer.php');
?>