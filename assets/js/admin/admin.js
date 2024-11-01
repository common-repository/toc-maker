jQuery(document).ready(function () {
  jQuery("#zipang_loading").fadeTo(600, 0);
  jQuery("#zipang_loading_bg").fadeTo(600, 0);
  setTimeout(function () {
    jQuery("#zipang_loading").remove();
    jQuery("#zipang_loading_bg").remove();
    console.log('hi')
  }, 600);

  
  jQuery("#zipang_settings_submit").click(function () {
    jQuery("body").prepend('<div id="zipang_loading_bg"><div id="zipang_loading"></div></div>');
  });


});




var zipang_timeoutID;

function zipang_stopTimeout() {
  var pop_up_message = document.getElementById('zipang_pop_up_message');
  pop_up_message.classList.add('inactive');
  clearTimeout(zipang_timeoutID);
  setTimeout(function () {
    pop_up_message.classList.remove('inactive');
  }, 100);

}

function zipang_pop_up_message(message, bg_color) {
  var today = new Date();
  
  if (typeof zipang_timeoutID !== 'undefined')
    zipang_stopTimeout();
  if (bg_color === '') bg_color = '#222';
  var pop_up_message = document.getElementById('zipang_pop_up_message');
  pop_up_message.style.backgroundColor = bg_color;
  pop_up_message.classList.add('active');
  pop_up_message.innerHTML = message;
  zipang_timeoutID = setTimeout(function () {
    pop_up_message.classList.remove('active');
  }, 4000);
}
