<div class="alert alert-danger display-hide" style="display: none;">
    <button data-close="alert" class="close"></button>Có lỗi xảy ra!
</div>

@if (Session::has('message'))
<div class="alert alert-success alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    {{ Session::get('message') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    @foreach($errors->all() as $error)
    <p>{{ $error }}</p>
    @endforeach
</div>
@endif