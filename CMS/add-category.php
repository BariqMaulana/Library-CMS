<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include 'inc/header.php';
include 'inc/connection.php';
?>

<!-- Category form -->
<div class="dashboard-content">
    <div class="dashboard-header">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="left">
                        <p><span>dashboard</span>Control panel</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right text-right">
                        <a href="dashboard.php"><i class="fas fa-home"></i>home</a>
                        <span class="disabled">Add Category</span>
                    </div>
                </div>
            </div>
            <div class="bstore">
                <form action="" method="post">
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="category_name" placeholder="Category Name" required="">
                            </td>
                        </tr>
                    </table>
                    <div class="submit mt-20">
                        <input type="submit" name="submit" class="btn btn-info submit" value="Add Category">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
<?php
if (isset($_POST["submit"])) {
    // Get the category name from the form
    $category_name = $_POST["category_name"];

    // Insert the new category into the database
    $query = "INSERT INTO categories (name) VALUES ('$category_name')";

    if (mysqli_query($link, $query)) {
        echo '<div class="alert alert-success">Category added successfully.</div>';
    } else {
        echo '<div class="alert alert-danger">Error adding category: ' . mysqli_error($link) . '</div>';
    }
}
?>

<?php
include 'inc/footer.php';
?>
