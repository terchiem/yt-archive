let searchbar = document.querySelector('#search-text');
if (!searchbar.value) {
  document.querySelector('#submit-btn').disabled = true;
} 

$(document).ready(() => {

  $('.browse-btn').on('click', () => {
    $('.categories').toggleClass('open');
  })

  $('.error-dialog').on('click', () => {
    $('.error-dialog').addClass('hidden');
  })

  $('#search-text').on('input', function() {
    if (this.value.length < 2) {
      $('#submit-btn').prop('disabled',true);
    } else {
      $('#submit-btn').prop('disabled',false);
    }
  })
})