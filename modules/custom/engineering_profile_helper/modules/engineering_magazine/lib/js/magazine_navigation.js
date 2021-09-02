(function ($, Drupal) {

    Drupal.behaviors.engineeringMagazine = {
        attach: function (context, settings) {
            console.log('I got into the JS successfully. yeet!');

            const toggleButton = $('#magazine-landing-nav__topics-toggle');

            toggleButton.click(function () {
                $('.magazine-landing-nav__topics_panel').slideToggle();
                if (toggleButton.getAttribute('aria-expanded') == 'false') {
                    toggleButton.attr('aria-expanded', 'true');
                } else {
                    toggleButton.attr('aria-expanded', 'false');
                }
            });
        }
    };
})(jQuery, Drupal);