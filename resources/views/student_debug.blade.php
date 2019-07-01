@extends ('layouts.app')

@section ('content')
    <h1>Meine Studienleistungen</h1>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (empty($student))
    <form action="{{ action('GradingController@index') }}" method="GET" class="form-horizontal">
        <div class="form-group">
            <label for="uni_identifier" class="col-xm-3 control-label">Unikennzeichen</label>
            <input type="text" class="form-control" name="uni_identifier" placeholder="Unikennzeichen">
        </div>
        <div class="form-group">
            <div class="col-xm-offset-3 col-xm-9">
                <button type="submit" class="btn btn-primary">Absenden</button>
            </div>
        </div>
    </form>

    @else
    <div class="table-responsive">
        <table class="table horizontal">
            <thead>
                <tr>
                    <th scope="col">Modulnummer</th>
                    <th scope="col">Titel</th>
                    <th scope="col">Note</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Versuche</th>
                    <th scope="col">Aktion</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($student->grades as $grade)
                    @if ($grade->islatestAttempt($grade->course->module, $student->id))
                    @php $grade->decryptGrade() @endphp
                    <tr>
                        <td>{{ $grade->course->module->number }}</td>
                        <td>{{ $grade->course->module->title }}</td>
                        <td>{{ $grade->grade }}</td>
                        <td>{{ $grade->course->semester }}</td>
                        <td>{{ $grade->countAttempts($grade->course->module, $student->id) }}</td>
                        <td>
                            <form action="{{ action('GradingController@destroy', [$grade->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Löschen</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
   </div>
    @endif
@endsection
