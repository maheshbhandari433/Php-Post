<?php
require_once "index.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card-img {
            height: 200px;
            object-fit: contain;
            padding: 1em; 
        }
        .expand-content {
            max-height: 300px;
            overflow: auto;
        }
        .expand-content img {
            max-height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Posts</h1>
        <a href="create.php" class="btn btn-primary m-2">Create new</a>
        <div class="row">
            <?php
            // Fetch all posts from the database
            $query = "SELECT * FROM posts";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $post_name = $row['post_name'];
                    $post_description = $row['post_description'];
                    $post_image = $row['post_image'];

                    // Display the post using a card
                    echo '<div class="col-md-4">';
                    echo '<div class="card mb-4">';
                    echo '<img src="' . $post_image . '" class="card-img card-img-top" alt="' . $post_name . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $post_name . '</h5>';
                    echo '<p class="card-text">' . substr($post_description, 0, 50) . '...</p>';

                    // triggers details extends card
                    echo '<button class="btn btn-primary" data-toggle="collapse" data-target="#postDetails' . $row['id'] . '">Details</button>';
                    echo ' ';

                    // triggers to update.php
                    echo '<a class="btn btn-primary" href="update.php?id=' . $row['id'] . '">Edit</a>';
                    echo ' ';

                    // triggers to delete.php
                    echo '<a class="btn btn-primary" href="delete.php?id=' . $row['id'] . '">Delete</a>';


                    echo '</div>';
                    echo '<div id="postDetails' . $row['id'] . '" class="collapse">';
                    echo '<div class="card-body expand-content">';
                    echo '<p class="card-text">' . $post_description . '</p>';

                   
                    
                    // Extract images from post_description
                    preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $post_description, $matches);

                    if (!empty($matches['src'])) {
                        foreach ($matches['src'] as $src) {
                            echo '<img src="' . $src . '" alt="Post Image">';
                        }
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo 'No posts found.';
            }

            $conn->close();
            ?>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>




