////////////////////////////////////////////////////////////////////////////////////////
// Events                                                                             //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Constants                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Variables                                                                          //
////////////////////////////////////////////////////////////////////////////////////////

var marker;

////////////////////////////////////////////////////////////////////////////////////////
// Constructor & Destructor                                                           //
////////////////////////////////////////////////////////////////////////////////////////

jQuery( document ).ready(function() {

	jQuery(".wd-marker-tabs-container .wd-marker-container:not(#marker-standart), .wd-color-tabs-container .wd-color-container:not(#1) ").hide();
	
	jQuery(".wd-marker-tabs li a").click(function(){
		jQuery(".wd-marker-tabs-container .wd-marker-container").hide();
		jQuery(".wd-marker-tabs li a").removeClass("wd-marker-active-tab");
		jQuery(jQuery(this).attr("href")).show();
		jQuery(this).addClass("wd-marker-active-tab");
		return false;
	});

});

////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////


function gmwdChooseIcon(obj){
	var iconType = jQuery(obj).attr("data-icon-type");
	var iconNumber = jQuery(obj).attr("data-icon-number");
    if(iconType == "custom_added"){
        var customIconUrl = jQuery(obj).find("img").attr("src");
    }
    else{
        var customIconUrl = jQuery("#" + inputHidden).val();
    }
	window.parent.chooseIcon(iconType, iconNumber, customIconUrl);
}
function chooseIcon(iconType, iconNumber, customIconUrl){
	tb_remove();
    if(iconType != "custom" && iconType != "custom_added"){
        var icon = iconType + "_" +  iconNumber + ".png" ; 
        var iconUrl = GMWD_UPLOAD_URL + '/markers/' + iconType + '/' + icon;      
    }
    else{
         var iconUrl = customIconUrl;
    }
    jQuery("#" + inputHidden).val(iconUrl);
    
    jQuery('.' + inputHidden + '_view').html('<img src="' + iconUrl + '" height="25" id="custom_marker_img"><span class="' + inputHidden +'_delete" onclick="jQuery(\'#' + inputHidden +'\').val(\'\');jQuery(\'.' + inputHidden + '_view\').html(\'\');gmwdSetMarkerIcon();">x</span>');
    
    var data = {
        'action': 'marker_size',
        'ajax': 1,
        'task': 'change_marker_size',
        'page': "markers_gmwd",
        "image_url": jQuery("#" + inputHidden).val(),
        "size": jQuery("#marker_size :selected").val(),
    };
                    
    jQuery.post(ajax_url, data, function(response) {     
        jQuery("#" + inputHidden).val(response);
        gmwdSetMarkerIcon();
    });	
    
}


function iconChange(obj){
    jQuery("canvas").remove();
    jQuery(".icon-block").removeClass("active_icon");
    var iconId = jQuery(obj).attr("id");
    jQuery("#current_marker").val(iconId);
    var iconBackground = "#" + jQuery("#icon_background_color").val()
    var iconUrl = changeBackground(iconId, iconBackground);
    
    var backgroundId = 'result_img';
    var background = "#" + jQuery("#background_color").val();   
    var backgroundUrl = changeBackground(backgroundId, background);
    jQuery(obj).parent().addClass("active_icon");
    createCustomMarker(backgroundUrl, iconUrl);
}

function backgroundChange(){
    jQuery("canvas").remove();
    var iconId = jQuery("#current_marker").val();
    var iconUrl = "";
    if(iconId){
        var iconBackground = "#" + jQuery("#icon_background_color").val()
         iconUrl = changeBackground(iconId, iconBackground);
    }
    var backgroundId = 'result_img';
    var background = "#" + jQuery("#background_color").val();   
    var backgroundUrl = changeBackground(backgroundId, background);
    
    createCustomMarker(backgroundUrl, iconUrl);
}

function changeBackground(imageId, tintColor){
    var imgElement = document.getElementById(imageId);
    //create canvas
    var canvas = document.createElement("canvas");  
    canvas.width = 256;
    canvas.height = 256;
    
    // get context
    var ctx = canvas.getContext("2d");
    // draw image
    ctx.drawImage(imgElement,0,0);
    
    // change background color
    ctx.globalCompositeOperation = "source-in";

    ctx.globalAlpha = 1;
    ctx.fillStyle = tintColor;
    ctx.fillRect(0,0,canvas.width,canvas.height);
    
    var url = canvas.toDataURL();
    
    return url;
}

function createCustomMarker(backgroundUrl, iconUrl){

    var background = new Image();
    background.src = backgroundUrl;
    
    if(iconUrl){
        var icon = new Image();
        icon.src = iconUrl;
    }
    
    var canvas = document.createElement('canvas');
    canvas.width = 256;
    canvas.height = 256;
    canvas.id = "custom_marker_img";
    
    var ctx = canvas.getContext("2d");

    var sources = {};
    sources.background = backgroundUrl;
    if(iconUrl){
        sources.icon = iconUrl;
    }


    loadImages(sources, function(images) {
        ctx.drawImage(images.background, 0,0);
        if(images.icon){
            ctx.drawImage(images.icon, 0, 0);
        }
		document.getElementById('icon_result').appendChild(canvas);
		var imgdata = canvas.toDataURL('image/png');	
		jQuery("#custom_marker_url").val(imgdata);   
    });
    
    
	jQuery('#result_img').hide();

 
}

function loadImages(sources, callback) {
    var images = {};
    var loadedImages = 0;
    var numImages = 0;
    // get num of sources
    for(var src in sources) {
      numImages++;
    }
    for(var src in sources) {
      images[src] = new Image();
      images[src].onload = function() {
        if(++loadedImages >= numImages) {
          callback(images);
        }
      };
      images[src].src = sources[src];
    }
}

function downloadMarkers(){
    var data = {};
    data.action = "download_markers";
    data.page = "maps_gmwd";
    data.task = "download_markers";
    
	jQuery.ajax({
        type: "POST",
        url: ajax_url,
		data : data,
        beforeSend: function(){
            jQuery(".gmwd_opacity_div").show();
        },
		success: function(response){
			jQuery(".gmwd_opacity_div").hide();
            document.location.reload();
		},
		failure: function (errorMsg) {
            alert(errorMsg);
        }, 
		error: function (errorMsg) {
            alert(errorMsg);
        }
	});

}   
     

////////////////////////////////////////////////////////////////////////////////////////
// Getters & Setters                                                                  //
////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////
// Private Methods                                                                    //
////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////
// Listeners                                                                          //
////////////////////////////////////////////////////////////////////////////////////////