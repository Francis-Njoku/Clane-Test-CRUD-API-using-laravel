@extends('layouts.app')

@section('content')
<h1>Create Post</h1>
{!! Form::open(['action' => ['ArticleController@update', $posts->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="form-group">
{{Form::label('title', 'Title')}}
{{Form::text('title', $posts->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}

</div>
<div class="form-group">
{{Form::label('body', 'Body')}}
{{Form::textarea('post', $posts->post, ['class' => 'form-control', 'placeholder' => 'Body'])}}

</div>

{{Form::hidden('_method', 'PUT')}}
{{Form::submit('Sumbit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}

@endsection