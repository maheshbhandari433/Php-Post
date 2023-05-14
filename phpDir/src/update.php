<?php 

require_once "index.php";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST["post_id"];
    $post_name = $_POST["post_name"];
    $post_description = $_POST["post_description"];

    // Check if an image has been uploaded
    if ($_FILES["post_image"]["error"] == UPLOAD_ERR_OK) {
        // Directory where uploaded images will be stored
        $target_dir = "uploads/";

        // Generate a unique name for the uploaded image
        $target_file = $target_dir . uniqid() . "." . pathinfo($_FILES["post_image"]["name"], PATHINFO_EXTENSION);

        // Move the uploaded image to the target directory
        if (move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file)) {
            // Update the post in the database with an image
            $stmt = $conn->prepare("UPDATE posts SET post_name=?, post_description=?, post_image=? WHERE id=?");
            $stmt->bind_param("sssi", $post_name, $post_description, $target_file, $post_id);
        } else {
            echo "Error uploading image.";
            exit; // Stop execution if there's an error uploading the image
        }
    } 

    else {
        // Update the post in the database without an image
        $stmt = $conn->prepare("UPDATE posts SET post_name=?, post_description=? WHERE id=?");
        $stmt->bind_param("ssi", $post_name, $post_description, $post_id);
    }  
    
    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Post updated successfully.";
    } else {
        echo "Error updating post: " . $stmt->error;
    }

    $stmt->close();
}

else {
    // Retrieve the post data based on the provided ID
    $post_id = $_GET['id'] ?? '';
    if (!empty($post_id)) {
        // Fetch the post from the database
        $stmt = $conn->prepare("SELECT post_name, post_description FROM posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $stmt->bind_result($post_name, $post_description);
        $stmt->fetch();
        $stmt->close();
    }
}

    $conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Update Post</title>
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
        <h1>Update Post</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="post_id">Post ID:</label>
                <input type="number" class="form-control" name="post_id" value="<?php echo $_GET['id'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="post_name">Post Name:</label>
                <input type="text" class="form-control" name="post_name" value="<?php echo $post_name ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="post_description">Post Description:</label>
                <textarea id="post_description" class="form-control" name="post_description"><?php echo htmlspecialchars($post_description) ?? ''; ?></textarea>
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
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
