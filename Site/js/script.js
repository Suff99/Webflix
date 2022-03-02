

function shareOnTwitter(id, title) {
  var url = 'https://twitter.com/intent/tweet?url=https%3A%2F%2Fcraig.software%2Fwebflix%2Frelease.php%3Fid%3D' + id + '&text=Just%20finished%20watching%20' + title + '&hashtags=Popcorn%2CWebflix%20';
  TwitterWindow = window.open(url, 'TwitterWindow', width = 600, height = 300);
  return false;
}

function shareOnFacebook(id){
  var url = 'https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fcraig.software%2Fwebflix%2Frelease.php%3Fid%3D'+id;
  FacebookWindow = window.open(url, 'FacebookWindow', width = 600, height = 300);
  return false;
}


function createDatePicker(calander_id) {
  $.noConflict();
  jQuery(document).ready(function ($) {
    $(calander_id).datepicker({
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      yearRange: "-100:+0",
      ok: '',
      clear: 'Clear selection',
      close: 'Cancel'
    });
  });
}
