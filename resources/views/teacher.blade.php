@extends('layout')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1>Lehrveranstaltungen</h1>
    </div>

    <div align="right">
        <a href="/courses/create">Add</a>
    </div>

    @if (session('message'))
    <div class="alert alert-success" role="alert">
        <strong>{{ session('message') }}</strong>
    </div>
    @endif


    <table class="table">
        <tbody>
            <tr>
                <th>Modulnummer</th>
                <th>Name</th>
                <th>Semester</th>
                <th>Aktion</th>
            </tr>

            {{-- courses --}}
            @forelse ($courses as $course)
                <tr>
                    <td>{{ $course->module->module_nr }}</td>
                    <td>{{ $course->module->name }}</td>
                    <td>{{ $course->semester }}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group" aria-label="...">
                            <form action="/courses/{{ $course->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Löschen</button>
                            </form>
                            <button class="btn btn-primary">Bearbeiten</button>
                        </div>
                    </td>
                </tr>
            @empty
                <p>Keine Lehrveranstaltungen vorhanden.</p>
            @endforelse
        </tbody>

    </table>
</div>
@endsection
