export function splitText() {

    var elements = $('.split-text');

    // Iterate over each element
    elements.each(function() {
      // Get the string with the class name 'splitline-animation' for the current element
      var inputText = $(this).html();
  
      // Split the string into an array based on new lines
      var lines = inputText.split(' ');
  
      // Create a div element for each line and construct the new HTML content
      var newContent = lines.map(function(line, index) {
        return '<div class="split-text__wrapper"><div class="split-text__text' + ' delay-' + index + '">' + line + ' </div></div>';
      }).join('');
  
      // Replace the content of the original element with the new HTML content for the current element
      $(this).html(newContent);
    });
}