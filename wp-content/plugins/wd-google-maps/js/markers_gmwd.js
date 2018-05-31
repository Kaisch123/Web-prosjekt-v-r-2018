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
var infoWindow;
var linkUrl;
var rightClick = false;

////////////////////////////////////////////////////////////////////////////////////////
// Constructor & Destructor                                                           //
////////////////////////////////////////////////////////////////////////////////////////

jQuery( document ).ready(function() {

    if(jQuery(".pois_table").length>0){
        jQuery(".pois_table").tooltip();
    }
    jQuery("input[type=text], input[type=number]").keypress(function(event){
        event = event || window.event;
        if(event.keyCode == 13){           
            return false;
        }   
    });
    jQuery("#marker_address").keypress(function(event){
        rightClick = false;
        event = event || window.event;
        if(event.keyCode == 13){ 
            if(marker){
                marker.setMap(null);
            }
            getLatLngFromAddress(jQuery(this).val(), "lat", "lng", gmwdSetMarker);
            
            return false;
        }   
    });

	
	jQuery(".wd-marker-tabs-container .wd-marker-container:not(#marker-standart), .wd-color-tabs-container .wd-color-container:not(#1) ").hide();
	
	jQuery(".wd-marker-tabs li a").click(function(){
		jQuery(".wd-marker-tabs-container .wd-marker-container").hide();
		jQuery(".wd-marker-tabs li a").removeClass("wd-marker-active-tab");
		jQuery(jQuery(this).attr("href")).show();
		jQuery(this).addClass("wd-marker-active-tab");
		return false;
	});
    
    jQuery("[name=choose_marker_icon]").change(function(){
        jQuery("#custom_marker_url").val("");
        jQuery(".custom_marker_url_view").html("");
        gmwdSetMarkerIcon(); 
        if(jQuery(this).val() == 0){
            jQuery(".from_media_uploader").show();
            jQuery(".from_icons").hide();
        }
        else{
            jQuery(".from_media_uploader").hide();
            jQuery(".from_icons").show();       
        }
    });
    jQuery("#marker_size").change(function(){
       
        if(jQuery("#custom_marker_url").val() && jQuery("#choose_marker_icon1").is(":checked") == true){
            var data = {
                'action': 'marker_size',
                'ajax': 1,
                'task': 'change_marker_size',
                'page': "markers_gmwd",
                "image_url": jQuery("#custom_marker_url").val(),
                "size": jQuery(this).val(),
            };
                            
            jQuery.post(ajax_url, data, function(response) {     
                jQuery("#custom_marker_url").val(response);
                 marker.setIcon(response);
            });
        }
        else if(jQuery("#custom_marker_url").val() || markerDefaultIcon){
            gmwdSetMarkerIcon(); 
        }

    });
    
    // lat, lng
    jQuery("#lat, #lng").change(function(){
        rightClick = false;
        getAddressFromLatLng(Number(jQuery("#lat").val()), Number(jQuery("#lng").val()), "marker_address", gmwdSetMarker);   
    });

	
	//link url
	jQuery("#link_url").blur(function(){
		linkUrl = jQuery(this).val();
		gmwdMarkerEvents();
	});	
	
	jQuery("#title").blur(function(){
		if(marker){
			marker.setTitle(jQuery(this).val());
			gmwdCreateInfoWindow();
		}
	});	

	jQuery("#description").blur(function(){
		if(marker){
			gmwdCreateInfoWindow();
		}
	});	
 	
	// info window
	jQuery("[name=info_window_open], [name=enable_info_window]").change(function(){    
        gmwdSetInfoWindow();      
	});


	// animations
	jQuery("#animation").change(function(){
		gmwdSetMarkerAnimation(jQuery(this).val());
	});
    
    // custom icons
    jQuery("#custom_marker_url").change(function(){
        if(jQuery(this).val() != ""){
			marker.setIcon(jQuery(this).val());
		} 
	});

   
    // editor

   jQuery(".open_editor").click(function(){
        jQuery(".gmwd_editor_wrapper").show();
        jQuery(".gmwd_opacity_div").show();
        tinymce.get("mdescription").setContent(jQuery("#description").val());
    
   }); 
   jQuery(".cancel_editor").click(function(){
        jQuery(".gmwd_editor_wrapper").hide();
        jQuery(".gmwd_opacity_div").hide();
    
   });
   
   jQuery(".insert_editor_text").click(function(){
        jQuery("#description").val(tinymce.get('mdescription').getContent()); 
        jQuery(".gmwd_editor_wrapper").hide();
        jQuery(".gmwd_opacity_div").hide();
   });

	jQuery(".categories").change(function(){
		var cats = [];
		jQuery(".categories").each(function(){
			if(jQuery(this).is(":checked") == true){
				cats.push(jQuery(this).val());
			}
		});
		jQuery("[name=category]").val(cats.join(","));
	});


});

////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////
function gmwdSetMarker(){	
    if(marker){
        marker.setMap(null);
    }
	marker = new google.maps.Marker({
		map: map,
        draggable: true,
		position: {lat: Number(jQuery("#lat").val()), lng: Number(jQuery("#lng").val())}
	});

	marker.setTitle(jQuery("#title").val());
    
    if(rightClick === false){
        map.setOptions({center: {lat: Number(jQuery("#lat").val()), lng: Number(jQuery("#lng").val())}});
    }
    gmwdCreateInfoWindow();
	if(jQuery("[name=info_window_open]:checked").val() == 1 && jQuery("[name=enable_info_window]:checked").val() == 1){
        if(jQuery("[name=infowindow_type]:checked").val() == 0){
            infoWindow.open(map, marker);
        }
        else{
            gmwdAdvancedInfoWindow(jQuery("#title").val(), jQuery("#marker_address").val(), jQuery("#pic_url").val(), jQuery("#description").val());
        }
	}
	gmwdSetMarkerAnimation(jQuery("#animation :selected").val());
	linkUrl = jQuery("#link_url").val();
    gmwdSetMarkerIcon();

	gmwdMarkerEvents();
}

function gmwdCreateInfoWindow(){
    if(jQuery("[name=infowindow_type]:checked").val() == 0){
        var infoWindowInfo = jQuery("[name=info_window_info]").val();
        var contentString = "";
        if(jQuery("#pic_url").val() && infoWindowInfo.indexOf("pic") != -1){
            contentString += '<img src="' + jQuery("#pic_url").val() + '"  style="float:right; margin-left: 10px; max-width:100px">';
        }
        if(infoWindowInfo.indexOf("title") != -1){
            contentString += jQuery("#title").val();
        }
        if(infoWindowInfo.indexOf("address") != -1){
            if(infoWindowInfo.indexOf("title") != -1){
                contentString += "<br>";
            }
            contentString += jQuery("#marker_address").val();
        } 
        if(infoWindowInfo.indexOf("desc") != -1){
            contentString += "<div style='max-width:300px;'>" + jQuery("#description").val() + "</div>";
        }         
        //contentString += (jQuery("#title").val() + "<br>" + jQuery("#marker_address").val()) + "<div style='max-width:300px;'>" + jQuery("#description").val() + "</div>";
        if(infoWindow){
            infoWindow.setOptions({content: contentString});
        }
        else{
            infoWindow = new google.maps.InfoWindow({
                content: contentString,
				disableAutoPan: false
            });
        }
    }
    else{
        if(jQuery("[name=enable_info_window]:checked").val() == 1 && jQuery("[name=info_window_open]:checked").val() == 1){ 
            gmwdAdvancedInfoWindow(jQuery("#title").val(), jQuery("#marker_address").val(), jQuery("#pic_url").val(), jQuery("#description").val());
        }
        else{
            jQuery(".gmwd_advanced_info_window").remove();
        }
    }
}

function gmwdSetInfoWindow(){
    gmwdCreateInfoWindow();	
    if(jQuery("[name=infowindow_type]:checked").val() == 0){
        if(jQuery("[name=enable_info_window]:checked").val() == 1){       
            if(marker){
                if(jQuery("[name=info_window_open]:checked").val() == 1 ){			
                    infoWindow.open(map, marker);
                    gmwdMarkerEvents();				
                }
                else{
                    infoWindow.close();	
                }
            }
        }
       else{
            infoWindow.open(null, null); 			
        }
    }
}


function gmwdSetMarkerAnimation(value){
  if(marker){
	  if(value == "BOUNCE"){
		marker.setAnimation(google.maps.Animation.BOUNCE)
	  }
	  else if(value == "DROP"){
		marker.setAnimation(google.maps.Animation.DROP)
	  } 
	  else{
		marker.setAnimation(null);
	  }
  }
}

function gmwdMarkerEvents(){

        if(marker){
            google.maps.event.addListener(marker, 'click', function() {
                if(linkUrl){
                    window.open(linkUrl);
                }
                if(jQuery("[name=enable_info_window]:checked").val() == 1 && jQuery("#info_window_open_on :selected").val() == "click"){
                    if(infoWindow && jQuery("[name=infowindow_type]:checked").val() == 0){
                        infoWindow.open(map, marker);	
                    }
                    else{
                        gmwdAdvancedInfoWindow(jQuery("#title").val(), jQuery("#marker_address").val(), jQuery("#pic_url").val(), jQuery("#description").val());
                    }
                }                               
            });
            google.maps.event.addListener(marker, 'mouseover', function() {
                if(jQuery("[name=enable_info_window]:checked").val() == 1 && jQuery("#info_window_open_on :selected").val() == "hover"){
                    if(infoWindow && jQuery("[name=infowindow_type]:checked").val() == 0){
                        infoWindow.open(map, marker);	
                    }
                    else{
                        gmwdAdvancedInfoWindow(jQuery("#title").val(), jQuery("#marker_address").val(), jQuery("#pic_url").val(), jQuery("#description").val());
                    }
                }                               
            });            
            google.maps.event.addListener(marker, 'dragend', function (event) {
                document.getElementById("lat").value = this.getPosition().lat();
                document.getElementById("lng").value = this.getPosition().lng();
                var latlng = new google.maps.LatLng(this.getPosition().lat(), this.getPosition().lng());
                var geocoder = geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById("marker_address").value = results[1].formatted_address;
                        }
                    }
                });
            });
        }
   

}

function gmwdMarkerMapEvents(){
    google.maps.event.addListener(map, 'rightclick', function(event) {
        if(marker){ 
            marker.setVisible(false);             
            marker.setMap(null);       
        }
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({       
            latLng: new google.maps.LatLng(event.latLng.lat(), event.latLng.lng())     
        }, 
        function(responses) {     
           if (responses && responses.length > 0) {        
               jQuery("#marker_address").val(responses[0].formatted_address); 
                jQuery("#lat").val( event.latLng.lat());
                jQuery("#lng").val( event.latLng.lng());
                rightClick = true;
                gmwdSetMarker();               
           }  
        });
      
    });
}

function gmwdMarkeroptions(){
	gmwdMarkerEvents();
	marker.setTitle(jQuery("#title").val());
	gmwdSetMarkerAnimation(jQuery("#animation :selected").val());
	gmwdSetInfoWindow();
}

function gmwdSetMarkerIcon(){
    if(marker){
        var size = Number(jQuery("#marker_size").val()); 
        
        if(jQuery("#custom_marker_url").val()){            
            var image = {
                url : jQuery("#custom_marker_url").val(),
                scaledSize: new google.maps.Size(size, size)
            };           
            marker.setIcon(image);
        } 
        else if(markerDefaultIcon){ 
            var image = {
                url : markerDefaultIcon,
                scaledSize: new google.maps.Size(size, size)
            };         
            marker.setIcon(image);
        }
        else{
            marker.setIcon(null);
        }
    }
}
var callback = gmwdSetMarkerIcon;
function gmwdChooseIcon(obj){
	var iconType = jQuery(obj).attr("data-icon-type");
	var iconNumber = jQuery(obj).attr("data-icon-number");
    if(iconType == "custom_added"){
        var customIconUrl = jQuery(obj).find("img").attr("src");
    }
    else{
        var customIconUrl = jQuery("#custom_marker_url").val();
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

    jQuery("#custom_marker_url").val(iconUrl);

    jQuery('.custom_marker_url_view').html('<img src="' + iconUrl + '" height="25" id="custom_marker_img"><span class="custom_marker_url_delete" onclick="jQuery(\'#custom_marker_url\').val(\'\');jQuery(\'.custom_marker_url_view\').html(\'\');gmwdSetMarkerIcon();">x</span>');
 
    var data = {
        'action': 'marker_size',
        'ajax': 1,
        'task': 'change_marker_size',
        'page': "markers_gmwd",
        "image_url": iconUrl,
        "size": jQuery("#marker_size :selected").val(),
    };
                    
    jQuery.post(ajax_url, data, function(response) {     
        jQuery("#custom_marker_url").val(response);
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