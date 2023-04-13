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
{{-- 		<form method="POST" action="/login">
			@csrf
			<div class="mb-3">
				<label for="username" class="form-label">Username</label>
				<input type="text" class="form-control" id="username" name="username">
				@error('username')
				<div class="alert alert-danger">{{ $message }}</div>
				@enderror
			</div>
			<div class="mb-3">
				<label for="password" class="form-label">Password</label>
				<input type="password" class="form-control" id="password" name="password">
				@error('password')
				<div class="alert alert-danger">{{ $message }}</div>
				@enderror
			</div>
			<button type="submit" name="submit" class="btn btn-primary loading">Login</button>
		</form> --}}
{{-- 		<iframe width="100%" height="100%" src="{{'http://'.config('api.INSTANCE').'identity/connect/authorize?response_type=code&client_id='.env('CLIENT_ID').'&redirect_uri=http://homestead.test/oauth-login&scope=api'}}" height="315" width="100%" allowfullscreen="" frameborder="0">
</iframe> --}}

<div class="text-center">
	<a href="{{'http://'.config('api.INSTANCE').'identity/connect/authorize?response_type=code&client_id='.env('CLIENT_ID').'&redirect_uri=http://homestead.test/oauth-login&scope=api'}}" class="btn btn-primary">Login</a>
	<p>You will be redirected to login</p>
</div>
</div>
</div>

@stop

