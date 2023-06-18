@extends('client.layout')
@section('title', 'Trang chủ')
@section('content')
<!-- Page content-->
  
  
    <!-- Blog entries-->
      <!-- Featured blog post-->
      <div id="carouselExampleIndicators" class="carousel slide mb-4" data-bs-ride="true">
        <div class="carousel-indicators">
          @foreach ($posts_slide as $key => $item)
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : ''}}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
          @endforeach
        </div>
        <div class="carousel-inner">
          @foreach ($posts_slide as $key => $item)
          <div class="carousel-item {{ $key == 0 ? 'active' : ''}}">
            <a href="{{route('post.detail', $item->id)}}">
              <img src="{{ asset('uploads/'.$item->thumbnail) }}" class="d-block w-100 object-fit-cover" style="max-height: 400px" alt="...">
            <div class="carousel-caption d-none d-md-block">
              <h5>{{ $item->title }}</h5>
              <p>{!!$item->short_content !!}</p>  
            </div>  
            </a>
          </div>
          @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
      <!-- Nested row for non-featured blog posts-->
      <div class="row">
        @foreach ($posts as $post)
        <div class="col-lg-6">
          <!-- Blog post-->
      @include('client.components.post', ['post' => $post])
        </div>
        @endforeach
       
      </div>
      <!-- Pagination-->
      {{ $posts->links('custom.pagination') }}

@endsection
@section('show-sidebar')
@php
$showSidebar = false;
@endphp
@endsection