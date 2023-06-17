$(document).ready(function() {
  $('.featureCheckbox').on('change', function() {
    var form = $(this).closest('form');
    form.submit();
  });
});