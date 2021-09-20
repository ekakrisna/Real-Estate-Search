@extends('frontend._base.app')
@section('title', '運営会社')
@section('description', '')
@section('page')
<div class="section-option-list flex-grow-1 d-flex flex-column">
    <div class="container flex-grow-1 d-flex d-md-block flex-column">
        <div class="content flex-grow-1 d-flex flex-column">
            <div class="content-header">
                <h1 class="title">運営会社</h1>
            </div>
            <div class="content-body mx-n3 mx-md-0 flex-grow-1 d-flex flex-column">
                <div class="content-list company-list h-auto flex-grow-1">
                    <div class="item-list">
                        <span class="list-title">商号</span>
                        <span class="list-text">株式会社開日ホールディングス</span>
                    </div>
                    <div class="item-list">
                        <span class="list-title">設立</span>
                        <span class="list-text">2019年4月1日</span>
                    </div>
                    <div class="item-list">
                        <span class="list-title">所在地</span>
                        <span class="list-text">宮城県仙台市若林区鶴代町3−15<br/>TEL:022-352-0290<br/>FAX:022-352-4310</span>
                    </div>
                    <div class="item-list">
                        <span class="list-title">代表者</span>
                        <span class="list-text">代表取締役 三浦 良太</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
