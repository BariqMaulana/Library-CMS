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
                        <span class="disabled">display categories</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="dbooks">
                        <table id="dtBasicExample" class="table table-striped table-dark text-center">
                            <thead>
                                <tr>
                                    <th>Category Name</th>
                                    <th>Edit/Delete Category</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $res = mysqli_query($link, "SELECT * FROM categories");
                                while ($row = mysqli_fetch_array($res)) {
                                    echo "<tr>";
                                    echo "<td>";
                                    echo $row["name"];
                                    echo "</td>";
                                    echo "<td>";
                                    ?>
                                    <a href="delete-category.php?id=<?php echo $row["id"]; ?>"><i class="fas fa-trash" style="color: #FF0000"></i></a>
                                    <a href="update-category.php?id=<?php echo $row["id"]; ?>"><i class="fas fa-edit" style="color: #FFFF00"></i></a>
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
        $('#dtBasicExample').DataTable();
        $('.dataTables_length').addClass('bs-select');
    });
</script>
