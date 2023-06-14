  <!-- Side widgets-->
  
    <!-- Search widget-->
    <div class="card mb-4">
      <div class="card-header">Search</div>
      <div class="card-body">
       <form action="{{ route('search') }}" method="get">
        <div class="input-group">
          <input class="form-control" type="text" placeholder="Enter search term..."
            aria-label="Enter search term..." aria-describedby="button-search" name="q" value="{{ old('q') }}"/>
          <button class="btn btn-primary" id="button-search" >Go!</button>
        </div>
      </form>
      </div>
    </div>
    <!-- Categories widget-->
    <div class="card mb-4">
      <div class="card-header">Danh mục</div>
      <div class="card-body">
        <div class="row">
            <div class="row mb-0">
              @foreach ($categories as $category)
              <div class="col-md-6 text-justify"><a href="{{route('post.showByCategory', ['categoryId' => $category->id]) }}">{{ $category->name }}</a></div>
              @endforeach
            </div>
         
        </div>
      </div>
    </div>
    <!-- Side widget-->
    <div class="card mb-4">
      <div class="card-header">Side Widget</div>
      <div class="card-body">You can put anything you want inside of these side widgets. They
        are easy to use, and feature the Bootstrap 5 card component!</div>
    </div>