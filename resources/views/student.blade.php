@extends ('layout')

@section ('content')
{{-- DSGVO Datenschutzerklärung etc --}}

<h1>Meine Studienleistungen</h1>

{{-- oder mit matrikelnummer pls pw form beides verschlüsselt in db --}}
@if (empty($student))
<form action="{{ action('GradingController@index') }}" method="GET">
    <div class="form-group">
        <label for="uni_identifier">Unikennzeichen</label>
        <input type="text" class="form-control" name="uni_identifier">
    </div>
        <button type="submit" class="btn btn-primary">Submit</button>
</form>

@else
<table class="table">
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
            <td>TODO</td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>

@endif
@endsection
