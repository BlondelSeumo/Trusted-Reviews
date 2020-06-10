@extends('admin.base')

@section('section_title')
  <strong>Update Review</strong>
@endsection



@section('section_body')

<form method="post" action="/admin/reviews/update/{{ $r->id }}">
@csrf


<div class="form-group">
<label>{{ __('Review Rating') }}</label>
<select name="rating">
   <option value="1" @if($r->rating == 1) selected @endif>1 Star</option>
   <option value="2" @if($r->rating == 2) selected @endif>2 Stars</option>
   <option value="3" @if($r->rating == 3) selected @endif>3 Stars</option>
   <option value="4" @if($r->rating == 4) selected @endif>4 Stars</option>
   <option value="5" @if($r->rating == 5) selected @endif>5 Stars</option>
</select>
</div>

<div class="form-group">
<label>{{ __('Review Title') }}</label>
<input class="form-control" type="text" name="review_title" value="{{ $r->review_title }}" required="required">
</div>
<div class="form-group">
<label>{{ __( 'Description' ) }}</label>
<textarea class="form-control" style="height: 180px;" name="review_content" required="required">{{ $r->review_content }}</textarea>
</div>
<button type="submit" name="sbReview" class="btn btn-primary btn-block">{{ __('Update Review') }}</button>

</form>

@endsection