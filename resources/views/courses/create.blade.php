@extends('layout')

@section('content')
    <div style="display: flex; align-items: center;" >
        <h2 style="margin-right: auto;">Neue Lehrveranstaltung</h2>
        <a href="{{ action('CourseController@index') }}">Zurück</a>
    </div>
    <hr>

    <form class="form-inline" action="{{ action('CourseController@store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="module_no">Modulnummer</label>
            <input type="text" class="form-control" name="module_no" required placeholder="563030">

            <label for="module_title">Title</label>
            <input type="text" class="form-control" name="module_title" required placeholder="Datenbanken Grundlagen">

            <label for="semester">Semester</label>
            <input type="text" class="form-control" name="semester" required="" placeholder="WS 18">
        </div>
        <button type="submit" class="btn btn-default">Lehrveranstaltung erstellen</button>

    </form>

    @if (session('message'))
    <div class="alert alert-danger" role="alert" style="margin-top: 20px">
        <strong>{{ session('message') }}</strong>
    </div>
    @endif
@endsection
