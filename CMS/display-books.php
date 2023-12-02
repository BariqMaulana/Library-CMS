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
							<p><span>Dashboard</span>Control Panel</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="right text-right">
							<a href="dashboard.php"><i class="fas fa-home"></i>home</a>
							<span class="disabled">display books</span>
						</div>
					</div>
				</div>				
			</div>	
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dbooks">
                            <form id="categoryFilterForm">
                                <label for="categoryFilter">Filter by Category:</label>
                                <select id="categoryFilter" name="categoryFilter" class="form-control">
                                    <option value="">All Categories</option>
                                    <?php
                                    // Fetch distinct categories from the database
                                    $res_categories = mysqli_query($link, "SELECT DISTINCT category FROM book");
                                    while ($category_row = mysqli_fetch_array($res_categories)) {
                                        $category_name = $category_row['category'];
                                        echo "<option value='$category_name'>$category_name</option>";
                                    }
                                    ?>
                                </select>
                            </form>
                            <table id="dtBasicExample" class="table table-striped table-dark text-center">
                           <thead>
                                <tr>
                                    <th>Book Cover</th>
                                    <th>Book Title</th>
                                    <th>Book Category</th>
                                    <th>Book Description</th>
                                    <th>Book Quantity</th>
                                    <th>Manage Book</th>
                                </tr>
                           </thead>
                            
                            <tbody>
                             <?php
                                $user_id = $id;
                                $res = mysqli_query($link, "select * from book");
                                // $res = mysqli_query($link, "SELECT * FROM book WHERE user_id = $user_id OR user_id IS NULL");
                                while ($row = mysqli_fetch_array($res)) {
                                    echo "<tr>";
                                    echo "<td>"; ?><img src="<?php echo $row["book_cover"]; ?> " height="100" width="100" alt=""> <?php "</td>";
                                    echo "<td>";
                                    echo $row["title"];
                                    echo "</td>";
                                    echo "<td>";
                                    echo $row["category"];
                                    echo "</td>";
                                    echo "<td>";
                                    echo $row["description"];
                                    echo "</td>";
                                    echo "<td>";
                                    echo $row["quantity"];
                                    echo "</td>";
                                     echo "<td>";
                                    ?>
                                    <?php
                                    if ($user_id === $row["user_id"]) {
                                        // Allow actions for books created by the current user or for admin
                                        ?>
                                        <a href="delete-book.php?id=<?php echo $row["id"]; ?>"><i class="fas fa-trash" style="color: #FF0000"></i></a>
                                        <a href="update-books.php?id=<?php echo $row["id"]; ?>"><i class="fas fa-edit" style="color: #FFFF00"></i></a>
                                        <?php
                                    }?>
                                    <a href="export-book.php?id=<?php echo $row["id"]; ?> "><i class="fas fa-file" style="color: #7CFC00"></i></a>
                                    <?php
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                 ?>
                                 </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>				
		</div>
	</div>
	<?php 
		include 'inc/footer.php';
	 ?>
     
  <script>
    $(document).ready(function () {
        // Initialize DataTable with search and pagination features
        var table = $('#dtBasicExample').DataTable({
            searching: true,
            paging: true,
            lengthChange: true
        });

        // Custom filter for category column
        $('#categoryFilter').on('change', function () {
            var category = $(this).val();
            table.column(2).search(category).draw();
        });

        $('.dataTables_length').addClass('bs-select');
    });
 </script> 