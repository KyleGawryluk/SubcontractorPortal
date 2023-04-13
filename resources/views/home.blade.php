@extends('default')

@section('styles')	
<style>
	.video-container {
		position: relative;
		padding-bottom: 56.25%;
		padding-top: 35px;
		height: 0;
		overflow: hidden;
	}

	.video-container iframe {
		position: absolute;
		top:0;
		left: 0;
		width: 100%;
		height: 100%;
	}

</style>
@stop

@section('content')

<div class="row">
	<div class="col-md-4 offset-md-4">
		<div class="text-center">
			<a href="{{config('api.INSTANCE').'identity/connect/authorize?response_type=code&client_id='.env('CLIENT_ID').'&redirect_uri='.url('oauth-login')}}" class="btn btn-primary">Click to Login</a>
			<br>
			<br>
			<p class="alert alert-warning">You will be redirected to an outside page to login</p>
		</div>
	</div>
</div>

@stop

