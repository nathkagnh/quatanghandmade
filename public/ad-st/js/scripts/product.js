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
      var tmp_html = '<div class="col-xl-4 col-md-6">'+
                        '<div class="example-wrap">'+
                          '<h4 class="example-title">Ảnh chi tiết '+current_img+'</h4>'+
                          '<div class="example">'+
                            '<input type="file" id="other_images'+current_img+'" name="other_images[]"/>'+
                          '</div>'+
                        '</div>'+
                      '</div>';
      $(tmp_html).insertBefore('.col_btn_more_image');
      $('#other_images'+current_img).dropify();
    });

  });
});