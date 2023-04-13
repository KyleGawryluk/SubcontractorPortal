@extends('default')

@section('styles')	
<style>

</style>
@stop

@section('content')

<div class="row">
	<div class="col-md-4 offset-md-4">
		<form method="POST" action="/change-pw" class="needs-validation" novalidate>
			@csrf
			<div class="mb-3">
				<label for="username" class="form-label">Username</label>
				<input type="text" class="form-control" id="username" name="username" value="{{$username}}">
				@error('username')
				<div class="text-danger">{{ $message }}</div>
				@enderror
			</div>
			<div class="mb-3">
				<label for="old_password" class="form-label">Old Password</label>
				<input type="password" class="form-control" id="old_password" name="old_password" >
				@error('old_password')
				<div class="text-danger">{{ $message }}</div>
				@enderror
			</div>
			<div class="mb-3">
				<label for="new_password" class="form-label">New Password</label>
				<input type="password" class="form-control" id="new_password" name="new_password" >
				@error('new_password')
				<div class="text-danger">{{ $message }}</div>
				@enderror
			</div>
			<div class="mb-3">
				<label for="confirm_password" class="form-label">Confirm Password</label>
				<input type="password" class="form-control" id="confirm_password" name="confirm_password" >
				@error('confirm_password')
				<div class="text-danger">{{ $message }}</div>
				@enderror
			</div>
			<button type="submit" name="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
</div>

@stop

