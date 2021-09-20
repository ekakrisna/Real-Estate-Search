<form id="{{ $id ?? "" }}" method="{{ !empty($method) ? $method : 'POST' }}" action="{{ $action }}"{!! !empty($files) ? ' enctype="multipart/form-data"' : "" !!} data-parsley data-parsley-validate>
@csrf
@if(empty($method))
    {{ $page_type == 'create' ? '' : method_field('PUT') }}
@endif
{{ $slot }}
</form>
