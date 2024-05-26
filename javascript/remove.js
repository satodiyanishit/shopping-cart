$(document).ready(function(){
        // Add click event listener to close button using event delegation
        $(document).on("click", ".close-button", function() {
            // Remove the parent element of the close button when clicked
            $('.error-message, .success-message').remove();
        });
})