

@extends('/layouts/master')


@section('content')

<script type="text/javascript" src="{{asset('/js/formula.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/utility.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/exercises-helper.js')}}" defer></script>

<div class="bigContainer">


@include('/exercises/sidebar')

@include('/exercises/main')

</div>

@endsection('content')
