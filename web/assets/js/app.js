(function ($) {
    $(function () {

        $('.list-extends-classes .toggle-visible').on('click', function(e) {
            e.preventDefault();
            var closest = $(this).closest('.list-extends-classes');

            if(closest.hasClass("show")) {
                closest.removeClass('show');
                closest.find('.limit').addClass('hide');
                $(this).text($(this).data('show-msg'))
            } else {
                closest.addClass('show');
                closest.find('.limit').removeClass('hide');
                $(this).text($(this).data('hide-msg'))
            }

        })

    });
})(jQuery);
