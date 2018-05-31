////////////////////////////////////////////////////////////////////////////////////////
// Events                                                                             //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Constants                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Variables                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
var frontendData = [];
var cnterLat, cnterLng;
var ajaxData = {};
 
////////////////////////////////////////////////////////////////////////////////////////
// Constructor & Destructor                                                           //
////////////////////////////////////////////////////////////////////////////////////////

function gmwdReadyFunction(key){
    // serach box
    if(gmwdmapData["enableSerchBox" + key] == 1){
        initSerachBox(key);
    }

    // geolocate user 
    if(gmwdmapData["geolocateUser" + key] == 1){     
        geoLocateUser(key);
    }
    
    // category filter
    if(gmwdmapData["enableCategoryFilter" + key] == 1){

		jQuery(".gmwd_open_filter" + key).click(function(){
            jQuery(this).closest(".gmwd_categories_container").find(".gmwd_cat_dropdown").slideToggle("fast");

        });
    }

     // category filter inside map
    if(gmwdmapData["enableCategoryFilterInsideMap" + key] == 1){
        var insideMapCats = document.createElement("div");                                 
        insideMapCats.innerHTML = '<div class="gmwd_cat_inside_map">' + markerCategoriesTree + '</div>';

        gmwdmapData["main_map" + key].controls[gmwdmapData["categoriesFilterPosition" + key]].push(insideMapCats);
        
        insideMapCats.addEventListener('click', function() {
           
        });             
    } 
      
	// directions 
	if(gmwdmapData["enableDirections" + key] == 1){
		
		var inputForm = /** @type {!HTMLInputElement} */(
		  document.getElementById('gmwd_form' + key));

		var autocompleteForm = new google.maps.places.Autocomplete(inputForm);
		autocompleteForm.bindTo('bounds', gmwdmapData["main_map" + key]);
		
		var inputTo = /** @type {!HTMLInputElement} */(
		  document.getElementById('gmwd_to' + key));

		var autocompleteTo = new google.maps.places.Autocomplete(inputTo);
		autocompleteTo.bindTo('bounds', gmwdmapData["main_map" + key]);

		jQuery("#gmwd_directions_go" + key).click(function(){	
			setDirections(key);
			return false;		
		});
		
		/*jQuery("#gmwd_direction_mode" + key).change(function(){
			setDirections(key);	
			return false;
		});*/
		
		jQuery(".gmwd_my_location" + key).click(function(){
			var input = jQuery("#" + jQuery(this).attr("data-for") + key);	
            getMyLocation(input);
		});
	}
	
	//marker listing
 
    if(gmwdmapData["widget" + key] == 0){

        if(gmwdmapData["markerListingType" + key] == 2){
            jQuery(document).on("click",".gmwd_marker_advanced_row" + key,function(){
                jQuery(".gmwd_advanced_markers_tbody" + key + " .wd-table-row").removeClass("gmwd_marker_listing_advanced_active");
                jQuery(this).addClass("gmwd_marker_listing_advanced_active");
                
                jQuery(".gmwd_advanced_info_window" + key).remove();   
                findMarker(this);
            });	
            
            jQuery(document).on("change","#gmwd_search" + key,function(){
                var searchedVal = jQuery(this).val();
                gmwdSearch("search", searchedVal, key);
            });	

        }	
        else if(gmwdmapData["markerListingType" + key] == 3 ){
            gmwdMarkerCarousel(key);	
        }
    }
    // move marker list inside map
    if(gmwdmapData["markerListInsideMap" + key] == 1 ){
        gmwdMarkerListInsideMap(key, gmwdmapData["mapMarkers" + key]);        
    } 

	//store locator
	if(gmwdmapData["enableStoreLocatior" + key] == 1 && gmwdmapData["widget" + key] == 0){
    
        jQuery(".gmwd_my_location_store_locator" + key).click(function(){
			var input = jQuery("#gmwd_store_locator_address"  + key);	
            getMyLocation(input);
		});
        
		var input = /** @type {!HTMLInputElement} */(
		  document.getElementById('gmwd_store_locator_address' + key));

		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.bindTo('bounds', gmwdmapData["main_map" + key]);
		
		/*autocomplete.addListener('place_changed', function() {
			var place = autocomplete.getPlace();
			if (!place.geometry) {
				window.alert("Autocomplete's returned place contains no geometry");
				return;
			}           
			cnterLat = place.geometry.location.lat(); 
			cnterLng = place.geometry.location.lng();
		});*/
			
		jQuery("#gmwd_store_locator_search" + key).click(function(){
            if(jQuery(".gmwd_store_locator_address" + key).val() == ""){
                alert("Please set location.");
                return false;
            }
			if(gmwdmapDataOptions["storeLocatorCircle" + key]){
				gmwdmapDataOptions["storeLocatorCircle" + key].setMap(null);
			}
			if(gmwdmapData["storeLocatorDistanceIn" + key] == "km"){
				var radius = Number(jQuery("#gmwd_store_locator_radius" + key + " :selected").val())*1000;
			}
			else if(gmwdmapData["storeLocatorDistanceIn" + key] == "mile"){
				var radius = Number(jQuery("#gmwd_store_locator_radius" + key + " :selected").val())*1609.34;
			}
            
            var categories = [];
            jQuery(".gmwd_marker_categories" + key + ":checked").each(function(){
                categories.push(jQuery(this).val());
            });        
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                "address": jQuery(".gmwd_store_locator_address" + key).val()
            }, function(results) {
                cnterLat = results[0].geometry.location.lat();             
                cnterLng = results[0].geometry.location.lng();  

                gmwdmapData["ajaxData" + key]["map_id"] = gmwdmapData["mapId" + key];
                gmwdmapData["ajaxData" + key]["page"] = "map";
                gmwdmapData["ajaxData" + key]["action"] = "get_ajax_store_loactor";
                gmwdmapData["ajaxData" + key]["task"] = "get_ajax_store_loactor";
                gmwdmapData["ajaxData" + key]["radius"] = Number(jQuery("#gmwd_store_locator_radius" + key + " :selected").val());
                gmwdmapData["ajaxData" + key]["lat"] = cnterLat;
                gmwdmapData["ajaxData" + key]["lng"] = cnterLng;
                gmwdmapData["ajaxData" + key]["distance_in"] = gmwdmapData["storeLocatorDistanceIn" + key];
                gmwdmapData["ajaxData" + key]["categories"] = categories;
     
                jQuery.post(ajaxURL, gmwdmapData["ajaxData" + key], function (response){
         
                    gmwdmapData["mapMarkers" + key] = JSON.parse(response);
                    for(var i=0; i<gmwdmapData["allMarkers" + key].length; i++){
                        gmwdmapData["allMarkers" + key][i].setMap(null);
                    }
                    gmwdmapData["allMarkers" + key] = [];
                    gmwdSetMapMarkers(key);
                    if(gmwdmapData["markerListInsideMap" + key] == 1){
                        gmwdMarkerListInsideMap(key, gmwdmapData["mapMarkers" + key]); 
                    }
                });
        
                
                gmwdmapData["ajaxData" + key]["action"] = "store_locator_filter";
                gmwdmapData["ajaxData" + key]["task"] = "";
               
                jQuery.post(window.location, gmwdmapData["ajaxData" + key], function (response){        
                    var markerListsing = jQuery(response).find('.gmwd_markers_data' + key).html();
                    
                    jQuery('.gmwd_markers_data' + key).html((markerListsing || ""));
                    if(gmwdmapData["markerListingType" + key] == 3){
                        gmwdMarkerCarousel(key);
                    }
                }); 
                
                gmwdmapDataOptions["storeLocatorCircle" + key] = new google.maps.Circle({
                    strokeWeight: gmwdmapData["storeLocatorStrokeWidth" + key],
                    strokeColor: gmwdmapData["storeLocatorLineColor" + key],
                    strokeOpacity: gmwdmapData["storeLocatorLineOpacity" + key],
                    fillColor: gmwdmapData["storeLocatorFillColor" + key],
                    fillOpacity: gmwdmapData["storeLocatorFillOpacity" + key],
                    center: {lat : cnterLat, lng: cnterLng},
                    radius: radius
                });
                
                gmwdmapDataOptions["storeLocatorCircle" + key].setMap(gmwdmapData["main_map" + key]);     
               
                gmwdmapData["main_map" + key].setCenter({lat : cnterLat, lng: cnterLng});                
                gmwdmapData["main_map" + key].setZoom(Number(gmwdmapData["zoom" + key]));                
            });
   
			return false;	
		
		});
		
		jQuery("#gmwd_store_locator_reset" + key).click(function(){
			if(gmwdmapDataOptions["storeLocatorCircle" + key]){
				gmwdmapDataOptions["storeLocatorCircle" + key].setMap(null);
			}
			gmwdmapData["ajaxData" + key] = {};
			gmwdmapData["ajaxData" + key]["map_id"] = gmwdmapData["mapId" + key];
			gmwdmapData["ajaxData" + key]["page"] = "map";
			gmwdmapData["ajaxData" + key]["action"] = "get_ajax_markers";
			gmwdmapData["ajaxData" + key]["task"] = "get_ajax_markers";
            
			jQuery.post(ajaxURL, gmwdmapData["ajaxData" + key], function (data){
				gmwdmapData["mapMarkers" + key] = JSON.parse(data);
				for(var i=0; i<gmwdmapData["allMarkers" + key].length; i++){
					gmwdmapData["allMarkers" + key][i].setMap(null);
				}
				gmwdmapData["allMarkers" + key] = [];
				gmwdSetMapMarkers(key);
				if(gmwdmapData["markerListInsideMap" + key] == 1){
					gmwdMarkerListInsideMap(key, gmwdmapData["mapMarkers" + key]);
				}
			});
			           
            gmwdmapData["ajaxData" + key]["action"] = "";
			gmwdmapData["ajaxData" + key]["task"] = "";
            
            jQuery.post(window.location, gmwdmapData["ajaxData" + key], function (response){            	
				var markerListsing = jQuery(response).find('.gmwd_markers_data' + key).html();
				jQuery('.gmwd_markers_data' + key).html(markerListsing);
                if(gmwdmapData["markerListingType" + key] == 3){
                    gmwdMarkerCarousel(key);
                }
				jQuery(".gmwd_marker_categories" + key).removeAttr("checked");
			}); 
            

			jQuery("#gmwd_store_locator_address" + key).val("");
			return false;	
		});		
	
	}
}	
////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////
function showDirectionsBox(obj){
    var key = jQuery(obj).attr("data-key");
	jQuery(".gmwd_directions_container" + key).show();
	var address = jQuery(obj).attr("data-address") ? jQuery(obj).attr("data-address") : "";

	if(!address){
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({       
			latLng: new google.maps.LatLng(Number(jQuery(obj).attr("data-lat")), Number(jQuery(obj).attr("data-lng")))     
		}, 
		function(responses) {  
		   if (responses && responses.length > 0) {        
			   jQuery(".gmwd_directions_container" + key + " #gmwd_to" + key ).val(responses[0].formatted_address);              
		   }  
		});
	}
	else{
		jQuery(".gmwd_directions_container" + key + " #gmwd_to" + key ).val(address);
	}
	
	var position = jQuery(".gmwd_directions_container" + key).offset();

	jQuery('html,body').animate({
		scrollTop: position.top
	});
	return false;	
}

function setDirections(key){
	if(frontendData["directionsDisplay" + key]){
		frontendData["directionsDisplay" + key].setMap(null);
		jQuery('#gmwd_directions_panel' + key).html("");
	}
	frontendData["directionsService" + key] = new google.maps.DirectionsService;
	frontendData["directionsDisplay" + key] = new google.maps.DirectionsRenderer;
	frontendData["directionsDisplay" + key].setPanel(document.getElementById('gmwd_directions_panel' + key));
	frontendData["directionsDisplay" + key].setMap(gmwdmapData["main_map" + key]);
	
	var selectedMode = jQuery("#gmwd_direction_mode" + key + " :selected").val();
	var avoidHighways = jQuery(".gmwd_direction_avoid_highways" + key).is(":checked") ? true : false;
	var avoidTolls = jQuery(".gmwd_direction_avoid_tolls" + key).is(":checked") ? true : false;
	
	frontendData["directionsService" + key] .route({
		origin: jQuery(".gmwd_direction_from" + key).val(),
		destination: jQuery(".gmwd_direction_to" + key).val(),
		travelMode: google.maps.TravelMode[selectedMode],
		avoidHighways: avoidHighways,
		avoidTolls: avoidTolls
		
	}, function(response, status) {
		if (status === google.maps.DirectionsStatus.OK) {
			frontendData["directionsDisplay" + key].setDirections(response);
		} else {
			window.alert('Directions request failed.');
		}
	});
	
}

function findMarker(obj){
	var lat = Number(jQuery(obj).attr("data-lat"));
	var lng = Number(jQuery(obj).attr("data-lng"));
	var key = jQuery(obj).attr("data-shortcode");


	gmwdmapData["main_map" + key].setCenter({lat: lat, lng: lng});
	gmwdmapData["main_map" + key].setZoom(gmwdmapData["zoom" + key] + 2);
	
	var marker__ = new google.maps.Marker({
		position: {lat: lat, lng: lng}
	});
    for(var i=0; i< gmwdmapData["allMarkers" + key].length; i++){
        var marker = gmwdmapData["allMarkers" + key][i];
        if(marker.position.lat() == marker__.position.lat()  && marker.position.lng() ==  marker__.position.lng() ){
            var searchedMarker =  marker;
            break;
        }
    }

	mapMarker =  gmwdmapData["mapMarkers" + key][jQuery(obj).attr("data-id")];
    
    if(gmwdmapData["infoWindowtype" + key] == 1){
        gmwdAdvancedInfoWindow(mapMarker, key);
    }
    else{
        for(var j=0; j < gmwdmapData["infoWindows" + key].length; j++){
            gmwdmapData["infoWindows" + key][j].open(null, null);
        }
		contentString = '';
		contentString += '<div style="float:left;max-width: 160px;">';
		
		if(gmwdmapData["infoWindowInfo" + key].indexOf("title") != -1){
			contentString += '<div class="gmwd-infowindow-title">' + mapMarker.title + '</div>';
		}
		if(gmwdmapData["infoWindowInfo" + key].indexOf("address") != -1){
			contentString += '<div class="gmwd-infowindow-address">' +  mapMarker.address + '</div>';
		} 
		if(gmwdmapData["infoWindowInfo" + key].indexOf("desc") != -1){
			var description = typeof mapMarker.description == "object" ? decodeEntities(mapMarker.description.join("<br>")) : decodeEntities(mapMarker.description);
			contentString += "<div class='gmwd-infowindow-description'>" + description + "</div>";
		} 
		contentString += '</div>'; 
		if(mapMarker.pic_url && gmwdmapData["infoWindowInfo" + key].indexOf("pic") != -1){
			contentString =  '<img src="' + mapMarker.pic_url + '"  style="float:right; margin-left: 10px; max-width:100px!important">';
		}				
            
        if(gmwdmapData["enableDirections" + key] == 1 ){
            contentString += "<div style='clear:both;'> <a href='javascript:void(0)' data-lat='" + Number(mapMarker.lat) + "' data-lng='" + Number(mapMarker.lng) + "' data-address='" + mapMarker.address + "' class='gmwd_direction' data-key='" + key + "' onclick='showDirectionsBox(this);'>Get Directions</a></div>";
        }
        infoWindow = new google.maps.InfoWindow({
            content: contentString
        });		

        infoWindow.open(gmwdmapData["main_map" + key], searchedMarker);
        gmwdmapData["infoWindows" + key].push(infoWindow);
    }

    var position = jQuery("#wd-map" + key).offset();

	jQuery('html,body').animate({
		scrollTop: position.top
	});

}

function gmwdMarkerOrder(field, key){
	var orderDir = jQuery("#orderDir" + key).val();
	if(orderDir == "ASC"){
		orderDir = "DESC";
	}
	else{
		orderDir = "ASC";
	}
	jQuery("#orderDir" + key).val(orderDir);
	
	gmwdmapData["ajaxData" + key]["order_by"] = field;
	gmwdmapData["ajaxData" + key]["order_dir"] = orderDir;
	gmwdmapData["ajaxData" + key]["action"] = "";
    gmwdmapData["ajaxData" + key]["task"] = "";
	jQuery.post(window.location, gmwdmapData["ajaxData" + key], function (data){
		var result = jQuery(data).find('.gmwd_markers_data' + key).html();
        jQuery('.gmwd_markers_data' + key).html(result);
	});
	return false;
}

function gmwdPagination(event, obj){
    var limit = Number(jQuery(obj).attr("data-limit"));
    var key = jQuery(obj).attr("data-key")
    limit = limit + 20;

    gmwdmapData["ajaxData" + key]["limit"] = limit;
	gmwdmapData["ajaxData" + key]["action"] = "";
    gmwdmapData["ajaxData" + key]["task"] = "";
	jQuery.post(window.location,  gmwdmapData["ajaxData" + key], function (data){

		var result = jQuery(data).find('.gmwd_markers_data' + key).html();
        jQuery('.gmwd_markers_data' + key).html(result);
        var total = Number(jQuery('.gmwd_markers_data' + key + " .gmwd-pagination").attr("data-total"));
        jQuery('.gmwd_markers_data' + key + " .gmwd-pagination").attr("data-limit", limit);
        if(limit>=total ){				
            jQuery('.gmwd_markers_data' + key + " .gmwd-pagination").hide();
        }
	});
}
function onBasicRowClick(obj){
    jQuery(obj).closest(".gmwd_markers_basic_container").find(".gmwd_markers_basic_box").removeClass("gmwd_marker_listing_basic_active");
    jQuery(obj).closest(".gmwd_markers_basic_box").addClass("gmwd_marker_listing_basic_active");
    findMarker(jQuery(obj));

}
function gmwdMarkerCarousel(key){
    var markerCarousel = jQuery("#gmwd_marker_carousel" + key);

    markerCarousel.owlCarouselGMWD({
          items : gmwdmapData["items" + key], //10 items above 1000px browser width
          itemsDesktop : [1000,5], //5 items between 1000px and 901px
          itemsDesktopSmall : [900,3], // betweem 900px and 601px
          itemsTablet: [600,2], //2 items between 600 and 0
          itemsMobile : false // itemsMobile disabled - inherit from itemsTablet option
    });

    // Custom Navigation Events
    jQuery(".next" + key).click(function(){
        markerCarousel.trigger('owl.next');
    });
    jQuery(".prev" + key).click(function(){
        markerCarousel.trigger('owl.prev');
    });
    
    jQuery(".gmwd_marker_carousel_box" + key).click(function(){
        markerCarousel.find(".owl-item .gmwd_marker_carousel_box").removeClass("gmwd_carousel_active");
        jQuery(this).addClass("gmwd_carousel_active");
        findMarker(jQuery(this));
    });

}

function gmwdSearch(fieldName, values, key ){
    gmwdmapData["ajaxData" + key]["page"] = "map";
    gmwdmapData["ajaxData" + key][fieldName] = values;
    gmwdmapData["ajaxData" + key]["action"] = "";
    gmwdmapData["ajaxData" + key]["task"] = "";
    
    jQuery.post(window.location, gmwdmapData["ajaxData" + key], function (data){
        var markerListsing = jQuery(data).find('.gmwd_markers_data' + key).html();
        jQuery('.gmwd_markers_data' + key).html((markerListsing || ""));
    });
    
    gmwdmapData["ajaxData" + key]["map_id"] = gmwdmapData["mapId" + key];   
    gmwdmapData["ajaxData" + key]["action"] = "get_ajax_markers";
    gmwdmapData["ajaxData" + key]["task"] = "get_ajax_markers";
     
    jQuery.post(ajaxURL, gmwdmapData["ajaxData" + key], function (data){
        gmwdmapData["mapMarkers" + key] = JSON.parse(data);              
        for(var i=0; i<gmwdmapData["allMarkers" + key].length; i++){
            gmwdmapData["allMarkers" + key][i].setMap(null);
        }
        gmwdmapData["allMarkers" + key] = [];
        gmwdSetMapMarkers(key);
        if(gmwdmapData["markerListInsideMap" + key] == 1 ){
            gmwdMarkerListInsideMap(key, gmwdmapData["mapMarkers" + key]);
        }
        if(gmwdmapData["markerListingType" + key] == 3){
            gmwdMarkerCarousel(key);
        }
    });        

}
function gmwdMarkerListInsideMap(key, mapMarkers){
	if(jQuery(".gmwd_marker_list_inside_map" + key).length > 0){
		jQuery(".gmwd_marker_list_inside_map" + key).remove();
	}

    if(Object.keys(mapMarkers).length != 0){
        var markerList = document.createElement("div");
        markerList.setAttribute("class","gmwd_marker_list_inside_map gmwd_marker_list_inside_map" + key);  
        markerRows = "";
        for(var j in mapMarkers){
            var marker = mapMarkers[j];
            markerRows += "<div data-lat='" + marker.lat + "' data-lng='" + marker.lng + "' data-id='" + marker.id + "'  data-shortcode='" + key + "' onclick='findMarker(this);'><img src='" + GMWD_URL + "/images/marker.png'> " + (marker.title || marker.address) + "</div>";  	
        }        
        markerList.innerHTML = markerRows;  
        gmwdmapData["markerListPosition" + key] =  gmwdmapData["markerListPosition" + key] == 0 ? 5 : gmwdmapData["markerListPosition" + key];
        gmwdmapData["main_map" + key].controls[gmwdmapData["markerListPosition" + key]].push(markerList);
    }
}

function geoLocateUser(key){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {      
          var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
          
          var marker = new google.maps.Marker({
                map: gmwdmapData["main_map" + key],
                position: latlng,
                icon: GMWD_URL + '/images/geoloc.png'
          });
          var geocoder = new google.maps.Geocoder();
            geocoder.geocode({"latLng":latlng},function(data,status){	 
                if(status == google.maps.GeocoderStatus.OK){	            
                    var address = data[1].formatted_address; 
                    
                    gmwdmapData["main_map" + key].setCenter(latlng);
                    gmwdmapData["main_map" + key].setZoom(13);
                    var infoWindow = new google.maps.InfoWindow({map: gmwdmapData["main_map" + key]});		
                    infoWindow.setPosition(latlng);
                    var contentString = address; 
                    if(gmwdmapData["enableDirections" + key] == 1 ){
                        contentString += "<br> <a href='javascript:void(0)' data-lat='" + position.coords.latitude + "' data-lng='" +Number(position.coords.longitude) + "' data-address='" + address + "' class='gmwd_direction' onclick='showDirectionsBox(this);' data-key='" + key + "'>Get Directions</a>";
                    }
                    infoWindow.setContent(contentString);
                }
           });
	
        }, function() {
          alert('Error: Your browser doesn\'t support geolocation.');
        });
    } 
    else {
        // Browser doesn't support Geolocation
        alert('Error: Your browser doesn\'t support geolocation.');
    }
}
function getMyLocation(input){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({"latLng":latlng},function(data,status){	 
                if(status == google.maps.GeocoderStatus.OK){	            
                    var address = data[1].formatted_address; 
                    input.val(address);
                    cnterLat = data[1].geometry.location.lat(); 
                    cnterLng = data[1].geometry.location.lng();                    
                }
            });
        });
    }
  else{
      alert("Browser doesn't support Geolocation.");
  }	   

}

function gmwdCategoryFilter(key){
	var ctaegoryIds = [];
	var selectedCats = "";
	jQuery(".gmwd_marker_cat" + key + ":checked").each(function(){
		ctaegoryIds.push(jQuery(this).val());
		selectedCats += "<span class='gmwd_selected' data-id='" + jQuery(this).val() + "'><span>" + jQuery(this).closest(".gmwd_category").find("label").html() + "</span><span class='gmwd_remove_selected'> X </span></span> ";
	});
	if(ctaegoryIds.length == 0){
		jQuery(".gmwd_category_selected_cats" + key).html("&nbsp;Filter By Category");
	}
	else{
		jQuery(".gmwd_category_selected_cats" + key).html(selectedCats);
	}
	jQuery(".gmwd_remove_selected").click(function(){
		gmwdRemoveSelectedCategory(this, key);
	});
	gmwdSearch("categories", ctaegoryIds, key);
}

function gmwdRemoveSelectedCategory(obj, key){
	jQuery(obj).closest('.gmwd_selected').remove();
	var catId = jQuery(obj).closest('.gmwd_selected').attr("data-id");
	jQuery('.gmwd_categories_wrapper'+ key).find("input[value=" + catId + "]").removeAttr("checked");
	gmwdCategoryFilter(key);
}


function gmwdAdvancedInfoWindow(mapMarker, key){

    jQuery(".gmwd_advanced_info_window" + key).remove();
    var advancedInfoWindow = document.createElement("div");
    advancedInfoWindow.setAttribute("class","gmwd_advanced_info_window" + key);
    var infoWindowInfo = gmwdmapData["infoWindowInfo" + key];
    var advancedInfoWindowInnerHTML = '<div style="text-align:right;cursor:pointer; right:2px" onclick="jQuery(this).parent().remove();">X</div>';
    if(infoWindowInfo.indexOf("title") != -1){
        advancedInfoWindowInnerHTML +=  '<div class="gmwd_advanced_info_window_title' + key + '">' + mapMarker.title + '</div>' ;
    } 
    if(infoWindowInfo.indexOf("address") != -1){
        advancedInfoWindowInnerHTML += '<div class="gmwd_advanced_info_window_address' + key + '">' + mapMarker.address + '</div>';
    }     
    advancedInfoWindowInnerHTML +=  '<div class="gmwd_advanced_info_window_description' + key + ' wd-clear">';		
                          
    if(mapMarker.pic_url && infoWindowInfo.indexOf("pic") != -1){
        advancedInfoWindowInnerHTML += '<img src="' + mapMarker.pic_url + '"  style="float:right;margin-left: 7px; max-width:100px!important">';
    }
    if(mapMarker.description && infoWindowInfo.indexOf("desc") != -1){
        advancedInfoWindowInnerHTML += decodeEntities(mapMarker.description.join("<br>"));
    }
    advancedInfoWindowInnerHTML += '</div>';
    if(gmwdmapData["enableDirections" + key] == 1){
        advancedInfoWindowInnerHTML +=	'<div class="gmwd_advanced_info_window_directions' + key + '">' +
                                    '<a href="javascript:void(0)" data-lat="' +  Number(mapMarker.lat) + '" data-lng="' +  Number(mapMarker.lng) + '" data-address="' +  mapMarker.address + '" data-key="' + key + '" class="gmwd_direction" onclick="showDirectionsBox(this);" >Directions</a>' + 
                                '</div>';
    }

    advancedInfoWindow.innerHTML = advancedInfoWindowInnerHTML;

    gmwdmapData["main_map" + key].controls[gmwdmapData["advancedInfoWindowPosition" + key]].push(advancedInfoWindow);
}

function initSerachBox(key){
    var input = document.createElement("input");
    input.id = "gmwd_serach_box" + key;
    input.type = "text";
    input.style.cssText = "width:400px;";
    input.setAttribute("onkeypress", "if(event.keyCode == 13) return false;") ;
    input.setAttribute("class", "gmwd_serach_box") ;

    searchBox = new google.maps.places.SearchBox(input);
    gmwdmapData["main_map" + key].controls[gmwdmapData["serchBoxPosition" + key]].push(input);
    
    gmwdmapData["main_map" + key].addListener('bounds_changed', function() {
        searchBox.setBounds( gmwdmapData["main_map" + key].getBounds());
    });
 
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
         gmwdmapData["main_map" + key].fitBounds(bounds);
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