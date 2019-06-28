@extends ('layout')

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
            @if (empty($student))
                <tr>
                    <td align="center" colspan="5">
                        <h4><strong>Es wurden keine Noten gefunden.</strong></h4>
                    </td>
                </tr>
            @else
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
            @endif
            </tbody>
        </table>
    </div>

@endsection
