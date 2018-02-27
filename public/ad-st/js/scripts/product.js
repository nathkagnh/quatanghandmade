(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/scripts/product', ['jquery', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('jquery'), require('Site'));
  } else {
    factory(global.jQuery, global.Site);
  }
})(this, function (_jquery, _Site) {
  'use strict';

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  (0, _jquery2.default)(document).ready(function ($$$1) {
    (0, _Site.run)();

    $('#image').dropify();
    $('#other_images1').dropify();
    $('#other_images2').dropify();
    $('#other_images3').dropify();
    $('#btn_more_image').click(function(){
      var current_img = $('input[name="other_images[]"]').length + 1;
      var key = (new Date).getTime();
      var tmp_html = '<div class="col-xl-4 col-md-6">'+'<div class="example-wrap">'+'<h4 class="example-title title_other_image">Ảnh chi tiết '+current_img+'</h4>'+'<div class="example">'+'<input type="file" id="other_images'+key+'" name="other_images[]"/>'+'</div>'+'</div>'+'</div>';
      $(tmp_html).insertBefore('.col_btn_more_image');
      var input_img = $('#other_images'+key);
      var dr = input_img.dropify();
      dr.on('dropify.afterClear', function(event, element){
        dr.data('dropify').destroy();
        input_img.parent().parent().parent().remove();
        $('.title_other_image').each(function(k, v){
          $(this).text('Ảnh chi tiết '+(k+1));
        });
      });
    });
    for(var i=1; i<=3; i++){
      $('#btn_more_image').trigger('click');
    }
  });
});