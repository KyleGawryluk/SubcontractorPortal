@extends('default')

@section('styles')	
<style>

</style>
@stop

@section('content')

<div class="row">
	<div class="col-md-4 offset-md-4">
		<form method="POST" action="/login">
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
			<button type="submit" name="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
</div>

@stop

