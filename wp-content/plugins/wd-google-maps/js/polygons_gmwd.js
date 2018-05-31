////////////////////////////////////////////////////////////////////////////////////////
// Events                                                                             //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Constants                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Variables                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
var markers = [];
var infoWindows = [];
var polygon;
var polygonHover;
var polygonCoord = [];
var rightclick = false;
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
	//styles
	jQuery("#line_color").blur(function(){
		if(jQuery(this).val() && polygon){
			polygon.setOptions({strokeColor: "#" + jQuery(this).val()});
		}
	});
	jQuery("#fill_color").blur(function(){
		if(jQuery(this).val() && polygon){
			polygon.setOptions({fillColor: "#" + jQuery(this).val()});
		}
	});
	
	jQuery("#line_color_hover").blur(function(){
		if(jQuery(this).val() && polygonHover){
			polygonHover.setOptions({strokeColor: "#" + jQuery(this).val()});
		}
	});
	jQuery("#fill_color_hover").blur(function(){
		if(jQuery(this).val() && polygonHover){
			polygonHover.setOptions({fillColor: "#" + jQuery(this).val()});
		}
	});
	
	jQuery("#line_width").bind("slider:ready slider:changed", function (event, data) { 
		if (polygon){
			polygon.setOptions({strokeWeight: data.value.toFixed(1)});
			polygonHover.setOptions({strokeWeight: data.value.toFixed(1)});
		}			
	});
	jQuery("#line_opacity").bind("slider:ready slider:changed", function (event, data) { 
		if (polygon){
			polygon.setOptions({strokeOpacity: data.value.toFixed(1)});
		}			
	});
	jQuery("#fill_opacity").bind("slider:ready slider:changed", function (event, data) { 
		if (polygon){
			polygon.setOptions({fillOpacity: data.value.toFixed(1)});
		}			
	});
	jQuery("#line_opacity_hover").bind("slider:ready slider:changed", function (event, data) { 
		if (polygonHover){
			polygonHover.setOptions({strokeOpacity: data.value.toFixed(1)});
		}			
	});
	jQuery("#fill_opacity_hover").bind("slider:ready slider:changed", function (event, data) { 
		if (polygonHover){
			polygonHover.setOptions({fillOpacity: data.value.toFixed(1)});
		}			
	});	
	
	
	// link url
	jQuery("#link").blur(function(){
		if(polygon){
			google.maps.event.addListener(polygonHover, 'click', function() {
				if(jQuery("#link").val() != '')
					window.open(jQuery("#link").val());
			});
		}
	});

	// show markers
	jQuery("[name=show_markers]").change(function(){
		var markervisibilaty = jQuery(this).val() == 1 ? true : false;
		for(var i = 0; i<markers.length; i++){
			markers[i].setVisible(markervisibilaty);
		}
	});

     // data
    jQuery("#data").blur(function(){
       
        polygonCoord = [];
        gmwdRemoveMarkersFromMap();
        markers = [];
        infoWindows = [];
        if(polygon && polygonHover){
            polygon.setMap(null);
            polygonHover.setMap(null);
        }

        if(jQuery(this).val().length != 0){
           gmwdSetPolygon();
        }
    });     

	jQuery("[name=enable_info_windows]").change(function(){
       if(jQuery('[name=enable_info_windows]:checked').val() == 1){
            infoWindows = [];
            for(var i = 0; i<markers.length; i++){
                marker = markers[i];            
                gmwdSetPolygonMarkersInfoWindow(marker);
            }
        }
        else{
            for(var i = 0; i<infoWindows.length; i++){
                infoWindow = infoWindows[i];            
                infoWindow.open(null, null);
            } 
        }
	}); 

});

////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////

function gmwdSetPolygon(){
	var polygonMarkers = jQuery("#data").val();
	jQuery("#data").val("");
	polygonMarkers = polygonMarkers.substr(1, polygonMarkers.length-4).split("),(");
	var polygonMarker;
   
	for(var i = 0; i<polygonMarkers.length; i++){

        if(polygonMarkers[i] == "0, Na"){
            return false;
        }
		polygonMarker = polygonMarkers[i].split(",");
		gmwdAddNewMarker(new google.maps.LatLng( Number(polygonMarker[0]), Number(polygonMarker[1])));		
		polygonCoord.push(new google.maps.LatLng( Number(polygonMarker[0]), Number(polygonMarker[1])));
	}
	gmwdAddMarkersToMap();
	gmwdDrawPolygon(polygonCoord);
	
	// set map center polygon center
	google.maps.Polygon.prototype.gmwdgetBounds=function(){
		var bounds = new google.maps.LatLngBounds()
		this.getPath().forEach(function(element,index){bounds.extend(element)})
		return bounds;
	}
	if(rightclick == false){
		map.setOptions({center: polygon.gmwdgetBounds().getCenter()});
	}	
	gmwdPolygonEvents();
}

function gmwdAddNewMarker(location){

    var marker = new google.maps.Marker({
        position: location,
		draggable:true
		
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
	var markervisibilaty = jQuery("[name=show_markers]:checked").val() == 1 ? true : false;
	marker.setVisible(markervisibilaty);
    
    if(markervisibilaty == true && jQuery('[name=enable_info_windows]:checked').val() == 1){	
        gmwdSetPolygonMarkersInfoWindow(marker);
    }
	
	markers.push(marker);
	jQuery("#data").val(jQuery("#data").val() + location + ",");
}
function gmwdSetPolygonMarkersInfoWindow(marker){

    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({       
        latLng: marker.getPosition()     
    }, 
    function(responses) {  
       if (responses && responses.length > 0) {
            infoWindow = new google.maps.InfoWindow({
                content: responses[0].formatted_address 
            });  
            infoWindows.push(infoWindow); 
            (function(marker, infoWindow, map) { 
                 google.maps.event.addListener(marker, 'click', function() {
                    if(jQuery('[name=enable_info_windows]:checked').val() == 1){
                        infoWindow.open(map, marker);
                    }        
                });             
            }(marker, infoWindow, map));	          
       }  
    });        
}  
function gmwdAddMarkersToMap(){
	for(var i = 0; i<markers.length; i++){		
		markers[i].setMap(map);
	}
}

function gmwdRemoveMarkersFromMap(){
	for(var i = 0; i<markers.length; i++){		
		markers[i].setMap(null);
	}
}

function gmwdAddPolygon(){
    polygonCoord = [];
	for(var i=0; i<markers.length; i++){
		var gMarker = markers[i];
		var gMarkerPosition = gMarker.getPosition();
		var coord = {};
		coord.lat = gMarkerPosition.lat();
		coord.lng = gMarkerPosition.lng();
		polygonCoord.push(coord);
	}
	gmwdDrawPolygon(polygonCoord);
}

function gmwdDrawPolygon(polygonCoord){
	// Construct the polygon.
	 polygon = new google.maps.Polygon({
		paths: polygonCoord,
		strokeWeight:Number(jQuery("#line_width").val()),
		strokeColor: "#" + jQuery("#line_color").val(),
		strokeOpacity: Number(jQuery("#line_opacity").val()),
		fillColor: "#" + jQuery("#fill_color").val(),
		fillOpacity: Number(jQuery("#fill_opacity").val()),
		draggable:true,
        geodesic: true
	});
	
	polygonHover = new google.maps.Polygon({
		paths: polygonCoord,
		strokeWeight:Number(jQuery("#line_width").val()),
		strokeColor: "#" + jQuery("#line_color_hover").val(),
		strokeOpacity: Number(jQuery("#line_opacity_hover").val()),
		fillColor: "#" + jQuery("#fill_color_hover").val(),
		fillOpacity: Number(jQuery("#fill_opacity_hover").val()),
		draggable:true,
        geodesic: true

	});

	polygon.setMap(map);
	gmwdPolygonEvents();
}

function gmwdPolygonMapEvents(){
	google.maps.event.addListener(map, 'rightclick', function(event) {
        gmwdRemoveMarkersFromMap();
        if(polygon){			
            polygon.setMap(null);
            polygonHover.setMap(null);
        }
       gmwdAddNewMarker(event.latLng); 
       gmwdAddMarkersToMap();  
       gmwdAddPolygon();	   
    });
}
function gmwdPolygonEvents(){	
    //events 
	google.maps.event.addListener(polygonHover, 'dragend', function (event) {

		jQuery("#data").val("");
		this.getPath().forEach(function(element,index){
			jQuery("#data").val(jQuery("#data").val() + "(" + element.lat() + "," + element.lng() + "),");			
		});
		gmwdRemoveMarkersFromMap();
		polygon.setMap(null);
		polygonHover.setMap(null);
		polygonCoord = [];
		markers = [];
		infoWindows = [];
		rightclick = true;
		gmwdSetPolygon();
	
	}); 
	
	google.maps.event.addListener(polygon,"mouseover",function(){
		polygon.setMap(null);
		polygonHover.setOptions({strokeWeight: Number(jQuery("#line_width").val())});
		polygonHover.setMap(map);
		/*if(jQuery(".wd-form-field#title").val()){
			var polygonInfoWindow = new google.maps.InfoWindow();
			infoWindow.setContent(jQuery(".wd-form-field#title").val());
			var latLng = event.latLng;
			infoWindow.setPosition(latLng);
			infoWindow.open(map);
		}*/
	}); 

	google.maps.event.addListener(polygonHover,"mouseout",function(){
		//this.getMap().getDiv().removeAttribute('title');
		polygonHover.setMap(null);				
		polygon.setMap(map);				
	}); 
	google.maps.event.addListener(polygonHover,'mouseover',function(){
		if(jQuery("#title").val()){
			this.getMap().getDiv().setAttribute('title',jQuery("#title").val());
		 }
	 });
	
	google.maps.event.addListener(polygonHover, 'click', function() {
		if(jQuery("#link").val() != '')
			window.open(jQuery("#link").val());
	});
	
	for(var i = 0; i<markers.length; i++){
		marker = markers[i];
		
		google.maps.event.addListener(marker, 'dragstart', function (event) {
			var position = this.getPosition();
			jQuery("#dragged_marker").val("(" + position.lat() + ", " + position.lng() + "),");						
		});
		google.maps.event.addListener(marker, 'dragend', function (event) {
			var position = this.getPosition();
			var newPosition = "(" + position.lat() + ", " + position.lng() + "),";
            gmwdChangeData(jQuery("#dragged_marker").val(), newPosition);

		});	

		google.maps.event.addListener(marker, 'rightclick', function (event) {
			var position = this.getPosition();	
			position = "(" + position.lat() + ", " + position.lng() + "),";	
            gmwdChangeData(position, "")
		});			
	
	}

}
function gmwdChangeData(position, newPosition){
    rightclick = true;
    jQuery("#data").val(jQuery("#data").val().replace(position,newPosition));
    gmwdRemoveMarkersFromMap();
    markers = [];	
    infoWindows = [];	
    polygonCoord = [];	
    polygon.setMap(null);
    polygonHover.setMap(null);	
    gmwdSetPolygon();
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