@extends('backend._base.content_datatables')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">{{ $page_title }}</li>
    </ol>
@endsection

@section('top_buttons')
    <a href="{{ route('admin.news.create') }}" class="btn btn-info">@lang(('label.createNew'))</a>
@endsection

@section('content')
    <th data-col="id">ID</th>
    <th data-col="title">@lang('label.title')</th>
    <th data-col="body">@lang('label.body')</th>

    {{-- example custom filter --}}
    <th data-col="custom_status" data-name="status" data-select='{!! str_replace("'","`", json_encode($filter_select_columns['custom_status'])) !!}'>@lang('label.status')</th>

    <th data-col="publish_date" width="77">@lang('label.publish_date')</th>
    <th data-col="updated_at" width="120">@lang('label.last_update')</th>
    <th data-col="action">@lang('label.action')</th>
@endsection
