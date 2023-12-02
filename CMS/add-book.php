	<?php 
		 session_start();
		if (!isset($_SESSION["username"])) {
            ?>
                <script type="text/javascript">
                    window.location="login.php";
                </script>
            <?php
        }
        include 'inc/header.php';
        include 'inc/connection.php';
        // Move the database query and assignment outside of the conditional block
        $query = "SELECT id FROM users WHERE username = '$_SESSION[username]'";
        $result = mysqli_query($link, $query);
    
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $id = $row["id"];
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
							<span class="disabled">add book</span>
						</div>
					</div>
				</div>
				<div class="bstore">
					<form action="" method="post" enctype="multipart/form-data">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                   <input type="text" class="form-control" name="title" placeholder="Book Title" required=""> 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Books image
                                    <input type="file" class="form-control" name="f1" required="">
                                </td>
                            </tr>
                             <tr>
                                <td>
                                    Books file
                                    <input type="file" class="form-control" name="file" required="">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <select class="form-control" name="category" required="">
                                        <option value="">Select Category</option>
                                        <?php
                                        // Fetch all categories from the categories database
                                        $res_categories = mysqli_query($link, "SELECT * FROM categories");
                                        while ($category_row = mysqli_fetch_array($res_categories)) {
                                            $category_name = $category_row['name'];
                                            echo "<option value='$category_name'>$category_name</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="description" placeholder="Book Description" required="">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="quantity" placeholder="Book Quantity" required="">
                                </td>
                            </tr>
                        </table>
                        <div class="submit mt-20">
                        	<input type="submit" name="submit" class="btn btn-info submit" value="Add Book">
                        </div>
                	</form>
				</div>				
			</div>					
		</div>
	</div>

        <?php

            if (isset($_POST["submit"])) {

                // Validate book cover file type (jpeg/jpg/png)
                $allowed_image_types = array("image/jpeg", "image/jpg", "image/png");
                if (!in_array($_FILES['f1']['type'], $allowed_image_types)) {
                    echo '<div class="alert alert-danger">Invalid book cover file type. Allowed types: JPEG, JPG, PNG.</div>';
                } else {
                    $image_name=$_FILES['f1']['name'];
                    $temp = explode(".", $image_name);
                    $newfilename = round(microtime(true)) . '.' . end($temp);
                    $imagepath="books-image/".$newfilename;
                    move_uploaded_file($_FILES["f1"]["tmp_name"],$imagepath);
            
                    // Validate book file type (pdf)
                    $allowed_pdf_types = array("application/pdf");
                    if (!in_array($_FILES['file']['type'], $allowed_pdf_types)) {
                        echo '<div class="alert alert-danger">Invalid book file type. Only PDF files are allowed.</div>';
                    } else {
                        $file_name=$_FILES['file']['name'];
                        $temp2 = explode(".", $file_name);
                        $newfilename2 = round(microtime(true)) . '.' . end($temp2);
                        $filepath="books-file/".$newfilename2;
                        move_uploaded_file($_FILES["file"]["tmp_name"],$filepath);
            
                        $query = "INSERT INTO book (title, category, description, quantity, book_file, book_cover, user_id) 
                                  VALUES ('$_POST[title]', '$_POST[category]', '$_POST[description]', '$_POST[quantity]', '$filepath', '$imagepath', $id)";
            
                        if (mysqli_query($link, $query)) {
                            echo '<div class="alert alert-success">Book information updated successfully.</div>';
                        } else {
                            echo '<div class="alert alert-danger">Error updating book information: ' . mysqli_error($link) . '</div>';
                        }
                    }
                }
            }
        ?>
			
	<?php 
		include 'inc/footer.php';
	 ?>