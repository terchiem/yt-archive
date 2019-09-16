


$(document).ready(() => {
  $('.browse-btn').on('click', () => {
    $('.categories').toggleClass('open');
  })

  $('.error-dialog').on('click', () => {
    $('.error-dialog').addClass('hidden');
  })

  $('#searchbar').on('input', function() {
    if (!this.value) {
      $('#submit-btn').prop('disabled',true);
    } else {
      $('#submit-btn').prop('disabled',false);
    }
  })
})