@extends('layouts.app')

@section('content')
<h3>
{{$posts->title}}
</h3>
<div>
{!!$posts->post!!}
</div>
<hr>
<small>written on {{$posts->created_at}} by {{$posts->author}}</small>
<hr>
@if(!Auth::guest())
@if(Auth::user()->id == $posts->author)
<a href="/posts/{{$posts->id}}/edit" class="btn btn-default">Edit</a>
{!!Form::open(['action' => ['ArticleController@destroy', $posts->id], 'method' => 'POST', 'class' => ''])!!}
{{Form::hidden('_method', 'DELETE')}}
{{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
{!!Form::close()!!}
@endif
@endif
@php
$i=1;
@endphp
@for($x = 1; $x <= 5; $x++)

@endfor
{!!Form::open(['action' => ['ArticleController@rating'], 'method' => 'POST', 'class' => ''])!!}
{{Form::hidden('post_id', $posts->id)}}

@for($x = 1; $x <= 5; $x++)
<input name="rate" type="radio" value="{{$x}}">
@endfor

{{Form::submit('Review', ['class' => 'btn btn-danger'])}}
{!!Form::close()!!}
        @endsection