$(document).ready(function() {
    // Tar form vid submit
    $('#loginForm').on('submit', function(event) {
        event.preventDefault()
        
        const formData = $(this);

        $.ajax({
            url: '../../controller/loginController.php',
            type: 'POST',
            data: formData,
            success: function() {},
            error: function(xhr) {
                console.error('Error:', xhr.statusText);
            }
        })
    })
})