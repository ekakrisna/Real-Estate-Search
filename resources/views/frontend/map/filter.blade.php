<script type="text/x-template" id="map-sidebar-filter">
    <div class>
        <a href="javascript:void(0)" class="btn-filter" @click="sidebar_close = !sidebar_close">
            <img src="{{ asset('frontend/assets/images/icons/icon_map_filter.png')}}" alt="icon-filter" class="img-filter">
            <span>条件で絞り込み</span>
        </a>

        <div :class="['sidebar-left-map', {'d-none' : sidebar_close}]">
            <div class="sidebar-overlay" @click="closeSidebarFilter"></div>
            <div class="content-sidebar">
                <a href="javascript:void(0)" class="btn-icon-close" @click="closeSidebarFilter">
                    <img src="{{ asset('frontend/assets/images/icons/icon_close.png') }}" alt="icon-close">
                </a>
                <form class="form-filter" action="#">
                    <div class="form-group mb-5">
                        <label class="label-input px-3">
                            <img src="{{ asset('frontend/assets/images/icons/icon_budget.png') }}" alt="icon_budget" class="img-icon-label">
                            <span>検討可能な予算</span>
                        </label>
                        <div class="row align-items-center px-3">
                            <div class="col">
                                <p id="slider-range-value1" class="form-control form-control-gray"></p>
                            </div>
                            <span>～</span>
                            <div class="col">
                                <p id="slider-range-value2" class="form-control form-control-gray"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="rangeslider rangeslider1">
                                    <div id="slider-range"></div>
                                    <input type="hidden" :value="filters.minimum_price" id="slider-min-price-value" value="">
                                    <input type="hidden" :value="filters.maximum_price" id="slider-max-price-value" value="">
                                    <input type="hidden" id="tmp-slider-min-price-value" value="">
                                    <input type="hidden" id="tmp-slider-max-price-value" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-5">
                        <label class="label-input px-3">
                            <img src="{{ asset('frontend/assets/images/icons/icon_prooerty_size.png') }}" alt="icon_prooerty_size" class="img-icon-label">
                            <span>土地面積</span>
                        </label>
                        <div class="row align-items-center px-3">
                            <div class="col">
                                <p id="slider-range-value3" class="form-control form-control-gray"></p>
                            </div>
                            <span>～</span>
                            <div class="col">
                                <p id="slider-range-value4" class="form-control form-control-gray"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="rangeslider rangeslider2">
                                    <div id="slider-range2"></div>
                                    <input type="hidden" :value="filters.minimum_land_area" id="slider-min-land_area-value" value="">
                                    <input type="hidden" :value="filters.maximum_land_area" id="slider-max-land_area-value" value="">
                                    <input type="hidden" id="tmp-slider-min-land_area-value" value="">
                                    <input type="hidden" id="tmp-slider-max-land_area-value" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row px-3">
                        <div class="col-12">
                            <button id="mapSearchButton" class="btn btn-primary-round btn-max-320" type="button" @click="sendFilters()">
                                <span>検索する</span>
                            </button>
                        </div>
                        <div class="col-12 d-flex justify-content-center">
                            <a href="javascript:void(0)" @click="resetFilter()" class="link-with-icon-times">
                                <img src="{{ asset('frontend/assets/images/icons/icon_reset.png') }}" alt="icon-reset">
                                <span>絞り込み条件をリセット</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</script>
@if (Auth::guard('user')->user())
    <script> @minify
    // ----------------------------------------------------------------------
    // If access by login user
    // ----------------------------------------------------------------------
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Result item
        // ----------------------------------------------------------------------
        Vue.component( 'MapFilter', {
            // ------------------------------------------------------------------
            props: ['value', 'filters', 'get-filters'] ,
            template: '#map-sidebar-filter',
            // ------------------------------------------------------------------
            data: function(){

                // --------------------------------------------------------------
                var data = {
                    sidebar_close : true,
                    applay_condition : false,
                    // --------------------------------------------------------------
                    //  min and max value from server for slider
                    // --------------------------------------------------------------
                    priceSliderMinValue : Number(@json(toMan($lowest_price_filter))),
                    priceSliderMaxValue : Number(@json(toMan($highest_price_filter))),
                    landAreaSliderMinValue : Number(@json(toTsubo($lowest_land_area_filter))),
                    landAreaSliderMaxValue : Number(@json(toTsubo($highest_land_area_filter))),
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    //  no upper limit and no lower limit value for slider
                    // --------------------------------------------------------------
                    noLimitMinPrice : Number(@json(toMan($lowest_price_filter))) - @json(config('const.man_price_step')),
                    noLimitMaxPrice : Number(@json(toMan($highest_price_filter))) + @json(config('const.man_price_step')),
                    noLimitMinLandArea : Number(@json(toTsubo($lowest_land_area_filter))) - @json(config('const.tsubo_area_step')),
                    noLimitMaxLandArea : Number(@json(toTsubo($highest_land_area_filter))) + @json(config('const.tsubo_area_step')),
                    // --------------------------------------------------------------

                    beforeMinPriceValue : 0,
                    beforeMaxPriceValue : 0,
                    beforeMinLandAreaValue : 0,
                    beforeMaxLandAreaValue : 0,
                };
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                return data;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
            mounted: function(){

                // ------------------------------------------------------------------
                //  Initialize create slider on mounted
                // ------------------------------------------------------------------
                this.initPriceSlider();
                this.initLandAreaSlider();
                // ------------------------------------------------------------------

            },
            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Call to action methods
            // ------------------------------------------------------------------
            methods: {
                // ------------------------------------------------------------------
                // handle inital price slider and update it's value
                // based on slider
                // ------------------------------------------------------------------
                initPriceSlider: function(){
                    priceRangeSlider = document.getElementById('slider-range');
                    var moneyFormat = wNumb({
                        decimals: 0,
                    });

                    // --------------------------------------------------------------
                    // for the max value, change it's value to = no limit of max price value
                    // when the value is = 0 (or null in DB), to handle this error
                    // https://t3716644.p.clickup-attachments.com/t3716644/5c78c8cd-a214-4ecd-89c3-65e4519d2462_large.gif
                    // --------------------------------------------------------------
                    const max_price_value = this.filters.maximum_price === 0 ? this.noLimitMaxPrice : this.filters.maximum_price;
                    // --------------------------------------------------------------

                    noUiSlider.create(priceRangeSlider, {
                        start: [this.filters.minimum_price, max_price_value],
                        step: @json(config('const.man_price_step')),
                        range: {
                            'min': this.noLimitMinPrice,
                            'max': this.noLimitMaxPrice
                        },
                        format: moneyFormat,
                        connect: true
                    });

                    const self = this;

                    priceRangeSlider.noUiSlider.on('update', function(values, handle) {
                        // ------------------------------------------------------------------
                        //  if max value is 0/null set the number as e no upper limit slider value
                        // ------------------------------------------------------------------
                        if( Number(values[1]) === 0 ) values[1] = self.noLimitMaxPrice;
                        // ------------------------------------------------------------------

                        // ------------------------------------------------------------------
                        //  set filter based on slider value
                        // ------------------------------------------------------------------
                        self.filters.minimum_price = values[0];
                        self.filters.maximum_price = values[1];
                        // ------------------------------------------------------------------

                        // ------------------------------------------------------------------
                        //  update value on element
                        // ------------------------------------------------------------------
                        document.getElementById('slider-range-value1').innerText = self.fromatDisplayPrice(values[0]);
                        document.getElementById('slider-range-value2').innerText = self.fromatDisplayPrice(values[1]);
                        document.getElementById('tmp-slider-min-price-value').value = moneyFormat.from(values[0]);
                        document.getElementById('tmp-slider-max-price-value').value = moneyFormat.from(values[1]);
                        // ------------------------------------------------------------------

                    });
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // format price and show above price slider
                // ------------------------------------------------------------------
                fromatDisplayPrice: function(targetValue){
                    if(Number(targetValue) < this.priceSliderMinValue){
                        return "下限無し";              // no lower limit
                    }

                    if(Number(targetValue) > this.priceSliderMaxValue){
                        return "上限無し";              // no upper limit
                    }

                    return targetValue + "万円";        // value + man unit
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // handle inital price slider and update it's value
                // based on slider
                // ------------------------------------------------------------------
                initLandAreaSlider: function(){
                    landAreaRangeSlider = document.getElementById('slider-range2');

                    var moneyFormat = wNumb({
                        decimals: 0,
                    });

                    // --------------------------------------------------------------
                    // for the max value, change it's value to = no limit of max land area value
                    // when the value is = 0 (or null in DB), to handle this error
                    // https://t3716644.p.clickup-attachments.com/t3716644/5c78c8cd-a214-4ecd-89c3-65e4519d2462_large.gif
                    // --------------------------------------------------------------
                    const max_land_area_value = this.filters.maximum_land_area === 0 ? this.noLimitMaxLandArea : this.filters.maximum_land_area;
                    // --------------------------------------------------------------

                    noUiSlider.create(landAreaRangeSlider, {
                        start: [this.filters.minimum_land_area, max_land_area_value],
                        step: @json(config('const.tsubo_area_step')),
                        range: {
                            'min': this.noLimitMinLandArea,
                            'max': this.noLimitMaxLandArea
                        },
                        format: moneyFormat,
                        connect: true
                    });

                    const self = this;

                    // Set visual min and max values and also update value hidden form inputs
                    landAreaRangeSlider.noUiSlider.on('update', function(values, handle) {

                        // ------------------------------------------------------------------
                        //  if max value is 0 / null, updates values with no upper limit land area value
                        // ------------------------------------------------------------------
                        if( Number(values[1]) === 15 ) values[1] = self.noLimitMaxLandArea;
                        // ------------------------------------------------------------------

                        // ------------------------------------------------------------------
                        //  update filters with value
                        // ------------------------------------------------------------------
                        self.filters.minimum_land_area = Number(values[0]);
                        self.filters.maximum_land_area = Number(values[1]);
                        // ------------------------------------------------------------------

                        // ------------------------------------------------------------------
                        // update element with value
                        // ------------------------------------------------------------------
                        document.getElementById('slider-range-value3').innerText = self.fromatDisplayLandArea(values[0]);
                        document.getElementById('slider-range-value4').innerText = self.fromatDisplayLandArea(values[1]);
                        document.getElementById('tmp-slider-min-land_area-value').value = moneyFormat.from(values[0]);
                        document.getElementById('tmp-slider-max-land_area-value').value = moneyFormat.from(values[1]);
                        // ------------------------------------------------------------------

                    });
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // format land area and show above land area slider
                // ------------------------------------------------------------------
                fromatDisplayLandArea: function(targetValue){
                    if(Number(targetValue) < this.landAreaSliderMinValue){
                        return "下限無し";              // no lower limit
                    }

                    if(Number(targetValue) > this.landAreaSliderMaxValue){
                        return "上限無し";              // no upper limit
                    }

                    return targetValue + "坪";          // value + tsubo
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // reset filter to initial customer value
                // ------------------------------------------------------------------
                resetFilter: function(){
                    // ------------------------------------------------------------------
                    // default min max value user from server
                    // ------------------------------------------------------------------
                    const min_price = Number(@json(toMan(Auth::guard('user')->user()->minimum_price)));
                    const max_price = Number(@json(toMan(Auth::guard('user')->user()->maximum_price)));
                    const min_land_area = Number(@json(toTsubo(Auth::guard('user')->user()->minimum_land_area)));
                    const max_land_area = Number(@json(toTsubo(Auth::guard('user')->user()->maximum_land_area)));
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // condition if max price is null / no upper limit
                    // ------------------------------------------------------------------
                    if ( !@json(Auth::guard('user')->user()->maximum_price) ) {
                        priceRangeSlider.noUiSlider.set([min_price, Number(this.noLimitMaxPrice) ]);
                    }
                    // ------------------------------------------------------------------
                    else {
                        priceRangeSlider.noUiSlider.set([min_price, max_price]);
                    }
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // condition if max land area is null / no upper limit
                    // ------------------------------------------------------------------
                    if ( !@json(Auth::guard('user')->user()->maximum_land_area) ) {
                        landAreaRangeSlider.noUiSlider.set([min_land_area, Number(this.noLimitMaxLandArea) ]);
                    }
                    // ------------------------------------------------------------------
                    else {
                        landAreaRangeSlider.noUiSlider.set([min_land_area, max_land_area]);
                    }
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // update filter with default user price and land area value
                    // ------------------------------------------------------------------

                    this.filters.minimum_price = min_price;
                    this.filters.maximum_price = @json(Auth::guard('user')->user()->maximum_price) ? max_price : Number(this.noLimitMaxPrice);
                    this.filters.minimum_land_area = min_land_area;
                    this.filters.maximum_land_area = @json(Auth::guard('user')->user()->maximum_land_area) ? max_land_area : Number(this.noLimitMaxLandArea);

                    // ------------------------------------------------------------------

                },

                // ------------------------------------------------------------------
                // send function to parent method
                // ------------------------------------------------------------------
                sendFilters: function(){
                    // ------------------------------------------------------------------
                    // update vue router, it will also trigger the render (in parent watcher)
                    // ------------------------------------------------------------------
                    this.$router.push({ name: 'index', query: this.filters }).catch(function(){});
                    this.$emit('get-filters');
                    this.sidebar_close = true;
                    this.applay_condition =true;
                    // ------------------------------------------------------------------

                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // reset filter when x button or sidebar overlay is clicked
                // ------------------------------------------------------------------
                closeSidebarFilter: function(){
                    this.sidebar_close = !this.sidebar_close;
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Wacthers
            // ------------------------------------------------------------------
            watch: {
                // --------------------------------------------------------------
                // Watch the route changes
                // This will run everytime the route is changing
                // It runs immediately after page load
                // --------------------------------------------------------------
                $route: function( to, from ){

                    // --------------------------------------------------------------
                    // update slider with current filter price/land area
                    // --------------------------------------------------------------
                    priceRangeSlider.noUiSlider.set([Number(this.filters.minimum_price), Number(this.filters.maximum_price)]);
                    landAreaRangeSlider.noUiSlider.set([Number(this.filters.minimum_land_area), Number(this.filters.maximum_land_area)]);
                    // --------------------------------------------------------------
                },
                sidebar_close:function(){
                    if(!this.sidebar_close){
                        // is opne
                        this.applay_condition = false;
                        this.beforeMinPriceValue = this.filters.minimum_price;
                        this.beforeMaxPriceValue = this.filters.maximum_price;
                        this.beforeMinLandAreaValue = this.filters.minimum_land_area;
                        this.beforeMaxLandAreaValue = this.filters.maximum_land_area;
                    }else{
                        if(!this.applay_condition){
                            priceRangeSlider.noUiSlider.set([Number(this.beforeMinPriceValue), Number(this.beforeMaxPriceValue)]);
                            landAreaRangeSlider.noUiSlider.set([Number(this.beforeMinLandAreaValue), Number(this.beforeMaxLandAreaValue)]);
                        }
                    }
                }
            }
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@else
    <script> @minify
    // ----------------------------------------------------------------------
    // If access by users not logged in (anonymous user)
    // ----------------------------------------------------------------------
    (function( $, io, document, window, undefined ){
        // ----------------------------------------------------------------------
        // Result item
        // ----------------------------------------------------------------------
        Vue.component( 'MapFilter', {
            // ------------------------------------------------------------------
            props: ['value', 'filters', 'get-filters'] ,
            template: '#map-sidebar-filter',
            // ------------------------------------------------------------------
            data: function(){
                // --------------------------------------------------------------
                var data = {
                    sidebar_close : true,
                    first_open : true,
                    applay_condition : false,
                    // --------------------------------------------------------------
                    //  min and max value from server for slider
                    // --------------------------------------------------------------
                    priceSliderMinValue : Number(@json(toMan($lowest_price_filter))),
                    priceSliderMaxValue : Number(@json(toMan($highest_price_filter))),
                    landAreaSliderMinValue : Number(@json(toTsubo($lowest_land_area_filter))),
                    landAreaSliderMaxValue : Number(@json(toTsubo($highest_land_area_filter))),
                    // --------------------------------------------------------------

                    // --------------------------------------------------------------
                    //  no upper limit and no lower limit value for slider
                    // --------------------------------------------------------------
                    noLimitMinPrice : Number(@json(toMan($lowest_price_filter))) - @json(config('const.man_price_step')),
                    noLimitMaxPrice : Number(@json(toMan($highest_price_filter))) + @json(config('const.man_price_step')),
                    noLimitMinLandArea : Number(@json(toTsubo($lowest_land_area_filter))) - @json(config('const.tsubo_area_step')),
                    noLimitMaxLandArea : Number(@json(toTsubo($highest_land_area_filter))) + @json(config('const.tsubo_area_step')),
                    // --------------------------------------------------------------

                    beforeMinPriceValue : 0,
                    beforeMaxPriceValue : 0,
                    beforeMinLandAreaValue : 0,
                    beforeMaxLandAreaValue : 0,
                };
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                return data;
                // --------------------------------------------------------------
            },
            // ------------------------------------------------------------------
            mounted: function(){

                // ------------------------------------------------------------------
                //  Initialize create slider on mounted
                // ------------------------------------------------------------------
                this.initPriceSlider();
                this.initLandAreaSlider();
                // ------------------------------------------------------------------

            },
            // ------------------------------------------------------------------
            // Computed properties
            // ------------------------------------------------------------------
            computed: {},
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Call to action methods
            // ------------------------------------------------------------------
            methods: {
                // ------------------------------------------------------------------
                // handle inital price slider and update it's value
                // based on slider
                // ------------------------------------------------------------------
                initPriceSlider: function(){

                    priceRangeSlider = document.getElementById('slider-range');

                    var moneyFormat = wNumb({
                        decimals: 0,
                    });

                    // --------------------------------------------------------------
                    // for the max value, change it's value to = no limit of max price value
                    // when the value is = 0 (or null in DB), to handle this error
                    // https://t3716644.p.clickup-attachments.com/t3716644/5c78c8cd-a214-4ecd-89c3-65e4519d2462_large.gif
                    // --------------------------------------------------------------
                    const max_price_value = this.filters.maximum_price === 0 ? this.noLimitMaxPrice : this.filters.maximum_price;
                    // --------------------------------------------------------------

                    noUiSlider.create(priceRangeSlider, {
                        start: [this.filters.minimum_price, max_price_value],
                        step: @json(config('const.man_price_step')),
                        range: {
                            'min': this.noLimitMinPrice,
                            'max': this.noLimitMaxPrice
                        },
                        format: moneyFormat,
                        connect: true
                    });

                    const self = this;
                    priceRangeSlider.noUiSlider.on('update', function(values, handle) {
                        // ------------------------------------------------------------------
                        //  if max value is 0/null set the number as e no upper limit slider value
                        // ------------------------------------------------------------------
                        if( Number(values[1]) === 0 ) values[1] = self.noLimitMaxPrice;
                        // ------------------------------------------------------------------

                        // ------------------------------------------------------------------
                        //  set filter based on slider value
                        // ------------------------------------------------------------------
                        self.filters.minimum_price = values[0];
                        self.filters.maximum_price = values[1];
                        // ------------------------------------------------------------------

                        // ------------------------------------------------------------------
                        //  update value on element
                        // ------------------------------------------------------------------
                        document.getElementById('slider-range-value1').innerText = self.fromatDisplayPrice(values[0]);
                        document.getElementById('slider-range-value2').innerText = self.fromatDisplayPrice(values[1]);
                        document.getElementById('tmp-slider-min-price-value').value = moneyFormat.from(values[0]);
                        document.getElementById('tmp-slider-max-price-value').value = moneyFormat.from(values[1]);
                        // ------------------------------------------------------------------

                    });
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // format price and show above price slider
                // ------------------------------------------------------------------
                fromatDisplayPrice: function(targetValue){
                    if(Number(targetValue) < this.priceSliderMinValue){
                        return "下限無し";              // no lower limit
                    }

                    if(Number(targetValue) > this.priceSliderMaxValue){
                        return "上限無し";              // no upper limit
                    }

                    return targetValue + "万円";        // value + man unit
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // handle inital price slider and update it's value
                // based on slider
                // ------------------------------------------------------------------
                initLandAreaSlider: function(){
                    landAreaRangeSlider = document.getElementById('slider-range2');

                    var moneyFormat = wNumb({
                        decimals: 0,
                    });

                    // --------------------------------------------------------------
                    // for the max value, change it's value to = no limit of max land area value
                    // when the value is = 0 (or null in DB), to handle this error
                    // https://t3716644.p.clickup-attachments.com/t3716644/5c78c8cd-a214-4ecd-89c3-65e4519d2462_large.gif
                    // --------------------------------------------------------------
                    const max_land_area_value = this.filters.maximum_land_area === 0 ? this.noLimitMaxLandArea : this.filters.maximum_land_area;
                    // --------------------------------------------------------------

                    noUiSlider.create(landAreaRangeSlider, {
                        start: [this.filters.minimum_land_area, max_land_area_value],
                        step: @json(config('const.tsubo_area_step')),
                        range: {
                            'min': this.noLimitMinLandArea,
                            'max': this.noLimitMaxLandArea
                        },
                        format: moneyFormat,
                        connect: true
                    });


                    const self = this;

                    // Set visual min and max values and also update value hidden form inputs
                    landAreaRangeSlider.noUiSlider.on('update', function(values, handle) {

                        // ------------------------------------------------------------------
                        //  if max value is 0 / null, updates values with no upper limit land area value
                        // ------------------------------------------------------------------
                        if( Number(values[1]) === 15 ) values[1] = self.noLimitMaxLandArea;
                        // ------------------------------------------------------------------

                        // ------------------------------------------------------------------
                        //  update filters with value
                        // ------------------------------------------------------------------
                        self.filters.minimum_land_area = Number(values[0]);
                        self.filters.maximum_land_area = Number(values[1]);
                        // ------------------------------------------------------------------

                        // ------------------------------------------------------------------
                        // update element with value
                        // ------------------------------------------------------------------
                        document.getElementById('slider-range-value3').innerText = self.fromatDisplayLandArea(values[0]);
                        document.getElementById('slider-range-value4').innerText = self.fromatDisplayLandArea(values[1]);
                        document.getElementById('tmp-slider-min-land_area-value').value = moneyFormat.from(values[0]);
                        document.getElementById('tmp-slider-max-land_area-value').value = moneyFormat.from(values[1]);
                        // ------------------------------------------------------------------

                    });
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // format land area and show above land area slider
                // ------------------------------------------------------------------
                fromatDisplayLandArea: function(targetValue){
                    if(Number(targetValue) < this.landAreaSliderMinValue){
                        return "下限無し";              // no lower limit
                    }

                    if(Number(targetValue) > this.landAreaSliderMaxValue){
                        return "上限無し";              // no upper limit
                    }

                    return targetValue + "坪";          // value + tsubo
                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // reset filter to initial customer value
                // ------------------------------------------------------------------
                resetFilter: function(){
                     // ------------------------------------------------------------------
                    // default min max value user from server
                    // ------------------------------------------------------------------
                    const min_price = 0;
                    const min_land_area = 0;

                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // condition if max price is null / no upper limit
                    // ------------------------------------------------------------------
                    priceRangeSlider.noUiSlider.set([min_price, Number(this.noLimitMaxPrice) ]);
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // condition if max land area is null / no upper limit
                    // ------------------------------------------------------------------
                    landAreaRangeSlider.noUiSlider.set([min_land_area, Number(this.noLimitMaxLandArea) ]);
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // update filter with default user price and land area value
                    // ------------------------------------------------------------------
                    this.filters.minimum_price = min_price;
                    this.filters.maximum_price = Number(this.noLimitMaxPrice);
                    this.filters.minimum_land_area = min_land_area;
                    this.filters.maximum_land_area = Number(this.noLimitMaxLandArea);
                    // ------------------------------------------------------------------
                },

                // ------------------------------------------------------------------
                // send function to parent method
                // ------------------------------------------------------------------
                sendFilters: function(){

                    // ------------------------------------------------------------------
                    // update vue router, it will also trigger the render (in parent watcher)
                    // ------------------------------------------------------------------
                    this.$router.push({ name: 'index', query: this.filters }).catch(function(){});
                    this.$emit('get-filters');
                    this.sidebar_close = true;
                    this.applay_condition =true;
                    // ------------------------------------------------------------------

                },
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // reset filter when x button or sidebar overlay is clicked
                // ------------------------------------------------------------------
                closeSidebarFilter: function(){
                    this.sidebar_close = !this.sidebar_close;
                }
            },
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Wacthers
            // ------------------------------------------------------------------
            watch: {
                // --------------------------------------------------------------
                // Watch the route changes
                // This will run everytime the route is changing
                // It runs immediately after page load
                // --------------------------------------------------------------
                $route: function( to, from ){

                    // --------------------------------------------------------------
                    // update slider with current filter price/land area
                    // --------------------------------------------------------------
                    priceRangeSlider.noUiSlider.set([Number(this.filters.minimum_price), Number(this.filters.maximum_price)]);
                    landAreaRangeSlider.noUiSlider.set([Number(this.filters.minimum_land_area), Number(this.filters.maximum_land_area)]);
                    // --------------------------------------------------------------
                },
                sidebar_close:function(val){
                    if(this.first_open){
                        this.first_open = false;
                        priceRangeSlider.noUiSlider.set([1000,3000]);
                        landAreaRangeSlider.noUiSlider.set([20,50]);
                    }

                    if(!this.sidebar_close){
                        // is opne
                        this.applay_condition = false;
                        this.beforeMinPriceValue = this.filters.minimum_price;
                        this.beforeMaxPriceValue = this.filters.maximum_price;
                        this.beforeMinLandAreaValue = this.filters.minimum_land_area;
                        this.beforeMaxLandAreaValue = this.filters.maximum_land_area;
                    }else{
                        if(!this.applay_condition){
                            priceRangeSlider.noUiSlider.set([Number(this.beforeMinPriceValue), Number(this.beforeMaxPriceValue)]);
                            landAreaRangeSlider.noUiSlider.set([Number(this.beforeMinLandAreaValue), Number(this.beforeMaxLandAreaValue)]);
                        }
                    }
                }
            }
            // ------------------------------------------------------------------
        });
        // ----------------------------------------------------------------------
    }( jQuery, _, document, window ));
@endminify </script>
@endif

