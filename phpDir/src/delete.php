<!DOCTYPE html>
<html>
<head>
    <title>Delete Post</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Delete Post</h1>
        <?php
        require_once "index.php";

        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
            $post_id = $_GET["id"];

            // Fetch the post details from the database
            $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $post_name = $row['post_name'];
        ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $post_name; ?></h5>
                        <p class="card-text">Are you sure you want to delete this post?</p>
                        <form method="post" action="deletePost.php">
                            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            <a href="home.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
        <?php
            } else {
                echo "Post not found.";
            }

            $stmt->close();
        } else {
            echo "Invalid request.";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>


