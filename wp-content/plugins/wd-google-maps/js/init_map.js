////////////////////////////////////////////////////////////////////////////////////////
// Events                                                                             //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Constants                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Variables                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
var gmwdmapDataOptions = [];

////////////////////////////////////////////////////////////////////////////////////////
// Constructor & Destructor                                                           //
////////////////////////////////////////////////////////////////////////////////////////

function gmwdInitMainMap(el, excludeOverlays, key){

	gmwdmapData["main_map" + key] = new google.maps.Map(document.getElementById(el), {
		center: {lat: gmwdmapData["centerLat" + key], lng: gmwdmapData["centerLng" + key]},		
		zoom: gmwdmapData["zoom" + key],
		maxZoom: gmwdmapData["maxZoom" + key],
		minZoom: gmwdmapData["minZoom" + key],
		scrollwheel: gmwdmapData["mapWhellScrolling" + key],
		draggable: gmwdmapData["mapDragable" + key],	
        disableDoubleClickZoom: gmwdmapData["mapDbClickZoom" + key],	        
		zoomControl: gmwdmapData["enableZoomControl" + key],
		mapTypeControl: gmwdmapData["enableMapTypeControl" + key],
		scaleControl: gmwdmapData["enableScaleControl" + key],
		streetViewControl: gmwdmapData["enableStreetViewControl" + key],
		fullscreenControl: gmwdmapData["enableFullscreenControl" + key],
		rotateControl: gmwdmapData["enableRotateControl" + key], 
        zoomControlOptions:{
            position: gmwdmapData["zoomControlPosition" + key] 
        },
        mapTypeControlOptions:{
            position: gmwdmapData["mapTypeControlPosition" + key],
            style: gmwdmapData["mapTypeControlStyle" + key]
        }, 
        fullscreenControlOptions:{
            position: gmwdmapData["fullscreenControlPosition" + key] 
        },	
        streetViewControlOptions:{
            position: gmwdmapData["streetViewControlPosition" + key] 
        },
 		
	}); 
    gmwdmapData["main_map" + key].setTilt(45);
	gmwdSetMapTypeId(key);
	

	//themes   
    jQuery("#wd-map" + key).css("border-radius", gmwdmapData["mapBorderRadius" + key] + "px");
	gmwdmapData["main_map" + key].setOptions({styles:eval(gmwdmapData["mapTheme" + key])});
	
	//layers
	gmwdSetLayers("bike", key);
	gmwdSetLayers("traffic", key);
	gmwdSetLayers("transit", key);
	gmwdSetGeoRSSURL(key);
	gmwdSetKMLURL(key);
	gmwdSetFusionTableId(key);
    
	if(excludeOverlays == false){
        // overlays
        gmwdSetMapMarkers(key);
        gmwdSetMapCircles(key);
        gmwdSetMapPolygons(key);
        gmwdSetMapPolylines(key);
        gmwdSetMapRectangles(key);
    }

    jQuery(document).on("click",".gm-fullscreen-control",function(){
        setTimeout(function(){ 
            gmwdmapData["main_map" + key].setCenter({lat: Number(gmwdmapData["centerLat" + key]), lng: Number(gmwdmapData["centerLng" + key])});
            gmwdmapData["main_map" + key].setZoom(gmwdmapData["zoom" + key]);
        }, 300);

    });
	
}

function gmwdSetMapMarkers(_key){
	var mapMarker;
    gmwdmapData["allMarkers" + _key] = [];
    if(Object.keys(gmwdmapData["mapMarkers" + _key]).length > 0){
        for(var key in gmwdmapData["mapMarkers" + _key]){
            mapMarker = gmwdmapData["mapMarkers" + _key][key];
            var marker = new google.maps.Marker({
                map: gmwdmapData["main_map" + _key],
                position: {lat: Number(mapMarker.lat), lng: Number(mapMarker.lng)}
            });
            gmwdmapData["allMarkers" + _key].push(marker);
            var infoWindow;
            if(mapMarker.enable_info_window == 1){
                contentString = '';
                contentString += '<div style="float:left;max-width: 160px;">'; 
                if(gmwdmapData["infoWindowInfo" + _key].indexOf("title") != -1){
                    contentString += '<div class="gmwd-infowindow-title">' + mapMarker.title + '</div>';
                }
                if(gmwdmapData["infoWindowInfo" + _key].indexOf("address") != -1){

                    contentString += '<div class="gmwd-infowindow-address">' +  mapMarker.address + '</div>';
                } 
                if(gmwdmapData["infoWindowInfo" + _key].indexOf("desc") != -1){
                    var description = typeof mapMarker.description == "object" ? decodeEntities(mapMarker.description.join("<br>")) : decodeEntities(mapMarker.description);
                    contentString += "<div  class='gmwd-infowindow-description'>" + description + "</div>";
                } 
                if(gmwdmapData["enableDirections" + _key] == 1){
                    contentString += "<div style='clear:both;'> <a href='javascript:void(0)' data-lat='" + Number(mapMarker.lat) + "' data-lng='" + Number(mapMarker.lng) + "' data-address='" + mapMarker.address + "' class='gmwd_direction' data-key='" + _key + "' onclick='showDirectionsBox(this);'>Get Directions</a></div>";
                }
                contentString += '</div>'; 
                if(mapMarker.pic_url && gmwdmapData["infoWindowInfo" + _key].indexOf("pic") != -1){
                    contentString +=  '<img src="' + mapMarker.pic_url + '"  style="float:right; margin-left: 10px; max-width:100px!important">';
                }
                infoWindow = new google.maps.InfoWindow({
                    content: contentString,
                    disableAutoPan: false
                });	
                if(mapMarker.info_window_open == 1){
                    infoWindow.open(gmwdmapData["main_map" + _key], marker);
                }
                gmwdmapData["infoWindows" + _key].push(infoWindow);
            }
        
            if(mapMarker.title){
                marker.setTitle(mapMarker.title);
            }

            var size = Number(mapMarker.marker_size); 
            if(mapMarker.custom_marker_url){          
                var image = {
                    url : mapMarker.custom_marker_url,
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
            
            if(mapMarker.animation == "BOUNCE"){
                marker.setAnimation(google.maps.Animation.BOUNCE)
              }
              else if(mapMarker.animation == "DROP"){
                marker.setAnimation(google.maps.Animation.DROP)
              } 
              else{
                marker.setAnimation(null);
              }
            
            //events
            (function(overlay, row, overlayWindow, map, openEvent, overlayWindows, infoWindowtype, _key) { 
        
                google.maps.event.addListener(overlay, 'click', function() {
                    if(row.link_url){
                        window.open(row.link_url);
                    }
                    if(openEvent == "click"){
                      
                        if(overlayWindow && row.enable_info_window == 1){
                            for(var j=0; j < overlayWindows.length; j++){
                                overlayWindows[j].open(null, null);
                            }
                            
                            if(overlayWindow && infoWindowtype == 0){
                                overlayWindow.open(map, overlay);	
                            }
                            else if(infoWindowtype == 1){
                                gmwdAdvancedInfoWindow(row, _key);
                            }
                        }
                    }				
                });
                google.maps.event.addListener(overlay, 'mouseover', function() {		
                    if(openEvent == "hover"){
                        
                        if(row.enable_info_window == 1){
                            for(var j=0; j < overlayWindows.length; j++){
                                overlayWindows[j].open(null, null);
                            }
                            if(overlayWindow && infoWindowtype == 0){
                                overlayWindow.open(map, overlay);	
                            }
                            else if(infoWindowtype == 1){
                                gmwdAdvancedInfoWindow(row, _key);
                            }
                        }
                    }				
                });
            
            }(marker, mapMarker, infoWindow, gmwdmapData["main_map" + _key], gmwdmapData["infoWindowOpenOn" + _key],  gmwdmapData["infoWindows" + _key], gmwdmapData["infoWindowtype" + _key], _key));
        
        }
    }

    if(typeof gmwdmapData["markerCLustering" + _key] != "undefined" && gmwdmapData["markerCLustering" + _key] == "1" ){
        var options = [gmwdmapData["markerCLusteringZoom" + _key], gmwdmapData["markerCLusteringSize" + _key], gmwdmapData["markerCLusteringStyle" + _key]];
       
        createMarkerCluster(gmwdmapData["allMarkers" + _key], gmwdmapData["main_map" + _key], options); 
    }
}

function gmwdSetMapCircles(_key){
	var mapCircle, circle, circleHover, circlMarker;
    if(Object.keys(gmwdmapData["mapCircles" + _key]).length > 0){
        for(var key in gmwdmapData["mapCircles" + _key]){
            mapCircle = gmwdmapData["mapCircles" + _key][key];
             
            if(mapCircle.show_marker == 1){
                circleMarker = new google.maps.Marker({
                    map: gmwdmapData["main_map" + _key],
                    position: {lat: Number(mapCircle.center_lat), lng: Number(mapCircle.center_lng) }
                });
                if(markerDefaultIcon){	
                    var image = {
                        url : markerDefaultIcon,
                        scaledSize: new google.maps.Size(32, 32)
                    };             
                    circleMarker.setIcon(image);
                }          
                circleMarker.setMap(gmwdmapData["main_map" + _key]);
                 if(mapCircle.enable_info_window == 1){
                    (function(circleMarker) { 
                        var infoWindow = new google.maps.InfoWindow({
                            content: mapCircle.center_address,
							disableAutoPan: false
                        });
                        google.maps.event.addListener(circleMarker, 'click', function() {
                            for(var j=0; j < gmwdmapData["infoWindows" + _key].length; j++){
                                gmwdmapData["infoWindows" + _key][j].open(null, null);
                            } 
                            infoWindow.open(gmwdmapData["main_map" + _key], circleMarker);
                            gmwdmapData["infoWindows" + _key].push(infoWindow);                            
                        });                   
                    }(circleMarker));
                }                
                gmwdmapData["allCircleMarkers" + _key].push(circlMarker);
            }
                  
             circle = new google.maps.Circle({
                strokeWeight:Number(mapCircle.line_width),
                strokeColor: "#" + mapCircle.line_color,
                strokeOpacity: Number(mapCircle.line_opacity),
                fillColor: "#" + mapCircle.fill_color,
                fillOpacity: Number(mapCircle.fill_opacity),
                center: {lat: Number(mapCircle.center_lat), lng: Number(mapCircle.center_lng) },
                radius: Number(mapCircle.radius)
            });
            
            circleHover = new google.maps.Circle({
                strokeWeight:Number(mapCircle.line_width),
                strokeColor: "#" + mapCircle.line_color_hover,
                strokeOpacity: Number(mapCircle.line_opacity_hover),
                fillColor: "#" + mapCircle.fill_color_hover,
                fillOpacity: Number(mapCircle.fill_opacity_hover),
                center: {lat: Number(mapCircle.center_lat), lng: Number(mapCircle.center_lng) },
                radius: Number(mapCircle.radius)
            });
            
            gmwdmapData["allCircles" + _key].push(circle,circleHover);
            circle.setMap(gmwdmapData["main_map" + _key]);
            
            //events
            (function(overlay, overlayHover, row, map) { 

                google.maps.event.addListener(overlayHover, 'click', function() {
                    if(row.link)
                        window.open(row.link);
                });
                    
                google.maps.event.addListener(overlay,"mouseover",function(){
                    overlay.setMap(null);
                    overlayHover.setOptions({strokeWeight: Number(row.line_width)});
                    overlayHover.setOptions({radius: Number(row.radius)});
                    overlayHover.setMap(map);
                }); 
                google.maps.event.addListener(overlayHover,"mouseover",function(){
                    if(row.title){
                        this.getMap().getDiv().setAttribute('title',row.title);
                     }				
                });
                google.maps.event.addListener(overlayHover,"mouseout",function(){
                    this.getMap().getDiv().removeAttribute('title');
                    overlayHover.setMap(null);				
                    overlay.setMap(map);				
                }); 		
            
            }(circle, circleHover, mapCircle, gmwdmapData["main_map" + _key]));	

        }
    }

}

function gmwdSetMapRectangles(_key){
	var mapRectangle, rectangle, rectangleHover,bounds; 
    if(Object.keys(gmwdmapData["mapRectangles" + _key]).length > 0){
        for(var key in gmwdmapData["mapRectangles" + _key]){
            var bounds = [];
            mapRectangle = gmwdmapData["mapRectangles" + _key][key];
            var southWestAr = mapRectangle.south_west.split(",");
            var northEastAr = mapRectangle.north_east.split(",");
            var southWest = new google.maps.LatLng(Number(southWestAr[0]),Number(southWestAr[1]));
            var northEast = new google.maps.LatLng(Number(northEastAr[0]),Number(northEastAr[1])); 
            
            bounds = new google.maps.LatLngBounds(southWest,northEast);  
            if(mapRectangle.show_markers == 1){
                var topPositions = [];
                topPositions.push(new google.maps.LatLng(southWest.lat(), southWest.lng()));
                topPositions.push(new google.maps.LatLng(northEast.lat(), southWest.lng()));
                topPositions.push(new google.maps.LatLng(northEast.lat(), northEast.lng()));
                topPositions.push(new google.maps.LatLng(southWest.lat(), northEast.lng()));
                for(var j=0; j<topPositions.length; j++){
                    var marker = new google.maps.Marker({
                        position: topPositions[j],
                        map: gmwdmapData["main_map" + _key]		
                    });
                    if(markerDefaultIcon){	
                        var image = {
                            url : markerDefaultIcon,
                            scaledSize: new google.maps.Size(32, 32)
                        };             
                        marker.setIcon(image);
                    }
                    if(mapRectangle.enable_info_windows == 1){  
                        showPoiInfoWindow(gmwdmapData["main_map" + _key], marker, _key);
                    }                     
                }
            }
            rectangle = new google.maps.Rectangle({
                strokeWeight:Number(mapRectangle.line_width),
                strokeColor: "#" + mapRectangle.line_color,
                strokeOpacity: Number(mapRectangle.line_opacity),
                fillColor: "#" + mapRectangle.fill_color,
                fillOpacity: Number(mapRectangle.fill_opacity),
                bounds: bounds,
            });
            
            rectangleHover = new google.maps.Rectangle({
                strokeWeight:Number(mapRectangle.line_width),
                strokeColor: "#" + mapRectangle.line_color_hover,
                strokeOpacity: Number(mapRectangle.line_opacity_hover),
                fillColor: "#" + mapRectangle.fill_color_hover,
                fillOpacity: Number(mapRectangle.fill_opacity_hover),
                bounds: bounds,
            });
            gmwdmapData["allRectangles" + _key].push(rectangle, rectangleHover);
            rectangle.setMap(gmwdmapData["main_map" + _key]);
            
            (function(overlay, overlayHover, row, map) { 
                google.maps.event.addListener(overlayHover, 'click', function() {
                    if(row.link)
                        window.open(row.link);
                });
                    
                google.maps.event.addListener(overlay,"mouseover",function(){
                    overlay.setMap(null);
                    overlayHover.setMap(map);
                }); 
                google.maps.event.addListener(overlayHover,"mouseover",function(){
                    if(row.title){
                        this.getMap().getDiv().setAttribute('title',row.title);
                     }				
                });
                google.maps.event.addListener(overlayHover,"mouseout",function(){
                    this.getMap().getDiv().removeAttribute('title');
                    overlayHover.setMap(null);				
                    overlay.setMap(map);				
                }); 		
            
            }(rectangle, rectangleHover, mapRectangle, gmwdmapData["main_map" + _key]));	       
            
        }
    }
}
function gmwdSetMapPolygons(_key){
	var mapPolygon, polygon, polygonHover;
    var polygonsByAreas = {};
    var polygonsAreas = [];
    if(Object.keys(gmwdmapData["mapPolygons" + _key]).length > 0){
        for(var key in gmwdmapData["mapPolygons" + _key]){
            var polygonCoord = [];
            mapPolygon = gmwdmapData["mapPolygons" + _key][key];
            polygonData = mapPolygon.data.substr(1, mapPolygon.data.length-4).split("),(");
            for(var j=0; j<polygonData.length; j++){
                var polygonMarker = polygonData[j].split(",");
                if(mapPolygon.show_markers == 1){										
                    var marker = new google.maps.Marker({
                        map: gmwdmapData["main_map" + _key],
                        position: {lat: Number(polygonMarker[0]), lng: Number(polygonMarker[1]) }
                    });
                    if(markerDefaultIcon){	
                        var image = {
                            url : markerDefaultIcon,
                            scaledSize: new google.maps.Size(32, 32)
                        };             
                        marker.setIcon(image);
                    }                     
                    marker.setMap(gmwdmapData["main_map" + _key]);
                    gmwdmapData["allPolygonMarkers" + _key].push(marker);
                    if(mapPolygon.enable_info_windows == 1){  
                        showPoiInfoWindow(gmwdmapData["main_map" + _key], marker, _key);
                    }                    
                }

                polygonCoord.push(new google.maps.LatLng( Number(polygonMarker[0]), Number(polygonMarker[1])));
            }
            
            polygon = new google.maps.Polygon({
                strokeWeight:Number(mapPolygon.line_width),
                strokeColor: "#" + mapPolygon.line_color,
                strokeOpacity: Number(mapPolygon.line_opacity),
                fillColor: "#" + mapPolygon.fill_color,
                fillOpacity: Number(mapPolygon.fill_opacity),
                paths: polygonCoord,
            });
            
            polygonHover = new google.maps.Polygon({
                strokeWeight:Number(mapPolygon.line_width),
                strokeColor: "#" + mapPolygon.line_color_hover,
                strokeOpacity: Number(mapPolygon.line_opacity_hover),
                fillColor: "#" + mapPolygon.fill_color_hover,
                fillOpacity: Number(mapPolygon.fill_opacity_hover),
                paths: polygonCoord,
            });
            gmwdmapData["allPolygons" + _key].push(polygon, polygonHover);
           // polygon.setMap(gmwdmapData["main_map" + _key]);
            var polygonArea = google.maps.geometry.spherical.computeArea(polygon.getPath());
            polygonsByAreas[polygonArea] = [polygon,polygonHover]; 
            polygonsAreas.push(polygonArea);
            (function(overlay, overlayHover, row, map) { 
                google.maps.event.addListener(overlayHover, 'click', function() {
                    if(row.link)
                        window.open(row.link);
                });
                    
                google.maps.event.addListener(overlay,"mouseover",function(){
                    overlay.setMap(null);
                    overlayHover.setMap(map);
                }); 
                google.maps.event.addListener(overlayHover,"mouseover",function(){
                    if(row.title){
                        this.getMap().getDiv().setAttribute('title',row.title);
                     }				
                });
                google.maps.event.addListener(overlayHover,"mouseout",function(){
                    this.getMap().getDiv().removeAttribute('title');
                    overlayHover.setMap(null);				
                    overlay.setMap(map);				
                });
            
            }(polygon, polygonHover, mapPolygon, gmwdmapData["main_map" + _key]));	
           
        }
    }
    polygonsAreas.sort(function(a,b){return b - a});

    for(var i=0; i< polygonsAreas.length ; i++){
        polygonsByAreas[polygonsAreas[i]][0].setMap(gmwdmapData["main_map" + _key]);
        polygonsByAreas[polygonsAreas[i]][0].setOptions({zIndex: i+1});
        polygonsByAreas[polygonsAreas[i]][1].setOptions({zIndex: i+1});
     
    }
	
}

function gmwdSetMapPolylines(_key){
	var mapPolyline, polyline, polylineHover; 
	if(Object.keys(gmwdmapData["mapPolylines" + _key]).length > 0){
        for(var key in gmwdmapData["mapPolylines" + _key]){
            var polylineCoord = [];
            mapPolyline = gmwdmapData["mapPolylines" + _key][key];
            polylineData = mapPolyline.data.substr(1, mapPolyline.data.length-4).split("),(");
            for(var j=0; j<polylineData.length; j++){
                var polylineMarker = polylineData[j].split(",");
                if(mapPolyline.show_markers == 1){										
                    var marker = new google.maps.Marker({
                        map: gmwdmapData["main_map" + _key],
                        position: {lat: Number(polylineMarker[0]), lng: Number(polylineMarker[1]) }
                    });
                    gmwdmapData["allPolylineMarkers" + _key].push(marker);
                    if(markerDefaultIcon){	
                        var image = {
                            url : markerDefaultIcon,
                            scaledSize: new google.maps.Size(32, 32)
                        };             
                        marker.setIcon(image);
                    }                     
                    marker.setMap(gmwdmapData["main_map" + _key]);
                }
                if(mapPolyline.enable_info_windows == 1){  
                    showPoiInfoWindow(gmwdmapData["main_map" + _key], marker, _key);
                }               
                polylineCoord.push(new google.maps.LatLng( Number(polylineMarker[0]), Number(polylineMarker[1])));
            }
            
            polyline = new google.maps.Polyline({
                strokeWeight:Number(mapPolyline.line_width),
                strokeColor: "#" + mapPolyline.line_color,
                strokeOpacity: Number(mapPolyline.line_opacity),
                path: polylineCoord,
                geodesic: true,
            });

            polylineHover = new google.maps.Polyline({
                strokeWeight:Number(mapPolyline.line_width),
                strokeColor: "#" + mapPolyline.line_color_hover,
                strokeOpacity: Number(mapPolyline.line_opacity_hover),
                path: polylineCoord,
                geodesic: true,
            });
            gmwdmapData["allPolylines" + _key].push(polyline, polylineHover );
            polyline.setMap(gmwdmapData["main_map" + _key]);
            
            (function(overlay, overlayHover, row, map) { 
                google.maps.event.addListener(overlayHover, 'click', function() {
                    if(row.link)
                        window.open(row.link);
                });
                    
                google.maps.event.addListener(overlay,"mouseover",function(){
                    overlay.setMap(null);
                    overlayHover.setMap(map);
                }); 
                google.maps.event.addListener(overlayHover,"mouseover",function(){
                    if(row.title){
                        this.getMap().getDiv().setAttribute('title',row.title);
                     }				
                });
                google.maps.event.addListener(overlayHover,"mouseout",function(){
                    this.getMap().getDiv().removeAttribute('title');
                    overlayHover.setMap(null);				
                    overlay.setMap(map);				
                }); 
            
            }(polyline, polylineHover, mapPolyline, gmwdmapData["main_map" + _key]));	        
                        
                
        }
    }
	
}
function showPoiInfoWindow(map, marker, key){

   (function(marker, map) { 
        var geocoder = new google.maps.Geocoder();            
        geocoder.geocode({       
            latLng: marker.getPosition()     
        }, 
        function(responses) {  
           if (responses && responses.length > 0) {
                var infoWindow = new google.maps.InfoWindow({
                    content: responses[0].formatted_address,
					disableAutoPan: false					
                });  
                 google.maps.event.addListener(marker, 'click', function() {
                    for(var j=0; j <  gmwdmapData["infoWindows" + key].length; j++){
                         gmwdmapData["infoWindows" + key][j].open(null, null);
                    } 
                    infoWindow.open(map, marker); 
                    gmwdmapData["infoWindows" + key].push(infoWindow);                    
                });                        
           }  
        }); 
    }(marker, map));     
}

////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////


function gmwdSetMapTypeId(key){
	switch(gmwdmapData["mapType" + key]){
		case "0" :
			gmwdmapData["main_map" + key].setMapTypeId(google.maps.MapTypeId.ROADMAP);
			break;
		case "1" :	
			gmwdmapData["main_map" + key].setMapTypeId(google.maps.MapTypeId.SATELLITE);
			break;
		case "2":	
			gmwdmapData["main_map" + key].setMapTypeId(google.maps.MapTypeId.HYBRID);
			break;
		case "3":		
			gmwdmapData["main_map" + key].setMapTypeId(google.maps.MapTypeId.TERRAIN);
			break;				
	}
}


function gmwdSetLayers(type, key){
	switch(type){
		case "bike" :
			if(gmwdmapData["enableBykeLayer" + key] == 1){
				 gmwdmapDataOptions["bikeLayer" + key] = new google.maps.BicyclingLayer();
				 gmwdmapDataOptions["bikeLayer" + key].setMap(gmwdmapData["main_map" + key]);
			 }
			 else{
				if(gmwdmapDataOptions["bikeLayer" + key]){
					gmwdmapDataOptions["bikeLayer" + key].setMap(null);
				}
			 }
			 break;
		case "traffic" :	 
			if(gmwdmapData["enableTrafficLayer" + key] == 1){
				gmwdmapDataOptions["trafficLayer" + key] = new google.maps.TrafficLayer();
				gmwdmapDataOptions["trafficLayer" + key].setMap(gmwdmapData["main_map" + key]);
			 }
			 else{
				if(gmwdmapDataOptions["trafficLayer" + key]){
					gmwdmapDataOptions["trafficLayer" + key].setMap(null);
				}
			 }	
			 break;	
		case "transit" :	 
			if(gmwdmapData["enableTransitLayer" + key] == 1){
				gmwdmapDataOptions["transitLayer" + key] = new google.maps.TransitLayer();
				gmwdmapDataOptions["transitLayer" + key].setMap(gmwdmapData["main_map" + key]);
			 }
			 else{
				if(gmwdmapDataOptions["transitLayer" + key]){
					gmwdmapDataOptions["transitLayer" + key].setMap(null);
				}
			 }	
			 break;					 
	}
}

function gmwdSetGeoRSSURL(key){
	if(gmwdmapData["geoRSSURL" + key]){
		gmwdmapDataOptions["georssLayer" + key] = new google.maps.KmlLayer({
			url: gmwdmapData["geoRSSURL" + key]
		  });
		gmwdmapDataOptions["georssLayer" + key].setMap(gmwdmapData["main_map" + key]);
	}
	else{
		if(gmwdmapDataOptions["georssLayer" + key]){
			gmwdmapDataOptions["georssLayer" + key].setMap(null);
		}
	}
}

function gmwdSetKMLURL(key){
	if(gmwdmapData["KMLURL" + key]){
	  gmwdmapDataOptions["ctaLayer" + key] = new google.maps.KmlLayer({
		url: gmwdmapData["KMLURL" + key]			
	  });
		gmwdmapDataOptions["ctaLayer" + key].setMap(gmwdmapData["main_map" + key]);
	}
	else{
		if(gmwdmapDataOptions["ctaLayer" + key]){
			gmwdmapDataOptions["ctaLayer" + key].setMap(null);
		}
	}
}

function gmwdSetFusionTableId(key){
	if(gmwdmapData["fusionTableId" + key]){
      var fusionTableWhere = (gmwdmapData["fusionTableWhereField" + key] && gmwdmapData["fusionTableWhereOperator" + key] && gmwdmapData["fusionTableWhereValue" + key]) ? "'" + gmwdmapData["fusionTableWhereField" + key].trim() + "' " + gmwdmapData["fusionTableWhereOperator" + key] + ((gmwdmapData["fusionTableWhereOperator" + key] != "IN") ? " '" + gmwdmapData["fusionTableWhereValue" + key].trim() + "'" : " " + gmwdmapData["fusionTableWhereValue" + key].trim()) : '';  
      
	  gmwdmapDataOptions["fusionTablesLayer" + key] = new google.maps.FusionTablesLayer({
		query: {
		  select: '\'Geocodable address\'',
		  from: gmwdmapData["fusionTableId" + key],
          where: fusionTableWhere
		}
	  });
	  gmwdmapDataOptions["fusionTablesLayer" + key].setMap(gmwdmapData["main_map" + key]);
	}
	else{
		if(gmwdmapDataOptions["fusionTablesLayer" + key]){
			gmwdmapDataOptions["fusionTablesLayer" + key].setMap(null);
		}
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