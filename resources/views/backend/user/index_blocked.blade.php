@extends('backend.layouts.default')

@section('title', __('Blocked Users'))

@section('content')
<div class="card">
    <header class="card-header">
        <form action="{{ route('admin.user.blocked_user') }}" method="get">
            <div class="card-header-actions">
                <div class="lookup lookup-right d-none d-lg-block">
                    <input name="search" placeholder="Search" type="text" value="{{ request('search') }}">
                </div>
                <button class="btn btn-primary">Search</button>
                <a href="{{ route('admin.user.blocked_user') }}" class="btn btn-sm">Reset</a>
            </div>
        </form>
    </header>

    <div class="card-body">
		<table class="table table-bordered table-striped table-vcenter dataTable no-footer">
			<thead>
				<tr>
					<th width="60">@sortablelink('id', 'ID')</th>
					<th>@sortablelink('name', 'Name')</th>
					{{--<th>@sortablelink('username', 'Username')</th>--}}
					<th>@sortablelink('email', 'Email')</th>
					<th>{{ __('Role') }}</th>
					<th>{{ __('Education College') }}</th>
					<th>{{ __('Status') }}</th>
					<th>@sortablelink('created_at', 'Created At')</th>
					{{--<th>@sortablelink('updated_at', 'Updated At')</th>--}}
					<th width="160" class="text-center">Actions</th>
				</tr>
			</thead>

			<tbody>

				@foreach($posts as $post)
				<tr>
					<td>{{ $post->id }}</td>
					<td>{{ $post->name }}</td>
					{{--<td>{{ $post->username }}</td>--}}
					<td>{{ $post->email }}</td>
					<td>
						@foreach($post->roles as $role)
							{{-- $post->roles->contains('id', $role->id) --}}
							{{ $role->name }}
						@endforeach
					</td>
                    <td>
                            {{ $post->college->title ?? '' }}
                    </td>
					<td class="text-left">
					{{--{!! $post->blocked ? '<i class="fa fa-check"></i>': '<i class="fa fa-minus"></i>' !!}--}}
					@if($post->verified == 0) Unverified
					@elseif($post->verified == 1) Verified
					@endif
					</td>
					<td>{{ $post->created_at }}</td>
					{{--<td>{{ $post->updated_at }}</td>--}}
					<td class="text-right table-options">
						@if ($post->id != 1)
							@can('edit_user')
								<a class="table-action hover-primary cat-edit" href="{{ route('admin.user.edit', $post->id) }}" data-provide="tooltip" title="Edit"><i class="ti-pencil"></i></a>
							@endcan
						@endif

						@if ($post->id != 1)
							@can('delete_user')
								{!! Form::open(array('route' => array('admin.user.destroy', $post->id), 'method' => 'delete' , 'onsubmit'	=> 'return confirm("Are you sure you want to delete?");', 'style' => 'display: inline', '')) !!}
								<button data-provide="tooltip" data-toggle="tooltip" title="Delete" type="submit" class="btn btn-pure table-action hover-danger confirmation-popup">
												<i class="ti-trash"></i>
												</button>
							{!! Form::close() !!}
							@endcan
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
    </div>

    <footer class="card-footer text-center">
        {{ $posts->links() }}
    </footer>
</div>
@endsection
