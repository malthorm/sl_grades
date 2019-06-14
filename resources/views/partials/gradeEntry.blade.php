@if ($grades)
    @foreach ($grades as $grade)
        <tr>
            <td hidden>{{ $grade->id }}</td>
            <td>{{ count($grades) + 1 - $loop->iteration }}</td>
            <td>{{ $grade->student_id }}</td>
            <td>{{ $grade->grade }}</td>
            <td>
                <button type='submit' class='btn btn-danger btn-sm deletegradebtn'>Löschen</button>
            </td>
        </tr>
    @endforeach
@endif
