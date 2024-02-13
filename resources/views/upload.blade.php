<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Editor</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Hide the mask input field */
        #mask {
            display: none;
        }
        #preview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container mt-5">
        <form id="uploadForm" action="{{ route('process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="feature">Choose editing feature:</label>
                <select class="form-control" name="feature" id="feature">
                    <option value="background_removal">Background Removal</option>
                    <option value="cleanup">Cleanup</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Choose an image:</label>
                <input class="form-control-file" type="file" name="image" id="image" accept="image/*" required>
            </div>
            
            <input type="hidden" name="selectedFeature" id="selectedFeature" value="">

            <div class="form-group" id="maskGroup">
                <label for="mask">Choose a mask (if applicable):</label>
                <input class="form-control-file" type="file" name="mask" id="mask" accept="image/*" required>
            </div>

            <!-- Image Preview -->
            <div class="form-group">
                <label for="preview">Preview:</label>
                <img id="preview" src="#" alt="Image Preview">
            </div>

            <!-- Image Manipulation Options -->
            <div class="form-group">
                <label>Image Manipulation:</label>
                <button type="button" class="btn btn-secondary" onclick="rotateImage()">Rotate</button>
                <button type="button" class="btn btn-secondary" onclick="resizeImage()">Resize</button>
            </div>

            <button class="btn btn-primary" id="submitButton" type="submit" disabled>Upload & Process</button>
        </form>
    </div>

    <!-- External JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/upload.js"></script>
</body>
</html>
