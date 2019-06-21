@extends('layout')

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div align="left">
                    <h3>{{ $course->module->title }}: {{ $course->semester }}</h3>
                </div>
                <div align="right">
                    <form action="{{ action('CourseController@edit', [$course->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Kurs löschen</button>
                    </form>
                </div>
                @if (session('error'))
                    <div class="alert alert-danger" role="alert" style="margin-top: 10px">
                        <strong>{{ session('error') }}</strong>
                    </div>
                @endif
            </div>

            <div class="panel-body">
                <form action="{{ action('CourseController@update', [$course->id]) }}" method="POST">
                    @method('PATCH')
                    @csrf
                    <div class="form-group row">
                         <label for="module_no" class="col-sm-2 col-form-label">Modulnummer</label>
                         <div class="col-sm-10">
                            <input type="text" class="form-control" name="module_no" placeholder="Modulnummer" required value="{{ $course->module->number }}">
                        </div>
                    </div>
                    <div class="form-group row">
                         <label for="module_title" class="col-sm-2 col-form-label">Titel</label>
                         <div class="col-sm-10">
                            <input type="text" class="form-control" name="module_title" placeholder="Titel" required  value="{{ $course->module->title }}">
                        </div>
                    </div>
                    <div class="form-group row">
                         <label for="semester" class="col-sm-2 col-form-label">Semester</label>
                         <div class="col-sm-10">
                            <input type="text" class="form-control" name="semester" required placeholder="Semester" value="{{ $course->semester }}">
                        </div>
                    </div>
                    <div class="form-group" align="right">
                        <button type="submit" class="btn btn-primary">Kurs bearbeiten</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
