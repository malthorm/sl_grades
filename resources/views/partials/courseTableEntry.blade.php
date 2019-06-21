@if ($course)
    <tr>
        <td class="hidden">{{ $course->id }}</td>
        <td>{{ $course->module->number }}</td>
        <td>
            <a href="{{action('CourseController@show', [$course->id]) }}">
                {{ $course->module->title }}
            </a>
        </td>
        <td>{{ $course->semester }}</td>
        <td>
            <div class="dropdown" aria-label="Course Actions">
                <button class="btn btn-primary dropbtn">Aktion</button>
                <div role="menu" class="dropdown-content">
                    <a href="#" class="gradesbtn">Noten eintragen</a>
                    <a href="#" class="csvbtn">Importiere CSV</a>
                    <a href="#" class="editbtn">Kurs bearbeiten</a>
                    <a href="#" class="deletebtn">Kurs löschen</a>
                </div>
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

