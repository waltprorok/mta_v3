$(document).ready(function () {
    /**
     * Sidebar Dropdown
     */
    $('.nav-dropdown-toggle').on('click', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('open');
    });

    // open sub-menu when an item is active.
    $('ul.nav').find('a.active').parent().parent().parent().addClass('open');

    /**
     * Sidebar Toggle
     */
    $('.sidebar-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-hidden');
    });

    /**
     * Mobile Sidebar Toggle
     */
    $('.sidebar-mobile-toggle').on('click', function () {
        $('body').toggleClass('sidebar-mobile-show');
    });

    /**
     * Format Phone Number
     */
    $('#phone').val(function (i, text) {
        text = text.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
        return text;
    });

    /**
     * Create Slug Tag
     */
    $('[name="title"]').keyup(function () {
        var title = $(this).val();
        title = title.toLowerCase();
        title = title.replace(/[^a-zA-Z0-9]+/g, '-');
        $('[name="slug"]').val(title);
    });

    $('#btnEdit').click(function () {
        /*Clear textarea using id */
        $('#dateOfBirth').val("");
        $('#dateOfBirth').attr('value', '');
        $("#dateOfBirth").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            timeFormat: "00:00:00",
            yearRange: "-70:+00",
            todayHighlight: true,
        });
    });

});
