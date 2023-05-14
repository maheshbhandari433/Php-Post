<?php
// Directory where uploaded images from CKEditor will be stored
$target_dir = "uploads-in/";

// Check if an image upload is requested from CKEditor
if (!empty($_FILES) && isset($_FILES['upload'])) {
    // Generate a unique name for the uploaded image
    $target_file = $target_dir . uniqid() . "." . pathinfo($_FILES["upload"]["name"], PATHINFO_EXTENSION);

    // Move the uploaded image to the target directory
    if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
        // Provide the image URL to CKEditor
        $response = [
            'uploaded' => 1,
            'fileName' => $_FILES["upload"]["name"],
            'url' => $target_file
        ];
        echo json_encode($response);
    } else {
        $response = [
            'uploaded' => 0,
            'error' => 'Error uploading image.'
        ];
        echo json_encode($response);
    }
    exit; // Stop execution after handling the image upload
}

// Continue with other code (e.g., handling the form submission)

?>
