/************
* AI Domain Search
* *************/

jQuery(document).ready(function ($) {
  jQuery('#ai-domain-results').on('click', 'li', function () {
    jQuery('body').addClass('aidomain-opened'); // Add class to body for styling or other purposes

    var aidomainname = jQuery(this).text().trim(); // Get the text content of the clicked li
    var inputField = jQuery('.domainname_input input[type="text"]');

    // Simulate typing into the input field
    typeText(inputField, aidomainname);
  });

  // Function to simulate typing with a delay
  function typeText(element, text) {
    var index = 0;
    var interval = setInterval(function () {
      element.val(text.substring(0, index + 1)); // Set value character by character
      index++;
      if (index >= text.length) {
        clearInterval(interval);
      }
    }, 100); // Adjust the delay (in milliseconds) between each character
  }// When clicking on an element with class .close-aidomain or .aidomain-overlay
  
  jQuery('.close-aidomain, .aidomain-overlay').on('click', function () {
    jQuery('body').removeClass('aidomain-opened'); // Use $ instead of jQuery for consistency
  });

});