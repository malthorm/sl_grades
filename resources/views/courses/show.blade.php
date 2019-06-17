@extends('layout')

@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $course->module->title }}: {{ $course->semester }}</h3>
                <form action="{{ action('GradingController@store', [$course->id]) }}" method="POST">
                    @csrf
                    <div class="form-inline">
                        <div class="form-group col-md-4">
                            <label for="uni_identifier">Unikennzeichen</label>
                            <input type="text" class="form-control" name="uni_identifier" required placeholder="Unikennzeichen" value="{{ old('uni_identifier') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="grade">Note</label>
                            <input type="text" class="form-control" name="grade" required placeholder="1.0" value="{{ old('grade') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">
                               Student hinzufügen
                        </button>
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
                <div class="alert alert-success" role="alert" style="margin-top: 10px">
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
                    <table class="table table-sm">
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
    </div>

<button class="btn">Import CSV</button>


@endsection

