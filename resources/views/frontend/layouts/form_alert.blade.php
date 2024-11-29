@if ( \Session::has('warning') || \Session::has('error') || \Session::has('success'))
    @php $alertClass = ( \Session::has('warning') ? 'alert-warning' : \Session::has('error')) ?
        'alert-danger' : 'alert-success';
    @endphp
@php $alertMsg = ( \Session::has('warning') ? \Session::get('warning') : \Session::has('error') ) ?
    \Session::get('error') : \Session::get('success');
@endphp
<div class="col-12">
    <div class="alert {{$alertClass}} alert-dismissible fade show" role="alert">
        {{$alertMsg}}
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
    </div> 
</div> 
@endif