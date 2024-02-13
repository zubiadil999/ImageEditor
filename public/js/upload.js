document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("uploadForm");
    const featureSelect = document.getElementById("feature");
    const imageInput = document.getElementById("image");
    const maskInput = document.getElementById("mask");
    const submitButton = document.getElementById("submitButton");

    function clearErrorMessages() {
        const errorMessages = document.querySelectorAll(".error-message");
        errorMessages.forEach(message => message.remove());
    }

    featureSelect.addEventListener("change", function() {
        clearErrorMessages(); 
    });

    submitButton.addEventListener("click", function(event) {
        event.preventDefault(); 

        clearErrorMessages();

        let errors = [];

        if (!featureSelect.value) {
            errors.push("Please choose an editing feature.");
        }

        if (!imageInput.files.length) {
            errors.push("Image is required.");
        } else {
            const imageFile = imageInput.files[0];
            const validExtensions = ["jpg", "jpeg", "png"];
            const extension = imageFile.name.split(".").pop().toLowerCase();
            if (!validExtensions.includes(extension)) {
                errors.push("Invalid image format. Only JPG, JPEG, and PNG are allowed.");
            }
        }

        if (featureSelect.value === "cleanup" && !maskInput.files.length) {
            errors.push("Mask image is required.");
        } else if (maskInput.files.length && maskInput.files[0] !== imageInput.files[0]) {
            errors.push("Image and masked image should be the same.");
        }

        errors.forEach(errorMessage => {
            const errorElement = document.createElement("div");
            errorElement.classList.add("alert", "alert-danger", "error-message");
            errorElement.textContent = errorMessage;
            form.insertBefore(errorElement, submitButton);
        });

        if (errors.length === 0) {
            form.submit();
        }
    });
});


function handleFeatureSelection() {
    var selectedFeature = document.getElementById('feature').value;
    document.getElementById('selectedFeature').value = selectedFeature;

    var maskGroup = document.getElementById('maskGroup');
    if (selectedFeature === 'cleanup') {
        maskGroup.style.display = 'block';
        document.getElementById('mask').setAttribute('required', 'required'); 
    } else {
        maskGroup.style.display = 'none';
        document.getElementById('mask').removeAttribute('required'); 
    }
    
    document.getElementById('submitButton').disabled = false;
}

document.getElementById('feature').addEventListener('change', handleFeatureSelection);

handleFeatureSelection();

        // Function to rotate image
        function rotateImage() {
            // Fetch the image element
            var img = document.getElementById('preview');
            // Increment the rotation by 90 degrees
            var currentRotation = (parseInt(img.getAttribute('data-rotation')) || 0) + 90;
            // Apply rotation to the image
            img.style.transform = 'rotate(' + currentRotation + 'deg)';
            // Update the rotation attribute
            img.setAttribute('data-rotation', currentRotation);
        }

        // Function to resize image
        function resizeImage() {
            // Fetch the image element
            var img = document.getElementById('preview');
            // Fetch the current width and height
            var currentWidth = img.width;
            var currentHeight = img.height;
            // Reduce dimensions by 10% (for example)
            var newWidth = currentWidth * 0.9;
            var newHeight = currentHeight * 0.9;
            // Apply new dimensions to the image
            img.style.width = newWidth + 'px';
            img.style.height = newHeight + 'px';
        }

        // Function to handle image preview
        function handleImagePreview(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Event listener for image input change
        $('#image').change(function() {
            handleImagePreview(this);
        });