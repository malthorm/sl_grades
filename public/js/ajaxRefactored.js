/*
 *
 */
function updateCourse(courseId)
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
}


function deleteCourse(courseId)
{
    $.ajax({
        type: 'DELETE',
        url: 'courses/' + courseId,
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
}

//search functionality TODO maybe change to enter click
function search(url, query = '')
{
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

/*
 * Store a grading for the current course and display it in the modal(side effect split?)
 *
 * @param int courseId
 */
function addGrade(courseId)
{
    $.ajax({
        type: 'POST',
        url: 'grades/' + courseId,
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
                // TODO
                alert('Unerwarter Fehler');
                // ajaxLogger
                console.log(response);
            }
        }
    });
}
