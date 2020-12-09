require('./bootstrap');

$(function () {
  // $('#addGuestBtn').on('click', function() {
  //   $('.register-input-wrapper').append('<input type="text" class="form-control mt-2" placeholder="Name" />');
  // });

  // Prevent multiple submites on every form trough the app
  $('form').on('submit', function() {
    $(this).find( '[type=\'submit\']').attr('disabled', 'true');
  });
});