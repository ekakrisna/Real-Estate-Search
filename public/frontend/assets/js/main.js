(function( $, document, window, undefined ){ 'use strict';
    $(document).ready( function(){
        // ---------------------------------------
        // Form Validation
        // ---------------------------------------
        $('.form-login-validate').each( function(){
            var $this = $(this);
            var $input_email = $this.find('input[type="email"]');
            var $input_pass = $this.find('input[type="password"]');
            var $agreement = $this.find('.label-agreement');

            $this.parsley().on('field:validated', function(){
                var ok = $('.parsley-error').length === 0;

                $('.error-user-pass').toggleClass('d-none', ok);
                $('.error-agreement').toggleClass('d-none', ok);

                if(!$input_email.hasClass('parsley-error') && !$input_pass.hasClass('parsley-error')){
                    $('.error-user-pass').addClass('d-none');
                }
                if(!$agreement.hasClass('parsley-error')){
                    $('.error-agreement').addClass('d-none');
                }
            }).on('form:submit', function() {
                return true;
            });
        });

        // ---------------------------------------
        // Button Menu Side Right
        // ---------------------------------------
        $('.btn-menu-sideright').on('click', function(){
            var $this       = $(this);
            var $iconOpen   = $this.find('.icon-open');
            var $iconClose  = $this.find('.icon-close');
            var $sideRight  = $('.sideright-menu');

            if($this.hasClass('active-close')){
                $iconOpen.removeClass('d-none');
                $this.removeClass('active-close');
                $iconClose.addClass('d-none');
                $sideRight.removeClass('d-none');
                $sideRight.addClass('d-none');
                $('body').removeClass('hide-scroll');
            } else{
                $iconClose.removeClass('d-none');
                $iconOpen.addClass('d-none');
                $this.addClass('active-close');
                $sideRight.removeClass('d-none');
                $('body').addClass('hide-scroll');
            }
        });

        $('.sideright-overlay').on('click', function(){
            $('.btn-menu-sideright').trigger('click');
        });

        // ---------------------------------------
        // Icon Click love
        // ---------------------------------------
        $('.content-icon-fav .col-12').each( function(){
            var $this       = $(this);
            var $iconLove   = $this.find('.icon-fav');
            var $iconOn     = $this.find('.icon-fav-on');
            var $iconOff     = $this.find('.icon-fav-off');

            $iconLove.on('click', function(){
                if($iconOn.hasClass('d-none')){
                    $iconOn.removeClass('d-none');
                    $iconOff.addClass('d-none');
                } else{
                    $iconOn.addClass('d-none');
                    $iconOff.removeClass('d-none');
                }
            })
        });

        $('.icon-fav-property').each( function(){
            var $this       = $(this);
            var $iconLove   = $this.find('.icon-fav');
            var $iconOn     = $this.find('.icon-fav-on');
            var $iconOff     = $this.find('.icon-fav-off');

            $iconLove.on('click', function(){
                if($iconOn.hasClass('d-none')){
                    $iconOn.removeClass('d-none');
                    $iconOff.addClass('d-none');
                } else{
                    $iconOn.addClass('d-none');
                    $iconOff.removeClass('d-none');
                }
            })
        });

        $('.section-property-detail').each( function (){
            var $this       = $(this);
            var $slider     = $this.find('.swiper-container');
            var $nav_prev   = $slider.find('.swiper-button-prev');
            var $nav_next   = $slider.find('.swiper-button-next');
            let options     = {};

            if ( $(".swiper-container .swiper-slide").length > 1 ) {
                options = {
                    autoplay:true,
                    loop: true,
                    slidesPerView: 2,
                      spaceBetween: 30,
                      centeredSlides : true,
                    touchRatio: 0,

                    navigation: {
                        nextEl: $nav_next,
                        prevEl: $nav_prev,
                    },
                    breakpoints: {
                      0: {
                          slidesPerView: 1,
                          spaceBetween: 0,
                      },
                      992: {
                          slidesPerView: 2,
                          spaceBetween: 30,
                      },
                    }
                }
            } else {
                options = {
                    loop: false,
                    autoplay: false,
                    slidesPerView: 2,
                    spaceBetween: 30,
                    centeredSlides : true,
                    touchRatio: 0,

                    navigation: {
                        nextEl: $nav_next,
                        prevEl: $nav_prev,
                    },
                    breakpoints: {
                        0: {
                            slidesPerView: 1,
                            spaceBetween: 0,
                        },
                        992: {
                            slidesPerView: 2,
                            spaceBetween: 30,
                        },
                    }
                }
            }

            var swiper = new Swiper('.swiper-container', options);
        });

        $('.form-setting').each( function(){
            var $this = $(this);
            var $fav  = $this.find('.select-required');
            var $form = $fav.find('.form-control');
            var $mb28 = $this.find('.mb-28');

            $this.parsley().on('field:validated', function(){
                var ok = $('.parsley-error').length === 0;

                if($form.hasClass('parsley-error')){
                    $mb28.addClass('d-block');
                } else{
                    $mb28.removeClass('d-block');
                }
            }).on('form:submit', function() {
                return true;
            });
        });
        $('.form-inquiry').each( function(){
            var $this = $(this);
            var $fav  = $this.find('.form-select');
            var $form = $fav.find('.form-control');
            var $mt28 = $this.find('.mt-28');

            $this.parsley().on('field:validated', function(){
                var ok = $('.parsley-error').length === 0;

                if($form.hasClass('parsley-error')){
                    $mt28.addClass('d-block');
                } else{
                    $mt28.removeClass('d-block');
                }
            }).on('form:submit', function() {
                return true;
            });
        });


        // Link Target Explanation
        $('.content-point-link .target1').on('click', function(){
            $('html, body').animate({
                scrollTop: $('.info1').offset().top - 64
            }, 200);
        });
        $('.content-point-link .target2').on('click', function(){
            $('html, body').animate({
                scrollTop: $('.info2').offset().top - 64
            }, 200);
        });
        $('.content-point-link .target3').on('click', function(){
            $('html, body').animate({
                scrollTop: $('.info3').offset().top - 64
            }, 200);
        });
    });
}( jQuery, document, window ));
