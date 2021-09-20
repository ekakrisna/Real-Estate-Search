@extends('frontend._base.vueform')
@section('title', $title)
@section('form-content')

    <!-- Page preloader - Start -->
    <div v-if="!isMounted && $route.query.step" class="preloader preloader-fullscreen d-flex justify-content-center align-items-center">
        <div class="folding-cube">
            <div class="cube cube-1"></div>
            <div class="cube cube-2"></div>
            <div class="cube cube-4"></div>
            <div class="cube cube-3"></div>
        </div>
    </div>
    <!-- Page preloader - End -->

    <template v-if="$route.query.step == 1">

        <div class="container">
            <div class="multisteps-form mt-5">
                <div class="row">
                    <div class="col-12 col-lg-12 mb-2 mx-auto">
                        <div class="multisteps-form__progress">
                            <button class="multisteps-form__progress-btn js-active" type="button" >
                                <img src="{{ asset('frontend/assets/images/icons/slider_dot.png') }}" class="active-position mx-auto"
                                    alt="icon_password" class="icon-title">
                            </button>
                            <button class="multisteps-form__progress-btn" type="button" ></button>
                            <button class="multisteps-form__progress-btn" type="button" >
                            </button>
                            <button class="multisteps-form__progress-btn" type="button" >        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-signup pt-4 full-screen">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-10 mx-auto">
                        <div class="content">
                            <div class="content-header">
                            </div>
                            <div class="content-body">
                                <div class="row">
                                    <div class="col-12">
                                       <div class="form-signup">
                                            <form id="form-signup" class="form form-login-validate" data-parsley>
                                                <h2 class="title title-pages">
                                                    <img src="{{ asset('frontend/assets/images/icons/icon_mail.png') }}"
                                                        alt="icon_mail" class="icon-title">
                                                    <span class="text-title">確認メールの送信</span>
                                                </h2>
                                                <p class="desc">入力したアドレスに本登録用のメールを送信します。 <br>
                                                    届いたメールのリンクに24時間以内にアクセスし、本登録に必要な情報の入力を進めてください。
                                                </p>
                                                
                                                <div class="row mt-4">
                                                    <div class="col-12 col-lg-6">
                                                        <div class="form-group form-with-icon">
                                                            <i v-if="spinnerCheckEmail == true" class="fal fa-cog fa-spin" style="top: 35%"></i>
                                                            <i v-else class="fa fa-envelope"></i>
                                                            <!-- data-parsley-pattern="^(?=[^@]*[A-Za-z])([a-zA-Z0-9])(([a-zA-Z0-9])*([\.])?([a-zA-Z0-9]*))*@(([a-zA-Z0-9\-])+(\.))+([a-zA-Z]{2,4})+$" -->
                                                            <input class="form-control" type="text" id="input-email"
                                                                name="email" v-model="email" required placeholder="example@example.com"
                                                                data-parsley-trigger="focusout"
                                                                @input="checkEmail"
                                                                data-parsley-pattern="^[a-zA-Z0-9\-\._]+@[a-zA-Z0-9\-]+(\.)[a-zA-Z]+$"
                                                                data-parsley-required-message="メールアドレスを入力してください。"
                                                                data-parsley-type-message="正しい形式でメールアドレスを入力してください。"
                                                                data-parsley-errors-container="#error-container-email"
                                                                data-parsley-pattern-message="正しい形式でメールアドレスを入力してください。"
                                                            />
                                                        </div>
                                                        <span class="error-notif" role="alert" v-if="isEmailExists"> <p class="text-error">このメールアドレスは既に登録済みです。</p> </span>
                                                        <div id="error-container-email">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group mb-0">
                                                    <div class="row mt-5">
                                                        <div class="col-11 col-lg-4 mx-auto">
                                                            <!-- Submit button - Start -->
                                                            <button type="button" class="btn btn-primary-round btn-alternate"
                                                                @click="signup()"  :class="{ 'is-loading': isLoading }">

                                                                <span class="innerset">
                                                                    <span class="interface">送信</span>
                                                                    <span class="interface">
                                                                        <i class="fal fa-spin" :class="[ 'fa-cog', 'fs-24' ]"></i>
                                                                    </span>
                                                                </span>
                                                                
                                                            </button>
                                                            <!-- Submit button - End -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template v-if="$route.query.step == 2">

        <div class="container">
            <div class="multisteps-form mt-5">
                <div class="row">
                    <div class="col-12 col-lg-12 mb-2">
                        <div class="multisteps-form__progress">
                            <button class="multisteps-form__progress-btn js-active" type="button" >
                            </button>
                            <button class="multisteps-form__progress-btn js-active" type="button" >
                                <img src="{{ asset('frontend/assets/images/icons/slider_dot.png') }}" class="active-position mx-auto"
                                    alt="icon_password" class="icon-title">
                            </button>
                            <button class="multisteps-form__progress-btn" type="button" >
                            </button>
                            <button class="multisteps-form__progress-btn" type="button" >        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-signup pt-4 full-screen">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-10 mx-auto">
                        <div class="content">
                            <div class="content-body">
                                <div class="row">
                                    <div class="col-12">
                                        <form class="form" data-parsley>
                                            <h2 class="title title-pages">
                                                <img src="{{ asset('frontend/assets/images/icons/icon_mail.png') }}"
                                                    alt="icon_mail" class="icon-title">
                                                <span class="text-title">本登録用のメールを送信しました</span>
                                            </h2>
                                            <p class="desc">
                                                入力したメールアドレスに本登録用のメールを送信しました。 <br>
                                                届いたメールのリンクより本登録の操作を行ってください。
                                            </p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template v-if="$route.query.step == 3">

        <div class="container">
            <div class="multisteps-form mt-5">
                <div class="row">
                    <div class="col-12 col-lg-12 mb-2">
                        <div class="multisteps-form__progress">
                            <button class="multisteps-form__progress-btn js-active" type="button" >
                            </button>
                            <button class="multisteps-form__progress-btn js-active" type="button" ></button>
                            <button class="multisteps-form__progress-btn js-active" type="button" >
                                <img src="{{ asset('frontend/assets/images/icons/slider_dot.png') }}" class="active-position mx-auto"
                                    alt="icon_password" class="icon-title">
                            </button>
                            <button class="multisteps-form__progress-btn" type="button" >        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-setting pt-4">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-10 mx-auto">
                        <div class="content">
                            <div class="content-body">
                                <form id="form-customerinfo" class="form-setting" data-parsley>
                                    <h2 class="title title-pages">
                                        <img src="{{ asset('frontend/assets/images/icons/icon_account.png') }}"
                                            alt="icon_mail" class="icon-title">
                                        <span class="text-title">アカウント情報の入力</span>
                                    </h2>

                                    <!-- customer input -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6">
                                                        <label>メールアドレス</label>

                                                        <input class="form-control" type="email" id="input-email"
                                                            name="email" v-model="item.email" required placeholder="example@example.com"
                                                            data-parsley-trigger="focusout" disabled
                                                            data-parsley-email-exists="[{{ route('api.customer_email.exists') }},0]"
                                                            data-parsley-required-message="メールアドレスを入力してください。"
                                                            data-parsley-type-message="正しい形式でメールアドレスを入力してください。" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6">
                                                    <label>パスワード<span style="color: #FE4B44">※必須</span></label>

                                                    <input class="form-control" type="password" id="input-password"
                                                        name="password" v-model="item.password" required
                                                        data-parsley-trigger="focusout"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6">
                                                    <label>氏名<span style="color: #FE4B44">※必須</span></label>
                                                    
                                                    <input class="form-control form-control-setting" type="text" id="input-name"
                                                        required placeholder="仙台太郎"
                                                        name="name" v-model="item.name"
                                                        data-parsley-trigger="focusout"
                                                        data-parsley-required-message="氏名を入力してください。" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6">
                                                    <label>電話番号</label>
                                                    <input class="form-control form-control-setting" type="text" id="input-phone"
                                                        name="phone" v-model="item.phone" placeholder="000-000-0000"
                                                        data-parsley-trigger="focusout"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- list fav area -->
                                    <p class="label-form">お気に入りエリア</p>
                                    <div class="row">
                                        <div class="col-12 col-lg-6 input-flag" v-for="(data,index) in favoritedAreas">
                                            <div class="form-group">
                                                <a href="javascript:void(0)" class="form-label-flag" @click="addFavorite(index)" v-if="data.default">
                                                    <i class="icon-flag fas fa-flag text-red fs-24"></i>
                                                    <p type="text" class="form-control">@{{data.prefecture.name}}@{{data.city.name}}@{{data.cities_area != null ? data.cities_area.display_name :"" }}</p>
                                                </a>
                                                <a href="javascript:void(0)" class="form-label-flag" @click="addFavorite(index)" v-else>
                                                    <i class="icon-flag fas fa-flag text-muted fs-24"></i>
                                                    <p type="text" class="form-control">@{{data.prefecture.name}}@{{data.city.name}}@{{data.cities_area != null  ? data.cities_area.display_name :"" }}</p>
                                                </a>                                                                                                
                                                <a href="javascript:void(0)" class="btn-minus" v-on:click="removeFavoriteArea(index)">                                                    
                                                    <i class="icon-flag fas fa-minus-circle fs-24 text-muted"></i>                                                                                                     
                                                </a>                                                                                                                    
                                            </div>
                                        </div>
                                    </div>        
                                    
                                    <!-- add area input -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="content-area-fav">
                                                <div class="label-with-icon">
                                                    <img src="{{ asset('frontend/assets/images/icons/icon_add_fav_area.png') }}" alt="icon-plus">
                                                    <p class="text-label">お気に入りエリアを追加する</p>
                                                </div>
                                                <div class="form-area-fav">
                                                    <form class="form-setting" id="form-favoritearea" data-parsley>                                                                                                                                            
                                                        <div class="row">
                                                            <!-- prefecture -->
                                                            <div class="col-12 col-md mb-3 mb-lg-0">            
                                                                <div class="select-required">                                                                                                                           
                                                                    <select name="prefectures" class="form-control" v-model="valuePrefecture" data-parsley-required-message="未選択の項目があります。" disabled>                                                                                                                                                                                                                     
                                                                        <option value="" :selected="valuePrefecture">宮城県</option>            
                                                                        <option v-for="(data,index) in prefectures" :key="data.id" :value="data.id" >@{{data.name}}</option>
                                                                    </select>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>

                                                            <!-- prefecture area -->
                                                            <div class="col-12 col-md mb-3 mb-lg-0">
                                                                <div class="select-required">
                                                                    <select name="prefecture_area" class="form-control" v-model="value.valuePrefectureArea" data-parsley-required-message="未選択の項目があります。" v-if="!isLoadingPrefectureArea">
                                                                        <option value="" class="d-none" selected>選択してください</option>
                                                                        <option v-for="(data,index) in prefecture_area" :key="data.id" :value="data.id">@{{data.display_name}}</option>
                                                                    </select>   
                                                                    <p class="form-control text-center" style="padding: 0.75rem 1rem;height: auto;" v-if="isLoadingPrefectureArea">
                                                                        <span class="innerset">
                                                                            <span class="interface">
                                                                                <i class="fal fa-spin fa-cog fs-24"></i>
                                                                            </span>
                                                                        </span>
                                                                    </p>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                                <div class="mb-lg-4"></div>
                                                            </div>
                                                            
                                                            <!-- city -->
                                                            <div class="col-12 col-md mb-3 mb-lg-0">
                                                                <div class="select-required">                                                                    
                                                                    <select name="city" class="form-control" v-model="value.valueCity" :disabled="!value.valuePrefectureArea" @change="cityChangeHandle(value.valueCity)" data-parsley-required-message="未選択の項目があります。" v-if="!isLoadingCity">
                                                                        <option v-if="value.valuePrefectureArea" value="" selected class="d-none">選択してください</option>
                                                                        <optgroup v-for="(data,index) in city" v-if="data.cities.length > 0" :label="data.group_character">
                                                                            <option v-for="(data_city,index) in data.cities" :key="data_city.id" :value="data_city.id">@{{data_city.name}}</option>
                                                                        </optgroup>
                                                                    </select>
                                                                    <p class="form-control text-center" style="padding: 0.75rem 1rem;height: auto;" v-if="isLoadingCity">
                                                                        <span class="innerset">                                                                        
                                                                            <span class="interface">
                                                                                <i class="fal fa-spin fa-cog fs-24"></i>
                                                                            </span>
                                                                        </span>
                                                                    </p>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                                <div class="mb-lg-4"></div>
                                                            </div>

                                                            <!-- town -->
                                                            <div class="col-12 col-md mb-5 mb-lg-0">
                                                                <div class="select-required">                                                                    
                                                                    <select name="town" class="form-control" v-model="value.valueCitiesArea" :disabled="!value.valueCity || !value.valuePrefectureArea" v-if="!isLoadingTown" data-parsley-required-message="未選択の項目があります。">
                                                                        <option v-if="value.valuePrefectureArea" value="" class="d-none">選択してください</option>
                                                                        <optgroup v-for="(data,index) in town" v-if="data.cities_areas.length > 0" :label="data.group_character">
                                                                            <option v-for="(data_cities,index) in data.cities_areas" :key="data_cities.id" :value="data_cities.id">@{{data_cities.display_name}}</option>                                                                            
                                                                        </optgroup>                                                                        
                                                                    </select>
                                                                    <p class="form-control text-center" style="padding: 0.75rem 1rem;height: auto;" v-if="isLoadingTown">
                                                                        <span class="innerset">                                                                        
                                                                            <span class="interface">
                                                                                <i class="fal fa-spin fa-cog fs-24"></i>
                                                                            </span>
                                                                        </span>
                                                                    </p>
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-6 col-lg-4 mx-auto px-lg-0 pt-3 pt-lg-4">    
                                                                <!-- Submit button - Start -->
                                                                <button type="button" class="btn btn-primary-round btn-alternate"
                                                                    @click="addFavoriteArea()"  :class="{ 'is-loading': isLoading }" 
                                                                    :disabled="!valuePrefecture || !value.valueCity">

                                                                    <span class="innerset">
                                                                        <span class="interface">追加する</span>
                                                                        <span class="interface">
                                                                            <i class="fal fa-spin" :class="[ 'fa-cog', 'fs-24' ]"></i>
                                                                        </span>
                                                                    </span>
                                                                    
                                                                </button>
                                                                <!-- Submit button - End -->                                                                                                                                                                                                                                                                
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- customer settings -->
                                    <div class="form-setting-customer">
                                        <!-- customer settings input -->
                                        <div class="row mt-4">      

                                            <div class="col-12 col-lg-6 mb-5">
                                                <div class="content-land">
                                                    <p class="label-form">検討可能な土地金額下限</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col"> 
                                                                <div class="select-required">                                                              
                                                                    <select required name="minimum_price" id="minimum_price" v-model="item.minimum_price" class="form-control" data-parsley-le="#maximum_price" data-parsley-trigger="input change" @input="trigger('#maximum_price')"> 
                                                                        <option value="0" :selected="item.minimum_price == 0" >下限なし</option>
                                                                        <option v-for="(data,index) in consider_amount" :key="data.value" :value="data.value" :selected="item.minimum_price == data.value">@{{data.value | toManDisplay}}</option>
                                                                    </select>                                                                    
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">                                                            
                                                                <div class="select-required">                                                              
                                                                    <select required name="maximum_price" id="maximum_price" v-model="item.maximum_price" class="form-control" data-parsley-ge="#minimum_price" data-parsley-trigger="input change" @input="trigger('#minimum_price')">     
                                                                        <option value="0" :selected="item.maximum_price == 0">上限なし</option>                                                                                                                                    
                                                                        <option v-for="(data,index) in consider_amount" :key="data.id" :value="data.value" :selected="item.maximum_price == data.value">@{{data.value | toManDisplay}}</option>
                                                                    </select>                                                                    
                                                                    <i class="fa fa-chevron-down"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-6 mb-5">
                                                <div class="content-land">
                                                    <p class="label-form">検討可能な土地 + 建物金額下限</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col">
                                                                <select name="minimum_price_land_area" id="minimum_price_land_area" v-model="item.minimum_price_land_area" class="form-control" data-parsley-le="#maximum_price_land_area" data-parsley-trigger="input change" @input="trigger('#maximum_price_land_area')">                                                                    
                                                                    <option value="0" :selected="item.minimum_price_land_area == 0" >下限なし</option>
                                                                    <option v-for="(data,index) in consider_amount" :key="data.id" :value="data.value" :selected="item.minimum_price_land_area == data.value" >@{{data.value | toManDisplay }}</option>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">
                                                                <select name="maximum_price_land_area" id="maximum_price_land_area" v-model="item.maximum_price_land_area" class="form-control" data-parsley-ge="#minimum_price_land_area" data-parsley-trigger="input change" @input="trigger('#minimum_price_land_area')">    
                                                                    <option value="0" :selected="item.maximum_price_land_area == 0">上限なし</option>                                                        
                                                                    <option v-for="(data,index) in consider_amount" :key="data.id" :value="data.value" :selected="item.maximum_price_land_area == data.value">@{{data.value | toManDisplay }}</option>    
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-6">
                                                <div class="content-land">
                                                    <p class="label-form">希望の土地面積下限</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col">
                                                                <select name="minimum_land_area" id="minimum_land_area" v-model="item.minimum_land_area" class="form-control" data-parsley-le="#maximum_land_area" data-parsley-trigger="input change" @input="trigger('#maximum_land_area')">       
                                                                    <option :value="0" :selected="item.minimum_land_area == 0">下限なし</option>                                                             
                                                                    <option v-for="(data,index) in land_area" :key="data.id" :value="data.value" :selected="item.minimum_land_area == data.value">@{{data.value | toTsubo | numeral('0,0')}}坪</option>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">
                                                                <select name="maximum_land_area" id="maximum_land_area" v-model="item.maximum_land_area" class="form-control" data-parsley-ge="#minimum_land_area" data-parsley-trigger="input change" @input="trigger('#minimum_land_area')">                                                              
                                                                    <option value="0" :selected="item.maximum_land_area == 0">上限なし</option>
                                                                    <option v-for="(data,index) in land_area" :key="data.id" :value="data.value" >@{{data.value | toTsubo | numeral('0,0')}}坪</option>                                                                  
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                        
                                        </div>
                                    </div>
                                    

                                    <div class="form-group mb-0">
                                        <div class="row mt-5">
                                            <div class="col-11 col-lg-4 mx-auto">
                                                <!-- Submit button - Start -->
                                                <button type="button" class="btn btn-primary-round btn-alternate"
                                                    @click="submit()"  :class="{ 'is-loading': isLoading }">

                                                    <span class="innerset">
                                                        <span class="interface">確認ボタン</span>
                                                        <span class="interface">
                                                            <i class="fal fa-spin" :class="[ 'fa-cog', 'fs-24' ]"></i>
                                                        </span>
                                                    </span>
                                                    
                                                </button>
                                                <!-- Submit button - End -->
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template v-if="$route.query.step == 4">

        <div class="container">
            <div class="multisteps-form mt-5">
                <div class="row">
                    <div class="col-12 col-lg-12 mb-2">
                        <div class="multisteps-form__progress">
                            <button class="multisteps-form__progress-btn js-active" type="button" >
                            </button>
                            <button class="multisteps-form__progress-btn js-active" type="button" ></button>
                            <button class="multisteps-form__progress-btn js-active" type="button" >
                                <img src="{{ asset('frontend/assets/images/icons/slider_dot.png') }}" class="active-position mx-auto"
                                    alt="icon_password" class="icon-title">
                            </button>
                            <button class="multisteps-form__progress-btn" type="button" >        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-setting pt-4">
            <div class="container">

                <div class="row">
                    <div class="col-12 col-lg-10 mx-auto">
                        <div class="content">
                            <div class="content-header">
                                <h2 class="title title-pages">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_account.png') }}" alt="icon_account_setting" class="icon-title">
                                    <span class="text-title">アカウント情報の確認</span>
                                </h2>
                            </div>

                            <div class="content-body">
                                <div class="form-setting parsley-minimal" data-parsley>
                                    
                                    <!-- customer info -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6">
                                                        <label>メールアドレス</label>
                                                        <label class="form-control form-control-red-left"> @{{item.email}} </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6">
                                                        <label>パスワード</label>
                                                        <label class="form-control form-control-red-left"> @{{generatedPassword}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6">
                                                        <label>氏名</label>
                                                        <label class="form-control form-control-red-left"> @{{item.name}} </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6">
                                                        <label>電話番号</label>
                                                        <label class="form-control form-control-red-left"> @{{item.phone}} </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- favorite area list -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>お気に入りエリア</label>
                                                <div class="row">
                                                    <div class="col-12 col-lg-6 pr-5" v-for="(data,index) in favoritedAreas">
                                                        <label class="form-control form-control-red-left" > @{{data.prefecture.name}}@{{data.city.name}}@{{data.cities_area != null ? data.cities_area.display_name :"" }} </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- customer settings -->
                                    <div class="form-setting-customer">
                                        <div class="row mt-4">
                                            
                                            <div class="col-12 col-lg-6 mb-5">
                                                <div class="content-land">
                                                    <p class="label-form">検討可能な土地金額下限</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col"> 
                                                                <label class="form-control form-control-red-left">
                                                                    <template v-if="item.minimum_price == 0">
                                                                        上限なし
                                                                    </template> 
                                                                    <template v-else-if="item.minimum_price">
                                                                        @{{item.minimum_price | toManDisplay}}
                                                                    </template> 
                                                                </label>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">                                                            
                                                                <label class="form-control form-control-red-left">
                                                                    <template v-if="item.maximum_price == 0">
                                                                        上限なし
                                                                    </template> 
                                                                    <template v-else-if="item.maximum_price">
                                                                        @{{item.maximum_price | toManDisplay}}
                                                                    </template> 
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-6 mb-5">
                                                <div class="content-land">
                                                    <p class="label-form">検討可能な土地 + 建物金額下限</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col"> 
                                                                <label class="form-control form-control-red-left"> 
                                                                    <template v-if="item.minimum_price_land_area == 0">
                                                                        上限なし
                                                                    </template>  
                                                                    <template v-else-if="item.minimum_price_land_area">
                                                                        @{{item.minimum_price_land_area | toManDisplay}}
                                                                    </template>
                                                                </label>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">                                                            
                                                                <label class="form-control form-control-red-left">
                                                                    <template v-if="item.maximum_price_land_area == 0">
                                                                        上限なし
                                                                    </template> 
                                                                    <template v-else-if="item.maximum_price_land_area">
                                                                        @{{item.maximum_price_land_area | toManDisplay}}
                                                                    </template> 
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-6 mb-5">
                                                <div class="content-land">
                                                    <p class="label-form">希望の土地面積下限</p>
                                                    <div class="row">
                                                        <div class="col-12 d-flex align-items-center px-0">
                                                            <div class="col"> 
                                                                <label class="form-control form-control-red-left"> 
                                                                    <template v-if="item.minimum_land_area == 0">
                                                                        上限なし
                                                                    </template> 
                                                                    <template v-else-if="item.minimum_land_area">
                                                                        @{{item.minimum_land_area | toTsubo | numeral('0,0')}}
                                                                    </template> 
                                                                </label>
                                                            </div>
                                                            <span>～</span>
                                                            <div class="col">                                                            
                                                                <label class="form-control form-control-red-left">
                                                                    <template v-if="item.maximum_land_area == 0">
                                                                        上限なし
                                                                    </template> 
                                                                    <template v-else-if="item.maximum_land_area">
                                                                        @{{item.maximum_land_area | toTsubo | numeral('0,0')}}
                                                                    </template>  
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6 col-lg-4 mx-auto">
                                            <!-- Back button - Start -->
                                            <button type="button" class="btn btn-primary-round btn-alternate btn-black-border"
                                                @click="handleBackButton()" :disabled="isBackButtonDisabled">

                                                <span class="innerset">
                                                    <span class="interface">戻るボタン</span>
                                                    <span class="interface">
                                                        <i class="fal fa-spin" :class="[ 'fa-cog', 'fs-24' ]"></i>
                                                    </span>
                                                </span>
                                                
                                            </button>
                                            <!-- Back button - End -->
                                        </div>
                                        <div class="col-6 col-lg-4 mx-auto">
                                            <!-- Back button - Start -->
                                            <button type="button" class="btn btn-primary-round btn-alternate"
                                                @click="register()"  :class="{ 'is-loading': isLoading }" :disabled="isRegisterButtonDisabled">

                                                <span class="innerset">
                                                    <span class="interface">登録ボタン</span>
                                                    <span class="interface">
                                                        <i class="fal fa-spin" :class="[ 'fa-cog', 'fs-24' ]"></i>
                                                    </span>
                                                </span>
                                                
                                            </button>
                                            <!-- Back button - End -->
                                        </div>
                                    </div>

                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </template>

    <template v-if="$route.query.step == 5">
        
        <div class="container">
            <div class="multisteps-form mt-5">
                <div class="row">
                    <div class="col-12 col-lg-12 mb-2 mx-auto">
                        <div class="multisteps-form__progress">
                            <button class="multisteps-form__progress-btn js-active first" type="button" >
                            </button>
                            <button class="multisteps-form__progress-btn js-active" type="button" >
                            </button>
                            <button class="multisteps-form__progress-btn js-active" type="button" >
                            </button>
                            <button class="multisteps-form__progress-btn js-active" type="button" >        
                                <img src="{{ asset('frontend/assets/images/icons/slider_dot.png') }}" class="active-position mx-auto"
                                    alt="icon_password" class="icon-title">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-signup pt-4 full-screen">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-10 mx-auto">
                        <div class="content">
                            <div class="content-header">
                                <h2 class="title title-pages">
                                    <img src="{{ asset('frontend/assets/images/icons/icon_account.png') }}"
                                        alt="icon_password" class="icon-title">
                                    <span class="text-title">登録完了</span>
                                </h2>
                            </div>
                            <div class="content-body">
                                <div class="row">
                                    <div class="col-12">
                                        <form id="form-signup" data-parsley>
                                            <p class="desc">
                                                会員登録が完了しました。 <br>
                                                引き続きトチサーチをご利用ください。
                                            </p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-5">                                    
                    <a href="{{ route('customer-login') }}" type="submit" class="btn btn-primary-round btn-max-320" href="">                                        
                        <span>ログイン画面へ</span>                                        
                    </a>
                </div>
            </div>
        </div>
    </template>
@endsection


@push('vue-scripts')

    <!-- Preloaders - Start -->
    @include('backend.vue.components.page-loader.import')
    <!-- Preloaders - End -->

    <!-- Frontend components - Start -->
    @include('frontend.vue.button-action.import')
    <!-- Frontend components - End -->

    <script>
        @minify
            (function(io, $, window, document, undefined) {
                // ----------------------------------------------------------------------
                window.queue = {}; // Event queue
                // ----------------------------------------------------------------------

                // ----------------------------------------------------------------------
                // Vue router
                // ----------------------------------------------------------------------
                router = {
                    mode: 'history',
                    routes: [{ 
                        name: 'index', path:'/signup' ,
                        component: { template: '<div/>' }
                    }]
                };
                // ----------------------------------------------------------------------

                // ----------------------------------------------------------------------
                // Vuex store
                // ----------------------------------------------------------------------
                store = {
                    // ------------------------------------------------------------------
                    // Reactive state
                    // ------------------------------------------------------------------
                    state: function() {
                        var state = {
                            status: {
                                mounted: false,
                                loading: false
                            },
                            preset: {
                                api: {
                                    check_email: @json( route('api.customer_email.exists') ),
                                    prefecture: @json( route( 'api.location.prefecture' )),
                                    prefecture_areas: @json( route( 'api.location.prefecture_areas' )),
                                    prefecture_area: @json( route( 'api.location.prefecture_area' )),
                                    city: @json( route( 'api.location.city' )),                                    
                                    town: @json( route( 'api.location.town' )),
                                    cities_areas: @json( route('api.location.cities_areas')),
                                    get_location: @json( route('api.signup.get_location')),
                                    store: @json( route( 'signup.email')),
                                    register: @json( route( 'signup.register')),
                                }
                            }
                        };
                        return state;
                    },
                    // ------------------------------------------------------------------

                    // ------------------------------------------------------------------
                    // Mutations
                    // ------------------------------------------------------------------
                    mutations: {
                        refreshParsley: function() {
                            setTimeout(function() {
                                var $formSignUp = $('#form-signup[data-parsley]');
                                $formSignUp.parsley().refresh();
                            });
                        },
                        // --------------------------------------------------------------
                        // Set loading state
                        // --------------------------------------------------------------
                        setLoading: function(state, loading) {
                            if (io.isUndefined(loading)) loading = true;
                            Vue.set(state.status, 'loading', !!loading);
                        },
                        // --------------------------------------------------------------
                        // --------------------------------------------------------------
                        // Set mounted state
                        // --------------------------------------------------------------
                        setMounted: function(state, mounted) {
                            if (io.isUndefined(mounted)) mounted = true;
                            Vue.set(state.status, 'mounted', !!mounted);
                        },
                        // --------------------------------------------------------------
                    }
                    // ------------------------------------------------------------------
                };
                // ----------------------------------------------------------------------


                // ----------------------------------------------------------------------
                // Vue mixin / Root component
                // ----------------------------------------------------------------------
                mixin = {
                    data: function(){
                        return {
                            email: '',
                            spinnerCheckEmail: false,
                            isEmailExists: false,
                            token: null,
                            item: {
                                email: null,
                                password: null,
                                name: null,
                                phone: null,
                                // -----------------------------------------------------
                                minimum_price: 0,   
                                maximum_price: 0,
                                minimum_price_land_area: 0,
                                maximum_price_land_area: 0,
                                minimum_land_area: 0,
                                maximum_land_area: 0,
                            },  
                            consider_amount         : @json( $consider_amount ),   
                            land_area               : @json( $land_area ),  
                            isLoadingCity           : false,
                            isLoadingTown           : false,
                            isLoadingPrefectureArea : false,
                            prefectures             : [],
                            prefecture_area         : [],
                            valuePrefecture         : 4,
                            city                    : [],                    
                            town                    : [],   
                            value                   : {
                                valuePrefectureArea: null,
                                valueCity: null,
                                valueCitiesArea: null
                            },   
                            favoritedAreas          : [], 
                            isBackButtonDisabled    : false,
                            isRegisterButtonDisabled: false,
                        }
                    },
                    // ------------------------------------------------------------------
                    // On mounted hook
                    // ------------------------------------------------------------------
                    mounted: function() {
                        this.$store.commit('setMounted', true);
                        $(document).trigger('vue-loaded', this);

                        const vm = this;

                        document.getElementById('back-button')?.addEventListener('click', () => {
                            if(this.$route.query.step == 2){
                                this.$router.push({ name: 'index', query: {step: 1} });
                            }
                            else if(this.$route.query.step == 4){
                                this.$router.push({ name: 'index', query: {step: 3, token: this.token} });
                            }
                            else {
                                window.location.href = `/map?${localStorage.getItem('mapQueryParams')}`;
                            }
                        });
                    },
                    created: function() {
                        if( typeof this.$route.query.step === 'undefined'){
                            this.$router.push({ name: 'index', query: {step: 1} });
                        }

                        if( this.$route.query.step == 3){
                            const signedUpCustomer = @json($signedUpCustomer);
                            this.item.email = signedUpCustomer.email;
                            this.token = signedUpCustomer.token;

                            if( this.$store.state.preset.api.prefecture ){
                                var vm = this;                    
                                var request = axios.post( this.$store.state.preset.api.prefecture );                    
                                request.then( function( response ){                        
                                    var status = io.get( response, 'status' );
                                    var prefectures = io.get( response, 'data' );                           
                                    if( 200 === status && prefectures.length ){
                                        vm.prefectures.length = 0;
                                        io.each( prefectures, function( prefecture ){
                                            vm.prefectures.push( prefecture );                                
                                        })
                                    }                        
                                });                    
                            }
                            this.prefectureChangeHandle(vm.valuePrefecture);
                        }

                        if( this.$route.query.step == 4 ){
                            var url = @json( route( 'signup' ));
                            var redirectPage = `${url}?step=3&token=${this.$route.query.token}`;
                            window.location = redirectPage;
                        }

                        if(this.$route.query.step == 5){
                            window.scrollTo({
                                top: 0,
                                behavior: "instant"
                            });
                        }
                    },
                    methods: { 

                        checkEmail: io.debounce( function(){
                            var vm = this;
                            var email = vm.email;

                            vm.isEmailExists = false;
                            vm.spinnerCheckEmail = true;

                            var url = vm.$store.state.preset.api.check_email;

                            if(email && $('.parsley-errors-list.filled').length == 0){
                                var request = axios.post(url, {email: email});
                                request.then( function( response ){
                                    vm.isEmailExists = response.data;
                                });

                                request.finally(function(){ vm.spinnerCheckEmail = false });
                            } else {
                                vm.spinnerCheckEmail = false;
                            }
                        }, 500 ),

                        signup: function(){
                            // ----------------------------------------------------------
                            let vm = this; let valid = true;
                            // ----------------------------------------------------------
                            // Set validation using parsley
                            // ----------------------------------------------------------
                            let $form   = $('#form-signup');
                            let form    = $form.parsley();
                            valid       = form.validate();

                            // ----------------------------------------------------------
                            if(valid !== false && $('.parsley-errors-list.filled').length == 0 && vm.isEmailExists == false ){
                                var state   = vm.$store.state;
                                vm.$store.commit( 'setLoading', true );
                                var data    = {};
                                var formData    = new FormData();
                                // Get UUID data from device
                                var uuid = window.deviceUuid.DeviceUUID().get();
                                var url = io.get(state.preset, 'api.store');
                                data.email  = vm.email;
                                data.uuid   = `${uuid}_${data.email}`;

                                formData.append('dataset', JSON.stringify(data));
                                var options = { headers: { 'Content-Type': 'multipart/form-data' }};
                                queue.save = axios.post( url, formData, options );
                                queue.save.then( function( response ){
                                    if( response.data ){
                                        //Email & token stored
                                        if( response.data.status == "success"){
                                        vm.$store.commit( 'setLoading', false );
                                        //Redirect to [C18-2]
                                        setTimeout( function(){
                                                vm.$router.push({ name: 'index', query: {step: 2} });
                                            //    var redirectPage = @json( route( 'signup.sendemail' ));
                                                // window.location = redirectPage;
                                            }, 1000 );
                                        }else{
                                            //Sending email failed
                                            var message = 'メールの送信に失敗しました';
                                            vm.$toasted.show( message, { type: 'error' });
                                            vm.$store.commit( 'setLoading', false );
                                        }
                                    }
                                });
                                // Handle other response
                                queue.save.catch( function(e){ console.log( e )});
                                queue.save.finally( function(){ vm.$store.commit( 'setLoading', false ) });
                            }
                        },

                        prefectureChangeHandle: function(prefectureId){
                            vm = this;
                            vm.isLoadingPrefectureArea = true;
                            
                            var prefectureAreaRequest = axios.post( this.$store.state.preset.api.prefecture_areas, { id: prefectureId });
                            prefectureAreaRequest.then( function(response) {
                                var status = io.get( response, 'status' );
                                var options = io.get( response, 'data' );                          
                                if( 200 === status && options.length ){                                    
                                    vm.prefecture_area.length = 0;
                                    io.each( options, function( option ){                                        
                                        vm.prefecture_area.push( option );                                 
                                    });  
                                }
                                vm.isLoadingPrefectureArea = false;
                            }); 
                        },
                        prefectureAreaChangeHandle: function(prefecture_areas_id){
                            vm = this;
                            vm.isLoadingCity = true;
                            vm.value.valueCity              = null;
                            vm.value.valueCitiesArea              = null;

                            var request = axios.post( this.$store.state.preset.api.city, 
                            { 
                                prefecture_id: vm.valuePrefecture,
                                prefecture_areas_id: prefecture_areas_id
                            });                        
                            request.then( function( response ){                            
                                var status = io.get( response, 'status' );
                                var options = io.get( response, 'data' );                            
                                if( 200 === status && options.length ){                                    
                                    vm.city.length = 0;
                                    vm.town.length = 0;
                                    io.each( options, function( option ){                                        
                                        vm.city.push( option );                                  
                                    });
                                    vm.isLoadingCity = false;      
                                }else{
                                    vm.isLoadingCity = false;    
                                }
                                                 
                            });  
                        },
                        cityChangeHandle: function(cityId){
                            vm = this;
                            vm.isLoadingTown = true; 
                            vm.value.valueCitiesArea              = null;
                            
                            var request = axios.post( this.$store.state.preset.api.cities_areas, { id: cityId });                        
                            request.then( function( response ){                         
                                var status = io.get( response, 'status' );
                                var options = io.get( response, 'data' );
                                vm.town.length = 0;                            
                                if( 200 === status && options.length ){
                                    io.each( options, function( option ){                                                                    
                                        vm.town.push( option );
                                        vm.isLoadingTown = false;                                         
                                    });
                                }
                                else {
                                    vm.isLoadingTown = false;
                                }                            
                            });     
                        },

                        addFavorite:function(index){
                            vm  = this;
                            var favoriteArea    = vm.favoritedAreas[index];
                            favoriteArea.default = !favoriteArea.default;    

                            //var message = '@lang('label.SUCCESS_CREATE_MESSAGE')';
                            //vm.$toasted.show( message, { type: 'success' });                                                               
                        }, 

                        removeFavoriteArea: function(index){
                            vm  = this;
                            vm.favoritedAreas.splice(index, 1);    

                            //var message = '@lang('label.SUCCESS_CREATE_MESSAGE')';
                            //vm.$toasted.show( message, { type: 'success' });
                        },

                        addFavoriteArea: function(){
                            var state   = vm.$store.state;
                            vm.$store.commit( 'setLoading', true );
                            var formData    = new FormData();

                            var url     = io.get(state.preset, 'api.get_location');
                            var data    = {
                                prefecture_id   : vm.valuePrefecture,
                                city_id         : vm.value.valueCity,
                                town_id         : vm.value.valueCitiesArea;
                            };

                            queue.save = axios.post( url, data );
                            queue.save.then( function( response ){
                                if( response.data ){
                                    let favoriteArea = response.data;
                                    favoriteArea.default = false;

                                    vm.favoritedAreas.push(favoriteArea);
                                    vm.value.valuePrefectureArea    = null;
                                    vm.value.valueCity              = null;
                                    vm.value.valueCitiesArea        = null;

                                    //var message = '@lang('label.SUCCESS_CREATE_MESSAGE')';
                                    //vm.$toasted.show( message, { type: 'success' });
                                }
                                vm.$store.commit( 'setLoading', false );
                            });
                            // Handle other response
                            queue.save.catch( function(e){ console.log( e )});
                            queue.save.finally( function(){ vm.$store.commit( 'setLoading', false ) });
                        },

                        submit: function(){
                            // ----------------------------------------------------------
                            let vm = this; let valid = true;
                            // ----------------------------------------------------------
                            // Set validation using parsley
                            // ----------------------------------------------------------
                            let $form   = $('#form-customerinfo');
                            let form    = $form.parsley();
                            valid       = form.validate();

                            window.scrollTo({
                                top: 0,
                                behavior: "instant"
                            });

                            // ----------------------------------------------------------
                            if(valid !== false && $('.parsley-errors-list.filled').length == 0 ){
                                vm.$store.commit( 'setLoading', true );
                                vm.$store.commit( 'setLoading', false );
                                vm.$router.push({ name: 'index', query: {step: 4, token: vm.token} });
                            }
                        },

                        handleBackButton: function(){
                            let vm = this;
                            window.scrollTo({
                                top: 0,
                                behavior: "instant"
                            });
                            this.$router.push({ name: 'index', query: {step: 3, token: this.token} });
                        },

                        register: function(){
                            var vm      = this;
                            var state   = vm.$store.state;
                            var data    = {
                                customer: this.item,
                                customer_desired_areas: this.favoritedAreas,
                                token: this.token,
                            };

                            vm.$store.commit( 'setLoading', true );
                            vm.isBackButtonDisabled = true;
                            vm.isRegisterButtonDisabled = true;

                            var url     = io.get(state.preset, 'api.register');
                            var request = axios.post( url, data );
                            request.then( function( response ){
                                if( response.data ){

                                    var message = '@lang('label.SUCCESS_CREATE_MESSAGE')';
                                    vm.$toasted.show( message, { type: 'success' });

                                }
                            });
                            // Handle other response
                            request.catch( function(e){ 
                                console.log( e ); 
                                vm.isRegisterButtonDisabled = true;
                            });
                            request.finally( function(){ 
                                setTimeout(() => {
                                    vm.$store.commit( 'setLoading', false );
                                    vm.isBackButtonDisabled = false;
                                    vm.$router.push({ name: 'index', query: {step: 5} });
                                }, 3000);
                             });
                        },

                         // Trigger input-event on the target element
                        trigger: function( target ){
                            $(target).trigger('input');
                        }
                    },
                    // ------------------------------------------------------------------
                    // Computed properties
                    // ------------------------------------------------------------------
                    computed: {
                        // --------------------------------------------------------------
                        // Loading and mounted status
                        // --------------------------------------------------------------
                        isLoading: function() {
                            return this.$store.state.status.loading
                        },
                        isMounted: function() {
                            return this.$store.state.status.mounted
                        },
                        generatedPassword: function() {
                            let generatedPass = '';
                            if(this.item.password){
                                var passwordLength = String(this.item.password).length;
                                for (let i = 0; i < passwordLength; i++) {
                                    generatedPass = `${generatedPass}*`;
                                }
                            } else {
                                generatedPass = ' ';
                            }
                            return generatedPass;
                        }
                        // --------------------------------------------------------------
                    },

                    // ------------------------------------------------------------------
                    // Wacthers
                    // ------------------------------------------------------------------
                    watch: {
                        $route: {
                            immediate: true,
                            handler: function( to, from ){
                                
                                // if(typeof myVariable === 'undefined') {
                                //     this.$route.query
                                // }
                                // console.log(to.query);

                            }
                        },
                        'value.valuePrefectureArea': function( areaID ){
                            this.prefectureAreaChangeHandle(areaID);      
                        },
                    }
                    // ------------------------------------------------------------------
                };
                // ----------------------------------------------------------------------
                // When vue has been mounted/loaded
                // ----------------------------------------------------------------------
                $(document).on('vue-loaded', function(event, vm) {
                    // ------------------------------------------------------------------
                    // Init parsley form validation
                    // ------------------------------------------------------------------
                    var $window = $(window);
                    var $formSignUp = $('#form-signup[data-parsley]');
                    var formSignup = $formSignUp.parsley();
                    var queue = window.queue;
                    var store = vm.$store;

                    if( typeof formSignup !== 'undefined'){
                         // ------------------------------------------------------------------
                        // On form submit Send Email
                        // ------------------------------------------------------------------
                        formSignup.on('form:validated', function() {
                            // --------------------------------------------------------------
                            // On form invalid,
                            // navigate/scroll the page to the validation messages
                            // --------------------------------------------------------------
                            var validForm = formSignup.isValid();
                            if (validForm == false) navigateValidation(validForm);
                            // --------------------------------------------------------------

                            // --------------------------------------------------------------
                            // On form valid
                            // --------------------------------------------------------------
                            else {
                                // var state   = vm.$store.state;
                                // vm.$store.commit( 'setLoading', true );
                                // var data    = {};
                                // var formData    = new FormData();
                                // // Get UUID data from device
                                // var uuid = window.deviceUuid.DeviceUUID().get();
                                // var url = io.get(state.preset, 'api.store');
                                // data.email  = vm.email;
                                // data.uuid   = uuid;

                                // formData.append('dataset', JSON.stringify(data));
                                // var options = { headers: { 'Content-Type': 'multipart/form-data' }};
                                // queue.save = axios.post( url, formData, options );
                                // queue.save.then( function( response ){
                                //     if( response.data ){
                                //         //Email & token stored
                                //         if( response.data.status == "success"){
                                //         vm.$store.commit( 'setLoading', false );
                                //         //Redirect to [C18-2]
                                //         setTimeout( function(){
                                //                 vm.$router.push({ name: 'index', query: {step: 2} });
                                //             //    var redirectPage = @json( route( 'signup.sendemail' ));
                                //                 // window.location = redirectPage;
                                //             }, 1000 );
                                //         }else{
                                //             //Sending email failed
                                //             var message = 'メールの送信に失敗しました';
                                //             vm.$toasted.show( message, { type: 'error' });
                                //             vm.$store.commit( 'setLoading', false );
                                //         }
                                //     }
                                // });
                                // // Handle other response
                                // queue.save.catch( function(e){ console.log( e )});
                                // queue.save.finally( function(){ vm.$store.commit( 'setLoading', false ) });
                                return false;
                            }
                            // --------------------------------------------------------------
                        }).on('form:submit', function() {
                            return false
                        });
                        // ------------------------------------------------------------------
                    }

                    var $formCustomerInfo = $('#form-customerinfo[data-parsley]');
                    var formCustomerInfo = $formCustomerInfo.parsley();

                    if( typeof formCustomerInfo !== 'undefined'){
                        // ------------------------------------------------------------------
                        // On form submit Send Email
                        // ------------------------------------------------------------------
                        formCustomerInfo.on('form:validated', function() {
                            // --------------------------------------------------------------
                            // On form invalid,
                            // navigate/scroll the page to the validation messages
                            // --------------------------------------------------------------
                            var validForm = formCustomerInfo.isValid();
                            if (validForm == false)navigateValidation(validForm); 
                            // --------------------------------------------------------------

                            // --------------------------------------------------------------
                            // On form valid
                            // --------------------------------------------------------------
                            else {
                                // var state   = vm.$store.state;
                                // vm.$store.commit( 'setLoading', true );

                                // vm.$store.commit( 'setLoading', false );
                                // vm.$router.push({ name: 'index', query: {step: 4} });   

                                return false;
                            }
                            // --------------------------------------------------------------
                        }).on('form:submit', function() {
                            return false
                        });
                        // ------------------------------------------------------------------
                    }

                    // validation on favorite area form
                    var $formFavoriteArea = $('#form-favoritearea[data-parsley]');
                    var formFavoriteArea = $formFavoriteArea.parsley();

                    if( typeof formFavoriteArea !== 'undefined'){
                        // ------------------------------------------------------------------
                        // On form submit Send Email
                        // ------------------------------------------------------------------
                        formFavoriteArea.on('form:validated', function() {
                            // --------------------------------------------------------------
                            // On form invalid,
                            // navigate/scroll the page to the validation messages
                            // --------------------------------------------------------------
                            var validForm = formFavoriteArea.isValid();
                            if (validForm == false)navigateValidation(validForm); 
                            // --------------------------------------------------------------

                            // --------------------------------------------------------------
                            // On form valid
                            // --------------------------------------------------------------
                            else {
                                
                                return false;
                            }
                            // --------------------------------------------------------------
                        }).on('form:submit', function() {
                            return false
                        });
                        // ------------------------------------------------------------------
                    }

                });
                // ----------------------------------------------------------------------
            }(_, jQuery, window, document))
        @endminify
    </script>
@endpush
