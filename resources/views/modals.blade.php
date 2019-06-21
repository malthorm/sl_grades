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
                    <div class="form-group has-feedback">
                        <label for="module_no">Modulnummer</label>
                        <input type="text" class="form-control" name="module_no" placeholder="563030" required>
                        <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                        <span class="text-danger">
                            <strong id="module_no-error"></strong>
                        </span>
                    </div>

                    <div class="form-group has-feedback">
                        <label for="module_title">Titel</label>
                        <input type="text" class="form-control" name="module_title" placeholder="Datenbanken Grundlagen" required>
                        <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                        <span class="text-danger">
                            <strong id="module_title-error"></strong>
                        </span>
                    </div>

                    <div class="form-group has-feedback">
                        <label for="semester">Semester</label>
                        <input type="text" class="form-control" name="semester" placeholder="SS 19" required>
                        <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                        <span class="text-danger">
                            <strong id="semester-error"></strong>
                        </span>
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


<!-- Edit Course Data Modal -->
<div class="modal fade in" id="courseEditModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="courseEditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" text-center primecolor id="courseTitleEditModal">Lehrveranstaltung</h3>
            </div>

            <form id="courseEditForm">
                <div class="modal-body">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="id" id="courseId">

                    <div class="form-group has-feedback">
                        <label for="module_no">Modulnummer</label>
                        <input type="text" class="form-control" name="module_no" placeholder="563030" required id="editModuleNr">
                        <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                        <span class="text-danger">
                            <strong id="editModal_module_no-error"></strong>
                        </span>
                    </div>

                    <div class="form-group has-feedback">
                        <label for="module_title">Titel</label>
                        <input type="text" class="form-control" name="module_title" placeholder="Datenbanken Grundlagen" required id="editModuleName">
                        <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                        <span class="text-danger">
                            <strong id="editModal_module_title-error"></strong>
                        </span>
                    </div>

                    <div class="form-group has-feedback">
                        <label for="semester">Semester</label>
                        <input type="text" class="form-control" name="semester" placeholder="SS 19" required id="editSemester">
                        <span class="glyphicon glyphicon-asterisk form-control-feedback"></span>
                        <span class="text-danger">
                            <strong id="editModal_semester-error"></strong>
                        </span>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="alert alert-danger" align="left" hidden id="courseEditFormErrors">
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
                <form enctype="multipart/form-data" class="form-inline" id="csvForm" hidden>
                    @csrf
                    <input type="file" name="file" class="form-control" accept=".csv" required / id="file">
                    <input type="submit" class="btn btn-primary submitCsvBtn" value="Csv importieren">
                </form>
                <form id="addGradeForm">
                    @csrf
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="uni_identifier" placeholder="Unikennzeichen" required>
                                    </td>
                                    <td>
                                        <input type="text" name="grade" placeholder="Note (2.0)" required>
                                        <input type="hidden" id="hiddenCourseId" readonly>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary">Speichern</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>

                <div class="alert alert-info alert-dismissible" role="alert" id="gradeModalAlert" hidden style="margin-top: 10px; margin-bottom: 0px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <ul id="gradeModalAlertMsg">
                    </ul>
                </div>
                </div>

            <div class="modal-body">
                <div class="table-reponsive">
                    <table class="table table-sm" id="modalGradesTable">
                        <thead>
                            <th scope="col">#</th>
                            <th scope="col">Unikennzeichen</th>
                            <th scope="col">Note</th>
                            <th scope="col">Löschen</th>
                        </thead>
                        <tbody>
                            <!-- Dynamic input -->

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>




