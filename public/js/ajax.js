$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function (){

    // student frontend: modulnummer + matrikelnummer + pw DSGVO buch?
    // modulname in titel ändern
    // TODO: add confirmation box alert sollte zeigen welche kurs gelöscht.
    // create ModelFactories
    // create js modules with functions
    // checke bei delete ob module noch kurse oder studenten noch belegungen, sonst löschen?
    // delete Course
    $('#courseTable').delegate('.deletebtn', 'click', function(e){
        $tr = $(this).closest('tr');
        let course = $tr.children(':first').text();

        $.ajax({
            type: 'DELETE',
            url: 'courses/' + course,
            success: function(response){
                // find and remove old course <tr> in courseTable
                $('#courseTable .hidden').filter(function(){
                    return $(this).text() === course;
                }).closest('tr').remove();

                $('#ajaxAlert').show();
                $('#ajaxAlertMsg').html('Kurs gelöscht.');
            },
            error: function (response) {
                // display response page + logging?
                alert('Ein unerwarteter Fehler ist aufgetreten.');
            }
        });
    });

    // AJAX Course Pagination
    $('body').on('click', '.pagination a', function(e){
        e.preventDefault();
        search($(this).attr('href'));
    });

    //search functionality TODO maybe change to enter click
    function search(url, query = '') {
        if (query) {
            url = url + query;
        }
        $.get(url, function(data, status){
            if (status === 'success') {
                //window.history.pushState("", "", url);
                $('.pagination').empty();
                $('#courseTable').empty();
                $('#courseTable').append(data);
            } else {
                //errorPage
                //log
            }
        });
    }
    $('#searchInput').on('keyup', function(e){
        e.preventDefault();
        let query = $('#searchForm').serialize();
        let url = '/courses/search?';
        search(url, query);
    });

    // store a grading for the course and display it in the modal
    $('#addGradeForm').on('submit', function(e){
        e.preventDefault();
        let gradeCount = $('#modalGradesTable tbody tr').length;
        let course = $('#hiddenCourseId').text();
        let entry = $("<tr><td hidden></td><td></td><td></td><td></td><td><button type='submit' class='btn btn-danger btn-sm deletegradebtn'>Löschen</button></td></tr>");
        $('#gradeModalAlertMsg').empty();
        $.ajax({
            type: 'POST',
            url: 'grades/' + course,
            data: $('#addGradeForm').serialize(),
            success: function(response){
                if (response.studentGraded) {
                    $('#gradeModalAlertMsg').append("<li>" + response.id + " bereits benotet</li>");
                    $('#gradeModalAlert').show();
                } else {
                    $('#gradeModalAlert').hide();
                    $(entry.children()[0]).text(response.id);
                    $(entry.children()[1]).text(++gradeCount);
                    $(entry.children()[2]).text(response.student_id);
                    $(entry.children()[3]).text(response.grade);
                    $('#modalGradesTable').prepend(entry);
                }
            },
            error: function (response) {
                // display response page + logging?
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    for (error in errors) {
                        $('#gradeModalAlertMsg').append("<li>" + errors[error] + "</li>");
                         $('#gradeModalAlert').show();
                    }
                } else {
                    alert('Unerwarter Fehler');
                    // ajaxLogger
                    console.log(response);
                }
            }
        });
    });

    // show Grade Modal and load course data and associated grades
    $('#courseTable').delegate('.gradesbtn', 'click', function(e){
        // get current course data
        $tr = $(this).closest('tr');
        let data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
        $('#gradesModalTitle').text(data[2] + ': ' +  data[3]);
        $('#hiddenCourseId').text(data[0]);
        $('#gradesModal').modal('show');

        $.get("/courses/" + data[0], function(data, status){
            if (status === 'success') {
                $('#modalGradesTable').append(data);
            } else {
                //logging
                alert('ERRROR');
            }
        });
    });

    // remove StudentGradeInput from table
    $('#gradesModal').delegate('.deletegradebtn', 'click', function(e){
        e.preventDefault();
        let gradingId = $(this).closest('tr').children(':first').text();
        // remove from html
        let tableEntry = $(this).closest('tr');
        let gradeCount = $('#modalGradesTable tbody tr').length;


        $.ajax({
            type: 'DELETE',
            url: '/grades/' + gradingId,
            success: function(response){
                tableEntry.remove();
                $('#modalGradesTable tr td:nth-child(2)').each(function(index){
                    $(this).text(--gradeCount);
                });
                if (response.success) {
                    $('#gradeModalAlert').html(response.id + ' gelöscht.');
                    $('#gradeModalAlert').show();
                }
            },
            error: function (response) {
                // TODO
                // display response page + logging?
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    for (error in errors) {
                        $('#editModal_' + error + '-error').html(errors[error][0]);
                    }
                } else {
                    //alert('ERRORPAGE');
                }
            }
        });
    });

    // update Course
    $('#courseEditForm').on('submit', function(e){
        e.preventDefault();
        let course = $('#courseId').val();

        // updateCourse()
        $.ajax({
            type: 'PATCH',
            url: 'courses/' + course,
            data: $('#courseEditForm').serialize(),
            success: function(response){
                if (response.error) {
                    $('#courseEditFormErrors').text(response.error);
                    $('#courseEditFormErrors').show();
                } else {
                    $('#courseEditFormErrors').hide();
                    // find and remove old course <tr> in courseTable
                    $('#courseTable .hidden').filter(function(){
                        return $(this).text() === course;
                    }).closest('tr').remove();

                    $('#courseTable').prepend(response);
                    $('#courseEditModal').modal('hide');
                    $('#ajaxAlertMsg').html('Änderung gespeichert.');
                    $('#ajaxAlert').show();
                }
            },
            error: function (response) {
                // display response page + logging?
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    for (error in errors) {
                        $('#editModal_' + error + '-error').html(errors[error][0]);
                    }
                } else {
                    //alert('ERRORPAGE');
                }
            }
        });
    });

    // hide ajaxAlert with new Action
    $('#courseTable').delegate('.btn', 'click', function(){
        $('#ajaxAlert').hide();
        //$('#ajaxAlertMsg').html('');
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
        $('#editModuleName').val(data[2]);
        $('#editSemester').val(data[3]);
    });

    // add Course
    $('#courseAddForm').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/courses',
            data: $('#courseAddForm').serialize(),
            success: function (response) {
                // logging wahrsch eher im controller?
                $('#courseTable').prepend(response);
                $('#courseAddModal').modal('hide');

                $('#ajaxAlert').show();
                $('#ajaxAlertMsg').html('Veranstaltung hinzugefügt.');
            },
            error: function (response) {
                // display response page + logging?
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    for (error in errors) {
                        $('#' + error + '-error').html(errors[error][0]);
                    }
                } else {
                    //alert('ERRORPAGE');
                }
            }
        });
    });

    // reset modal content when hidden
    $('#courseAddModal').on("hidden.bs.modal", function(){
        $('#module_nr-error').html('');
        $('#module_name-error').html('');
        $('#semester-error').html('');
        $('#courseAddModal input.form-control').val('');
    });
    $('#courseEditModal').on("hidden.bs.modal", function(){
        $('#editModal_module_nr-error').html('');
        $('#editModal_module_name-error').html('');
        $('#editModal_semester-error').html('');
        $('#courseEditFormErrors').hide();
    });
    $('#gradesModal').on("hidden.bs.modal", function(){
        $('#gradeModalAlert').hide();
        let entry = $('#gradeEntryTemplate').clone();
        $('#modalGradesTable tbody').empty();
        $('#modalGradesTable tbody').append(entry);
    });

    // no used?
    function addGradeInputField(count)
    {
        let removeButton = '<button type="button" name="remove" id="'+count+'" class="btn btn-danger btn-xs remove">x</button>';
        if (count > 1) {

        }
    }
});
