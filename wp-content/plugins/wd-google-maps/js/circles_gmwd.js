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
var circle;
var circleHover;
var rightClick = false;
var infoWindow;
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
    jQuery("#circle_address").keypress(function(event){
        event = event || window.event;
        if(event.keyCode == 13){ 
            if(marker){
                marker.setMap(null);
            }
            rightClick = false;
            getLatLngFromAddress(jQuery(this).val(), "lat", "lng", gmwdSetMarkerCircle);
            
            return false;
        }   
    });  

    // lat, lng
    jQuery("#lat, #lng").change(function(){ 
        rightClick = false;        
        getAddressFromLatLng(Number(jQuery("#lat").val()), Number(jQuery("#lng").val()), "circle_address", gmwdSetCircle);   
    });
    
    // radius    
	jQuery("#radius").blur(function(){
		if(jQuery(this).val() && circle){
			circle.setOptions({radius: Number(jQuery("#radius").val())});
		}
	});	 

	// circle styles
	jQuery("#line_color").blur(function(){
		if(jQuery(this).val() && circle){
			circle.setOptions({strokeColor: "#" + jQuery(this).val()});
		}
	});
	jQuery("#fill_color").blur(function(){
		if(jQuery(this).val() && circle){
			circle.setOptions({fillColor: "#" + jQuery(this).val()});
		}
	});
	
	jQuery("#line_color_hover").blur(function(){
		if(jQuery(this).val() && circleHover){
			circleHover.setOptions({strokeColor: "#" + jQuery(this).val()});
		}
	});
	jQuery("#fill_color_hover").blur(function(){
		if(jQuery(this).val() && circleHover){
			circleHover.setOptions({fillColor: "#" + jQuery(this).val()});
		}
	});
	
	jQuery("#line_width").bind("slider:ready slider:changed", function (event, data) { 
		if (circle){
			circle.setOptions({strokeWeight: data.value.toFixed(1)});
		}
	});
	jQuery("#line_opacity").bind("slider:ready slider:changed", function (event, data) { 
		if (circle){
			circle.setOptions({strokeOpacity: data.value.toFixed(1)});
		}			
	});
	jQuery("#fill_opacity").bind("slider:ready slider:changed", function (event, data) { 
		if (circle){
			circle.setOptions({fillOpacity: data.value.toFixed(1)});
		}			
	});
	jQuery("#line_opacity_hover").bind("slider:ready slider:changed", function (event, data) { 
		if (circleHover){
			circleHover.setOptions({strokeOpacity: data.value.toFixed(1)});
		}			
	});
	jQuery("#fill_opacity_hover").bind("slider:ready slider:changed", function (event, data) { 
		if (circleHover){
			circleHover.setOptions({fillOpacity: data.value.toFixed(1)});
		}			
	});	
	
	// link url
	jQuery("#link").blur(function(){
		if(circle){
			google.maps.event.addListener(circleHover, 'click', function() {
				if(jQuery("#link").val() != '')
					window.open(jQuery("#link").val());
			});
		}
	});
	
	
	// show marker
	jQuery("[name=show_marker]").change(function(){
		if(marker){
			if(jQuery(this).val() != 1){			
				marker.setVisible(false);
                if(infoWindow){
                    infoWindow.open(null, null);
                }
			}
			else{
				marker.setVisible(true);
			}
		}
	
	});
    
	jQuery("[name=enable_info_window]").change(function(){
       if(jQuery('[name=enable_info_window]:checked').val() == 1){	
            gmwdSetCircleMarkerInfoWindow();
        }
        else{
            if(infoWindow){
                infoWindow.open(null, null);
            }   
        }
	});    
    

});

////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////
function gmwdSetMarkerCircle(){
	marker = new google.maps.Marker({
		map: map,
		position: {lat: Number(jQuery("#lat").val()), lng: Number(jQuery("#lng").val())}
	});
    if(markerDefaultIcon){	
        var image = {
            url : markerDefaultIcon,
            scaledSize: new google.maps.Size(32, 32),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(0, 32)
        };             
        marker.setIcon(image);
    }
    if(jQuery('[name=show_marker]:checked').val() != 1){			
        marker.setVisible(false);
    }
    else{
        marker.setVisible(true);
        if(jQuery('[name=enable_info_window]:checked').val() == 1){	
            gmwdSetCircleMarkerInfoWindow();
        }
    }
    gmwdSetCircle();
}

function gmwdSetCircleMarkerInfoWindow(){
    if(infoWindow){
        infoWindow.open(null, null);
    }    
    var contentString = jQuery("#circle_address").val();
    infoWindow = new google.maps.InfoWindow({
        content: contentString
    });
    google.maps.event.addListener(marker, 'click', function() {
        if(jQuery('[name=enable_info_window]:checked').val() == 1){
            infoWindow.open(map, marker);
        }        
    });
    
}   

function gmwdSetCircle(){
	if(marker){
		marker.setMap(null);
	}
    if(circle && circleHover){
        circle.setMap(null);
        circleHover.setMap(null);
    }  
	marker = new google.maps.Marker({
		map: map,
		position: {lat: Number(jQuery("#lat").val()), lng: Number(jQuery("#lng").val())}
	});
    if(markerDefaultIcon){	
        var image = {
            url : markerDefaultIcon,
            scaledSize: new google.maps.Size(32, 32),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(0, 32)
        };             
        marker.setIcon(image);
    }
	marker.setMap(map);
    if(jQuery('[name=enable_info_window]:checked').val() == 1){	
        gmwdSetCircleMarkerInfoWindow();
    }
    if(rightClick == false){
        map.setOptions({center: {lat: Number(jQuery("#lat").val()), lng: Number(jQuery("#lng").val())}});
    }
	gmwdAddCircle();
		
	var markerVisiblity = jQuery("[name=show_marker]:checked").val() == 1 ? true : false;
	marker.setVisible(markerVisiblity);
	gmwdCircleEvents();
}

function gmwdAddCircle(){
     if(circle && circleHover)  {
        circle.setMap(null);
        circleHover.setMap(null);
    }     
	// Construct the circle.
	 circle = new google.maps.Circle({
		strokeWeight:Number(jQuery("#line_width").val()),
		strokeColor: "#" + jQuery("#line_color").val(),
		strokeOpacity: Number(jQuery("#line_opacity").val()),
		fillColor: "#" + jQuery("#fill_color").val(),
		fillOpacity: Number(jQuery("#fill_opacity").val()),
		center: marker.position,
        radius: Number(jQuery("#radius").val()),
        draggable:true
	});
	
	circleHover = new google.maps.Circle({
		strokeWeight:Number(jQuery("#line_width").val()),
		strokeColor: "#" + jQuery("#line_color_hover").val(),
		strokeOpacity: Number(jQuery("#line_opacity_hover").val()),
		fillColor: "#" + jQuery("#fill_color_hover").val(),
		fillOpacity: Number(jQuery("#fill_opacity_hover").val()),
		center: marker.position,
        radius: Number(jQuery("#radius").val()),
        draggable:true
	});

	circle.setMap(map);
	gmwdCircleEvents();

}
function gmwdCircleMapEvents(){
	google.maps.event.addListener(map, 'rightclick', function(event) {
	if(marker){
		marker.setMap(null);
	}
	if(circle && circleHover){
		circle.setMap(null);
		circleHover.setMap(null);
	}
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({       
		latLng: new google.maps.LatLng(event.latLng.lat(), event.latLng.lng())     
	}, 
	function(responses) {     
	   if (responses && responses.length > 0) {        
		   jQuery("#circle_address").val(responses[0].formatted_address); 
			jQuery("#lat").val( event.latLng.lat());
			jQuery("#lng").val( event.latLng.lng());
			rightClick = true;
			gmwdSetCircle();               
	   }  
	});
  
});
}
function gmwdCircleEvents(){
		
   google.maps.event.addListener(circleHover, 'dragend', function (event) {			   
		document.getElementById("lat").value = this.getCenter().lat();
		document.getElementById("lng").value = this.getCenter().lng();
		var latlng = new google.maps.LatLng(this.getCenter().lat(), this.getCenter().lng());
		var geocoder = geocoder = new google.maps.Geocoder();
		geocoder.geocode({ 'latLng': latlng }, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[1]) {				
					circle.setMap(null);
					circleHover.setMap(null);								
					document.getElementById("circle_address").value = results[1].formatted_address;
					rightClick = true;
					marker.setMap(null);
					gmwdSetMarkerCircle();
				}
			}
		});	
   }); 
		   	
	google.maps.event.addListener(circle,"mouseover",function(){
		circle.setMap(null);
		circleHover.setOptions({strokeWeight: Number(jQuery("#line_width").val())});
		circleHover.setOptions({radius: Number(jQuery("#radius").val())});
		circleHover.setMap(map);
	}); 

	google.maps.event.addListener(circleHover,"mouseout",function(){
		//this.getMap().getDiv().removeAttribute('title');
		circleHover.setMap(null);				
		circle.setMap(map);
		
	}); 
	 google.maps.event.addListener(circleHover,'mouseover',function(){
		if(jQuery("#title").val()){
			this.getMap().getDiv().setAttribute('title',jQuery("#title").val());
		 }
	 });           
	google.maps.event.addListener(circleHover, 'click', function() {
		if(jQuery("#link").val() != '')
			window.open(jQuery("#link").val());
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