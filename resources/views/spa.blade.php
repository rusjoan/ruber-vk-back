@extends('app')

@section('wrapper')
    <div id="app">
        <div class="container-fluid">
            <router-view></router-view>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://vk.com/js/api/xd_connection.js?2" type="text/javascript"></script>
    <script src="/js/manifest.js"></script>
    <script src="/js/vendor.js"></script>
    <script src="/js/app.js"></script>
@endsection