$(document).ready(function() {
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