<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processed Image</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- External CSS -->
    <link href="css/styles.css" rel="stylesheet">

    <style>
        .download-link {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 10px;
    transition: background-color 0.3s ease; /* Add transition for smooth color change */
}

.download-link:hover {
   cursor: pointer;
   background-color: #0056b3;
   color: #fff; /* Change text color on hover */
}

    </style>

</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Processed Image</h1>

        <!-- Loading indicator -->
        <div class="loader"></div>

        @if (isset($editedImage['original_image_path']))
            <h2>Original Image:</h2>
            <img src="{{ asset($editedImage['original_image_path']) }}" alt="Original Image" class="img-fluid mb-3">
            <a href="{{ asset($editedImage['original_image_path']) }}" download="original_image.jpg" class="download-link">Download Original Image</a>
        @endif

        @if (isset($editedImage['masked_image_path']))
            <h2>Masked Image:</h2>
            <img src="{{ asset($editedImage['masked_image_path']) }}" alt="Masked Image" class="img-fluid mb-3">
            <a href="{{ asset($editedImage['masked_image_path']) }}" download="masked_image.jpg" class="download-link">Download Masked Image</a>
        @endif

        @if (isset($base64Image))
            <h2>Edited Image:</h2>
            <img src="{{ $base64Image }}" alt="Edited Image" class="img-fluid mb-3">
            <!-- Assuming $base64Image contains a base64 encoded image -->
            <a href="{{ $base64Image }}" download="edited_image.jpg" class="download-link">Download Edited Image</a>
        @endif
    </div>
    <!-- External JavaScript -->
    <script src="js/script.js"></script>
</body>
</html>
