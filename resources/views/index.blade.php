@extends('layouts.app')

@section('content')
    {{--Показ формы только если авторизованы--}}
    @auth
    <form method="post" action="{{route('logOut')}}">
        @csrf
        @method('DELETE')
        <button type="submit">Выйти</button>
    </form>
    @endauth
@endsection

{{--{{env('GITHUB_CLIENT_CALLBACK')}}--}}
