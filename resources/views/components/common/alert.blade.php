<div class="alert alert-danger display-hide" style="display: none;">
    <button data-close="alert" class="close"></button>Có lỗi xảy ra!
</div>

@if (Session::has('message'))
<div class="alert alert-success">
    <button class="close" data-close="alert"></button>
    {{ Session::get('message') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <button class="close" data-close="alert"></button>
    @foreach($errors->all() as $error)
    <p>{{ $error }}</p>
    @endforeach
</div>
@endif