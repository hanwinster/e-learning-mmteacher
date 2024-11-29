<h3>{{ __('Level') }}</h3>
<div class="list-group">
    @foreach ($levels as $key => $level)
        <div class="input-group p-1 mt-1">
            <input type="checkbox" class="checkbox checkbox-inline mt-1 mr-2 course-filter" name="course_level"
                   value="{{ $key }}"
            ><label for="">{{ $level }}</label>
        </div>
    @endforeach
</div>

<br>
