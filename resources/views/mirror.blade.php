@extends('default')

@section('title')
Login As
@stop

@section('styles')	
<style>

</style>
@stop

@section('content')

<div class="row">
	<div class="col-md-4 offset-md-4">
		<form action="/mirror" method="post" >
			@csrf

			<label>Account ID</label>
			<input type="text" class="form-control {{ $errors->has('account_id') ? 'error' : '' }}" name="account_id" id="account_id">
			@if ($errors->has('account_id'))
			<div class="error">
				{{ $errors->first('account_id') }}
			</div>
			@endif
			<br>
			<button type="submit" class="btn btn-primary loading">Login</button>

		</form>
	</div>
</div>

@stop

