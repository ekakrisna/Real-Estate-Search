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
        // Range Slider
        // ---------------------------------------
        var priceRangeSlider;
        var landAreaRangeSlider;

        const priceSliderMinValue = 0;
        const priceSliderMaxValue = 150000000;
        const landAreaSliderMinValue = 0;
        const landAreaSliderMaxValue = 100;

        var defaultPriceMinValue;
        var defaultPriceMaxValue;
        var defaultLandAreaMinValue;
        var defaultLandAreaMaxValue;

        // Get User Setting
        (function () {
            // get proprerty list with ajax
            var csrf = $('input[name=_csrfToken]').val();
            $.ajax({
                url: location.origin + "/api/v1/map/getUserSetting",
                type: "post",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrf);
                },
                data: {
                    cust_id: Number($("#loggedInCustomerID").val()),
                },
                dataType: "JSON"}).done(function (data, dataType) {
                    defaultPriceMinValue = data['priceMin'];
                    defaultPriceMaxValue  = data['priceMax'];
                    defaultLandAreaMinValue  = data['landAreaMin'];
                    defaultLandAreaMaxValue = data['landAreaMax'];

                    document.getElementById('slider-min-price-value').value = defaultPriceMinValue;
                    document.getElementById('slider-max-price-value').value = defaultPriceMaxValue;
                    document.getElementById('slider-min-land_area-value').value = defaultLandAreaMinValue;
                    document.getElementById('slider-max-land_area-value').value = defaultLandAreaMaxValue;

                }).fail(function (data, dataType) {
                    if (data['status'] == 403) {
                        location.reload();
                    }
                }).always(function(){
                    initPriceSlider();
                    initLandAreaSlider();
                    initButton();
                });
        }());

        function initPriceSlider(){
            $('.rangeslider1').each( function(){
                priceRangeSlider = document.getElementById('slider-range');
                var moneyFormat = wNumb({
                    decimals: 0,
                });
                noUiSlider.create(priceRangeSlider, {
                    start: [defaultPriceMinValue, defaultPriceMaxValue],
                    step: 5000000,
                    range: {
                        'min': priceSliderMinValue,
                        'max': priceSliderMaxValue
                    },
                    format: moneyFormat,
                    connect: true
                });

                // Set visual min and max values and also update value hidden form inputs
                priceRangeSlider.noUiSlider.on('update', function(values, handle) {
                    document.getElementById('slider-range-value1').innerText = fromatDisplayPrice(values[0]);
                    document.getElementById('slider-range-value2').innerText = fromatDisplayPrice(values[1]);
                    document.getElementById('tmp-slider-min-price-value').value = moneyFormat.from(values[0]);
                    document.getElementById('tmp-slider-max-price-value').value = moneyFormat.from(values[1]);
                });
            })
        }

        function fromatDisplayPrice(targetValue) {
            if(Number(targetValue) === priceSliderMinValue){
                return "下限無し";
            }

            if(Number(targetValue) === priceSliderMaxValue){
                return "上限無し";
            }

            var convertValue = targetValue/ 10000;
            var strValue = String(convertValue);
            var valueLength =strValue.length;

            if(valueLength <= 4){
                return convertValue + "万円";
            }

            var lendValue = strValue.substr(0,1) + "億";
            var lastValue = Number(strValue.substr(1,valueLength - 1));
            if(lastValue !== 0 ){
                lastValue = lastValue + "万";
            }
            return lendValue + lastValue + "円";
        }

        /**
         * Land Area Slider
         */
        function initLandAreaSlider(){
            $('.rangeslider2').each( function(){
                landAreaRangeSlider = document.getElementById('slider-range2');
                var moneyFormat = wNumb({
                    decimals: 0,
                });
                noUiSlider.create(landAreaRangeSlider, {
                    start: [defaultLandAreaMinValue, defaultLandAreaMaxValue],
                    step: 1,
                    range: {
                        'min': landAreaSliderMinValue,
                        'max': landAreaSliderMaxValue
                    },
                    format: moneyFormat,
                    connect: true
                });

                // Set visual min and max values and also update value hidden form inputs
                landAreaRangeSlider.noUiSlider.on('update', function(values, handle) {
                    document.getElementById('slider-range-value3').innerText = fromatDisplayLandArea(values[0]);
                    document.getElementById('slider-range-value4').innerText = fromatDisplayLandArea(values[1]);
                    document.getElementById('tmp-slider-min-land_area-value').value = moneyFormat.from(values[0]);
                    document.getElementById('tmp-slider-max-land_area-value').value = moneyFormat.from(values[1]);
                });
            });
        }

        function fromatDisplayLandArea(targetValue) {
            if(Number(targetValue) === landAreaSliderMinValue){
                return "下限無し";
            }

            if(Number(targetValue) === landAreaSliderMaxValue){
                return "上限無し";
            }
            return targetValue + "坪";
        }

        // ---------------------------------------
        // Button Filter Map
        // ---------------------------------------

        function initButton(){
            $('.map-page').each( function(){
                var $this = $(this);
                var $btnOpenFilter = $this.find('.btn-filter');
                var $sidebarLeft = $this.find('.sidebar-left-map');
                var $btnCloseFilter = $sidebarLeft.find('.btn-icon-close');
                var $btnFilterReset = $sidebarLeft.find('.link-with-icon-times');

                $btnFilterReset.on('click',function(){
                    priceRangeSlider.noUiSlider.set([defaultPriceMinValue, defaultPriceMaxValue]);
                    landAreaRangeSlider.noUiSlider.set([defaultLandAreaMinValue, defaultLandAreaMaxValue]);
                })

                $btnOpenFilter.on('click', function(){
                    if($sidebarLeft.hasClass('d-none')){
                        $sidebarLeft.removeClass('d-none');
                    } else{
                        $sidebarLeft.addClass('d-none');
                    }
                })

                $btnCloseFilter.on('click', function(){
                    if($sidebarLeft.hasClass('d-none')){
                        $sidebarLeft.removeClass('d-none');
                    } else{
                        $sidebarLeft.addClass('d-none');
                        var priceMinValue =  document.getElementById('slider-min-price-value').value;
                        var priceMaxValue =  document.getElementById('slider-max-price-value').value;
                        var landAreaMinValue =  document.getElementById('slider-min-land_area-value').value;
                        var landAreaMaxValue =  document.getElementById('slider-max-land_area-value').value;

                        document.getElementById('slider-range-value1').innerText = fromatDisplayPrice(priceMinValue);
                        document.getElementById('slider-range-value2').innerText = fromatDisplayPrice(priceMaxValue);
                        document.getElementById('slider-range-value3').innerText = fromatDisplayLandArea(landAreaMinValue);
                        document.getElementById('slider-range-value4').innerText = fromatDisplayLandArea(landAreaMaxValue);

                        priceRangeSlider.noUiSlider.set([priceMinValue, priceMaxValue]);
                        landAreaRangeSlider.noUiSlider.set([landAreaMinValue,landAreaMaxValue]);
                    }
                })
            });
        }

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

            var swiper = new Swiper ( $slider, {
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
            });
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
        })

    });
}( jQuery, document, window ));
