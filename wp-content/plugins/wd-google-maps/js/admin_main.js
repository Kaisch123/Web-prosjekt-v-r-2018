////////////////////////////////////////////////////////////////////////////////////////
// Events                                                                             //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Constants                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Variables                                                                          //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Constructor & Destructor                                                           //
////////////////////////////////////////////////////////////////////////////////////////	
jQuery(document).ready(function () {

	jQuery("[type=text], [type=number]").keypress(function(event){
		event = event || window.event;
		if(event.keyCode == 13){
			return false;
		}
	});
    /*if(jQuery('.gmwd_follow_scroll').length > 0){
   
        var elemPosOrig = jQuery('.gmwd_follow_scroll').offset().top; 
        var docHeight = jQuery(window).height();
        jQuery(window).scroll(function(){
            var windowPos = jQuery(this).scrollTop(); 
                
            if(windowPos >= elemPosOrig ) {
                //var position = windowPos-elemPosOrig < docHeight
            
                jQuery('.gmwd_follow_scroll').stop().animate({'top':windowPos-elemPosOrig },500);
            } 
            else {
                jQuery('.gmwd_follow_scroll').stop().animate({'top':0},500);
                
            }
        });
        
    }*/
	
});



////////////////////////////////////////////////////////////////////////////////////////
// Public Methods                                                                     //
////////////////////////////////////////////////////////////////////////////////////////

function gmwdOpenMediaUploader(e,id,callback){
    if(typeof callback == "undefined"){
        callback = false;
    }
    e.preventDefault();
	var custom_uploader = wp.media({
        title: 'Upload',
        button: {
            text: 'Upload'
        },
        multiple: false  
    })
    .on('select', function() {
       var attachment = custom_uploader.state().get('selection').first().toJSON();
       jQuery('#' + id).val(attachment.url);
       jQuery('.' + id + "_view").html('<img src="' + attachment.url + '" height="25"><span class="' + id + '_delete" onclick="jQuery(\'#' + id + '\').val(\'\');jQuery(\'.' + id + '_view\').html(\'\');if(callback){callback();}">x</span>');
       if(callback){
            callback();
       }
    })
    .open();
	
	return false;

}
function gmwdFormSubmit(task){
	if(typeof task == "undefinded"){
		task = "";
	}
	var adminForm = jQuery("#adminForm");	
	if(task != ""){
		gmwdFormInputSet("task",task);
		switch(task){
			case "save" :
			case "apply" :           
			case "save2copy" :           
				  fillInputs();
                  if(checkFields("wd-required") == false){
                    return false;
                  }                  
				break;
		
		}
	}
	adminForm.submit();

}

function removeRedBorder(obj){
	jQuery(obj).removeClass("wd-required-active");
}

function checkFields(fieldClass){
    var isValid = true;
    jQuery("." + fieldClass).removeClass("wd-required-active"); 
    jQuery("." + fieldClass).each(function(){
       if (jQuery(this).val() == ""){
            jQuery(this).addClass("wd-required-active");         
            isValid = false;                        
       } 
    });

    if(isValid == false){
        alert("Please Fill Required Fields.");
        return false;
    } 
   return true;
}

////////////////////////////////////////////////////////////////////////////////////////
// Getters & Setters                                                                  //
////////////////////////////////////////////////////////////////////////////////////////
function gmwdFormInputSet(name, value){
	jQuery("[name=" + name + "]").val(value);
}

function fillInputs(){
	switch(_page){
		case "maps_gmwd" :
			fillInputPois();
			break;
		case "themes_gmwd" :
			fillInputStyles();
			break;            
		
	}
}
////////////////////////////////////////////////////////////////////////////////////////
// Private Methods                                                                    //
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
// Listeners                                                                          //
////////////////////////////////////////////////////////////////////////////////////////