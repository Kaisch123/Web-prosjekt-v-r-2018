////////////////////////////////////////////////////////////////////////////////////////
// Events                                                                             //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Constants                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Variables                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
var stylesArray = [];
var map;
var currentStyle = {};                        
////////////////////////////////////////////////////////////////////////////////////////
// Constructor & Destructor                                                           //
////////////////////////////////////////////////////////////////////////////////////////

jQuery( document ).ready(function() {
   
    jQuery(".gmwd").tooltip();
    jQuery("#wd-map_styles-map").css("border-radius", Number(jQuery("#map_border_radius").val()) + "px");
	jQuery(".wd-themes-tabs li a").click(function(){
		jQuery(".wd-themes-tabs-container .wd-themes-container").hide();
		jQuery(".wd-themes-tabs li a").removeClass("wd-btn-primary");
		jQuery(jQuery(this).attr("href")).show();
		jQuery(this).addClass("wd-btn-primary");
        jQuery("#active_tab").val(jQuery(this).attr("href"));
		return false;
	});
    jQuery("#auto_generate_style_code").change(function(){
        if(jQuery(this).is(":checked") == true){
            jQuery("#map_style_code").attr("readonly","readonly");
        }
        else{
            jQuery("#map_style_code").removeAttr("readonly");
        }
    });
	
    if(jQuery("#gmwd_marker_carousel").length>0){
        initCarousel();
    }
	jQuery("#carousel_items_count").change(function(){
       jQuery("#gmwd_marker_carousel").data('owlCarousel').destroy();
       initCarousel();
    });
    
    // map styles
    if(jQuery("#wd-map_styles-map").length > 0){
        map = new google.maps.Map(document.getElementById("wd-map_styles-map"), {
            center: {lat: centerLat, lng: centerLng},		
            zoom: zoom,
            scrollwheel: mapWhellScrolling,
            draggable: mapDragable       
        });
        map.setOptions({styles:eval(styles)});
	}
    gmwdMapStyleEvents();
   
    jQuery(".gmwd_add_style").click(function(){       
        onBtnClickStyleImg(null);
	});

	jQuery("#map_style_code").blur(function(){
        var newStyles = JSON.parse(jQuery(this).val());
        jQuery(".map_theme_img_active").closest(".wd-left").find(".gmwd_map_style_code").val(jQuery(this).val());
        onBtnClickStyleImg(jQuery(".map_theme_img_active"));
        changeStaticMap(newStyles);
       
	});
	
	jQuery("#map_border_radius").blur(function(){
		mapBorderRadius = jQuery(this).val();
		jQuery("#wd-map_styles-map").css("border-radius", mapBorderRadius + "px");		
	});
});

////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////

function initCarousel(){
    var markerCarousel = jQuery("#gmwd_marker_carousel");
    markerCarousel.owlCarouselGMWD({
          items : Number(jQuery("#carousel_items_count").val()), 
          itemsDesktop : [1000,5], 
          itemsDesktopSmall : [900,3], 
          itemsTablet: [600,2], 
          itemsMobile : false
    });

    // Custom Navigation Events
    jQuery(".next").click(function(){
        markerCarousel.trigger('owl.next');
    });
    jQuery(".prev").click(function(){
        markerCarousel.trigger('owl.prev');
    });

}

function changeStaticMap(styles_array){
    var static_map_url = "";
 
    for(var key in styles_array ){
        var style = styles_array[key];
        style.featureType = typeof style.featureType != "undefined" ? style.featureType : "all";
        style.elementType = typeof style.elementType != "undefined" ? style.elementType : "all";
        static_map_url += "&style=feature:" + style.featureType + "|element:" + style.elementType;
        if(typeof style.stylers != "undefined"){
            var stylers_ = style.stylers;
            for(var k in stylers_){
                var styler = stylers_[k];
                for(var index in styler){
                    var val = styler[index];
                    val = typeof val == "string" ? val.replace("#","0x") : val;
                    static_map_url += "|" + index + ":" + val;
                }
            }
        }
    }
    var APIKey_ = APIKey ? "key=" + APIKey + "&" : "";
    jQuery(".map_theme_img_active").attr("src", 'http://maps.googleapis.com/maps/api/staticmap?' + APIKey_ +'size=600x300&zoom=13&center=static_map_url' + centerAddress + static_map_url);
}

function onBtnClickStyleImg(obj){

    var style_id = obj == null ? 0 : obj.next("[name=map_style_id_radio]").val();
    var map_style_code = obj == null ? '[]' : obj.closest(".wd-left").find(".gmwd_map_style_code").val();
    var data = {
        "style_code" : map_style_code
    };
    data.nonce_gmwd = nonce;
    jQuery(".gmwd_opacity_div").show();
    jQuery.post(window.location, data, function (response){        
        var editMapStyle = jQuery(response).find('.edit_map_style .wd-left').html();     
        jQuery('.edit_map_style .wd-left').html(editMapStyle);
        jscolor.init();
        gmwdMapStyleEvents();
        jQuery("[name=map_style_id_radio]").removeAttr("checked");
        jQuery(".map_theme_img").removeClass("map_theme_img_active");
        if(obj != null){
            obj.next("[name=map_style_id_radio]").attr("checked", "checked");          
            obj.addClass("map_theme_img_active");
            jQuery(".edit-map-style").html("Edit Map Style");
        }
        else{
             jQuery(".edit-map-style").html("Add New Map Style");
             var newId =  "tmp_" + Number(jQuery(".static-maps .wd-left").length) + 1;   
             var APIKey_ = APIKey ? "key=" + APIKey + "&" : "";
             var newStyle = '<div class="wd-left">' +
                                '<img src="http://maps.googleapis.com/maps/api/staticmap?' + APIKey_ + 'size=600x300&zoom=13&center=' +  centerAddress + '" class="map_theme_img map_theme_img_active" onclick="onBtnClickStyleImg(jQuery(this));">' +
                                '<input type="radio" name="map_style_id_radio" id="map_style_id' + newId + '" value="' + newId + '" checked >' +
                                '<input type="hidden" class="gmwd_map_style_code" value>' +
                            '</div>';
             jQuery(newStyle).insertBefore(".static-maps .gmwd_add_style_wrapper");  
        }
        jQuery("#map_style_code").val(map_style_code);
        map.setOptions({styles:eval(map_style_code)});        
        jQuery(".gmwd_opacity_div").hide();
    });
}
function gmwdMapStyleEvents(){
	jQuery("#featureType, #elementType, #color, #gamma, #hue, #lightness, #saturation, #weight, #visibility, [name=invert_lightness]").change(function(){
		gmwdMapSetStyles();
	});
    
    jQuery(".role_label").click(function(){
        jQuery(this).closest("tr").find(".hide_role").addClass("changed_role");
        jQuery(this).closest("tr").find(".hide_role").removeClass("hide_role");
        //gmwdMapSetStyles();   
    });
    
    jQuery(".default_role").click(function(){
        jQuery(this).closest("tr").find(".changed_role").addClass("hide_role");
        jQuery(this).closest("tr").find(".changed_role").removeClass("changed_role");
        gmwdMapSetStyles();        
    });    
    
}

function gmwdMapSetStyles(){
	stylesArray = [];
	jQuery(".gmwd_map_features .gmwd_map_feature").each(function(index){
		jQuery(this).attr("data-key", index);
		gmwdAddFeature(jQuery(this));
	});
	
	map.setOptions({styles:stylesArray});
    changeStaticMap(stylesArray);
	jQuery("#map_style_code").val(JSON.stringify(stylesArray));
	jQuery(".map_theme_img_active").closest(".wd-left").find(".gmwd_map_style_code").val(JSON.stringify(stylesArray));
}
function gmwdAddFeatureTemplate(){

	var newStyle = jQuery(".wd-template").clone();
	newStyle.removeClass("wd-template");
	jQuery(".gmwd_map_features").append(newStyle);
	jscolor.init();
	jQuery(".add-template").hide();
    gmwdMapStyleEvents();	
}
 
function gmwdAddSingleFeature(obj){
	jQuery(obj).closest(".gmwd_map_feature").find(".gmwd_map_feature_type").removeClass("hide");
	jQuery(obj).closest(".gmwd_map_feature").find(".map_styles").addClass("hide");
	jQuery(".add-template").show();
    gmwdAddFeature(jQuery(obj).closest(".gmwd_map_feature"), true);
	gmwdMapSetStyles();	
}
function gmwdSetStylesFromHidden(styleContainer, style){
    styleContainer.find(".feature_type").html(style.featureType);
    styleContainer.find(".element_type").html(style.elementType);
    styleContainer.find("#featureType option").removeAttr("selected");
    styleContainer.find("#featureType [value='" + style.featureType + "']").attr("selected","selected");
   
    styleContainer.find("#elementType option").removeAttr("selected");
    styleContainer.find("#elementType [value='" + style.elementType + "']").attr("selected","selected");
    var stylers_array_obj = typeof style.stylers == "undefined" ? [] : style.stylers;
    var stylers = {};
    for(var i in stylers_array_obj){
        var styler = stylers_array_obj[i];
        for(var key in styler){
            stylers[key] = styler[key];
        }
    }

    styleContainer.find(".wd-form-field").closest("td").removeClass("changed_role");
    styleContainer.find(".wd-form-field").closest("td").addClass("hide_role");
    if(typeof stylers.color != "undefined"){
        styleContainer.find("#color").val(stylers.color.substr(1));
        styleContainer.find("#color").attr("style","color:#000; background:" + stylers.color);
        
        styleContainer.find("#color").closest("td").removeClass("hide_role");
        styleContainer.find("#color").closest("td").addClass("changed_role");
    }
    if(typeof stylers.gamma != "undefined"){
        styleContainer.find("#gamma").val(stylers.gamma);
        styleContainer.find("#gamma").closest("td").removeClass("hide_role");
        styleContainer.find("#gamma").closest("td").addClass("changed_role");
    }    
    if(typeof stylers.hue != "undefined"){
        styleContainer.find("#hue").val(stylers.hue.substr(1));
        styleContainer.find("#hue").attr("style","color:#000; background:" + stylers.hue);
        styleContainer.find("#hue").closest("td").removeClass("hide_role");
        styleContainer.find("#hue").closest("td").addClass("changed_role");
    }
    if(typeof stylers.invert_lightness != "undefined"){
        styleContainer.find("[name=invert_lightness]").removeAttr("checked");
        styleContainer.find("[value=" + stylers.invert_lightness + "]").attr("checked");
        styleContainer.find("#hue").closest("td").removeClass("hide_role");
        styleContainer.find("#hue").closest("td").addClass("changed_role");
    }    
    if(typeof stylers.lightness != "undefined"){
        styleContainer.find("#lightness").val(stylers.lightness);
        styleContainer.find("#lightness").closest("td").removeClass("hide_role");
        styleContainer.find("#lightness").closest("td").addClass("changed_role");
    }  
    if(typeof stylers.saturation != "undefined"){
        styleContainer.find("#saturation").val(stylers.saturation);
        styleContainer.find("#saturation").closest("td").removeClass("hide_role");
        styleContainer.find("#saturation").closest("td").addClass("changed_role");
    }
    if(typeof stylers.visibility != "undefined"){
        styleContainer.find("#visibility option").removeAttr("selected");
        styleContainer.find("#visibility [value=" + stylers.visibility + "]").attr("selected","selected");
        styleContainer.find("#visibility").closest("td").removeClass("hide_role");
        styleContainer.find("#visibility").closest("td").addClass("changed_role");
    } 
    if(typeof stylers.weight != "undefined"){
        styleContainer.find("#weight").val(stylers.weight);
        styleContainer.find("#weight").closest("td").removeClass("hide_role");
        styleContainer.find("#weight").closest("td").addClass("changed_role");
    } 
}
function gmwdCancelFeature(obj){    
	var styleContainer = jQuery(obj).closest(".gmwd_map_feature");
    var style = styleContainer.find(".map_style").val();
    style = JSON.parse(style);
    
    gmwdSetStylesFromHidden(styleContainer, style);
    
	if(styleContainer.attr("data-key")){
		styleContainer.find(".map_styles").addClass("hide");
	}
	else{
		styleContainer.remove();
	}
	jQuery(".add-template").show();
    gmwdMapSetStyles();	
}
function gmwdRemoveFeature(obj){
	jQuery(obj).closest(".gmwd_map_feature").remove();
	gmwdMapSetStyles();
}
function gmwdEditFeature(obj){
	jQuery(obj).closest(".gmwd_map_feature").find(".map_styles").removeClass("hide");
}

function gmwdAddFeature(styleContainer, changeInputStyle){
    if(typeof changeInputStyle == "undefined"){
        changeInputStyle = false;
    }
	var featureType = styleContainer.find("#featureType :selected").val();
    
	var elementType = styleContainer.find("#elementType :selected").val();
	styleContainer.find(".feature_type").html(featureType);
	styleContainer.find(".element_type").html(elementType);
	var styles = {};
	styleContainer.find(".changed_role .wd-form-field").each(function(){
		var name = jQuery(this).attr("name");
		if(jQuery(this).is("input[type=radio]") ){	      
            styles[name] = jQuery("[name='" + name + "']:checked").val();						
		}
		else if(jQuery(this).is("select")){
			styles[name] = jQuery("[name='" + name + "'] :selected").val();
		}
		else {       
			styles[name] = jQuery(this).val();
		}
	});
	var stylers = [];
    if(typeof styles.color != "undefined"){
        stylers.push({color: "#" + styles.color});
    }
    if(typeof styles.gamma != "undefined"){
        stylers.push({gamma: Number(styles.gamma)});
    }
    if(typeof styles.hue != "undefined"){
        stylers.push({hue: "#" + styles.hue});
    }
    if(typeof styles.invert_lightness != "undefined"){
        stylers.push({invert_lightness: (styles.invert_lightness == 1 ? true : false)});
    }
    if(typeof styles.lightness != "undefined"){
        stylers.push({lightness: Number(styles.lightness)});
    }  
    if(typeof styles.saturation != "undefined"){
        stylers.push({saturation: Number(styles.saturation)});
    } 
    if(typeof styles.visibility != "undefined"){
        stylers.push({visibility: styles.visibility});
    } 
    if(typeof styles.weight != "undefined"){
        stylers.push({weight: Number(styles.weight)});
    }    
    
	var mapFetaureStyle = {
		featureType: featureType,
		elementType: elementType,
		stylers:stylers			
	};
    
    stylesArray.push(mapFetaureStyle);
    if(changeInputStyle == true){
        styleContainer.find(".map_style").val(JSON.stringify(mapFetaureStyle)); 
    }
    
}

////////////////////////////////////////////////////////////////////////////////////////
// Getters & Setters                                                                  //
////////////////////////////////////////////////////////////////////////////////////////
function fillInputStyles(){
    var allStyles = {};
    jQuery(".static-maps .wd-left:not(.gmwd_add_style_wrapper)").each(function(){
        var id = jQuery(this).find("[name=map_style_id_radio]").val();
        var value = jQuery(this).find(".gmwd_map_style_code").val();
        allStyles[id] = value;  
    });
    jQuery("#all_styles").val(JSON.stringify(allStyles));
    jQuery("#map_style_id").val(jQuery(".map_theme_img_active").next("[name=map_style_id_radio]").val());
}
////////////////////////////////////////////////////////////////////////////////////////
// Private Methods                                                                    //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Listeners                                                                          //
////////////////////////////////////////////////////////////////////////////////////////