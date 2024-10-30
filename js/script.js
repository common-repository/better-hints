
jQuery( document ).ready(function( $ ) {

	//init stuff
  var initialHeight = $('#betterhints').outerHeight();
  var toHeight = 0 - initialHeight / 1.6;

  var toHeight = 0 - initialHeight * 0.45;

  //$('body').css('padding-bottom',initialHeight / 2);

  var hint_animation_popout_time = 10000;

  if(frontend_ajax_object.hint_animation_popout_time!="") { hint_animation_popout_time = frontend_ajax_object.hint_animation_popout_time; }

  setTimeout(function(){
    $('#betterhints').animate({
      bottom: toHeight
    }, 250, function() {
      // Animation complete.
      $(this).find('.pulsate').removeClass('pulsate');
    });
  }, hint_animation_popout_time)

	//countdown
	$('#betterhints').on("mouseenter", function() {
    $(this).stop().animate({ bottom: 0 }, 250, function() {
    $(this).find('.betterhint-content').addClass('pulsate');
    });
	}).on("mouseleave", function() {
    $(this).animate({ bottom: toHeight }, 250, function() {
    $(this).find('.pulsate').removeClass('pulsate');
    });
	});


  //click hint
	$('#betterhints').on('click', '.betterhint', function(e) {

		var that = $(this);
		var itemid = $(this).data('id');
    var url = $(this).data('url');


		if( that.hasClass('clicked')) {
			//$(e.target).trigger('click');
			//return true;
      console.log('click not saved');
		} else {

      console.log('click registered');

      //analytics click event
      if(window.ga && ga.create) { ga('send', 'event', 'Hint', 'click', itemid+":"+url ); }

      //click event
      $.post( frontend_ajax_object.ajaxurl, { 'action': 'betterhints_addHintClick', 'ID': itemid }, function(response) {

  			var response = jQuery.parseJSON(response);

  			if(response.success===true) {
          that.addClass('clicked');
          console.log('click registered and saved..');

  			}

  			if(response.error!==undefined) { console.log('click registered and error');	}

  			if(response.message!==undefined) { }

        if( url !== undefined && url !== "" ) { console.log('redirecting...'); window.location.href = url; }
  			console.log( response );

  		});

    }

	});

});
