/**
 *
 */
$(function () {
    $("#blogRelease").datepicker({
        dateFormat: "yy-mm-dd",
        timeFormat: "00:00:00",
        todayHighlight: true,
    });

    $("#dateofbirth").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        timeFormat: "00:00:00",
        yearRange: "-70:+00",
        todayHighlight: true,
    });

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
    });

    $("#datepicker2").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        timeFormat: "00:00:00",
        yearRange: "-90:+00",
        todayHighlight: true,
    });
});
