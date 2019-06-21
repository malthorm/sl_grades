$.ajaxSetup({
    headers: {
        // check if that works with tucal
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        // cache: false?
    }
});

$(document).ready(function (){

    // media querys
    // auth
    // css file

    //import CSV
    $('#csvForm').on('submit', function(e){
        e.preventDefault();
        let course = $('#hiddenCourseId').text();
        ajaxImportCSV(course);
    });

    // delete Course
    $('#courseTable').delegate('.deletebtn', 'click', function(e){
        $tr = $(this).closest('tr');
        let course = $tr.children(':first').text();
        let courseName = $tr.children(':nth-child(3)').text().trim();
        let semester = $tr.children(':nth-child(4)').text().trim();
        let msg = courseName + ' - ' + semester + ': wirklich löschen?';
        if (confirm(msg)) {
            ajaxDeleteCourse(course);
        }
    });

    // AJAX Course Pagination
    $('body').on('click', '.pagination a', function(e){
        e.preventDefault();
        search($(this).attr('href'));
    });

    $('#searchInput').on('keyup', function(e){
        e.preventDefault();
        let query = $('#searchForm').serialize();
        let url = '/courses/search?';
        search(url, query);
    });

    // store a grading for the course and display it in the modal
    $('#addGradeForm').on('submit', function(e){
        e.preventDefault();
        let course = $('#hiddenCourseId').text();
        ajaxStoreGrading(course);
    });

    // show Grade Modal with csv Upload Form
    $('#courseTable').delegate('.csvbtn', 'click', function(e){
        $('#addGradeForm').hide();
        $('#csvForm').show();
        // get current course data
        $tr = $(this).closest('tr');
        let data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
        $('#gradesModalTitle').text(data[2] + ': ' +  data[3]);
        $('#hiddenCourseId').text(data[0]); //courseId
        $('#gradesModal').modal('show');
        getCourseGrades(data[0]);
    });

    // show Grade Modal and load course data and associated grades
    $('#courseTable').delegate('.gradesbtn', 'click', function(e){
        //hide csvForm
        $('#csvForm').hide();
        $('#addGradeForm').show();
        // get current course data
        $tr = $(this).closest('tr');
        let data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
        $('#gradesModalTitle').text(data[2] + ': ' +  data[3]);
        $('#hiddenCourseId').text(data[0]); //courseId
        $('#gradesModal').modal('show');
        getCourseGrades(data[0]);
    });

    // remove StudentGradeInput from table
    $('#gradesModal').delegate('.deletegradebtn', 'click', function(e){
        e.preventDefault();
        let gradingId = $(this).closest('tr').children(':first').text();
        // entry to be remove without reload
        let tableEntry = $(this).closest('tr');
        $msg = 'Note wirklich löschen?';
        if (confirm($msg)) {
            ajaxDeleteGrading(gradingId, tableEntry);
        }
    });

    // update Course
    $('#courseEditForm').on('submit', function(e){
        e.preventDefault();
        let course = $('#courseId').val();
        ajaxUpdateCourse(course);

    });

    // hide ajaxAlert with new Action
    $('#courseTable').delegate('.btn', 'click', function(){
        $('#ajaxAlert').hide();
    });

    // show courseEditModal
    $('#courseTable').delegate('.editbtn', 'click', function(e){
        $('#courseEditModal').modal('show');

        // get current course data
        $tr = $(this).closest('tr');
        let data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();

        //display current course data in input boxes
        $('#courseId').val(data[0]);
        $('#editModuleNr').val(data[1]);
        $('#editModuleName').val(data[2].trim());
        $('#editSemester').val(data[3]);
    });

    // add Course
    $('#courseAddForm').on('submit', function(e){
        e.preventDefault();
        ajaxStoreCourse();
    });

    // reset modal content when hidden
    $('#courseAddModal').on("hidden.bs.modal", function(){
        $('#module_no-error').html('');
        $('#module_title-error').html('');
        $('#semester-error').html('');
        $('#courseAddModal input.form-control').val('');
    });
    $('#courseEditModal').on("hidden.bs.modal", function(){
        $('#editModal_module_no-error').html('');
        $('#editModal_module_title-error').html('');
        $('#editModal_semester-error').html('');
        $('#courseEditFormErrors').hide();
    });
    $('#gradesModal').on("hidden.bs.modal", function(){
        $('#gradeModalAlert').hide();
        let entry = $('#gradeEntryTemplate').clone();
        $('#modalGradesTable tbody').empty();
        $('#modalGradesTable tbody').append(entry);
        $('#addGradeForm').trigger('reset');
    });

    // toggle Dropdown Actions
    $('#courseTable').delegate('.dropbtn', 'click', function(){
        $(this).next('div').show();
        $(this).next('div').addClass('activeDropdown');
    });

    // toggle collapse if user clicks outside of it
    $(window).on('click', function(e){
        if (!e.target.matches('.dropbtn')) {
            $('.activeDropdown').hide();
            $('.activeDropdown').removeClass('activeDropdown');
        }
    });
});

/*
 * Imports Grades from a csv-file and persits them in storage.
 *
 * @param int courseId
 * @return void
 */
function ajaxImportCSV(courseId)
{
    let gradeCount = $('#modalGradesTable tbody tr').length;
    let entryTemplate = $("<tr><td hidden></td><td></td><td></td><td></td>" +
        "<td><button type='submit' class='btn btn-danger btn-sm" +
        " deletegradebtn'>Löschen</button></td></tr>");
    $('#gradeModalAlertMsg').empty();
    $('#gradeModalAlert').hide();

    let formData = new FormData();
    formData.append('file', $('#file')[0].files[0]);

    $.ajax({
        type: 'POST',
        url: 'grades/' + courseId + '/csv',
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        success: function(response){
            if (response.exception) {
                $('#gradeModalAlertMsg').append("<li>"+response.msg+"</li>");
                $('#gradeModalAlert').show();
            }
            if (response.errors) {
                for (error of response.errors) {
                    $('#gradeModalAlertMsg').append("<li>" + error + "</li>");
                }
                $('#gradeModalAlert').show();
            }
            if (response.gradings) {
                for (grading of response.gradings) {
                    let entry = entryTemplate.clone();
                    $(entry.children()[0]).text(grading.id);
                    $(entry.children()[1]).text(++gradeCount);
                    $(entry.children()[2]).text(grading.uni_identifier);
                    $(entry.children()[3]).text(grading.grade);
                    $('#modalGradesTable').prepend(entry);
                }
            }
        },
        error: function (response) {
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
                for (error in errors) {
                    $('#gradeModalAlert').html(errors[error][0]);
                    $('#gradeModalAlert').show();
                }
            } else {
                $('#gradeModalAlertMsg').append(
                    "<li>Ein unerwarteter Fehler ist aufgetreten.</li>"
                );
                $('#gradeModalAlert').show();
            }
        }
    });
}

/*
 * Deletes a course in storage.
 *
 * @param int courseId
 * @return void
 */
function ajaxDeleteCourse(courseId)
{
    $.ajax({
        type: 'DELETE',
        url: 'courses/' + courseId,
        success: function(response){
            if (response.exception) {
                $('#ajaxAlertMsg').html(response.msg);
                $('#ajaxAlert').show();
            } else {
                // find and remove old course <tr> in courseTable
                $('#courseTable .hidden').filter(function(){
                    return $(this).text() === course;
                }).closest('tr').remove();

                $('#ajaxAlert').show();
                $('#ajaxAlertMsg').html('Kurs gelöscht.');
            }
        },
        error: function (response) {
            $('#ajaxAlertMsg').html('Ein unerwarteter Fehler ist aufgetreten.');
            $('#ajaxAlert').show();
        }
    });
}

function ajaxUpdateCourse(courseId)
{
    $.ajax({
        type: 'PATCH',
        url: 'courses/' + courseId,
        data: $('#courseEditForm').serialize(),
        success: function(response){
            if (response.exception) {
                $('#courseEditFormErrors').text(response.msg);
                $('#courseEditFormErrors').show();
            } else if (response.error) {
                $('#courseEditFormErrors').text(response.error);
                $('#courseEditFormErrors').show();
            } else {
                $('#courseEditFormErrors').hide();
                // find and remove old course <tr> in courseTable
                $('#courseTable .hidden').filter(function(){
                    return $(this).text() === course;
                }).closest('tr').remove();
                $('#courseTable tbody').prepend(response);
                $('#courseEditModal').modal('hide');
                $('#ajaxAlertMsg').html('Änderung gespeichert.');
                $('#ajaxAlert').show();
            }
        },
        error: function (response) {
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
                for (error in errors) {
                    $('#editModal_' + error + '-error').html(errors[error][0]);
                }
            } else {
                $('#courseEditFormErrors').text(response.error);
                $('#courseEditFormErrors').show();
            }
        }
    });
}

/*
 * Finds and displays courses from storage.
 *
 * @param string url
 * @param string query
 * @return void
 */
function search(url, query = '')
{
    if (query) {
        url = url + query;
    }
    $.get(url, function(data, status){
        if (status === 'success') {
            $('.pagination').empty();
            $('#courseTable').empty();
            $('#courseTable').append(data);
        } else {
            $('#ajaxAlert').html('Unerwarteter Fehler bei der Suche.');
            $('#ajaxAlert').show();
        }
    });
}

/*
 * Deletes a grading in storage.
 *
 * @param int gradingId
 * @param JQuery-Object $rowInTable
 * @return void
 */
function ajaxDeleteGrading(gradingId, $rowInTable)
{
    let gradeCount = $('#modalGradesTable tbody tr').length;
    $.ajax({
        type: 'DELETE',
        url: 'grades/' + gradingId,
        success: function(response){
            if (response.success) {
                $rowInTable.remove();
                $('#modalGradesTable tr td:nth-child(2)').each(function(index){
                    $(this).text(--gradeCount);
                });
                $('#gradeModalAlertMsg').append(
                    "<li>" + response.id + ' gelöscht.</li>'
                );
                $('#gradeModalAlert').show();
            }
            if (response.exception) {
                $('#gradeModalAlertMsg').append(
                    "<li>" + response.msg + '</li>'
                );
                $('#gradeModalAlert').show();
            }
        },
        error: function (response) {
                $('#gradeModalAlertMsg').append(
                    '<li>Ein unerwarteter Fehler ist aufgetreten.</li>'
                );
                $('#gradeModalAlert').show();
        }
    });

}

/*
 * Deletes a course in storage.
 *
 * @param int courseId
 * @return void
 */
function ajaxDeleteCourse(courseId)
{
    $.ajax({
        type: 'DELETE',
        url: 'courses/' + courseId,
        success: function(response){
            if (response.exception) {
                $('#ajaxAlert').html(response.msg);
                $('#ajaxAlert').show();
            } else {
                // find and remove old course <tr> in courseTable
                $('#courseTable .hidden').filter(function(){
                    return $(this).text() === courseId;
                }).closest('tr').remove();

                $('#ajaxAlert').show();
                $('#ajaxAlertMsg').html('Kurs gelöscht.');
            }
        },
        error: function (response) {
                $('#ajaxAlert').html(
                    'Ein unerwarteter Fehler ist aufgetreten.'
                );
                $('#ajaxAlert').show();
        }
    });
}

/*
 * Updates a course in storage.
 *
 * @param int courseId
 * @return void
 */
function ajaxUpdateCourse(courseId)
{
    $.ajax({
        type: 'PATCH',
        url: 'courses/' + courseId,
        data: $('#courseEditForm').serialize(),
        success: function(response){
            if (response.error) {
                $('#courseEditFormErrors').text(response.error);
                $('#courseEditFormErrors').show();
            } else {
                $('#courseEditFormErrors').hide();
                // find and remove old course <tr> in courseTable
                $('#courseTable .hidden').filter(function(){
                    return $(this).text() === courseId;
                }).closest('tr').remove();
                $('#courseTable tbody').prepend(response);
                $('#courseEditModal').modal('hide');
                $('#ajaxAlertMsg').html('Änderung gespeichert.');
                $('#ajaxAlert').show();
            }
            if (response.exception) {
                $('#ajaxAlert').html(response.msg);
            }
        },
        error: function (response) {
            // Form Input Validation failed
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
                for (error in errors) {
                    $('#editModal_' + error + '-error').html(errors[error][0]);
                }
            } else {
                $('#ajaxAlert').html(
                    'Ein unerwarteter Fehler ist aufgetreten'
                );
            }
        }
    });
}

/*
 * Stores a new course in storage.
 *
 * @return void
 */
function ajaxStoreCourse()
{
    $.ajax({
        type: 'POST',
        url: 'courses',
        data: $('#courseAddForm').serialize(),
        success: function (response) {
            $('#courseTable tbody').prepend(response);
            $('#courseAddModal').modal('hide');

            $('#ajaxAlert').show();
            $('#ajaxAlertMsg').html('Veranstaltung hinzugefügt.');
            if (response.exception) {
                $('#ajaxAlert').html(response.msg);
            }
        },
        error: function (response) {
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
                for (error in errors) {
                    $('#' + error + '-error').html(errors[error][0]);
                }
            } else {
                $('#ajaxAlert').html(
                    'Ein unerwarteter Fehler ist aufgetreten.'
                );
            }
        }
    });
}

/*
 * Stores a new grading for a course in storage.
 *
 * @param int courseId
 * @return void
 */
function ajaxStoreGrading(courseId)
{
    let gradeCount = $('#modalGradesTable tbody tr').length;
    let entry = $("<tr><td hidden></td><td></td><td></td><td></td><td>" +
        "<button type='submit' class='btn btn-danger btn-sm" +
         " deletegradebtn'>Löschen</button></td></tr>");
    $('#gradeModalAlertMsg').empty();

    $.ajax({
        type: 'POST',
        url: 'grades/' + courseId,
        data: $('#addGradeForm').serialize(),
        success: function(response){
            if (response.studentGraded) {
                $('#gradeModalAlertMsg').append(
                    "<li>" + response.uni_identifier + " bereits benotet</li>"
                );
                $('#gradeModalAlert').show();
            } else {
                $('#gradeModalAlert').hide();
                $(entry.children()[0]).text(response.id);
                $(entry.children()[1]).text(++gradeCount);
                $(entry.children()[2]).text(response.uni_identifier);
                $(entry.children()[3]).text(response.grade);
                $('#modalGradesTable').prepend(entry);
            }
            if (response.exception) {
                $('#gradeModalAlertMsg').append(
                    "<li>" + response.msg + '</li>'
                );
                $('#gradeModalAlert').show();
            }
        },
        error: function (response) {
            // form input validaition  failed
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
                for (error in errors) {
                    $('#gradeModalAlertMsg').append(
                        "<li>" + errors[error] + "</li>"
                    );
                     $('#gradeModalAlert').show();
                }
            } else {
                $('#gradeModalAlertMsg').append(
                    '<li>Ein unerwarteter Fehler ist aufgetreten.</li>'
                );
                $('#gradeModalAlert').show();
            }
        }
    });
}

/*
 * Gets gradings for a course and diplay them in grades modal.
 *
 * @param int courseId
 * @return void
 */
function getCourseGrades(courseId)
{
    $.get("courses/" + courseId, function(data, status){
        if (status === 'success') {
            $('#modalGradesTable').append(data);
        } else {
            $('#gradeModalAlertMsg').append(
                '<li>Ein unerwarteter Fehler ist aufgetreten.</li>'
            );
            $('#gradeModalAlert').show();
        }
    });
}

