$('[data-edit-text]').attr('data-fixture', '').attr('data-ce-tag', 'p');

jQuery(document).ready(function($){

// edit images

$('#wto-upload-form [name=slug]').val(location.pathname);

var editImages = function (){
  $('#wto-upload-form [name=edit-id]').val($(this).attr('data-edit-img'));
  $('#wto-upload-form [name=image]').trigger('click');
}

$('[name=image]').on('change', function(){
  $('#wto-upload-form').trigger('submit');
});

$('#wto-upload-form').on('submit', function(e){
  e.preventDefault();
  $(this).request('onSaveImages', { form: $(this)[0], files: true, success: function(data) {  
    if( $('[data-edit-img='+data['edit-id']+']')[0].tagName === 'IMG' ){
      $('[data-edit-img='+data['edit-id']+']').attr('src', data.src);
    } else {
      $('[data-edit-img='+data['edit-id']+']').attr('style', 'background-image: url('+data.src+');');
    }
    $('#wto-upload-form').trigger('reset');
    $('#wto-upload-form [name=slug]').val(location.pathname);
  }});
});

// edit texts

var editor = ContentTools.EditorApp.get();
editor.init('[data-edit-text]', 'data-edit-text');

editor.toolbox().tools([['bold', 'italic', 'link', 'undo', 'redo', 'line-break']]);

editor.addEventListener('start', function (ev) {
  $('[data-edit-img]').bind('click', editImages);
  $('body').on('click','a', function(event){
   event.stopPropagation();
  });
})

editor.addEventListener('stop', function (ev) {
  $('[data-edit-img]').unbind('click', editImages);
})

editor.addEventListener('saved', function (ev) {

  this.busy(true);
  regions = ev.detail().regions;
  form_data = {};

  for (name in regions) {
    if (regions.hasOwnProperty(name)) {
      if( name.indexOf('link_text_') === 0 && $('[data-edit-text='+name+']').find('a')[0] ){
        link_id = name.replace('link_text_', 'link_');
        link_url = $('[data-edit-text='+name+'] a').attr('href');
        form_data[link_id] = link_url;
        form_data[name] = $('[data-edit-text='+name+']').unwrap().text();
      } else {
        form_data[name] = regions[name];
      }
    }
  }

  form_data['wto_page_path'] = location.pathname;

  $.request('onSaveTexts', { data: form_data, success: function(data) {
    new ContentTools.FlashUI('ok');
    setTimeout(function () {
      editor.busy(false);
    }, 600);
  }});

});

})