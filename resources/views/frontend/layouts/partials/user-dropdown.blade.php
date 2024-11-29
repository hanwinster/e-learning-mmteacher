<div class="dropdown user-dropdown">
    <span id="user-dropdown" class="dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown">
        <span class="nav-item pl-0">
            {{ __('Hi') }} 
            @php if(config('app.locale') !== 'en') echo "<br/>"; @endphp
            <strong>{{ $username }}</strong>
        </span>
    </span>                
    <div class="dropdown-menu" style="z-index:3000;">                           
        <a class="dropdown-item" 
            href="{{ route('member.dashboard') }}">
                {{ __('Dashboard') }}
        </a>
        <a class="dropdown-item"
            href="{{ route('courses.my-courses') }}">
                {{ __('My Courses') }}
        </a>                       
    </div>
</div>