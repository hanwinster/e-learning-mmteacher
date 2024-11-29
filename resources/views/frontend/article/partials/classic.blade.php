<div class="card card-blog">

    <div class="card-image">
        <a href="{{ url($post->path()) }}">
            {{-- @if ($image_url = $post->getImagePath())
            <img class="shadow-2 rounded" src="{{ asset($image_url) }}" alt="{{ $post->title }}">
            @else
            <img class="shadow-2 rounded" src="{{ asset('assets/img/vector/3.png') }}" alt="{{ $post->title }}">
            @endif --}}

            @if(!file_exists(public_path($post->getImagePath())))
            @foreach($post->media as $mediafile)
            @php
            $_filename = str_replace('-thumb','',$mediafile->file_name);
            @endphp
            <img src="{{ asset('storage/'.$mediafile->id.'/'.$_filename) }}" alt="{{ $post->title }}">
            @break
            @endforeach
            
            @elseif ($image_url = $post->getImagePath())
            <img class="shadow-2 rounded" src="{{ asset($image_url) }}" alt="{{ $post->title }}">
            @else
            <img class="shadow-2 rounded" src="{{ asset('assets/img/vector/3.png') }}" alt="{{ $post->title }}">
            @endif
        </a>
		
    </div>
	
    <div class="card-body">
        
		
        <h5 class="card-title">
            <a href="{{ url($post->path()) }}">{{ $post->title }}</a>
        </h5>
		
		<!--<div class="category text-warning small-2">
             <i class="now-ui-icons business_bulb-63"></i>{{ $post->category->title  }}
        </div>-->
		
		<div class="category text-warning small-2">
             <!--<span class="align-middle px-1"></span>-->
          <time datetime="{{ $post->created_at }}">Posted on {{  $post->created_at->format('Y-m-d') }}</time>
          <span>in {{ $post->category->title  }}</span>
          <span>
        </div>
		
        <p class="card-description">
            {{ str_limit(strip_tags($post->description), 100, '...') }}
        </p>
		
		<a class="small ls-1" href="{{ url($post->path()) }}">{{ __('Read More') }} <span class="pl-1">⟶</span></a>
    </div>
</div>




