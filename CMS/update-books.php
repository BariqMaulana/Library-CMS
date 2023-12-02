<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include 'inc/header.php';
include 'inc/connection.php';

// Check if the book ID is provided in the URL
if (isset($_GET["id"])) {
    $book_id = $_GET["id"];

    // Retrieve the book details from the database based on the book ID
    $query = "SELECT * FROM book WHERE id = $book_id";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0) {
        $book = mysqli_fetch_assoc($result);
        $bTitle = $book['title'];
        $bCategory = $book['category'];
        $bDescription = $book['description'];
        $bQuantity = $book['quantity'];
        $bCover = $book['book_cover'];
        $bFile = $book['book_file'];  
    } else {
        echo "Book not found.";
        exit();
    }
}
?>

<!--dashboard area-->
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
                        <span class="disabled">Update Book</span>
                    </div>
                </div>
            </div>
            <div class="bstore">
                <form action="" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="title" placeholder="Book Title" value="<?php echo $bTitle; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Books image
                                <input type="file" class="form-control" name="book_cover"  value="<?php echo $bCover; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Books file
                                <input type="file" class="form-control" name="book_file" value="<?php echo $bFile; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select class="form-control" name="category">
                                    <?php
                                    // Fetch categories from the categories database
                                    $res_categories = mysqli_query($link, "SELECT DISTINCT name FROM categories");
                                    
                                    // Loop through the categories and generate the options
                                    while ($category_row = mysqli_fetch_array($res_categories)) {
                                        $category_name = $category_row['name'];

                                        // Check if the current category is the same as the book's category
                                        if ($category_name === $bCategory) {
                                            echo '<option value="' . $category_name . '" selected>' . $category_name . '</option>';
                                        } else {
                                            echo '<option value="' . $category_name . '">' . $category_name . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="description" placeholder="Book Description" value="<?php echo $bDescription; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="quantity" placeholder="Book Quantity" value="<?php echo $bQuantity; ?>">
                            </td>
                        </tr>
                    </table>
                    <div class="update mt-20">
                        <input type="submit" name="update" class="btn btn-info update" value="Update Book">
                    </div>
                </form>
                <?php
                    if (isset($_POST["update"])) {
                        // Get the updated book information from the form
                        $bTitle = $_POST["title"];
                        $bCategory = $_POST["category"];
                        $bDescription = $_POST["description"];
                        $bQuantity = $_POST["quantity"];

                        // Check if a new book cover is uploaded
                        if (!empty($_FILES['book_cover']['name'])) {
                            // Validate book cover file type (jpeg/jpg/png)
                            $allowed_image_types = array("image/jpeg", "image/jpg", "image/png");
                            if (in_array($_FILES['book_cover']['type'], $allowed_image_types)) {
                                $image_name = $_FILES['book_cover']['name'];
                                $temp = explode(".", $image_name);
                                $newfilename = round(microtime(true)) . '.' . end($temp);
                                $bCover = "books-image/" . $newfilename;
                                move_uploaded_file($_FILES["book_cover"]["tmp_name"], $bCover);
                            } else {
                                echo '<div class="alert alert-danger">Invalid book cover file type. Allowed types: JPEG, JPG, PNG.</div>';
                            }
                        }

                        // Check if a new book file is uploaded
                        if (!empty($_FILES['book_file']['name'])) {
                            // Validate book file type (pdf)
                            $allowed_pdf_types = array("application/pdf");
                            if (in_array($_FILES['book_file']['type'], $allowed_pdf_types)) {
                                $file_name = $_FILES['book_file']['name'];
                                $temp2 = explode(".", $file_name);
                                $newfilename2 = round(microtime(true)) . '.' . end($temp2);
                                $bFile = "books-file/" . $newfilename2;
                                move_uploaded_file($_FILES["book_file"]["tmp_name"], $bFile);
                            } else {
                                echo '<div class="alert alert-danger">Invalid book file type. Only PDF files are allowed.</div>';
                            }
                        }

                        // Update the book information in the database
                        $query = "UPDATE book SET
                                    title = '$bTitle',
                                    category = '$bCategory',
                                    description = '$bDescription',
                                    quantity = '$bQuantity'";

                        // Append book cover and file paths if updated
                        if (!empty($bCover)) {
                            $query .= ", book_cover = '$bCover'";
                        }
                        if (!empty($bFile)) {
                            $query .= ", book_file = '$bFile'";
                        }

                        $query .= " WHERE id = $book_id";

                        if (mysqli_query($link, $query)) {
                            echo '<div class="alert alert-success">Book information updated successfully.</div>';
                        } else {
                            echo '<div class="alert alert-danger">Error updating book information: ' . mysqli_error($link) . '</div>';
                        }
                    }                    
                ?>
            </div>
        </div>
    </div>
</div>

<?php
include 'inc/footer.php';
?>
