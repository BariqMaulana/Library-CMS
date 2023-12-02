<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include 'inc/header.php';
include 'inc/connection.php';

// Check if the category ID is provided in the URL
if (isset($_GET["id"])) {
    $category_id = $_GET["id"];

    // Retrieve the category details from the database based on the category ID
    $query = "SELECT * FROM categories WHERE id = $category_id";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0) {
        $category = mysqli_fetch_assoc($result);
        $cName = $category['name'];
    } else {
        echo "Category not found.";
        exit();
    }
}
?>

<!-- dashboard area -->
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
                        <span class="disabled">Update Category</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bstore">
            <form action="" method="post">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="name" placeholder="Category Name" value="<?php echo $cName; ?>">
                        </td>
                    </tr>
                </table>
                <div class="update mt-20">
                    <input type="submit" name="update" class="btn btn-info update" value="Update Category">
                </div>
            </form>
            <?php
                if (isset($_POST["update"])) {
                    // Get the updated category name from the form
                    $cName = $_POST["name"];

                    // Update the category name in the database
                    $query = "UPDATE categories SET name = '$cName' WHERE id = $category_id";

                    if (mysqli_query($link, $query)) {
                        echo '<div class="alert alert-success">Category updated successfully.</div>';
                    } else {
                        echo '<div class="alert alert-danger">Error updating category: ' . mysqli_error($link) . '</div>';
                    }
                }                    
            ?>
        </div>
    </div>
</div>

<?php
include 'inc/footer.php';
?>
