////////////////////////////////////////////////////////////////////////////////////////
// Events                                                                             //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Constants                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Variables                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
var map;
var transitLayer;
var bikeLayer;
var trafficLayer;
var georssLayer;
var ctaLayer;
var fusionTablesLayer;
////////////////////////////////////////////////////////////////////////////////////////
// Constructor & Destructor                                                           //
////////////////////////////////////////////////////////////////////////////////////////

function gmwdInitMainMap(el, excludeOverlays){

	map = new google.maps.Map(document.getElementById(el), {
		center: {lat: centerLat, lng: centerLng},		
		zoom: zoom,
		maxZoom: maxZoom,
		minZoom: minZoom,
		scrollwheel: mapWhellScrolling,
		draggable: mapDragable,
        disableDoubleClickZoom: mapDbClickZoom,		
		zoomControl: enableZoomControl,
		mapTypeControl: enableMapTypeControl,
		scaleControl: enableScaleControl,
		streetViewControl: enableStreetViewControl,
		fullscreenControl: enableFullscreenControl,
		rotateControl: enableRotateControl,        
        zoomControlOptions:{
            position: zoomControlPosition 
        },
        mapTypeControlOptions:{
            position: mapTypeControlPosition,
            style: mapTypeControlStyle
        }, 
        fullscreenControlOptions:{
            position: fullscreenControlPosition 
        },	
        streetViewControlOptions:{
            position: streetViewControlPosition 
        }        
	}); 
    map.setTilt(45);

	gmwdSetMapTypeId();

	//themes
	jQuery("#wd-map3, #wd-map").css("border-radius", mapBorderRadius + "px");
	map.setOptions({styles:eval(mapTheme)});
	
	//layers
	gmwdSetLayers("bike");
	gmwdSetLayers("traffic");
	gmwdSetLayers("transit");
	gmwdSetGeoRSSURL();
	gmwdSetKMLURL();
	gmwdSetFusionTableId();

	if(excludeOverlays == false){
        // overlays
		infoWindowInfo = jQuery("[name=info_window_info]").length > 0 ? jQuery("[name=info_window_info]").val() : infoWindowInfo;
        gmwdSetMapMarkers();
        gmwdSetMapCircles();
        gmwdSetMapRectangles();
        gmwdSetMapPolygons();
        gmwdSetMapPolylines();
    }
     if(el == "wd-map3" || el == "wd-map"){
        // map events
        google.maps.event.addListener(map, 'drag', function(event) { 
           var mapCenter = map.getCenter();
           jQuery("#center_lat").val(mapCenter.lat());
           jQuery("#center_lng").val(mapCenter.lng());
           getAddressFromLatLng(mapCenter.lat(), mapCenter.lng(), "address", false);
          // map.setCenter({lat:Number(mapCenter.lat()), lng:Number(mapCenter.lng())});       
        });
        
        google.maps.event.addListener(map, 'zoom_changed', function(event) {      
           zoom = map.getZoom();
           jQuery("#zoom_level").val(zoom);        
          // map.setZoom(zoom);	  
        });
    }    
    if(el == "wd-map3"){
        google.maps.event.addListener(map, 'rightclick', function(event) {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({       
                latLng: new google.maps.LatLng(event.latLng.lat(), event.latLng.lng())     
            }, 
            function(responses) {     
               if (responses && responses.length > 0) {        
                   jQuery("#address").val(responses[0].formatted_address); 
                   jQuery("#center_lat").val( event.latLng.lat());
                   jQuery("#center_lng").val( event.latLng.lng());
                   map.setCenter(event.latLng);                        
               }  
            });
          
        });

    }
    
	
}



var infoWindows = [];
function gmwdSetMapMarkers(){
	var mapMarker;
    allMarkers = [];
	for(var key in mapMarkers){
		mapMarker = mapMarkers[key];
        if(mapMarker.published == 0){
            continue;
        }
		var marker = new google.maps.Marker({
			map: map,
			position: {lat: Number(mapMarker.lat), lng: Number(mapMarker.lng)}
		});
		
		allMarkers.push(marker);
        var infoWindow;
        if(mapMarker.enable_info_window == 1 && jQuery("[name=infowindow_type]:checked").val() == 0){
            //var infoWindowInfo = jQuery("[name=info_window_info]").val();
            contentString = '';
            if(mapMarker.pic_url && infoWindowInfo.indexOf("pic") != -1){
                contentString =  '<img src="' + mapMarker.pic_url + '"  style="float:right; margin-left: 10px; max-width:100px">';
            }
            if(infoWindowInfo.indexOf("title") != -1){
                contentString += mapMarker.title;
            }
            if(infoWindowInfo.indexOf("address") != -1){
                if(infoWindowInfo.indexOf("title") != -1){
                    contentString += "<br>" ;
                }
                contentString +=  mapMarker.address;
            } 
            if(infoWindowInfo.indexOf("desc") != -1){
                var description = typeof mapMarker.description == "object" ? decodeEntities(mapMarker.description.join("<br>")) : decodeEntities(mapMarker.description);
                contentString += "<div style='max-width:300px;'>" + description + "</div>";
            }            
  
            infoWindow = new google.maps.InfoWindow({
                content: contentString,
                disableAutoPan: false
            });	
            if(mapMarker.info_window_open == 1){
                infoWindow.open(map, marker);
            }
            infoWindows.push(infoWindow);
             
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
		(function(overlay, row, overlayWindow, map, overlayWindows ) { 
		
			google.maps.event.addListener(overlay, 'click', function() {
				if(row.link_url){
					window.open(row.link_url);
				}
				if(jQuery("#info_window_open_on :selected").val() == "click"){
                    if(row.enable_info_window == 1){
                        for(var j=0; j < overlayWindows.length; j++){
                            overlayWindows[j].open(null, null);
                        }                    
                        if(overlayWindow && jQuery("[name=infowindow_type]:checked").val() == 0){
                            overlayWindow.open(map, overlay);	
                        }
                        else if(jQuery("[name=infowindow_type]:checked").val() == 1){
                            gmwdAdvancedInfoWindow(row.title, row.address, row.pic_url, row.description);
                        }
                    }
                    
				}				
			});
			google.maps.event.addListener(overlay, 'mouseover', function() {		
				if(jQuery("#info_window_open_on :selected").val() == "hover"){
                    if(row.enable_info_window == 1){
                        for(var j=0; j < overlayWindows.length; j++){
                            overlayWindows[j].open(null, null);
                        }                     
                        if(overlayWindow && jQuery("[name=infowindow_type]:checked").val() == 0){
                            overlayWindow.open(map, overlay);	
                        }
                        else if(jQuery("[name=infowindow_type]:checked").val() == 1){
                            gmwdAdvancedInfoWindow(row.title, row.address, row.pic_url, row.description);
                        }
                    }
				}				
			});
		
		}(marker, mapMarker, infoWindow, map, infoWindows));	
	
	}

    if(typeof markerCLustering != "undefined" && markerCLustering == "1" ){
        var options = [markerCLusteringZoom, markerCLusteringSize, markerCLusteringStyle];
        createMarkerCluster(allMarkers, map, options); 
    }

}

var allCircles = [];
var allCircleMarkers = [];
function gmwdSetMapCircles(){
	var mapCircle, circle, circleHover, marker;
	for(var key in mapCircles){
		mapCircle = mapCircles[key];
        if(mapCircle.published == 0){
            continue;
        }
		if(mapCircle.show_marker == 1){
			marker = new google.maps.Marker({
				map: map,
				position: {lat: Number(mapCircle.center_lat), lng: Number(mapCircle.center_lng) }
			});
            if(markerDefaultIcon){	
                var image = {
                    url : markerDefaultIcon,
                    scaledSize: new google.maps.Size(32, 32)
                };             
                marker.setIcon(image);
            }	
			marker.setMap(map);
			allCircleMarkers.push(marker);

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
		
		allCircles.push(circle,circleHover);
		circle.setMap(map);
		
		//events
		(function(overlay, overlayHover, row, map, marker, overlayWindows) { 

            if(row.show_marker == 1 && row.enable_info_window == 1){
                 for(var j=0; j < infoWindows.length; j++){
                    infoWindows[j].open(null, null);
                }          
                var infoWindow = new google.maps.InfoWindow({
                    content: row.center_address,
					disableAutoPan: false
                });
                google.maps.event.addListener(marker, 'click', function() {
                    for(var j=0; j < overlayWindows.length; j++){
                        overlayWindows[j].open(null, null);
                    }  
                    infoWindow.open(map, marker);
                    overlayWindows.push(infoWindow);                    
                });
                
            }
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
                this.getMap().getDiv().removeAttribute('title');
				overlayHover.setMap(null);				
				overlay.setMap(map);				
			}); 		
		
		}(circle, circleHover, mapCircle, map, marker, infoWindows));	

	}

}

var allPolygons = [];
var allPolygonMarkers = [];
function gmwdSetMapPolygons(){
	var mapPolygon, polygon, polygonHover; 
    var polygonsByAreas = {};
    var polygonsAreas = [];
	for(var key in mapPolygons){
		var polygonCoord = [];
		mapPolygon = mapPolygons[key];
        if(mapPolygon.published == 0){
            continue;
        }
		polygonData = mapPolygon.data.substr(1, mapPolygon.data.length-4).split("),(");
		for(var j=0; j<polygonData.length; j++){
			var polygonMarker = polygonData[j].split(",");
			if(mapPolygon.show_markers == 1){										
                var marker = new google.maps.Marker({
                    map: map,
                    position: {lat: Number(polygonMarker[0]), lng: Number(polygonMarker[1]) }
                });
                if(markerDefaultIcon){	
                    var image = {
                        url : markerDefaultIcon,
                        scaledSize: new google.maps.Size(32, 32)
                    };             
                    marker.setIcon(image);
                }
                allPolygonMarkers.push(marker);
                if(mapPolygon.enable_info_windows == 1){  
                    showPoiInfoWindow(map, marker);
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
		allPolygons.push(polygon, polygonHover);  
        var polygonArea = google.maps.geometry.spherical.computeArea(polygon.getPath());
        polygonsByAreas[polygonArea] = [polygon,polygonHover]; 
        polygonsAreas.push(polygonArea);
		//polygon.setMap(map);
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
		
		}(polygon, polygonHover, mapPolygon, map));	       
        	
	}
    polygonsAreas.sort(function(a,b){return b - a});

    for(var i=0; i< polygonsAreas.length ; i++){
        polygonsByAreas[polygonsAreas[i]][0].setMap(map);
        polygonsByAreas[polygonsAreas[i]][0].setOptions({zIndex: i+1});
        polygonsByAreas[polygonsAreas[i]][1].setOptions({zIndex: i+1});
     
    }
}

var allRectangles = [];
var allRectangleMarkers = [];
function gmwdSetMapRectangles(){
	var mapRectangle, rectangle, rectangleHover,bounds; 

	for(var key in mapRectangles){
		var bounds = [];
		mapRectangle = mapRectangles[key];
        if(mapRectangle.published == 0){
            continue;
        }
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
                    map: map		
                });
                if(markerDefaultIcon){	
                    var image = {
                        url : markerDefaultIcon,
                        scaledSize: new google.maps.Size(32, 32)
                    };             
                    marker.setIcon(image);
                }
                if(mapRectangle.enable_info_windows == 1){  
                    showPoiInfoWindow(map, marker);
                }                
                allRectangleMarkers.push(marker);
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
		allRectangles.push(rectangle, rectangleHover);
		rectangle.setMap(map);
        
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
		
		}(rectangle, rectangleHover, mapRectangle, map));	       
		
	}
}

var allPolylines = [];
var allPolylineMarkers = [];
function gmwdSetMapPolylines(){
	var mapPolyline, polyline, polylineHover; 

	for(var key in mapPolylines){
		var polylineCoord = [];
		mapPolyline = mapPolylines[key];
        if(mapPolyline.published == 0){
            continue;
        }
		polylineData = mapPolyline.data.substr(1, mapPolyline.data.length-4).split("),(");
		for(var j=0; j<polylineData.length; j++){
			var polylineMarker = polylineData[j].split(",");
			if(mapPolyline.show_markers == 1){										
                var marker = new google.maps.Marker({
                    map: map,
                    position: {lat: Number(polylineMarker[0]), lng: Number(polylineMarker[1]) }
                });
                if(markerDefaultIcon){	
                    var image = {
                        url : markerDefaultIcon,
                        scaledSize: new google.maps.Size(32, 32)
                    };             
                    marker.setIcon(image);
                }                   
                allPolylineMarkers.push(marker);

			}
            if(mapPolyline.enable_info_windows == 1){  
                showPoiInfoWindow(map, marker);
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
		allPolylines.push(polyline, polylineHover );
		polyline.setMap(map);
        
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
		
		}(polyline, polylineHover, mapPolyline, map));	  
 					
			
	}

}


function showPoiInfoWindow(map, marker){
   (function(marker, map) { 
        var geocoder = new google.maps.Geocoder();            
        geocoder.geocode({       
            latLng: marker.getPosition()     
        }, 
        function(responses) {  
           if (responses && responses.length > 0) {
               for(var j=0; j < infoWindows.length; j++){
                    infoWindows[j].open(null, null);
                } 
                var infoWindow = new google.maps.InfoWindow({
                    content: responses[0].formatted_address,
					disableAutoPan: false					
                });  
                google.maps.event.addListener(marker, 'click', function() {
                   for(var j=0; j < infoWindows.length; j++){
                        infoWindows[j].open(null, null);
                    } 
                    infoWindow.open(map, marker);
                    infoWindows.push(infoWindow);                    
                });                        
           }  
        }); 
    }(marker, map));     
}

////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////


function gmwdSetMapTypeId(){
	switch(mapType){
		case "0" :
			map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
			break;
		case "1" :	
			map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
			break;
		case "2":	
			map.setMapTypeId(google.maps.MapTypeId.HYBRID);
			break;
		case "3":		
			map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
			break;				
	}
}


function gmwdSetLayers(type){
	switch(type){
		case "bike" :
			if(enableBykeLayer == 1){
				 bikeLayer = new google.maps.BicyclingLayer();
				 bikeLayer.setMap(map);
			 }
			 else{
				if(bikeLayer){
					bikeLayer.setMap(null);
				}
			 }
			 break;
		case "traffic" :	 
			if(enableTrafficLayer == 1){
				trafficLayer = new google.maps.TrafficLayer();
				trafficLayer.setMap(map);
			 }
			 else{
				if(trafficLayer){
					trafficLayer.setMap(null);
				}
			 }	
			 break;	
		case "transit" :	 
			if(enableTransitLayer == 1){
				transitLayer = new google.maps.TransitLayer();
				transitLayer.setMap(map);
			 }
			 else{
				if(transitLayer){
					transitLayer.setMap(null);
				}
			 }	
			 break;					 
	}
}

function gmwdSetGeoRSSURL(){
	if(geoRSSURL){
		georssLayer = new google.maps.KmlLayer({
			url: geoRSSURL
		  });
		georssLayer.setMap(map);
	}
	else{
		if(georssLayer){
			georssLayer.setMap(null);
		}
	}
}

function gmwdSetKMLURL(){
	if(KMLURL){
	  ctaLayer = new google.maps.KmlLayer({
		url: KMLURL			
	  });
		ctaLayer.setMap(map);
	}
	else{
		if(ctaLayer){
			ctaLayer.setMap(null);
		}
	}
}

function gmwdSetFusionTableId(){
	if(fusionTableId){
      if(fusionTablesLayer){
        fusionTablesLayer.setMap(null);
      } 
      
      var fusionTableWhere = (fusionTableWhereField && fusionTableWhereOperator && fusionTableWhereValue) ? "'" + fusionTableWhereField.trim() + "' " + fusionTableWhereOperator + ((fusionTableWhereOperator != "IN") ? " '" + fusionTableWhereValue.trim() + "'" : " " + fusionTableWhereValue.trim()) : '';  
      
	  fusionTablesLayer = new google.maps.FusionTablesLayer({
		query: {
		  select: '\'Geocodable address\'',
		  from: fusionTableId,
          where: fusionTableWhere
		}
	  });
	  fusionTablesLayer.setMap(map);
	}
	else{
		if(fusionTablesLayer){
			fusionTablesLayer.setMap(null);
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