<table class="table" id="courseTable">
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
