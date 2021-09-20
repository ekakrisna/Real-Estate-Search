@extends('frontend._base.app')

@section('title', $title)

@section('page')

    <div class="section-signup full-screen">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="content">
                        <div class="content-header">
                        </div>
                        <div class="content-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="desc text-center font-weight-bold">
                                        無効な操作が行われました
                                    </p>
                                </div>
                                <div class="col-12 mt-3">
                                    <p class="desc text-center">
                                        無効なトークンです。<br>
                                        再度、新規登録用のメールを送信し、 操作を行ってください。
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3">                                    
                <a href="{{ route('customer-login') }}" type="submit" class="btn btn-primary-round btn-max-320" href="">                                        
                    <span>ログイン画面へ</span>                                        
                </a>
            </div>
        </div>
    </div>

@endsection