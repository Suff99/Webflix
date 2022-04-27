function shareOnTwitter(id, title) {
    const url = 'https://twitter.com/intent/tweet?url=https%3A%2F%2Fcraig.software%2Fwebflix%2Frelease.php%3Fid%3D' + id + '&text=Just%20finished%20watching%20' + title + '&hashtags=Popcorn%2CWebflix%20';
    let twitterWindow = window.open(url, 'twitterWindow', width = 600, height = 300);
    return false;
}

function shareOnFacebook(id) {
    const url = 'https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fcraig.software%2Fwebflix%2Frelease.php%3Fid%3D' + id;
    let facebookWindow = window.open(url, 'facebookWindow', width = 600, height = 300);
    return false;
}

function lookup() {
    var value = document.getElementById('search').value;
    $.getJSON('https://craig.software/webflix/includes/search.php?search=' + value, function (data) {
        console.log(data[0].title);
    });
}


function createDatePicker(calander_id) {
    $.noConflict();
    jQuery(document).ready(function ($) {
        $(calander_id).datepicker({
            dateFormat: 'dd-mm-yy', // Format date to Day/Month/Year
            changeMonth: true, // Enable the user to select the month
            changeYear: true, // Enable the user to select the year
            yearRange: "-100:+0" // Allow the selection of the past 100 years within the date picker
        });
    });
}
