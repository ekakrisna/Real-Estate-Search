@extends('frontend._base.app')
@section('title', 'Real Estate Confirm')
@section('description', '')
@section('page')
<!-- Button modal -->
<div class="container">
    <div class="row">
        <div class="col-12 col-lg-3">
            <button type="button" class="btn btn-primary-round mt-4" data-toggle="modal" data-target="#modalPopup">
                <span>Confirm Window</span>
            </button>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal modal-custom fade" id="modalPopup" tabindex="-1" role="dialog" aria-labelledby="modalPopup" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p class="text">確認用メッセージが入ります。確認用メッセージが入ります。確認用メッセージが入ります。確認用メッセージが入ります。</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary-round" data-dismiss="modal">
                    <span>確認</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
