
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta content="ERP 2.0 Integrated System ERP & SAP" name="description" />
  <meta content="IT Team" name="author" />
	<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
	<title>{{ isset($title) ? $title.' | ' : '' }}ERP 2.0 Integrated System ERP & SAP | LTA - TAA</title>
  @include('layouts.backend.assets')
</head>
<body>
	@include('layouts.backend.navbar')
	<div class="page-content">
		<div class="content-wrapper">
			<div class="content-inner">
				@yield('content')
			</div>
		</div>
	</div>
  @include('layouts.backend.footer')
	@yield('customjs')
</body>
</html>
