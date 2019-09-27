// jQuery disable ENTER key
jQuery.fn.disableEnter = function() {
    $('form').keypress(function(e) {
      //Enter key
      if (e.which == 13) {
          return false;
      }
    });
  };
  $('form').disableEnter();