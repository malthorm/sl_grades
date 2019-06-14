@if ($course)
    <tr>
        <td class="hidden">{{ $course->id }}</td>
        <td>{{ $course->module->module_nr }}</td>
        <td>{{ $course->module->name }}</td>
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

           {{--}}
            <noscript>
                <div class="btn-group btn-group-sm" role="group" aria-label="...">
                    <form action="/courses/{{ $course->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Löschen</button>
                    </form>
                    <a href="/courses/{{ $course->id }}">
                        <button id="editbtn" class="btn btn-primary btn-sm">Bearbeiten</button>
                    </a>
            </noscript>
                             <a href="#" class="show-modal btn btn-info btn-sm" data-id="{{ $course->id }}" data-title="{{ $course->module->name }}" data-body="{{ $course->enrolled }}">
                    <i class="fa fa-eye"></i>
                </a>
                </div>
                {{--}}
