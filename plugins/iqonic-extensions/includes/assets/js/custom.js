document.addEventListener('click', function (e) {
  /*---------------------------------------------------------------------
    Reject Friend suggetion
-----------------------------------------------------------------------*/
var parentNode = e.target.parentElement;
  if (e.target.classList.contains('reject') || parentNode.classList.contains('reject') ) {
    e.preventDefault();

    // Find the closest .socialv-suggested-friends-widget element
    var widget = e.target.closest('.socialv-suggested-friends-widget');
    var ajaxURL = widget.querySelector('.ajax-url').value;

    // Find the closest .socialv-friend-request element
    var item = e.target.closest('.socialv-friend-request');

    item.style.display = 'none'; // Hide the item

    // Get the 'href' attribute of the clicked element
    var url = e.target.getAttribute('href');
    if (url == null) {
      url = parentNode.getAttribute('href');
    }

    // Extract query parameters using a custom function
    var suggestion_id = getVarInUrl(url, 'suggestion_id');
    var _wpnonce = getVarInUrl(url, '_wpnonce');

    // Make an AJAX post request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', ajaxURL, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        // Handle the AJAX response if needed
      }
    };
    var params = 'action=socialv_friends_refused_suggestion&suggestion_id=' + suggestion_id + '&_wpnonce=' + _wpnonce;
    
    xhr.send(params);    

    if (item) {
      item.remove();
    }

    // Prevent the default behavior of the anchor element
     e.preventDefault(); 
  }
});

function getVarInUrl(url, name) {
  var urlParts = url.split('?');
  if (urlParts.length > 1) {
    var query = urlParts[1];
    var vars = query.split('&');
    for (var i = 0; i < vars.length; i++) {
      var pair = vars[i].split('=');
      if (pair[0] === name) {
        return pair[1];
      }
    }
  }
  return '';
}
