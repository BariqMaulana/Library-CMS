<?php 
ob_start();
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
 ?>
<?php


if(isset($_GET["id"])) {
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
							<span class="disabled">Export Book</span>
						</div>
					</div>
				</div>
				<div class="profile-content">
					<div class="row">
						<div class="col-md-3">
							<div class="photo">
								<?php
                                    $res = mysqli_query($link, "SELECT* FROM book WHERE id='$_GET[id]'");
                                    while ($row = mysqli_fetch_array($res)){
                                        ?><img src="<?php echo $row["book_cover"]; ?> " height="" width="" alt="something wrong"></a> <?php
                                    }
                                ?>
							</div>
							<div class="uploadPhoto">
								<div class="gap-30"></div>
								<!-- <form action="" method="post" enctype="multipart/form-data"> -->
									<input type="file" name="image" class="modal-mt" id="image">
									<div class="gap-30"></div>
								<!-- </form> -->
							</div>
						</div>
						<div class="col-md-7 ml-30">
							<div class="details">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="title" class="text-right">Book Title:</label>
                                        <input type="text" class="form-control custom"  name="title" readonly="readonly" value="<?php echo $bTitle; ?>" />
                                    </div>
                                    <div class="form-group">
                                         <label for="category">Book Category:</label>
                                        <input type="text" class="form-control custom" name="category" readonly="readonly" value="<?php echo $bCategory; ?>" />
                                    </div>
                                    <div class="form-group">
                                         <label for="description">Book Description:</label>
                                        <input type="text" class="form-control custom"  name="description" readonly="readonly" value="<?php echo $bDescription; ?>" />
                                    </div>
                                    <div class="form-group">
                                         <label for="quantity">Book Quantity:</label>
                                        <input type="text" class="form-control custom"  name="quantity" readonly="readonly" value="<?php echo $bQuantity; ?>" />
                                    </div> 
                                    <div class="form-group">
                                        <label for="book_file">Book File:</label>
                                         <input type="text" class="form-control custom"  name="book_file" readonly="readonly" value="<?php echo $bFile; ?>" />
                                    </div>
                                    <div class="text-right mt-20">
                                        <input type="submit" value="Export" class="btn btn-info" name="export">
                                    </div>
                                </form>
			                </div> 
                            <?php
                               // Handle form submission for export
                               if (isset($_POST['export'])) {
                                // Export book details to PDF using dompdf
                                require_once 'D:\Xampp\htdocs\Digital Perpustakaan berbasis Website\vendor\autoload.php';
                                $dompdf = new Dompdf\Dompdf();

                                // Build the HTML content for the PDF
                                $html = '
                                <h1>Book Details</h1>
                                <p><strong>Title:</strong> ' . $bTitle . '</p>
                                <p><strong>Category:</strong> ' . $bCategory . '</p>
                                <p><strong>Description:</strong> ' . $bDescription . '</p>
                                <p><strong>Quantity:</strong> ' . $bQuantity . '</p>';

                                $dompdf->loadHtml($html);
                                $dompdf->setPaper('A4', 'portrait');

                                // Render the PDF
                                $dompdf->render();

                                // Generate a unique filename for the PDF
                                $unique_filename = 'book_details_' . uniqid() . '.pdf';

                                // Save the PDF to the 'exported-books' directory with the unique filename
                                $file_path = __DIR__ . '/exported-books/' . $unique_filename;
                                file_put_contents($file_path, $dompdf->output());

                                // Force the file to be downloaded by the user
                                header("Content-Type: application/pdf");
                                header("Content-Disposition: attachment; filename='" . $unique_filename . "'");
                                readfile($file_path);
                                exit();
                             }
                            ?>
		                </div>    
					</div>
				</div>
			</div>					
		</div>
	</div>
	<?php 
		include 'inc/footer.php';
	 ?>