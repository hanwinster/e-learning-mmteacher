<div>
    <h3 id="reviews">{{ __('Ratings & Reviews') }}</h3>

    @guest
    <div id="start_review_msg" class="">
        <div class="mb-0">{{ __('You must login to leave a review.') }}</div>
        <p class="text-gray fs-14">If you have not logged in, please <a href="{{ route('login') }}">login</a> to leave a review.</p>
    </div>
    @else
    @if ($post->allow_feedback)
    <form action="{{ route('member.review.store', $post->id) }}" method="post">
    {{ csrf_field() }}
    <div class="form-group">
        <div class="my-rating"></div>
        {{-- <input id="input-1" name="rate" class="rating rating-loading" data-min="0" data-max="5" data-step="1" value="{{ $post->userAverageRating }}" data-size="xs"> --}}
        <input type="hidden" name="rating" id="rating" required="" value="">
        <input type="hidden" name="resource_id" required="" value="{{ $post->id }}">
        {!! $errors->first('rating', '<div class="invalid-feedback">:message</div>') !!}
        <br/>
    </div>
    <div class="form-group">
        <textarea name="comment" class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}" rows="4" placeholder="{{ __('Leave a reveiw') }}"></textarea>
        {!! $errors->first('comment', '<div class="invalid-feedback">:message</div>') !!}
    </div>
    <div class="form-group">
        <button class="btn btn-success">{{ __('Submit Review') }}</button>
    </div>
    </form>
    @else
    <p>{{ __('Currently feedbacks are not allowed.') }}</p>
    @endif
    @endguest

    @if ($post->reviews)
    @foreach ($post->reviews as $review)
    <div class="pb-5">
        <div class="card border shadow-6">
        <div class="card-body px-5">
            <div class="row">
            <div class="col-auto mr-auto">
                <!--<h6><strong></strong></h6>-->
            </div>

            <div class="col-auto">
                <div class="rating ml-4">
                    @for($i= 1; $i <= 5; $i++)
                    <label class="fa fa-star {{ $i <= $review->rating ? '': 'empty' }}"></label>
                    @endfor
                </div>
            </div>
            </div>

            <p>{{ $review->comment }}</p>
            <p class="small-2 text-lighter mb-0">{{ __('By') }}
            <span class="text-inherit"><em>{{ $review->user->name ?? '' }}</em></span> {{ __('at') }} {{ $review->created_at }}
            </p>

        </div>
        </div>
    </div>
    @endforeach
    @endif
</div>
