@extends('layout_local')

@section('content')


    <a href="/Shibboleth.sso/Login?target=https://www.tu-chemnitz.de/~malth/">Login</a>

    <div>
        <form action="{{ action('CourseController@testAuth') }}">
            <button type="submit">Student</button>
        </form>
    </div>
    <div>
        <form action="{{ action('CourseController@testAuth', [true]) }}">
            <button type="submit">Student</button>
        </form>
    </div>
@endsection
