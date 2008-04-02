var alloc_http_request = new Array();

function makeAjaxRequest(url,entityid) {
  $("#"+entityid).html('<img src="../images/ticker2.gif" alt="Updating field..." title="Updating field...">');
  jQuery.get(url,'',function(data) {
    $("#"+entityid).hide();
    $("#"+entityid).html(data);
    $("#"+entityid).fadeIn("fast");
  })
}

function set_grow_shrink_box(id, images, text, id_to_hide) {
  if ($("#"+id).is(':visible')) {
    image = images+'small_grow.gif';
  } else {
    image = images+'small_shrink.gif';
  }
  // hide or show the actual div
  $("#"+id).slideToggle("fast");

  // toggle the other div - if any
  if (id_to_hide) {
    $("#"+id_to_hide).slideToggle("fast");
  }
  str = "<nobr><a class=\"growshrink nobr\" href=\"#\" onClick=\"return set_grow_shrink_box('"+id+"','"+images+"','"+text+"','"+id_to_hide+"');\">"+text+"<img border=\"0\" src=\""+image+"\"></a></nobr>"
  $('#button_'+id).html(str);
  return false;
}

// This is a generic show/hide for anything
function set_grow_shrink(id, id_to_hide, use_classes_instead_of_ids) {
  // toggle the other div - if any
  if (use_classes_instead_of_ids && id_to_hide) {
    $("."+id_to_hide).slideToggle("fast");
  } else if (id_to_hide) {
    $("#"+id_to_hide).slideToggle("fast");
  }
  // hide or show the actual div
  if (use_classes_instead_of_ids) {
    $("."+id).slideToggle("fast");
  } else {
    $("#"+id).slideToggle("fast");
  }
  return false;
}

function sidebyside_activate(id,arr) {
  if (id == "sbsAll") {
    for (var i=0; i<arr.length; i++) {
      if (arr[i] != "sbsAll") {
        $("#"+arr[i]).show();
        $('#sbs_link_'+arr[i]).removeClass("sidebyside_active").addClass("sidebyside");
      }
    }
    $('#sbs_link_' + id).addClass("sidebyside_active");

  } else {
    for (var i=0; i<arr.length; i++) {
      if (arr[i] != "sbsAll") {
        $("#"+arr[i]).hide();
      }
      $('#sbs_link_' + arr[i]).removeClass("sidebyside_active").addClass("sidebyside");
    }
    $('#sbs_link_' + id).addClass("sidebyside_active");
    $("#"+id).show();
  }
}


// These global variables are for the setTimeout() below
var alloc_current_resizable_textarea = "";
var alloc_current_resizable_textarea_default_height = "";
var alloc_current_resizable_textarea_timer = "";

// this function dynamically resizes a text area as data is inputted
function adjust_textarea(textarea, default_height) {
  // a div is setup off screen, we use that div to determine the height of the textarea
  alloc_current_resizable_textarea = textarea;
  alloc_current_resizable_textarea_default_height = default_height;
  var shadow = document.getElementById("shadow_" + textarea.id);
  shadow.style.width=parseInt(textarea.clientWidth-8)+'px';
  shadow.innerHTML = textarea.value.replace(/[\n]/g,'<br />&nbsp;');
  var shadow_height = shadow.clientHeight;
  if(shadow_height < default_height) {
    var n = default_height;
  } else {
    var n = shadow_height+14;
  }
  textarea.style.height = n+'px';
  alloc_current_resizable_textarea_timer = setTimeout('adjust_textarea(alloc_current_resizable_textarea,alloc_current_resizable_textarea_default_height)', 1000);
}

function stop_textarea_timer() {
  clearTimeout(alloc_current_resizable_textarea_timer);
}
