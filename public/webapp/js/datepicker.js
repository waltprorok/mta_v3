$(document).ready(function () {
    /**
     *
     */
    $(function () {
        $("#blogRelease").datepicker({
            dateFormat: "yy-mm-dd",
            timeFormat: "00:00:00",
            todayHighlight: true,
        });

        if ($('#dateOfBirth').val() === '') {
            $("#dateOfBirth").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                timeFormat: "00:00:00",
                yearRange: "-70:+00",
                todayHighlight: true,
            });
        }

        $("#lessonDate").datepicker({
            changeMonth: false,
            changeYear: false,
            hideIfNoPrevNext: true,
            showOtherMonths: false,
            showStatus: true,
            dateFormat: "yy-mm-dd",
            timeFormat: "00:00:00",
            yearRange: "-05:+05",
            todayHighlight: true,
            locale: 'en',
        }).change('changeDate', function () {
            let url = window.location.pathname.split('/');
            let studentId = url[2]
            window.location.href = '/students/' + studentId + '/schedule/' + $('#lessonDate').val();
        })

        $("#editLessonDate").datepicker({
            changeMonth: false,
            changeYear: false,
            hideIfNoPrevNext: true,
            showOtherMonths: false,
            showStatus: true,
            dateFormat: "yy-mm-dd",
            timeFormat: "00:00:00",
            yearRange: "-05:+05",
            todayHighlight: true,
            locale: 'en',
        }).change('changeDate', function () {
            let url = window.location.pathname.split('/');
            let studentId = url[3];
            let lessonId = url[5];
            window.location.href = '/students/schedule/' + studentId + '/edit/' + lessonId + '/' + $('#editLessonDate').val();
        })

        $("#datepicker2").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            timeFormat: "00:00:00",
            yearRange: "-90:+00",
            todayHighlight: true,
        });
    });

});
