@if ($grades)
    @foreach ($grades as $grading)
        <tr>
            <td hidden>{{ $grading->id }}</td>
            <td>{{ count($grades) + 1 - $loop->iteration }}</td>
            <td>{{ $grading->student->uni_identifier }}</td>
            <td>{{ $grading->grade }}</td>
            <td>
                <button type='submit' class='btn btn-danger btn-sm deletegradebtn'>Löschen</button>
            </td>
        </tr>
    @endforeach
@endif
