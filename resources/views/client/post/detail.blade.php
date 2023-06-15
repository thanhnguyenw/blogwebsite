@extends('client.layout')
@section('title', 'Home')
@section('header', 'Blog Home')
@section('content')
<div class="border p-3 mb-4 rounded shadow-sm bg-body">
  <div class=" mb-3  " style="--bs-breadcrumb-divider: '\\|/';" aria-label="breadcrumb">
    <div class="breadcrumb px-2 border-top border-bottom border-dark">
      <p class="py-1 breadcrumb-item mb-0"><i class="fa-solid fa-user"></i> {{ $post->user->name }}
      </p>
      <p class="py-1 breadcrumb-item mb-0">Cập nhật lần cuối: {{ $post->created_at->diffForHumans()
        }}</p>
      <p class="py-1 breadcrumb-item mb-0">{{$comments->count()}} Bình luận</p>

      <p class="py-1 breadcrumb-item mb-0">
      <form id="likeForm" action="">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <p class="py-1 mb-0">Thích <button type="button" id="likeButton"
            class="bg-white border-0"><i class="fa-regular fa-heart"
              style="color: rgb(244, 137, 155)"></i></button></p>

      </form>
      </p>

    </div>
  </div>
  <div class="d-flex flex-column gap-3">
    <h2>{{ $post->title }}</h2>
    <div class="text-center">
      <img src="{{ asset('uploads/' . $post->thumbnail) }}" alt="" class="img-fluid">
    </div>
    <div class="w-100">{!! $post->short_content !!}</div>
  </div>
  <div>{!! $post->content !!}</div>
</div>
<div class="border p-3 mb-4 rounded shadow-sm bg-body">
  <h5>Bài viết liên quan</h5>
  <div class="row">
    @foreach ($relatedPosts as $relatedPost)
    <div class="col-4">
      @include('client.components.post_related', ['post' => $relatedPost])
    </div>
    @endforeach
  </div>
</div>
<div class="border p-3 mb-4 rounded shadow-sm bg-body">
  <h5>Bình luận</h5>
  <div class="row d-flex justify-content-center">
    <div class="col-12">
      <div class="card border-0">
        <div  class="card-body p-4">
          @foreach ($post->comments as $comment)
          <!-- Hiển thị thông tin về comment -->
          <div id="comment-{{ $comment->id }}" class="d-flex flex-start mt-4">
            <img class="rounded-circle shadow-1-strong me-3"
              src="{{ asset('uploads/' . $comment->user->avatar) }}" alt="avatar" width="40"
              height="40" />
            <div class="flex-grow-1 flex-shrink-1">
              <div class="comment-container">
                <div class="d-flex justify-content-between align-items-center">
                  <p class="mb-1">
                    {{ $comment->user->name }} <span class="small">- {{
                      $comment->created_at->diffForHumans() }}</span>
                  </p>
                  <a href="#!" class="reply-btn"><i class="fas fa-reply fa-xs"></i><span
                      class="small">
                      reply</span></a>
                </div>
                <p class="small mb-0">
                  {{ $comment->content }}
                </p>
                @if (Auth::check())
                <div class="reply-form d-none mt-3">
                  <form action="{{ route('comment.reply', ['commentId' => $comment->id]) }}"
                    method="post">
                    @csrf
                    <div class="d-flex flex-start w-100">
                      <img class="rounded-circle shadow-1-strong me-3"
                        src="{{ asset('uploads/' . Auth::user()->avatar) }}" alt="avatar" width="40"
                        height="40" />
                      <div class="form-outline w-100">
                        <textarea class="form-control" id="textAreaExample" rows="4" name="content"
                          style="background: #fff;"></textarea>
                        <label class="form-label" for="textAreaExample">Message</label>
                      </div>
                    </div>
                    <div class="float-end mt-2 pt-1">
                      <button type="submit" class="btn btn-primary btn-sm">Post comment</button>
                      <button type="button" class="btn btn-outline-primary btn-sm" >Cancel</button>
                    </div>
                  </form>
                </div>
                @endif
              </div>
              @foreach ($comment->replies->sortByDesc('created_at') as $reply)
              <!-- Hiển thị thông tin về reply -->
              <div class="d-flex flex-start mt-4">
                <a class="me-3" href="#">
                  <img class="rounded-circle shadow-1-strong"
                    src="{{ asset('uploads/' . $reply->user->avatar) }}" alt="avatar" width="40"
                    height="40" />
                </a>
                <div class="flex-grow-1 flex-shrink-1">
                  <div>
                    <div class="d-flex justify-content-between align-items-center">
                      <p class="mb-1">
                        {{ $reply->user->name }} <span class="small">- {{
                          $reply->created_at->diffForHumans() }}</span>
                      </p>
                    </div>
                    <p class="small mb-0">
                      {{ $reply->content }}
                    </p>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js-custom')
<script>
  $(document).ready(function() {
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
    const post_id = $('input[name="post_id"]');
const likeButton = $('#likeButton');
const iconLike = likeButton.find('i');
const likeQuantity = $('#likeQuantity');
let isUnauthorizedError = false;

function updateLikeButton(like) {
  if (like) {
    iconLike.removeClass('fa-regular').addClass('fa-solid');
  } else {
    iconLike.removeClass('fa-solid').addClass('fa-regular');
  }
}

function handleErrorResponse(status) {
  if (status === 401) {
    isUnauthorizedError = true;
  } else {
    // errorContainer.text("Lỗi không xác định.");
  }
}

function getLike() {
  $.ajax({
    url: "{{ route('like.check', ['postId' => $post->id]) }}",
    method: "GET",
    success: function(response) {
      updateLikeButton(response.like);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      handleErrorResponse(jqXHR);
    }
  });
}

  getLike();
  likeButton.on('click', function(event) {
  event.preventDefault(); // Ngăn chặn gửi yêu cầu POST mặc định
  if (isUnauthorizedError) {
    window.location.href = '/login';
  }
  
  $.ajax({
    url: "{{ route('like.add', ['postId' => $post->id]) }}",
    method: "POST",
    data: {
      post_id: post_id.val()
    },
    success: function(response) {
      updateLikeButton(response.like);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      handleErrorResponse(jqXHR.status);
    }
  });
});


    $('.reply-btn').click(function() {
      console.log('click');
      $(this).closest('.comment-container').find('.reply-form').toggleClass('d-none');
    });
    
});
</script>
@if (session('anchor'))
<script>
  window.location.hash = '{{ session('anchor') }}';
</script>
@endif

@endsection
