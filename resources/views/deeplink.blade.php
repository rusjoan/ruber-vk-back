@extends('app')
@section('head')
    <meta property="al:ios:url" content="rubervk://product/{{ $product->id }}" />
    <meta property="al:ios:app_store_id" content="44492821" />
    <meta property="al:ios:app_name" content="RuberVK" />
    <meta property="og:title" content="RuberVK AR shop" />
@endsection
@section('wrapper')
    <div class="container padding-top">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-sm-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">{{ $product->title }}</h4>
                    </div>
                    <div class="panel-body">
                        <p class="text-center">
                            <img src="{{ $product->thumb_photo }}" alt="{{ $product->title }}" width="100%" />
                        </p>
                        <p>
                            <a class="btn btn-default" href="rubervk://product/{{ $product->id }}">
                                Открыть AR-просмотр
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection