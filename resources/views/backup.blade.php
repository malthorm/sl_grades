    {{-- form Create Course --}}
    <div id="courseForm" class="modal fade in" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group row add">
                            <label for="moduleNr" class="control-label col-sm-4">Modul Nummer:</label>
                            <div class="col-sm-8">
                                <input id="moduleNr" type="text" class="form-control" name="moduleNr" placeholder="Modulnummer" required>
                                <p class="error text-center alert alert-danger hidden"></p>
                            <label for="moduleName" class="control-label col-sm-4">Name:</label>
                            </div>
                            <div class="col-sm-8">
                                <input id="moduleName" type="text" class="form-control" name="moduleName" placeholder="Name der Lehrveranstaltung" required>
                                <p class="error text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Semester" class="control-label col-sm-4">Semester :</label>
                            <div class="col-sm-8">
                                <input id="semester" type="text" class="form-control" name="semester" placeholder="WS 18" required>
                                <p class="error text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" id="createCourse">
                        <span class="glyphicon glyphicon-plus"></span>Lehrveranstaltung erstellen
                    </button>
                    <button class="btn btn-warning" type="button" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove"></span>Schließen
                    </button>
                </div>
            </div>
        </div>
    </div>

{{-- AJAX form createCourse --}}
<script type="text/javascript">
$(document).on('click', '.create-modal', function() {
  $('#courseForm').modal('show');
  $('.form-horizontal').show();
  $('.modal-title').text('Neue Lehrveranstaltung');
});
// function createCourse
$('#createCourse').click(function() {
  $.ajax({
    type: 'POST',
    url: '/courses',
    data: {
      '_token': $('input[name=_token]').val(),
      'module_nr': $('input[name=moduleNr]').val(),
      'module_name': $('input[name=moduleName]').val(),
      'semester': $('input[name=semester]').val()
    },
    success: function(data) {
      dd('test');
      if (data.errors) {
        $('.error').removeClass('hidden');
        $('.error').text(data.errors.moduleNr);
        $('.error').text(data.errors.moduleName);
        $('.error').text(data.errors.semester);
      } else {
        $('.error').remove();
        $('#courseTable').append
        (
          "<p>test</p>"
        );
      }
    },
    error: function(error) {
      // Logging?
      console.log(error.responseJSON.errors);

    }
  });
});

</script>--}}

<!-- ADD Course Data Modal -->
<div class="modal fade in" id="courseAddModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="courseAddModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" text-center primecolor id="courseTitleModal">Neue Lehrveranstaltung</h3>
            </div>

            <form id="courseAddForm">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="module_nr">Modulnummer</label>
                        <input type="text" class="form-control" name="module_nr" placeholder="563030">
                    </div>

                    <div class="form-group">
                        <label for="module_name">Titel</label>
                        <input type="text" class="form-control" name="module_name" placeholder="Datenbanken Grundlagen">
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <input type="text" class="form-control" name="semester" placeholder="SS 19">
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="alert alert-danger" align="left" hidden id="courseFormErrors">
                    </div>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Grades Modal -->
<div class="modal fade in" id="gradesModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="gradesModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" text-center primecolor id="gradesModalTitle"></h3>
                <input type="hidden" id="hiddenCourseId" readonly>
            </div>
            <form id="addGradesForm">
                <div class="modal-body">
                    @csrf
                    {{--@method('POST')--}}
                    <div class="table-reponsive">
                        <table class="table table-sm" id="modalGradesTable">
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">Unikennzeichen</th>
                                <th scope="col">Note</th>
                                <th scope="col">
                                    <a href="#" class="btn btn-success" id="addStudentGrade">+</a>
                                </th>
                            </thead>
                            <tbody>
                                <!-- Dynamic input -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="alert alert-danger" align="left" hidden id="gradesFormErrors">
                    </div>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>


        // Search functionality
        $request->session()->put('search', $request
                ->has('search') ? $request->get('search') : ($request->session()
                ->has('search') ? $request->session()->get('search') : ''));
        $modules = Module::where('name', 'like', '%' . $request->session()->get('search') . '%')
            ->orWhere('module_nr', 'like', '%' . $request->session()->get('search') . '%')
            ->get();

        if ($modules) {
            $courses = collect();
            foreach ($modules as $module) {
                $courses = $courses->concat($module->courses);
            }
        } else {
            $courses = Course::all();
        }
