$(document).ready(function(){
    function updateCardNumber(){
        $.ajax({
            url: 'action.php',
            method: 'get',
            data: { cardItem: "card-number" },
            success:function(response){
                $("#card-number").text(response);
            }
        });
    }
     // Update card number initially
     updateCardNumber();

     // Update card number every 5 seconds
     setInterval(updateCardNumber, 5000);
    $(".card-button").click(function(e){
        e.preventDefault();
        var $form = $(this).closest(".form-submit");
        var pid = $form.find(".pid").val();
        var pname = $form.find(".pname").val();
        var pprice = $form.find(".pprice").val();
        var pimage = $form.find(".pimage").val();
        var pcode = $form.find(".pcode").val();
        var user_id = $(this).data('user-id'); // Get the user ID from the button's data attribute
        
        if (!user_id) {
            alert("Please login first.");
            return; // Stop further execution
        }
        $.ajax({
            url: 'action.php',
            method: 'post',
            data: {
                pid: pid,
                pname: pname,
                pprice: pprice,
                pimage: pimage,
                pcode: pcode,
                user_id: user_id
            },
            success:function(response){
                $("#message").html(response);
                updateCardNumber(); // Call the function here, after adding the item to the cart
            }
        });
    });
        // Initially hide the navbar collapse
        $("#navbarSupportedContent").hide();
     
        // Toggle the navbar collapse on button click
       $("#navbarToggle").click(function() {
          $("#navbarSupportedContent").slideToggle();
        });
    
        // Check window width on load and resize
        $(window).on("load resize", function() {
          if ($(window).width() >= 768) {
            // Hide the toggle button and show the navbar collapse
            $("#navbarToggle").hide();
            $("#navbarSupportedContent").show();
          } else {
            // Show the toggle button and hide the navbar collapse
            $("#navbarToggle").show();
            $("#navbarSupportedContent").hide();
          }
        });
});