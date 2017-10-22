@extends('spa')

@section('head')
    @parent
    <script>
        const loadParams = {!! json_encode($loadParams) !!};
    </script>
@endsection