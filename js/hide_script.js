jQuery(document).ready(function(){
		jQuery('.rm_opts').slideUp();

		jQuery('.rm_section h3').click(function(){
			if(jQuery(this).parent().next('.rm_opts').css('display')==='none')
				{	jQuery(this).removeClass('inactive').addClass('active').children('img').removeClass('inactive').addClass('active');

				}
			else
				{	jQuery(this).removeClass('active').addClass('inactive').children('img').removeClass('active').addClass('inactive');
				}

			jQuery(this).parent().next('.rm_opts').slideToggle('slow');
		});
});
