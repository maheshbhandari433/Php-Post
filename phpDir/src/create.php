<?php

require_once "index.php";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_name = $_POST["post_name"];
    $post_description = $_POST["post_description"];
    $created_date = date("Y-m-d H:i:s");

    // Check if an image has been uploaded
    if ($_FILES["post_image"]["error"] == UPLOAD_ERR_OK) {
        // Directory where uploaded images will be stored
        $target_dir = "uploads/";

        // Generate a unique name for the uploaded image
        $target_file = $target_dir . uniqid() . "." . pathinfo($_FILES["post_image"]["name"], PATHINFO_EXTENSION);

        // Move the uploaded image to the target directory
        if (move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file)) {

        // Insert the new post into the database with an image
            $stmt = $conn->prepare("INSERT INTO posts (post_name, post_description, post_image, created_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $post_name, $post_description, $target_file, $created_date);
        } else {
            echo "Error uploading image.";
            
            exit; // Stop execution if there's an error uploading the image
        }
    } else {
        // Insert the new post into the database without an image
        $stmt = $conn->prepare("INSERT INTO posts (post_name, post_description, created_date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $post_name, $post_description, $created_date);
    }
    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Post created successfully.";
    } else {
        echo "Error creating post: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

?>


<!DOCTYPE html>
<html>
<head>
    <title>Create</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
    .ck-editor__editable {
        height: 200px; /* Set the desired height here */
    }
</style>
<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
<body>
    <div class="container">
        <h1>Create Post</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="post_name">Post Name:</label>
                <input type="text" class="form-control" name="post_name" required>
            </div>
            <div class="form-group">
                <label for="post_description">Post Description:</label>
                <textarea id="post_description" class="form-control" name="post_description"></textarea>
            </div>
            <script>
                ClassicEditor
                    .create(document.querySelector('#post_description'), {
                        ckfinder: {
                            uploadUrl: 'textareaImageHandler.php' // URL of server-side image upload handler
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            </script>
            <div class="form-group">
                <label for="post_image">Post Image:</label>
                <input type="file" class="form-control-file" name="post_image">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</body>
</html>

