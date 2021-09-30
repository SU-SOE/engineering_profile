(function ($, Drupal) {

    Drupal.behaviors.engineeringMagazine = {
        attach: function (context, settings) {

            const toggleButton = $('#magazine-landing-nav__topics-toggle');

            toggleButton.click(function () {

                $('.magazine-landing-nav__topics-panel').slideToggle();
                console.log('preparing to check');
                console.log('prop = ' + toggleButton.attr("aria-expanded"));
                if (toggleButton.attr('aria-expanded') == 'false') {
                    console.log('got in');
                    toggleButton.attr('aria-expanded', 'true');
                    console.log("after change: " + toggleButton.attr('aria-expanded'));
                    toggleButton.removeClass('soe-magazine__navigation-rotate-down');
                    toggleButton.addClass('soe-magazine__navigation-rotate-up');
                } else {
                    toggleButton.attr('aria-expanded', 'false');
                    toggleButton.removeClass('soe-magazine__navigation-rotate-up');
                    toggleButton.addClass('soe-magazine__navigation-rotate-down');
                }
            });
        }
    };
})(jQuery, Drupal);
