@extends('layouts.app')

@section('content')
<h1>Create Post</h1>
{!! Form::open(['action' => 'ArticleController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
<div class="form-group">
{{Form::label('title', 'Title')}}
{{Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Title'])}}

</div>
<div class="form-group">
{{Form::label('body', 'Body')}}
{{Form::textarea('post', '', ['class' => 'form-control', 'placeholder' => 'Body'])}}

</div>
<div class="form-group">
</div>
{{Form::submit('Sumbit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}

@endsection