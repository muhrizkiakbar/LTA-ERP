@if (session()->has('message'))
  <div class="alert alert-{{ session()->get('type') }}" role="alert">
    <button type="button" class="close" data-dismiss="alert">
      <span>&times;</span>
      <span class="sr-only">Close</span>
    </button>
    <span class="text-semibold"> {{ session()->get('message') }} </span>
  </div>
@endif

@if ($errors)
  @foreach($errors->all() as $error)
  <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">
      <span>&times;</span>
      <span class="sr-only">Close</span>
    </button>
    <span class="text-semibold">Error !</span> {{ $error }}
  </div> 
  @endforeach
@endif