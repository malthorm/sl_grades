<table class="table horizontal table-sm" id="courseTable">
    <thead>
        <tr>
            <th scope="col">Modulnummer</th>
            <th scope="col">Titel</th>
            <th scope="col">Semester</th>
            <th scope="col">
                <!-- Button trigger courseAddModal -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#courseAddModal" role="button">Neue Lehrveranstaltung</button>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($courses as $course)
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
        @endforeach
    </tbody>
</table>

<div>
    <nav aria-label="Courses navigation">
        <ul class="pagination">
            {{ $courses->links() }}
        </ul>
    </nav>
</div>

