@extends('admin.layout')
@section('title', 'Dashboard')
    
@section('header_title', 'Tổng quan')    
@section('content')
<div class="row">
  <div class="col-xl-6 col-md-12 mb-4">
    @include('admin.components.card',['color'=>'primary','title'=>'Tổng bài viết'])
  </div>
</div>
<h2>Section title</h2>
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Header</th>
        <th scope="col">Header</th>
        <th scope="col">Header</th>
        <th scope="col">Header</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1,001</td>
        <td>random</td>
        <td>data</td>
        <td>placeholder</td>
        <td>text</td>
      </tr>

    </tbody>
  </table>
</div>
@endsection