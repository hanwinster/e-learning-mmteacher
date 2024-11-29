@extends('backend.layouts.default')

@section('title', 'User Import')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Default Elements -->
            <div class="card">
                <h4 class="card-title">
                    [User Import]
                </h4>
				
                <div class="card-body">
								
					{!! \Form::open(array('files' => true, 'route' => 'admin.user.batch-upload.store', 'class' => 'form-horizontal')) !!}				         
					
                    {{ csrf_field() }}
					
                    <div class="row">
                        <div class="col-sm-8">
						
                            <div class="form-group">
								<div class="col-xs-12">
									<label for="published">@lang('Import Excel File')</label>
									{{ Form::file('import_excel_file') }}
									
									{{--
									@if (isset($post))
									@php
									$images = $post->getMedia('articles');
									@endphp
									<div>
									@foreach($images as $image)
									<a target="_blank" href="{{ asset($image->getUrl()) }}">
										<img src="{{ asset($image->getUrl('thumb')) }}">
									</a>
									<a onclick="return confirm('Are you sure you want to delete?')" href="{{ route('admin.media.destroy', $image->id) }}">Remove</a>
									@endforeach
									</div>
									@endif
									--}}
									
									{!! $errors->first('import_excel_file', '<div class="invalid-feedback">:message</div>') !!}
								</div>
							</div>

                            <div class="form-group">
								<button class="btn btn-primary" type="submit" name="btnSave" value="1">Save	</button>
								<button class="btn btn-secondary" type="submit" name="btnApply" value="1">Apply</button>
								<a href="{{ route('admin.user.index') }}" class="btn btn-flat">Cancel</a>
                            </div>
                        </div>
						
                        <div class="col-sm-4">
                           

                            
							
                        </div>
                    
                    </form>
					</div>
                </div>
            </div>
            <!-- END Default Elements -->
        </div>
    </div>
</div>
@stop
@section('css') @parent
@endsection

@section('js') @parent

@endsection