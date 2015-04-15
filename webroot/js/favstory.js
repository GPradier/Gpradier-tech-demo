$(function(){
	var count = 0;

	function swapImage(images){
		$('.topimage').fadeOut('slow', function(){
			if(count < 0) {
				count = images.length -1;
			}
			var current = Math.floor(count % images.length);
			$(this).attr('src', images[current]);
			$(this).fadeIn();
		});
	}
	
	function preload(arrayOfImages) {
		$(arrayOfImages).each(function(){
			$('<img/>')[0].src = this;
		});
	}
	
	$(window).scroll(function(e){
		var el = $('#productMenuSmall');		
		if ($(this).scrollTop() > 300){
			el.slideDown();
		}
		else{
			el.stop().slideUp();
		}
		
		if ($(this).scrollTop() > 770 && $(this).scrollTop() < ($('#timelineBot').offset().top - 90)){
			$('#moveIcon').css({'position': 'fixed', 'top': '50px'});
		}
		else{
			$('#moveIcon').css({'position': 'absolute', 'top': 'auto'});
			//$('#moveIcon').css({'position': 'absolute', 'top': $('#moveIcon').offset().top +'px' });
		}
	});
	
	if($('#productMenu').length > 0) {
		/*
		var images = ['http://dribbble.s3.amazonaws.com/users/22013/screenshots/323757/attachments/14426/scout_view.png', 'http://www.coolblahblah.com/wp-content/uploads/2013/01/Paris-City-Lights.jpg', 'http://20.rond-point.emdl.fr/rp1-2/files/2013/01/paris_night1.jpg', 'http://us.123rf.com/400wm/400/400/tupungato/tupungato1207/tupungato120700195/14588766-paris-france--vue-sur-la-ville-aerienne-de-la-tour-eiffel.jpg', 'http://imalbum.aufeminin.com/album/D20070802/321001_4TEG2XMGBTHMQUMMTPWAADCGWERURN_photo-010_H073343_L.jpg', 'http://www.konkours.com/w_data/images/30300/30247/30247_128.jpg'];
		
		var images = $('#loadImages');
		*/
		
		var images = [];
		$( "#loadImages img" ).each(function() {
			images.push($(this).attr('src'));
		});

		preload(images);
		$('.moveLeft').click(function(e){
				count--;
				swapImage(images);
		});
		
		$('.moveRight').click(function(e){
				count++;
				swapImage(images);
		});
	}
	
	$('.ytVid a').click(function(e) {
		e.preventDefault();
		var link = $(this).attr('href');
		var idY = link.split("-");
		$(link).find('.phYoutubePict').hide();
		$(link).find('.phYoutubeVideo').html('<iframe width="385" height="217" src="http://www.youtube.com/embed/'+idY[1]+'?autoplay=1" frameborder="0" allowfullscreen></iframe>');
	});

	if($('#headerHome').length > 0) {
		var indx=1;// iterate from 1, reinitiate to 0
		var texts=[];

		function switchText(){
			indx++;
			if (indx>texts.length-1) indx=0;
			$('h1#main-title').fadeTo(400, 0.01, function(){
				$(this).html(texts[indx]).fadeTo(400, 1);
			});
		};

		$('#title-text span').each(function(indx){
			texts.push($(this).html())
		});

		setInterval(function(){switchText()},4000);
	}

});