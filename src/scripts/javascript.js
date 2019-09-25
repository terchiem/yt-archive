let searchbar = document.querySelector('#search-text');
if (searchbar.value.length < 2) {
  document.querySelector('#submit-btn').disabled = true;
} 

let likesBar = document.querySelector('#likes-ratio');
if (likesBar) {
  likesBar.style.width = likesRatio();
}

function likesRatio() {
  let likes = parseInt($('.likes').text().replace(',', ''));
  let dislikes = parseInt($('.dislikes').text().replace(',', '')); 
  let ratio = (likes / (likes + dislikes)) * 100;
  return ratio.toFixed(1) + '%';
}

/* =================================== */

$(document).ready(() => {

  $('#browse-btn').on('click', () => {
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

