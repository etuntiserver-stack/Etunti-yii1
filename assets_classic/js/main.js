// MAIN.JS
//--------------------------------------------------------------------------------------------------------------------------------
//This is main JS file that contains custom JS scipts and initialization used in this template*/
// -------------------------------------------------------------------------------------------------------------------------------
// Template Name: Roker.
// Author: Iwthemes.
// Version 1.4 - Updated on 01 / 04 / 2015
// Website: http://www.iwthemes.com
// Email: support@iwthemes.com
// Copyright: (C) 2015
// -------------------------------------------------------------------------------------------------------------------------------

$(document).ready(function($) {

	'use strict';

	//=================================== Twitter Feed  ======================================//
    // $(".twitter").tweet({
    //     modpath: 'js/twitter/index.php',
    //     username: "envato",
    //     count: 5,
    //     loading_text: "Loading tweets...",
    // });



 	//=================================== Sticky nav ===================================//
	$("header").sticky({topSpacing:0});

	//=================================== Nav Responsive ==============================//
    $('#menu').tinyNav({
       active: 'selected'
    });

    //=================================== Parallax Efect ==============================//
  	$('.bg_parallax').parallax("50%", .12);

  	//=================================== Loader =====================================//
	jQuery(window).load(function() {
		jQuery(".status").fadeOut();
	    jQuery(".preloader").delay(1000).fadeOut("slow");
	})


	//=================================== Nav Scroll One Page===========================//
	$('nav ul li a').click(function(){
        var el = $(this).attr('href');
        var elWrapped = $(el);
        scrollToDiv(elWrapped,40);
        return false;
    });
    function scrollToDiv(element,navheight){
		var offset = element.offset();
		var offsetTop = offset.top;
		var totalScroll = offsetTop-navheight;
			$('body,html').animate({
						scrollTop: totalScroll
			}, 500);
    }

  	//=================================== Accordion  =================================//
	$('.accordion-container').hide();
	$('.accordion-trigger:first').addClass('active').next().show();
	$('.accordion-trigger').click(function(){
		if( $(this).next().is(':hidden') ) {
			$('.accordion-trigger').removeClass('active').next().slideUp();
			$(this).toggleClass('active').next().slideDown();
		}
		return false;
	});

   	//=================================== jBar  =============================================//
	$('.jBar').hide();
	$('.jRibbon').show().removeClass('up', 500);
	$('.jTrigger').click(function(){
		$('.jRibbon').toggleClass('up', 500);
		$('.jBar').slideToggle();
	});

	//=================================== Simple slide  ====================================//
	$('.carousel').carousel();

	//=================================== Carousel Services  ===============================//
	$("#services-carousel").owlCarousel({
		autoPlay: 3200,
		items : 4,
		navigation: true,
		itemsDesktop : [1600,3],
		itemsDesktopSmall : [1024,2],
		itemsMobile : [800,1],
		pagination: false
	});

	//=================================== Carousel Works  ==================================//
 	$("#works").owlCarousel({
		autoPlay: 3200,
		items : 5,
		navigation: true,
		itemsDesktop : [1600,4],
		itemsDesktopSmall : [1024,3],
		itemsMobile : [500,1],
		pagination: true
	});

	//=================================== Carousel works-no-margin  ==================================//
 	$("#works-no-margin").owlCarousel({
		autoPlay: 3200,
		items : 4,
		navigation: false,
		itemsDesktop : [1600,4],
		itemsDesktopSmall : [1024,3],
		itemsMobile : [500,1],
		pagination: false
	});

	//=================================== Carousels Footer  =================================//
  	$(".tweet_list").owlCarousel({
		autoPlay: 4000,
		items : 1,
	    navigation: false,
	    pagination: true,
		singleItem: true
	});

	//=================================== Slide Services  ==================================//
 	$("#slide-services").owlCarousel({
		autoPlay: false,
		items : 1,
        navigation : true,
        autoHeight : true,
        slideSpeed : 400,
        singleItem: true,
        pagination : true
	});

  	//=================================== Carousel Sponsors  ================================//
 	$("#sponsors").owlCarousel({
      autoPlay: 3200,
       items : 6,
       navigation: true,
       itemsDesktopSmall : [1024,4],
       itemsTablet : [768,3],
       itemsMobile : [500,2],
       pagination: false
	});

	//=================================== Slide Services  ================================//
	$("#slide-team").owlCarousel({
		items : 1,
		autoPlay: false,
    	navigation : true,
    	autoHeight : true,
    	slideSpeed : 400,
    	singleItem: true,
    	pagination : false
	});

	//=================================== Subtmit Form  ====================================//
	$('.form-contact').submit(function(event) {
	    event.preventDefault();
	    var url = $(this).attr('action');
	    var datos = $(this).serialize();
	    $.get(url, datos, function(resultado) {
	    	$('.result').html(resultado);
		});
 	});

	//=================================== Subtmit Form Newslleter ===========================//
	$('#newsletterForm').submit(function(event) {
	    event.preventDefault();
	    var url = $(this).attr('action');
	    var datos = $(this).serialize();
	    $.get(url, datos, function(resultado) {
	        $('#result-newsletter').html(resultado);
		});
	});

	//=================================== Ligbox  ===========================================//
	$('.fancybox').fancybox({
		'overlayOpacity'	:  0.7,
		'overlayColor'		: '#000000',
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic',
    	'easingIn'			: 'easeOutBack',
    	'easingOut'      	: 'easeInBack',
		'speedIn'         	: '700',
		'centerOnScroll'	: true,
		'titlePosition'     : 'over'
	});

	//=================================== Tooltips ========================================//
	// tooltip demo
    $('.sponsors, .social, .icons-work, .tooltip-hover').tooltip({
      selector: "[data-toggle=tooltip]",
      container: "body"
   	});

    //=================================== Hover Efects =====================================//
	$('.item-service, .feature-element li, .item-table').hover(function() {
		$(this).toggleClass('animated pulse');
	});

    //================================== Scroll Efects =====================================//
  	$(window).scroll(function() {
	    $('.animation-services .icons li, .icon-section').each(function(){
	        var imagePos = $(this).offset().top;
	         var topOfWindow = $(window).scrollTop();
	          if (imagePos < topOfWindow+500) {
	              $(this).addClass("animated bounceInUp").css('opacity' , '1');
	              }
	        });

	    $('.animation-services .image-big').each(function(){
			var imagePos = $(this).offset().top;
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+500) {
           		$(this).addClass("animated bounceInUp").css('opacity' , '1');
			}
		});
	});



	//=================================== Totop  ==========================================//
  	$().UItoTop({
		scrollSpeed:500,
		easingType:'linear'
	});

    //omat

    $('.boxes-info .check .checkbox').click(function(event) {
        if(!$(this).parents('.boxes-info').is('.disabled, .pakollinen')){
            $(this).parents('.boxes-info').toggleClass('selected');
            // $(this).find('.fa').toggleClass('fa-star');
            // $(this).find('.fa').toggleClass('fa-star-o');
        }
        $('.pyydatarjous').addClass('show');
    });
    $('.modal .checkbox').click(function(event) {
            if ($(this).closest('.radio').length) {
                $(this).closest('.radio').find('.checkbox').removeClass('selected');
                $(this).closest('.radio').addClass('check');
                $(this).closest('.radio').removeClass('error');
            }
            $(this).toggleClass('selected');
    });
    $('a[href^="#"], a[href^="/#"]').click(function(e){
        var hash = $(this).prop("hash");
        if (hash){
            e.preventDefault();
            //$('html,body').animate({scrollTop:$(hash).offset().top -100}, 1500); //Roman 27.03.2017
        }
    });
    $('a.sulje').click(function(event) {
        event.preventDefault();
        $(this).parent('div').removeClass('show');
    });
    $('a.tarjouspyynto').click(function(event) {
       var valitut_modulit = "";
       $('.etuntibox.selected h3').each(function(index, el) {
          valitut_modulit += $(this).html() + ", ";
       });
       valitut_modulit = valitut_modulit.slice(0, -2);
       $('.modal p.valitut_modulit').html(valitut_modulit);
       $('.modal input.valitut_modulit').val(valitut_modulit);
    });

    //poistetaan alabanneri, kun lomake on lähetetty ja modal suljetaan.
    $('.pyydatarjous_lomake.modal').on('hide.bs.modal', function (e) {
        if($(this).find('.kiitos').length){
             $('.pyydatarjous').removeClass('show');
        }
    });


    $('.testeri-modal button').click(function(event) {
        testeri();
    });

    //testerin nollaus
    $('.testeri-modal').on('hide.bs.modal', function (e) {
        $('.checkbox').removeClass('selected');
        $('.radio').removeClass('check error');
        $('.testeri-modal').removeClass('tulos');
        $('ul.kysymykset li').removeClass('show-vastaus');
        $('.testerintulos').html('');
    });

    $('.pyydatarjous_lomake.modal').on('show.bs.modal', function (e) {
        $('.testeri-modal').modal('hide');
    });

});
function testeri (argument) {
    // validointi
    var _return = true;
    var kysymykeset_count = 0;
    var kysymykeset_oikein = 0;
    $('ul.kysymykset li').each(function(index, el) {
        var vastaus;
        if(!$(this).find('.radio').hasClass('check')){
            $(this).find('.radio').addClass('error');
            _return = false;
        }
        if ($(this).find('.selected').hasClass('kylla')) {
            vastaus = 'kylla';
	    $(this).find('.vastaus').show(370);
	    $(this).find('.vastaus2').hide(370);
        }else{
            vastaus = 'ei';
	    $(this).find('.vastaus').hide(370);
	    $(this).find('.vastaus2').show(370);
        }
        if ($(this).hasClass(vastaus)) {
            kysymykeset_oikein++ ;
            $(this).addClass('show-vastaus');
        }
        kysymykeset_count++ ;

    });
    var tulos = kysymykeset_oikein / kysymykeset_count * 100;
    if (_return == true) {
        $('.testeri-modal .testerintulos').html(100 - parseInt(tulos) + ' %');
        $('.testeri-modal').addClass('tulos');
    }

}
