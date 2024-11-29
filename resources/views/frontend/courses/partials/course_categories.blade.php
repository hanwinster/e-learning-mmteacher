<h3>{{ __('Course Categories') }}</h3>
<div class="list-group">
    @foreach ($courseCategories as $category)
        <div class="input-group p-1 mt-1">
            <input type="checkbox" class="checkbox checkbox-inline mt-1 mr-2 course-filter" name="course_category"
                   value="{{ $category->id }}"
            ><label for="">{{ $category->name }}</label>
        </div>
    @endforeach
</div>
<br>
