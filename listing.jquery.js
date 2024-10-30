jQuery(function(){	
	jQuery(".readbio").click(function(){
		jQuery(this).parent().next(".bio").slideToggle('fast');
		if (jQuery(this).hasClass('contributor-name') ){
		} else {
		jQuery(this).slideUp('fast');
	}
	return false;
	});
	jQuery(".hidebio").click(function(){
		if (jQuery(this).hasClass('contributor-hide')){
		jQuery(this).parent().parent().slideUp('fast');
		} else {
		jQuery(this).parent().slideUp('fast');
	}
		jQuery('.readbio').slideDown('fast');
		return false;
		});
	jQuery(".bio").hide();
});