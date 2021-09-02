(function ($, Drupal) {

    Drupal.behaviors.engineeringMagazineCurtain = {
        attach: function (context, settings) {

            const classes = ['hero-curtain', 'hero-static'];

            function setFocusOut() {
                $.each(classes, function (i, heroClass) {
                    var hero = $('.' + heroClass);
                    // If focus is moved away from the hero, scroll to the top of the normal page.
                    $(hero).find('a').last().focusout(function (e) {
                        if ($(this).is(':visible')) {
                            var topPage = $(hero).height();
                            $('body').scrollTop(topPage);
                        }
                    })
                });
            }

            function heroSetSize() {
                var winHeight = $(window).height();
                $.each(classes, function (i, heroClass) {
                    $('.hero-curtain').height(winHeight).css('overflow', 'hidden');
                });
                $('.hero-curtain').css('margin-bottom', winHeight);
            }

            function heroScroller(classes) {
                $('.curtain-content').append($('<a>', {
                    class: 'scroll-down',
                    href: '#',
                    title: Drupal.t('Scroll Down'),
                    'aria-label': Drupal.t('Scroll Down'),
                    html: '<div class="scroll-text">' + Drupal.t('Scroll') + '</div><div class="fa fa-arrow-circle-o-down"></div>'
                }).click(function (e) {
                    e.preventDefault();
                    $("html, body").animate({
                        scrollTop: $('.hero-scroll').height()
                    }, 800);
                }));
            }

            $(window).scroll(function () {

                var curtain = $('.hero-curtain');

                var curtainPadding = $(curtain).css('padding-bottom');
                curtainPadding = curtainPadding.substring(0, curtainPadding.length - 2);
                var curtainHeight = $(curtain).height();
                var scrollPos = 0 - curtainHeight + $(window).scrollTop();

                // keeping this here for reference.  can be deleted.
                var curtainTop = $('.hero-curtain').offset().top;
                var curtainBottom = $('.hero-curtain').offset().top + $('.hero-curtain').height();
                var screenBottom = $(window).scrollTop() + $(window).innerHeight();
                var screenTop = $(window).scrollTop();
                // end reference

                if (scrollPos < 0) {
                    $('.hero-curtain-reveal').removeClass('below-hero');
                    $(curtain).removeClass('below-hero');
                } else {
                    $('.hero-curtain-reveal').addClass('below-hero');
                    $(curtain).addClass('below-hero');
                    $(curtain).css('padding-bottom', 0);
                }

            });

            $.each(classes, function (i, heroClass) {
                // Move the hero to the top of the body tag.
                $('.' + heroClass, context).each(function (i, a, b) {
                    var clonedBlock = $(this).detach().prependTo('body')
                    var wrapper = $('<div>', {
                        class: heroClass + '-reveal'
                    });
                    $(clonedBlock).siblings().wrapAll(wrapper);
                });
            });

            $(window).on("load", function (classes = classes) {
                if (typeof $.imagesLoaded !== 'undefined') {
                    $('.hero-curtain').imagesLoaded(heroSetSize);
                } else {
                    heroSetSize(classes);
                }
                setFocusOut(classes);
                $(window).resize(heroSetSize);
                heroSetSize();
                heroScroller();
            });

        }
    }
})(jQuery, Drupal);