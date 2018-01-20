@extends('/layouts/master')


@section('content')

<div class="bigContainer">


@include('/videos/sidebar')

@if ($currentVideo === NULL)

<div class="main videos">
</div>

@else

@include('/videos/main')

@endif

</div>

@endsection('content')
