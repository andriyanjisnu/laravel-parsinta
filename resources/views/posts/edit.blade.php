@extends('layouts.app', ['title' => 'Update post'])
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      @include('alert')
      <div class="card">
        <div class="card-header">
          Update post {{ $post->title }}
        </div>
        <div class="card-body">
          <form action="/post/{{$post->slug}}/edit" method="post" enctype="multipart/form-data">
            @method('patch')
            @csrf
            @include('posts.partials.form-control')
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection