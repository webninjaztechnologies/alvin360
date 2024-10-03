document.addEventListener('DOMContentLoaded', function () {
  var elements = document.querySelectorAll('.socialv_media_manager');
  
  elements.forEach(function (element) {
    element.addEventListener('click', function (e) {
      e.preventDefault();
      var img = this.parentNode;
      var field = img.querySelector('.menu_icon_id');
      var image_frame;

      if (image_frame) {
        image_frame.open();
      }

      image_frame = wp.media({
        title: 'Select Media',
        multiple: false,
        library: {
          type: 'image',
        }
      });

      image_frame.on('close', function () {
        var selection = image_frame.state().get('selection');
        var gallery_ids = [];
        selection.each(function (attachment) {
          gallery_ids.push(attachment.get('id'));
        });
        var ids = gallery_ids.join(',');
        field.value = ids;
        Refresh_Image(ids, img);
      });

      image_frame.on('open', function () {
        var selection = image_frame.state().get('selection');
        var ids = document.querySelector('input#socialv_image_id').value.split(',');
        
        ids.forEach(function (id) {
          var attachment = wp.media.attachment(id);
          attachment.fetch();
          selection.add(attachment ? [attachment] : []);
        });
      });

      image_frame.open();
    });
  });
});


// Ajax request to refresh the image preview
function Refresh_Image(the_id, img_select) {
  var data = new FormData();
  data.append('action', 'socialv_ajax_menu_get_image');
  data.append('id', the_id);

  fetch(ajaxurl, {
    method: 'POST',
    body: data,
  })
  .then(function (response) {
    return response.json();
  })
  .then(function (response) {
    if (response.success === true) {
      var img = img_select.querySelector('img');
      if (img === null) {
        var newImage = document.createElement('img');
        newImage.src = response.data.image;
        newImage.removeAttribute('width');
        newImage.removeAttribute('height');
        img_select.querySelector('.socialv_media_manager').appendChild(newImage);
      } else {
        var newImage = document.createElement('img');
        newImage.src = response.data.image;
        newImage.removeAttribute('width');
        newImage.removeAttribute('height');
        img_select.replaceChild(newImage, img);
      }
    }
  })
  .catch(function (error) {
    console.error('Error:', error);
  });
}
