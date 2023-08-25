jQuery(document).ready(function($) {
      'use strict';
    
      // Register Slider
      $('#top-slider').sliderPro({
      width: '100%',
      height: 800,
      arrows: true,
      buttons: true,
      waitForLayers: true,
      fade: true,
      autoplay: true,
      autoplayDelay: 10000,
      slideDistance: 0,
      touchSwipe: false,
      autoplayOnHover: 'pause',
      breakpoints: {
      1200: {
        height: '700'
      },
      974: {
        height: '400'
      },
      767: {
        height: '300'
      }
    }
    });
    
    // Dropdown on hover
    $(function() {
                $("#hoverdrop").bootstrapDropdownOnHover({
                    mouseOutDelay: 50
                });
    });
    
    // Search Toggle
    $(function(){
        var $searchlink = $('#searchtoggl i');
        var $searchbar  = $('.searchbar');

        $('.menu-search-wrap a').on('click', function(e){
        e.preventDefault();

            if($(this).attr('id') == 'searchtoggl') {
                  $searchbar.slideToggle(300, function(){
                });
            }
        });
    });
    
    $('.sponsors-carousel').owlCarousel({
        loop: false,
        items: 5,
        margin: 50,
        dots: false,
        navText: ['<span class="glyphicon glyphicon-chevron-left"></span>','<span class="glyphicon glyphicon-chevron-right"></span>'],
        responsiveClass: true,
        responsive : {
            0 : {
                items: 1
            },
            480 : {
                items: 2
            },
            767 : {
                items: 3
            },
            991 : {
                items: 4
            },
            1200 : {
                items: 5
            }
        }
    });
    
    $('.players-wrap').owlCarousel({
        loop: false,
        nav: true,
        items: 4,
        navContainer: '.players-nav-container',
        dots: false,
        navText: ['<span class="glyphicon glyphicon-chevron-left"></span>','<span class="glyphicon glyphicon-chevron-right"></span>'],
        margin: 10,
        responsiveClass: true,
        responsive : {
            0 : {
                items: 1
            },
            480 : {
                items: 2
            },
            767 : {
                items: 3
            },
            991 : {
                items: 4
            },
            1200 : {
                items: 4
            }
        }
    });
    
    $('.shop-carousel').owlCarousel({
        loop: true,
        nav: true,
        items: 4,
        navContainer: '.shop-nav-container',
        dots: false,
        navText: ['<span class="glyphicon glyphicon-chevron-left"></span>','<span class="glyphicon glyphicon-chevron-right"></span>'],
        margin: 10,
        responsiveClass: true,
        responsive : {
            0 : {
                items: 1
            },
            550 : {
                items: 2
            },
            767 : {
                items: 2
            },
            991 : {
                items: 3
            },
            1200 : {
                items: 4
            }
        }
    });
    

    if(! /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
    
        jQuery('.afterload').addClass("hiddens").viewportChecker({
        classToAdd: 'visibles animated slideInUp',
        offset: 100
        });

        jQuery('.afterloadFadeIn').addClass("hiddens").viewportChecker({
        classToAdd: 'visibles animated fadeIn',
        offset: 100
        });
    }



$(".tosrus-inline").tosrus({
    wrapper: {
        onClick: 'close'
    },
    buttons: {
        close: false,
        prev: false,
        next: false
    },
    caption: {
        add: true
    }
});


    
});

