<!-- Global stylesheets -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/all.css') }}" rel="stylesheet" type="text/css">
<?php if (!empty($assets['style'])): ?>
  <?php foreach ($assets['style'] as $style): ?>
    <link href="{{asset($style)}}" rel="stylesheet">
  <?php endforeach ?>
<?php endif ?>
<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css">
<!-- /global stylesheets -->

<!-- Core JS files -->
<script src="{{ asset('assets/js/main/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/main/bootstrap.bundle.min.js') }}"></script>
<?php if (!empty($assets['script'])): ?>
<?php foreach ($assets['script'] as $script): ?>
  <script src="{{ asset($script) }}"></script>
<?php endforeach ?>
<?php endif ?>
<script src="{{ asset('assets/js/app.js') }}"></script>
