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
var rectangle;
var rectangleHover;
var bounds = [];
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
		if(jQuery(this).val() && rectangle){
			rectangle.setOptions({strokeColor: "#" + jQuery(this).val()});
		}
	});
	jQuery("#fill_color").blur(function(){
		if(jQuery(this).val() && rectangle){
			rectangle.setOptions({fillColor: "#" + jQuery(this).val()});
		}
	});
	
	jQuery("#line_color_hover").blur(function(){
		if(jQuery(this).val() && rectangleHover){
			rectangleHover.setOptions({strokeColor: "#" + jQuery(this).val()});
		}
	});
	jQuery("#fill_color_hover").blur(function(){
		if(jQuery(this).val() && rectangleHover){
			rectangleHover.setOptions({fillColor: "#" + jQuery(this).val()});
		}
	});
	
	jQuery("#line_width").bind("slider:ready slider:changed", function (event, data) { 
		if (rectangle){
			rectangle.setOptions({strokeWeight: data.value.toFixed(1)});
		}			
	});
	jQuery("#line_opacity").bind("slider:ready slider:changed", function (event, data) { 
		if (rectangle){
			rectangle.setOptions({strokeOpacity: data.value.toFixed(1)});
		}			
	});
	jQuery("#fill_opacity").bind("slider:ready slider:changed", function (event, data) { 
		if (rectangle){
			rectangle.setOptions({fillOpacity: data.value.toFixed(1)});
		}			
	});
	jQuery("#line_opacity_hover").bind("slider:ready slider:changed", function (event, data) { 
		if (rectangleHover){
			rectangleHover.setOptions({strokeOpacity: data.value.toFixed(1)});
		}			
	});
	jQuery("#fill_opacity_hover").bind("slider:ready slider:changed", function (event, data) { 
		if (rectangleHover){
			rectangleHover.setOptions({fillOpacity: data.value.toFixed(1)});
		}			
	});	
	
	
    jQuery("#south_west, #north_east").blur(function(){
        if(jQuery("#south_west").val() == "" || jQuery("#north_east").val() == ""){
            return false;
        }
        gmwdRemoveMarkersFromMap();
        if(rectangle){			
            rectangle.setMap(null);
            rectangleHover.setMap(null); 
            rectangle = undefined;                           
            rectangleHover = undefined;                           
            markers = [];
            infoWindows = [];
        }
       rightclick = false;
       gmwdSetRectangle();
	});
    
	// link url
	jQuery("#link").blur(function(){
		if(rectangle){
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
    
	jQuery("[name=enable_info_windows]").change(function(){
       if(jQuery('[name=enable_info_windows]:checked').val() == 1){
            infoWindows = [];
            for(var i = 0; i<markers.length; i++){
                marker = markers[i];            
                gmwdSetRectangleMarkersInfoWindow(marker);
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

function gmwdSetRectangle(){

    var southWestAr = jQuery("#south_west").val().split(",");
    var northEastAr = jQuery("#north_east").val().split(",");

    var southWest = new google.maps.LatLng(Number(southWestAr[0]),Number(southWestAr[1]));
    var northEast = new google.maps.LatLng(Number(northEastAr[0]),Number(northEastAr[1])); 
    
    gmwdFindMarkers(southWest,northEast);

    bounds = new google.maps.LatLngBounds(southWest,northEast); 

	gmwdDrawRectangle(bounds);
	if(rightclick == false){
		map.setOptions({center: bounds.getCenter()});
	}
	//events
	gmwdRectangleEvents();
}

function gmwdAddNewMarker(location, visibilaty){
    
    if(typeof visibilaty == 'undefined'){
        visibilaty = false;
    }
    var marker = new google.maps.Marker({
        position: location,
        map: map,
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
	if(visibilaty){
        var markervisibilaty = jQuery("[name=show_markers]:checked").val() == 1 ? true : false;
        marker.setVisible(markervisibilaty);
       
        if(markervisibilaty == true && jQuery('[name=enable_info_windows]:checked').val() == 1){	
            gmwdSetRectangleMarkersInfoWindow(marker);
        }
        
    }     
	markers.push(marker); 
}
function gmwdSetRectangleMarkersInfoWindow(marker){

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

function gmwdAddRectangle(){
    bounds = [];  
    if(markers.length == 2){
        var lat1 = markers[0].getPosition().lat();
        var lng1 = markers[0].getPosition().lng();
        var lat2 = markers[1].getPosition().lat();
        var lng2 = markers[1].getPosition().lng();

        if(lat1>lat2 && lng1>lng2){
            var southWest = new google.maps.LatLng(lat2,lng2);
            var northEast = new google.maps.LatLng(lat1,lng1); 
            jQuery("#south_west").val(lat2 + "," + lng2);            
            jQuery("#north_east").val(lat1 + "," + lng1);            
        }
        else if(lat1>lat2 && lng2>lng1){
            var southWest = new google.maps.LatLng(lat2,lng1);
            var northEast = new google.maps.LatLng(lat1,lng2); 
            jQuery("#south_west").val(lat2 + "," + lng1);            
            jQuery("#north_east").val(lat1 + "," + lng2);         
        }
        else if(lat1<lat2 && lng1>lng2){
            var southWest = new google.maps.LatLng(lat1,lng2);
            var northEast = new google.maps.LatLng(lat2,lng1); 
            jQuery("#south_west").val(lat1 + "," + lng2);            
            jQuery("#north_east").val(lat2 + "," + lng1);         
        }        
        else if(lat1<lat2 && lng2>lng1){
            var southWest = new google.maps.LatLng(lat1,lng1);
            var northEast = new google.maps.LatLng(lat2,lng2); 
            jQuery("#south_west").val(lat1 + "," + lng1);            
            jQuery("#north_east").val(lat2 + "," + lng2);              
        }     
        bounds = new google.maps.LatLngBounds(southWest,northEast);        
        //map.fitBounds(bounds);  
        gmwdFindMarkers(southWest, northEast);
        gmwdDrawRectangle(bounds);
        
    }	
}


function gmwdDrawRectangle(bounds){
    // Construct the rectangle.
	 rectangle = new google.maps.Rectangle({
		bounds: bounds,
		strokeWeight:Number(jQuery("#line_width").val()),
		strokeColor: "#" + jQuery("#line_color").val(),
		strokeOpacity: Number(jQuery("#line_opacity").val()),
		fillColor: "#" + jQuery("#fill_color").val(),
		fillOpacity: Number(jQuery("#fill_opacity").val()),
		draggable:true
	});
	
	rectangleHover = new google.maps.Rectangle({
		bounds: bounds,
		strokeWeight:Number(jQuery("#line_width").val()),
		strokeColor: "#" + jQuery("#line_color_hover").val(),
		strokeOpacity: Number(jQuery("#line_opacity_hover").val()),
		fillColor: "#" + jQuery("#fill_color_hover").val(),
		fillOpacity: Number(jQuery("#fill_opacity_hover").val()),
		draggable:true

	});
    rectangle.setMap(map);
    gmwdRectangleEvents();
}

function gmwdFindMarkers(southWest,northEast){
    gmwdRemoveMarkersFromMap();
    markers = [];
    infoWindows = [];
    var topPositions = [];
    topPositions.push(new google.maps.LatLng(southWest.lat(), southWest.lng()));
    topPositions.push(new google.maps.LatLng(northEast.lat(), southWest.lng()));
    topPositions.push(new google.maps.LatLng(northEast.lat(), northEast.lng()));
    topPositions.push(new google.maps.LatLng(southWest.lat(), northEast.lng()));
    for(var j=0; j<topPositions.length; j++){
        gmwdAddNewMarker(topPositions[j], true);
    }
}

function gmwdRectangleMapEvents(){
    //events
    google.maps.event.addListener(map, 'rightclick', function(event) {
        gmwdRemoveMarkersFromMap();
        if(rectangle){			
            rectangle.setMap(null);
            rectangleHover.setMap(null); 
            rectangle = undefined;                           
            rectangleHover = undefined;                           
            markers = [];
            infoWindows = [];
        }
       rightclick = true;
       gmwdAddNewMarker(event.latLng); 
       gmwdAddMarkersToMap();  
       gmwdAddRectangle();	   
    });	
}

function gmwdRectangleEvents(){
	google.maps.event.addListener(rectangleHover, 'dragend', function (event) {			   
		bounds = this.getBounds();
		var southWest = bounds.getSouthWest();
		var northEast = bounds.getNorthEast();
		gmwdRemoveMarkersFromMap();                         
		markers = [];
        infoWindows = [];
		rectangle.setMap(null);
		rectangleHover.setMap(null); 
		jQuery("#south_west").val(southWest.lat() + "," + southWest.lng() );
        jQuery("#north_east").val(northEast.lat() + "," + northEast.lng() );
        rightclick = true;
		gmwdSetRectangle();

	}); 	
		
	google.maps.event.addListener(rectangle,"mouseover",function(){
		rectangle.setMap(null);
		rectangleHover.setOptions({strokeWeight: Number(jQuery("#line_width").val())});
		rectangleHover.setMap(map);

	}); 

	google.maps.event.addListener(rectangleHover,"mouseout",function(){
		//this.getMap().getDiv().removeAttribute('title');
		rectangleHover.setMap(null);				
		rectangle.setMap(map);				
	}); 
	google.maps.event.addListener(rectangleHover,'mouseover',function(){
		if(jQuery("#title").val()){
			this.getMap().getDiv().setAttribute('title',jQuery("#title").val());
		 }
	 });
	google.maps.event.addListener(rectangleHover, 'click', function() {
		if(jQuery("#link").val() != '')
			window.open(jQuery("#link").val());
	});	
    
	for(var i = 0; i<markers.length; i++){
		marker = markers[i];
		
		google.maps.event.addListener(marker, 'dragstart', function (event) {
			var position = this.getPosition();
			jQuery("#dragged_marker").val( position.lat() + ", " + position.lng() );
            if(jQuery("#south_west").val().indexOf(position.lat()) != -1){
               jQuery("#dragged_marker_side_lat").val("south");
            }
            else if(jQuery("#north_east").val().indexOf(position.lat()) != -1){       
                jQuery("#dragged_marker_side_lat").val("north");
            } 

            if(jQuery("#south_west").val().indexOf(position.lng()) != -1){
               jQuery("#dragged_marker_side_lng").val("west");
            }
            else if(jQuery("#north_east").val().indexOf(position.lng()) != -1){       
                jQuery("#dragged_marker_side_lng").val("east");
            }              
           
		});
		google.maps.event.addListener(marker, 'dragend', function (event) {
            var newPosition = this.getPosition();
            gmwdRemoveMarkersFromMap();	            
            markers = [];
            
            gmwdAddNewMarker(newPosition);
            var locationLat = "";
            var locationLng = "";
            if(jQuery("#dragged_marker_side_lat").val() == "north"){
                 locationLat = jQuery("#south_west").val();
            }  
            else if(jQuery("#dragged_marker_side_lat").val() == "south"){       
                locationLat = jQuery("#north_east").val();
            }
            if(jQuery("#dragged_marker_side_lng").val() == "west"){
                 locationLng = jQuery("#north_east").val();
            }  
            else if(jQuery("#dragged_marker_side_lng").val() == "east"){       
                locationLng = jQuery("#south_west").val();
            } 
            
            var locationArLat = locationLat.split(",");
            var locationArLng = locationLng.split(",");
            gmwdAddNewMarker(new google.maps.LatLng(Number(locationArLat[0]),Number(locationArLng[1])));
            
            gmwdAddMarkersToMap();
            bounds = [];	
            rectangle.setMap(null);
            rectangleHover.setMap(null);
            rectangle = "";	        
            gmwdAddRectangle();
   
		});	
		google.maps.event.addListener(marker, 'rightclick', function (event) {
			var position = this.getPosition();	
            rightclick = true;    
            var location = "";

            var lat = position.lat();
            var lng = position.lng();
            
               
            if(jQuery("#south_west").val().indexOf(lat) != -1){
                location = jQuery("#north_east").val();
            }
            else if(jQuery("#north_east").val().indexOf(lat) != -1){       
                location = jQuery("#south_west").val();
            }
            var locationAr = location.split(",");
            var location = new google.maps.LatLng(Number(locationAr[0]),Number(locationAr[1]));
            jQuery("#south_west").val("");
            jQuery("#north_east").val("");

            gmwdRemoveMarkersFromMap();
            markers = [];
            infoWindows = [];	
            bounds = [];	
            rectangle.setMap(null);
            rectangleHover.setMap(null);
            rectangle = "";	
          
            gmwdAddNewMarker(location, true);
		});			
	
	}    
	
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