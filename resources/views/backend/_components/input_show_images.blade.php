<div class="row form-group d-flex align-items-start">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        {{ $label }}
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        <div class="row mx-n2 mt-n3">
            @forelse ($images as $image)
                <div class="px-2 col-lg-6 col-xl-4 mt-3">
                    <div class="ratiobox ratio--4-3">
                        <div class="ratiobox-innerset">
                            <img class="img-thumbnail img-responsive" src="{{$image->file->url->image}}" alt="{{$image->file->name}}">
                        </div>
                    </div>    
                </div>
            @empty
                <div class="px-2 col-lg-6 col-xl-4 mt-3">
                    <img src="{{asset('img/backend/default.jpg')}}" class="img-thumbnail" alt="default">
                </div>
            @endforelse
        </div>
    </div>
</div>