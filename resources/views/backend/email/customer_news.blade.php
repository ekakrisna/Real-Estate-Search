<!DOCTYPE html>
<html lang="en-US">
    <head>
    <meta charset="utf-8">
    </head>
    <body>
    <h2>{{ $title }}</h2>
    <p>{{ $subtitle }}</p>
    {{-- use nl2br to break line (PHP_EOL) in emai lbody --}}
    <p>{!! nl2br($body_line_1) !!}</p>

    <p>{!! nl2br($body_line_2) !!}</p>
    <br>

    <p>■新規登録物件（A newly registered property）</p>
    @foreach($customer_news as $customer_new)
        @if($customer_new->type == 2)
            @if (count($customer_new->customer_news_property) > 0)
                @foreach($customer_new->customer_news_property as $customer_new_property)
                    <p>
                        住所 : {{$customer_new_property->property->location}} <br>
                        価格 : {{ $customer_new_property->property->minimum_price }} ~ {{ $customer_new_property->property->maximum_price }} <br>
                        土地面積 : {{ $customer_new_property->property->minimum_land_area }} ~ {{ $customer_new_property->property->maximum_land_area }} <br>
                        URL : {{$customer_new_property->property->url->frontend_view}}
                    </p>
                @endforeach
            @endif
        @endif
    @endforeach
    <p>■更新があった物件（Renewed properties）</p>
    @foreach($customer_news as $customer_new)
        @if($customer_new->type == 3)
            @if (count($customer_new->customer_news_property) > 0)
                @foreach($customer_new->customer_news_property as $customer_new_property)
                    <p>
                        住所 : {{$customer_new_property->property->location}} <br>
                        価格 : {{ $customer_new_property->property->minimum_price }} ~ {{ $customer_new_property->property->maximum_price }} <br>
                        土地面積 : {{ $customer_new_property->property->minimum_land_area }} ~ {{ $customer_new_property->property->maximum_land_area }} <br>
                        URL : {{$customer_new_property->property->url->frontend_view}}
                    </p>
                @endforeach
            @endif    
        @endif
    @endforeach
    <p>■削除された物件 （Deleted properties）</p>
    @foreach($customer_news as $customer_new)
        @if($customer_new->type == 4)
            @if (count($customer_new->customer_news_property) > 0)
                @foreach($customer_new->customer_news_property as $customer_new_property)
                    <p>
                        住所 : {{$customer_new_property->property->location}} <br>
                        価格 : {{ $customer_new_property->property->minimum_price }} ~ {{ $customer_new_property->property->maximum_price }} <br>
                        土地面積 : {{ $customer_new_property->property->minimum_land_area }} ~ {{ $customer_new_property->property->maximum_land_area }} <br>
                @endforeach
            @endif
        @endif
    @endforeach

    <br>
    <p>{!! nl2br($contact_us) !!} </p>
    <p>{!! nl2br($body_line_3) !!} </p>
    <p>{!! nl2br($body_line_4) !!} </p>
    </body>
</html>