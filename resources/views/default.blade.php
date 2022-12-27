<!DOCTYPE html>
<html lang="en">
<head>
	<title>SGH Portal</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="{{asset('css/custom.css')}}" rel="stylesheet">
	<link href="{{asset('css/app.css')}}" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" crossorigin="anonymous">
	@yield('styles')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://use.typekit.net/arc5ewz.js"></script>
	<script>try{Typekit.load({ async: true });}catch(e){}</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
	<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
</head>
<body>
	<nav class="navbar navbar-expand-lg shadow-sm"style="background-color: #eaeaea">
		<div class="container-fluid">
			<a class="navbar-brand" href="/">SGH Portal</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="/">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{URL::to('contracts')}}">Contracts</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							Dropdown
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="">Action</a></li>
							<li><a class="dropdown-item" href="">Another action</a></li>
							<li><hr class="dropdown-divider"></li>
							<li><a class="dropdown-item" href="">Something else here</a></li>
						</ul>
					</li>
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="{{URL::to('logout')}}">Logout</a>
					</li>
				</ul>
				<form class="d-flex" role="search">
					<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
					<button class="btn btn-outline-success" type="submit">Search</button>
				</form>
			</div>
		</div>
	</nav>
	<br>
	<div class="container-fluid main">
		<div class="row">
			<div class="col-md-6 offset-md-3">
				@if(session()->has('message'))
				<div class="alert alert-info" role="alert"> 
					{!! session('message') !!}
				</div>
				@endif
				@if($errors->any())
				<div class="alert alert-danger" role="alert">
					{{ implode('', $errors->all(':message')) }}
				</div>
				@endif
			</div>
		</div>
		
		@yield('content')
		<hr>
		<footer>
			<div class="row">
				<div class="col-lg">
					<p class="text-center">Copyright &copy; SGH Redglaze Holdings Inc. {{Carbon\Carbon::now()->format('Y')}}</p>
				</div>
			</div>
		</footer>
	</div>
	<script src="{{asset('js/custom.js')}}"></script>
	@yield('scripts')
</body>
</html>