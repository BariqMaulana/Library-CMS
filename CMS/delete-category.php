<?php
    session_start();
    if (!isset($_SESSION["username"])) {
        ?>
        <script type="text/javascript">
            window.location="login.php";
        </script>
        <?php
    }

    include 'inc/connection.php';
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        // Check if the user confirmed the deletion
        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            // Proceed with the deletion
            mysqli_query($link, "DELETE FROM categories WHERE id=$id");

            ?>
            <script type="text/javascript">
                window.location="display-categories.php";
            </script>
            <?php
        } else {
            // Show the confirmation prompt
            ?>
            <script type="text/javascript">
                var confirmation = confirm("Are you sure you want to delete this category?");
                if (confirmation === true) {
                    // If the user confirms, redirect with 'confirm' parameter set to 'yes'
                    window.location = "delete-category.php?id=<?php echo $id; ?>&confirm=yes";
                } else {
                    // If the user cancels, redirect back to the book list page
                    window.location = "display-categories.php";
                }
            </script>
            <?php
        }
    }
?>