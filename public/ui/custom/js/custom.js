/**
 * Admin
 */

'use strict';

$(function () {
  const select2 = $('.select2'),
    selectPicker = $('.selectpicker');

  // Bootstrap select
  if (selectPicker.length) {
    selectPicker.selectpicker();
  }

  // select2
  // select2
  // if (select2.length) {
  //   select2.each(function () {
  //     var $this = $(this);
  //     $this.wrap('<div class="position-relative"></div>');
  //     if($this.closest(".modal").length){
  //       $this.select2({
  //         placeholder: '選択してください',
  //         dropdownParent: $this.parent()
  //       });
  //     } else {
  //       $this.select2({
  //         placeholder: '選択してください',
  //       });
  //     }
  //   });
  // }

  $(".checkbox_all").each(function(){
    $(this).on("click", function() {
      // $(".checkbox_all").closest("table").find(".sub_checkbox").prop("checked", $(".checkbox_all").prop("checked"));
      $(this).closest("table").find(".sub_checkbox").prop("checked", $(this).prop("checked"));
    });
  });
});

(function () {
  const page_width = $(window).width();

  $(".p-toolbox, .sub-toolbox-close").on("click", function(e){
    const target_object = $(this).closest(".p-toolbox-group").find(".sub-toolbox");
    target_object.toggle();
  });
  
  // =========== multi selectbox script =============
  $(".multi-select-all-select").on("click", function(e) {
    $(this).closest(".btn-group").find(".checkbox-group .form-check-input").prop("checked", true);
  });
  $(".multi-select-all-deselect").on("click", function(e) {
    $(this).closest(".btn-group").find(".checkbox-group .form-check-input").prop("checked", false);
  });

  $(".multi-select-search-name").on("keyup", function(e){
    $(this).closest(".multi-select-list").find(".checkbox-group .form-check").show();

    var value = $(this).val().toLowerCase();
    if(value == "") {
      return;
    }

    $(this).closest(".btn-group").find(".checkbox-group .form-check-label").each(function(index) {
      if($(this).text().trim().toLowerCase().indexOf(value) == -1){
        $(this).parent().hide();
      }
    });
  });

  // ============= Date Picker =============
  $('.flatpickr-input').each(function(index){
    $(this).flatpickr({
      monthSelectorType: 'static',
      "locale": "ja"  // このインスタンスのみの言語指定
    });
  });
  $('.flatpickr-input-time').each(function(index){
    $(this).flatpickr({
      enableTime: true,
      dateFormat: 'Y-m-d H:i',
      "locale": "ja"  // このインスタンスのみの言語指定
    });
  });

  // =========== perfecct-scrollbar ===========
  var scroll_bar_array = [];
  $(".perfect-scrollbar").each(function(index){
    scroll_bar_array[index] = new PerfectScrollbar(this, {
      wheelPropagation: false,
      // suppressScrollY: true
    });
  });

  function refreshScrollBars(){
    scroll_bar_array.forEach(function(scroll_bar){
      scroll_bar.update();
    });
  }

  $(".print_content").html($(".print_body").html());
})();


var submitForm = function(form_id, call_back, preProcess = null, additonal_data = {}){
  
  
  $("#" + form_id).submit(function(event) {
    // HTMLでの送信をキャンセル
    event.preventDefault();

    let data = $(this).serialize();

    for (const [key, value] of Object.entries(additonal_data)) {
      data += "&" + key + "=" + value;
    }

    if(preProcess != null){
      preProcess();
    }

    // 送信
    $.ajax({
      url: $(this).attr('action'),
      method: $(this).attr('method'),
      data: data,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      }
    }).done(function(data) {
      call_back(data);
    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
      alert("エラー:" + XMLHttpRequest.responseText);
    });
  });
};

var getData = function(url, data, call_back){
  $.ajax({
    url: url,
    method: "GET",
    data: data,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
  }).done(function(data) {
    call_back(data);
  }).fail(function(XMLHttpRequest, textStatus, errorThrown){
    alert("エラー:" + XMLHttpRequest.responseText);
  });
};

var getNavigator = function(data, refreshFunctionName){
  var html = "";
  html += '<nav aria-label="Page navigation">';
  html += '<ul class="pagination justify-content-center pagination-info">';
  if (data.current_page == 1) {
    html += '<li class="page-item first disabled">';
    html += '  <botton class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-left ti-xs"></i></botton>';
    html += '</li>';
    html += '<li class="page-item prev disabled">';
    html += '  <botton class="page-link waves-effect disable" href="javascript:void(0);"><i class="ti ti-chevron-left ti-xs"></i></botton>';
    html += '</li>';
  } else {
    html += '<li class="page-item first">';
    html += '  <botton onclick="' + refreshFunctionName + '(1)" class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-left ti-xs"></i></botton>';
    html += '</li>';
    html += '<li class="page-item prev">';
    html += '  <botton onclick="' + refreshFunctionName + '(' + (data.current_page - 1) + ')" class="page-link waves-effect disable" href="javascript:void(0);"><i class="ti ti-chevron-left ti-xs"></i></botton>';
    html += '</li>';
  }

  for (var i = data.current_page - 3; i < data.current_page + 3; i++) {
    if (i == data.current_page) {
      html += '<li class="page-item active">';
      html += '<botton onclick="' + refreshFunctionName + '(' + i + ')" class="page-link waves-effect" href="javascript:void(0);">' + i + '</botton>';
      html += '</li>';
    } else if (i > 0 && i <= data.last_page) {
      html += '<li class="page-item">';
      html += '<botton onclick="' + refreshFunctionName + '(' + i + ')" class="page-link waves-effect" href="javascript:void(0);">' + i + '</botton>';
      html += '</li>';
    }
  }

  if (data.current_page == data.last_page) {
    html += '<li class="page-item next disabled">';
    html += '  <botton class="page-link waves-effect disable" href="javascript:void(0);"><i class="ti ti-chevron-right ti-xs"></i></botton>';
    html += '</li>';
    html += '<li class="page-item last disabled">';
    html += '  <botton class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-right ti-xs"></i></botton>';
    html += '</li>';
  } else {
    html += '<li class="page-item next">';
    html += '  <botton onclick="' + refreshFunctionName + '(' + (data.current_page + 1) + ')" class="page-link waves-effect disable" href="javascript:void(0);"><i class="ti ti-chevron-right ti-xs"></i></botton>';
    html += '</li>';
    html += '<li class="page-item last">';
    html += '  <botton onclick="' + refreshFunctionName + '(' + data.last_page + ')" class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-right ti-xs"></i></botton>';
    html += '</li>';
  }

  return html;
}