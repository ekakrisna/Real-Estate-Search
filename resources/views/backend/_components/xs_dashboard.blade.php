<div class="row">
    @if ($data_type != 'button')
        <div class="d-sm-none col-5">
            {{ __($header) }}
        </div>
        <div class="d-sm-none col-1">
            :
        </div>
        <div class="col-6 col-sm-12">
            {{-- anchor element type --}}
            @if ($data_type === 'anchor')
                <a href={{$redirect}}>{{$data}}</a>
            {{-- flag image element type --}}
            @elseif ($data_type === 'image')
                @if ($data == 1)
                    <a class="dashboard-flag" href={{$redirect}}><i class="fas fa-flag text-dark"></i></a>
                @else
                    {{-- change the image to star-inactive --}}
                    <a class="dashboard-flag" href={{$redirect}}><i class="fas fa-flag text-secondary"></i></a>
                @endif
            {{-- star image element type --}}
            @elseif ($data_type === 'image_star')
                @if ($data == 1)
                    <a class="dashboard-star" href={{$redirect}}><i class="fas fa-star text-dark"></i></a>
                @else 
                    {{-- change the image to star-inactive --}}
                    <a class="dashboard-star" href={{$redirect}}><i class="far fa-star text-dark"></i></a>
                @endif
            {{--  is finish boolean element type --}}
            @elseif ($data_type === 'is_finish')
                @if ($data == 1)
                    @lang('label.acknowledged')
                @else
                    @lang('label.not_compatible')
                @endif
            @else
                {{ $data }}
            @endif
        </div>
    @else
        <div class="col-12 mt-2 mt-sm-0">
            <a class="btn btn-primary btn-sm btn-block" href={{$redirect}}>{{ __($header) }}</a>
        </div>
    @endif
    
</div>