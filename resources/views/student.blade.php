@extends ('layout')

@section ('content')
{{-- DSGVO Datenschutzerklärung etc --}}

<h1>Meine Studienleistungen</h1>

{{-- oder mit matrikelnummer pls pw form beides verschlüsselt in db --}}
@if (empty($student))
<form action="/grades" method="GET">
    <div class="form-group">
        <label for="studentId">Unikennzeichen</label>
        <input type="text" class="form-control" name="studentId">
        <small class="form-text text-muted">verschlüsseln?</small>
    </div>
        <button type="submit" class="btn btn-primary">Submit</button>
</form>

@else
<table class="table">
    <thead>
        <tr>
            <th scope="col">Modulnummer</th>
            <th scope="col">Modulname</th>
            <th scope="col">Note</th>
            <th scope="col">Semester</th>
            <th scope="col">Versuche</th>
            <th scope="col">Aktion</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($student->enrolledIn as $grade)
        @if ($grade->islatestAttempt($grade->course->module, $student->id))
        <tr>
            <td>{{ $grade->course->module->module_nr }}</td>
            <td>{{ $grade->course->module->name }}</td>
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
