@extends('layout')
{{--     <form action="{{ action('GradingController@csvImport', [$course->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="from-group">
            <label for="file">CSV-Datei</label>
            <input type="file" name="file" class="form-control" accept=".csv">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Upload CSV</button>
        </div>
    </form> --}}
@section('content')
     <div class="btn-group btn-group-justified">
          <div class="btn-group">
              <form action="{{ action('CourseController@destroy', [$course->id]) }}" method="POST">
                  @csrf
                  @method('DELETE')
          <button type="submit" class="btn btn-primary">Kurs löschen
          </button>
              </form>
          </div>

          <a href="{{ action('CourseController@edit', [$course->id]) }}" class="btn btn-primary">Kurs bearbeiten</a>
          <a href="{{ action('CourseController@index') }}" class="btn btn-primary">Zurück</a>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <form action="{{ action('GradingController@store', [$course->id]) }}" method="POST" class="form-horizontal" role="form">
                @csrf
                <div class="form-group">
                    <label class="control-label col-sm-2" for="uni_identifier">Unikennzeichen</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control"
                        name="uni_identifier" required
                        placeholder="Unikennzeichen"
                        value="{{ old('uni_identifier') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2"
                    for="grade">Note</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control"
                         name="grade" required placeholder="1.0"
                         value="{{ old('grade') }}" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">
                               Student hinzufügen
                        </button>
                    </div>
                </div>
            </form>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert" style="margin-top: 10px">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            @elseif (session('danger'))
            <div class="alert alert-danger" role="alert" style="margin-top: 10px">
                <strong>{{ session('danger') }}</strong>
            </div>
            @elseif (session('message'))
            <div class="alert alert-info" role="alert" style="margin-top: 10px">
                <strong>{{ session('message') }}</strong>
            </div>
            @elseif (session('change'))
            <div class="alert alert-info" role="alert" style="margin-top: 10px">
                <strong>{{ session('change') }}</strong>
            </div>
            @endif
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table horizontal table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Unikennzeichen</th>
                            <th scope="col">Note</th>
                            <th scope="col">Aktion</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($course->gradings as $gradedStudent)
                        <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $gradedStudent->student->uni_identifier}}</td>
                                <td>{{ $gradedStudent->grade }}</td>
                                <td>
                                    <form action="{{ action('GradingController@destroy', [$gradedStudent->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Löschen</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                           <tr>
                                <td align="center" colspan="5">
                                    <h4><strong>Es wurden noch keine Noten vergeben.</strong></h4>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<form action="{{ action('GradingController@csvImport', [$course->id]) }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="from-group">
    <label for="file">Importiere Csv</label>
    <input type="file" name="file" class="form-control" accept=".csv">
</div>
<div class="form-group">
    <button class="btn btn-primary" type="submit">Upload Csv</button>
</div>
</form>

@endsection

