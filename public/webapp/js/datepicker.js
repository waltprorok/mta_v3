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
        }).change('changeDate',function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '/students/process_date',
                data: {
                    date: e.timeStamp.toString(), // e.date.toString() NOT WORKING
                },
                success: function (result) {
                    console.log(result);
                },
                error: function (error) {
                    console.log(error);
                }
            });
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
