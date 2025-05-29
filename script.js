// Function to handle file upload and preview
function handleFileUpload(input, preview) {
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
}

// Initialize file upload handlers
document.addEventListener('DOMContentLoaded', function() {
    const initialPhoto = document.getElementById('initial-photo');
    const finalPhoto = document.getElementById('final-photo');
    const initialPreview = document.getElementById('initial-preview');
    const finalPreview = document.getElementById('final-preview');

    handleFileUpload(initialPhoto, initialPreview);
    handleFileUpload(finalPhoto, finalPreview);
}); 