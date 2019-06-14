@extends('layout')

@section('content')
    <h1>Neue Lehrveranstaltung</h1>
    <hr>

    <form class="form-inline" action="/courses" method="POST">
        @csrf

        <div class="form-group">
            <label for="module_nr">Modulnummer</label>
            <input type="text" class="form-control" name="module_nr" required placeholder="563030">

            <label for="name">Modulname</label>
            <input type="text" class="form-control" name="module_name" required placeholder="Datenbanken Grundlagen">
            {{-- bootstrap form controls anstelle von placeholder? --}}
            <label for="semester">Semester</label>
            <input type="text" class="form-control" name="semester" required="" placeholder="WS 18">
        </div>
        <button type="submit" class="btn btn-default">Lehrveranstaltung erstellen</button>

    </form>
@endsection
