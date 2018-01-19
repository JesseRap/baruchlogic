@extends('/layouts/master')


@section('content')

<div class="bigContainer">


@include('/videos/sidebar')

@if ($currentVideo === NULL)
@include('/videos/main-empty')

@else
@include('/videos/main')

@endif

</div>

@endsection('content')
