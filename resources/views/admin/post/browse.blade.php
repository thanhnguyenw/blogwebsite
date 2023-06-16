@extends('admin.layout')
@section('title', 'Duyệt bài viết')
    
@section('header_title', 'Bài viết chưa duyệt')    
@section('content')
@if (session()->has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
    
@endif
<div class="table-responsive">
  <table class="table table-primary">
    <thead>
      <tr>
        <th scope="col">STT</th>
        <th scope="col">Ngày đăng</th>
        <th scope="col">Tên người tạo</th>
        <th scope="col">Tiêu đề</th>
        <th scope="col">Ảnh đại diện</th>
        <th scope="col">Mô tả ngắn</th>
        <th scope="col">Đánh dấu là bài đặc sắc</th>
        <th scope="col">Hành động</th>
      </tr>
    </thead>
    <tbody>
     @foreach ($posts as $key => $post)
     <tr>
      <th scope="row">{{ ++$key }}</th>
      <td>{{ $post->updated_at->diffForHumans() }}</td>
      <td>{{ $post->user->name }}</td>
      <td>{{ $post->title }}</td>
      <td>
        @if ($post->thumbnail)
        <img class="object-fit-cover" src="{{ asset('uploads/'.$post->thumbnail) }}" width="100px" height="100px" alt="">
        @endif
      </td>
      <td class="text-wrap">{!! $post->short_content !!}</td>
      <td>
        <form class="featureForm" action="{{ route('admin.post.feature', $post->id)}}" method="post">
          @csrf
          <div class="form-check form-switch">
            <input class="form-check-input featureCheckbox" type="checkbox" @if ($post->is_featured == 1) checked @endif>
          </div>
        </form>
      </td>
      <td>
        <form action="{{ route('admin.post.status', $post->id)}}" method="post">
          @csrf
            <button type="submit" class="btn btn-primary"name="status" value="approved">Duyệt</button>
            <button type="submit" class="btn btn-danger"name="status" value="rejected">Từ chối</button>
        </form>
      </td>
     </tr>
      @endforeach
   <tr>
    <td colspan="8">
      {{ $posts->links('custom.pagination') }}
    </td>
   </tr>
    </tbody>
  </table>
</div>

@endsection
@section('js-custom')
<script>
   $(document).ready(function() {
    $('.featureCheckbox').on('change', function() {
      var form = $(this).closest('form');
      form.submit();
    });
  });
</script>

@endsection