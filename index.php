<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="registrationForm">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label>Upload Attachments:</label>
        <div id="dropzone" class="dropzone"></div><br>

        <button type="submit">Register</button>
    </form>

    <script>
        // Initialize Dropzone
        const uploadedFiles = [];
        const myDropzone = new Dropzone("#dropzone", {
            url: "upload.php",
            autoProcessQueue: false,
            addRemoveLinks: true,
            maxFilesize: 2, // MB
            acceptedFiles: ".jpeg,.jpg,.png,.pdf",
            success: function (file, response) {
                uploadedFiles.push(JSON.parse(response).fileUrl);
            },
        });

        // Submit form and upload files
        $("#registrationForm").on("submit", function (e) {
            e.preventDefault();

            // Process files in Dropzone first
            myDropzone.processQueue();

            // Wait for Dropzone uploads to finish
            myDropzone.on("queuecomplete", function () {
                // Collect form data
                const formData = {
                    name: $("#name").val(),
                    email: $("#email").val(),
                    password: $("#password").val(),
                    files: uploadedFiles, // Uploaded file URLs
                };

                // Submit data via AJAX
                $.ajax({
                    url: "register.php",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        alert(response.message);
                    },
                    error: function () {
                        alert("An error occurred. Please try again.");
                    },
                });
            });
        });
    </script>
</body>
</html>
