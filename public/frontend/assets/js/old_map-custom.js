var map;
var marker = [];
var first_getMarker = true;
var data;
var searchBox;
var input;

(function ($, document, window, undefined) {
    'use strict';
    $(document).ready(function () {
        $(function () {
            init();
            function init() {
                /**
                 * Map init
                 */

                /**
                 * If already selected center at C2,set value in "latlng" variable
                 */

                // set map center
                var latlng = {
                    lat: Number($("#townLat").val()),
                    lng: Number($("#townLng").val())
                };

                // create google.maps.LatLng object
                var mapLatLng = new google.maps.LatLng(latlng);

                // create map
                map = new google.maps.Map(document.getElementById('map'), {
                    center: mapLatLng,
                    zoom: 14,
                    mapTypeId: 'roadmap',
                    mapTypeControl: true,
                    scaleControl: true,
                    clickableIcons: false
                });

                /**
                 * search box init
                 */

                // get search box 
                input = $('#place').get(0);
                // Create the search box and link it to the UI element.
                searchBox = new google.maps.places.SearchBox(input);

                /**
                 * search event
                 */

                // search box add event for text change
                searchBox.addListener('places_changed', function () {
                    var places = searchBox.getPlaces();
                    if (places.length == 0) {
                        return;
                    }
                    // For each place, get the icon, name and location.
                    var bounds = new google.maps.LatLngBounds();
                    places.forEach(function (place) {
                        if (!place.geometry) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                    map.setZoom(15);
                });

                /**
                 * map event
                 */

                // 
                map.addListener('bounds_changed', function () {
                    searchBox.setBounds(map.getBounds());
                    if (first_getMarker) {
                        first_getMarker = false;
                        drawing(null, false);
                    }
                });

                // dragend event
                map.addListener('dragend', function () {
                    drawing();
                });

                // zoom_changed event
                map.addListener('zoom_changed', function () {
                    if (map.getZoom() < 14) {
                        clearMarker();
                        // display message dialog 
                        // message dialog is not implementation
                        $('#map').addClass('messageOn');
                        // display message dialog after 4 second
                        setTimeout(function () {
                            $('#map').removeClass('messageOn');
                        }, 4000);
                    } else if (map.getZoom() >= 14) {
                        // show message
                        $('#map').removeClass('messageOn');
                        drawing();
                    }
                });
            }

            /**
             * drawing marker
             */
            function drawing(zoom = null, close = true) {
                if (zoom) {
                    map.setZoom(zoom);
                }
                // get and set property marker
                getMarker();
            }

            /**
             * get property number
             */
            function getMarker() {
                // init marker
                clearMarker();

                if (map.getZoom() >= 14) {
                    // get current display info of google map
                    var bounds = map.getBounds()
                    // create request data
                    data = {
                        // customer id
                        cust_id: Number($("#loggedInCustomerID").val()),
                        // coordinate
                        north: bounds.getNorthEast().lat(),
                        east: bounds.getNorthEast().lng(),
                        south: bounds.getSouthWest().lat(),
                        west: bounds.getSouthWest().lng(),

                        // search condition
                        min_landArea : Number($("#slider-min-land_area-value").val()),
                        max_landArea : Number($("#slider-max-land_area-value").val()),
                        min_price : Number($("#slider-min-price-value").val()),
                        max_price : Number($("#slider-max-price-value").val()),
                    }
                    
                    // get proprerty list with ajax
                    var csrf = $('input[name=_csrfToken]').val();
                    $.ajax({
                        url: location.origin + "/api/v1/map/getpropertylist",
                        type: "post",
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', csrf);
                        },
                        dataType: "JSON",
                        data: data,
                        success: function (data, dataType) {
                            console.log(data);
                            createMarker(data);
                        },
                        error: function (data, dataType) {
                            if (data['status'] == 403) {
                                location.reload();
                            }
                        }
                    });
                }
            }

            /**
             * marker create
             */
            function createMarker(markerData) {

               if(markerData) {
                for (var i = 0; i < markerData.length; i++) {
                    // get marker info
                    var markerLatLng = new google.maps.LatLng({
                        lat: Number(markerData[i]['lat']),
                        lng: Number(markerData[i]['lng'])
                    });

                    // set marker label
                    var markerLabel = {
                        color: "#da380c",
                        fontFamily: "Arial",
                        fontSize: "14px",
                        fontWeight: "bold",
                        text: String(markerData[i]['label']),
                    };

                    // TODO(C3): Please implementation change icon type by marker type
                    // See the example below.
                    // image is stored at directory public/frontend/assets/images/bg_plot*****.png
                    // if new inclued data new, image that image name inclued "new" use.
                    // if new inclued data fav, image that image name inclued "fav" use.
                    // if new inclued data area, image that image name inclued "area" use.                 
                    // In case of multiple,Please use image that each one data type inclued
                    // if marker data is new only
                    if (markerData[i]['new'] && markerData[i]['fav'] == false && markerData[i]['area'] == false) {
                        var image = {
                            url: 'frontend/assets/images/bg_plot_new.png',
                            scaledSize: new google.maps.Size(60, 40),
                            labelOrigin: new google.maps.Point(43,20)
                        };
                    }
                    // if marker data is area only
                    else if (markerData[i]['area'] && markerData[i]['new'] == false && markerData[i]['fav'] == false) {
                        var image = {
                            url: 'frontend/assets/images/bg_plot_fav_area.png',
                            scaledSize: new google.maps.Size(48, 40),
                            labelOrigin: new google.maps.Point(18,20)
                        };
                    }
                    // if marker data is fav only 
                    else if (markerData[i]['fav'] && markerData[i]['new'] == false && markerData[i]['fav'] == false) {
                        var image = {
                            url: 'frontend/assets/images/bg_plot_fav_property.png',
                            scaledSize: new google.maps.Size(48, 40),
                            labelOrigin: new google.maps.Point(18,20)
                        };
                    }
                    // if marker data is fav and new
                    else if (markerData[i]['fav'] && markerData[i]['new'] && markerData[i]['area'] == false) {
                        var image = {
                            url: 'frontend/assets/images/bg_plot_fav_property_new.png',
                            scaledSize: new google.maps.Size(70, 40),
                            labelOrigin: new google.maps.Point(43,20)
                        };
                    } 
                    // if marker data is area and new
                    else if (markerData[i]['area'] && markerData[i]['new'] && markerData[i]['fav'] == false) {
                        var image = {
                            url: 'frontend/assets/images/bg_plot_fav_area_new.png',
                            scaledSize: new google.maps.Size(70, 40),
                            labelOrigin: new google.maps.Point(43,20)
                        };
                    }
                    // if marker data is area and fav
                    else if (markerData[i]['area'] && markerData[i]['new'] == false && markerData[i]['fav']) {
                        var image = {
                            url: 'frontend/assets/images/bg_plot_full.png',
                            scaledSize: new google.maps.Size(45, 40),
                            labelOrigin: new google.maps.Point(18,20)
                        };
                    }
                    // if marker data is area, fav, and new
                    else if (markerData[i]['area'] && markerData[i]['new'] && markerData[i]['fav']) {
                        var image = {
                            url: 'frontend/assets/images/bg_plot_full_new.png',
                            scaledSize: new google.maps.Size(70, 40),
                            labelOrigin: new google.maps.Point(43,20)
                        };
                    }
                    else {
                        var image = {
                            url: 'frontend/assets/images/bg_plot.png',
                            scaledSize: new google.maps.Size(30, 30),
                        };
                    }

                    // create marker and put on google map
                    marker[i] = new google.maps.Marker({
                        position: markerLatLng,
                        map: map,
                        label: markerLabel,
                        icon: image,
                        section_id: String(markerData[i]['section_id']),
                        title: String(markerData[i]['name'])
                    });
                    // add event in marker object
                    markerEvent(i);
                }
               }
            }

            /**
             * marker clear
             */
            function clearMarker() {
                // delete marker in map
                marker.forEach(function (element) {
                    element.setMap(null);
                });
                // object init
                marker = [];
            }

            /**
             * TODO(C3):Please implementation marker events
             * TODO(C3):send all the data needed
             * if click,go to C11(Property - list)
             *
             * @param {integer} i
             */
            function markerEvent(i) {
                marker[i].addListener('click', function () {
                    /**
                     * 
                     * Please implementation flow in marker event 
                     * 
                     */ 
                    console.log(marker[i]);
                    // window.location.href = location.origin + "/frontend/property";      
                });
            }

            $("#mapSearchButton").on('click', function () {            
                document.getElementById('slider-min-price-value').value =  document.getElementById('tmp-slider-min-price-value').value;
                document.getElementById('slider-max-price-value').value = document.getElementById('tmp-slider-max-price-value').value;
                document.getElementById('slider-min-land_area-value').value = document.getElementById('tmp-slider-min-land_area-value').value;
                document.getElementById('slider-max-land_area-value').value = document.getElementById('tmp-slider-max-land_area-value').value;
                console.log(document.getElementById('slider-min-price-value').value);
                console.log(document.getElementById('slider-max-price-value').value);
                console.log(document.getElementById('slider-min-land_area-value').value);
                console.log(document.getElementById('slider-max-land_area-value').value);
                getMarker();
                drawing();
                $('.btn-icon-close').trigger('click');
            });
        });
    });
}(jQuery, document, window));
