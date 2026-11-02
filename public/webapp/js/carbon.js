$(function () {
    const $body = $('body');
    const $nav = $('ul.nav');
    const $home = $('#at_home');
    const $studio = $('#at_studio');
    const $homeAddress = $('#studentHomeAddress');

    const NAV_STORAGE_KEY = 'navDropdownToggle';

    /**
     * Sidebar Dropdown
     */
    $('.nav-dropdown-toggle').on('click', function (e) {
        e.preventDefault();

        const $parent = $(this).parent();
        const isOpen = $parent.toggleClass('open').hasClass('open');

        localStorage.setItem(
            NAV_STORAGE_KEY,
            isOpen ? $(this).attr('href') || '#' : '#'
        );
    });

    // Open submenu containing the active link
    $nav.find('a.active').parent().parent().parent().addClass('open');

    // Restore previously opened dropdown
    const savedDropdown = localStorage.getItem(NAV_STORAGE_KEY);

    if (savedDropdown && savedDropdown !== '#') {
        $nav.find('a').filter(function () {
            return $(this).attr('href') === savedDropdown;
        }).parent().addClass('open');
    }

    /**
     * Sidebar Toggles
     */
    $('.sidebar-toggle').on('click', function (e) {
        e.preventDefault();
        $body.toggleClass('sidebar-hidden');
    });

    $('.sidebar-mobile-toggle').on('click', function () {
        $body.toggleClass('sidebar-mobile-show');
    });

    /**
     * Format Phone Number
     */
    const $phone = $('#phone');

    if ($phone.length) {
        $phone.val(function (_, value) {
            return value.replace(
                /^(\d{3})(\d{3})(\d{4})$/,
                '$1-$2-$3'
            );
        });
    }

    /**
     * Student Lesson Location
     */
    function updateLessonLocation(changed) {
        const isHome = changed === 'home'
            ? $home.is(':checked')
            : !$studio.is(':checked');

        $home.prop('checked', isHome);
        $studio.prop('checked', !isHome);
        $homeAddress.toggle(isHome);
    }

    $home.on('change', function () {
        updateLessonLocation('home');
    });

    $studio.on('change', function () {
        updateLessonLocation('studio');
    });

    // Initialize based on current selection
    if ($home.length && $studio.length) {
        updateLessonLocation($home.is(':checked') ? 'home' : 'studio');
    }

    /**
     * Clickable Table Rows
     */
    $('.table-row').on('click', function (e) {
        if ($(e.target).closest('a, button, input, select, textarea').length) {
            return;
        }

        const url = $(this).data('href');

        if (url) {
            window.location.href = url;
        }
    });

    /**
     * Logout
     */
    $('#logout-link').on('click', function () {
        localStorage.removeItem(NAV_STORAGE_KEY);
    });
});
