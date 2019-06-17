@if ($course)
    <tr>
        <td class="hidden">{{ $course->id }}</td>
        <td>{{ $course->module->number }}</td>
        <td>{{ $course->module->title }}</td>
        <td>{{ $course->semester }}</td>
        <td>
            <div class="btn-group" role="group" aria-label="Course Actions">
                <a href="#" class="btn btn-success btn-sm gradesbtn">Noten eintragen</a>
                <a href="#" class="btn btn-primary btn-sm editbtn">Bearbeiten</a>
                <a href="#" class="btn btn-danger btn-sm deletebtn">Löschen</a>
            </div>

        </td>
    </tr>

@else
   <tr>
       <td align="center" colspan="5">
            Keine Lehrveranstaltungen gefunden
        </td>
   </tr>
@endif

