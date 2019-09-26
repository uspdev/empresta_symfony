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

// jQuery plugin to prevent double submission of forms
// https://stackoverflow.com/questions/2830542/prevent-double-submission-of-forms-in-jquery
jQuery.fn.preventDoubleSubmission = function() {
  $(this).on('submit',function(e){
    var $form = $(this);

    if ($form.data('submitted') === true) {
      // Previously submitted - don't submit again
      e.preventDefault();
    } else {
      // Mark it so that the next submit can be ignored
      $form.data('submitted', true);
    }
  });

  // Keep chainability
  return this;
};
$('form').preventDoubleSubmission();

// https://stackoverflow.com/questions/277544/how-to-set-the-focus-to-the-first-input-element-in-an-html-form-independent-from
// Foco no primeiro input da página
$(document).ready(function() {
    $('form:first *:input[type!=hidden]:first').focus();
});


// É necessário testar melhor esse código
// https://stackoverflow.com/questions/27960841/usb-barcode-scanner-opens-browsers-downloads-page
// Problema com alguns leitores de códigos de barras no linux
/*
let data = ''
window.onload = function () {
     window.document.body.addEventListener('keydown', function(event){
        if( event.keyCode == 13 || event.keyCode == 16 ||  event.keyCode == 17 ) {
                event.preventDefault();
                return;
                }

                if(event.ctrlKey) {
                    event.preventDefault();
                    return;
                }

        data += event.key
    });
}
*/
