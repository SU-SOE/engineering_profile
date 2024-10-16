/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
(function ($, Drupal) {
  Drupal.behaviors.engineeringMagazine = {
    attach: function attach(context, settings) {
      var toggleButton = $('#magazine-landing-nav__topics-toggle');
      var topicsItem = $('.news-navigation-nav__item.topics_item');
      toggleButton.click(function () {
        $('.magazine-landing-nav__topics-panel').slideToggle();
        if (toggleButton.attr('aria-expanded') == 'false') {
          toggleButton.attr('aria-expanded', 'true');
          toggleButton.removeClass('soe-magazine__navigation-rotate-down');
          toggleButton.addClass('soe-magazine__navigation-rotate-up');
        } else {
          toggleButton.attr('aria-expanded', 'false');
          toggleButton.removeClass('soe-magazine__navigation-rotate-up');
          toggleButton.addClass('soe-magazine__navigation-rotate-down');
        }
      });
      var mobileToggle = $('#mobile-magazine-landing-nav__toggle');
      mobileToggle.click(function () {
        $('.news-navigation-bar__container').slideToggle(100, function () {
          if ($(this).is(':visible')) {
            $(this).css('display', 'flex');
          }
        });
        if (mobileToggle.attr('aria-expanded') == 'false') {
          mobileToggle.attr('aria-expanded', 'true');
          mobileToggle.removeClass('soe-magazine__navigation-rotate-down');
          mobileToggle.addClass('soe-magazine__navigation-rotate-up');
        } else {
          mobileToggle.attr('aria-expanded', 'false');
          mobileToggle.removeClass('soe-magazine__navigation-rotate-up');
          mobileToggle.addClass('soe-magazine__navigation-rotate-down');
        }
      });
      function reorderElements() {
        var windowWidth = $(window).width();
        if (windowWidth < 768) {
          // Reorder for mobile view (less than 768px)
          $(".magazine-landing-nav__topics-panel").insertAfter(".topics_item");
        } else {
          // Reorder back for larger viewports
          $(".magazine-landing-nav__topics-panel").insertAfter(".news-navigation-bar");
          // Make sure we show the navigation bar if it was hidden.
          $(".news-navigation-bar__container").show();
        }
      }
      function fixNewsActivePath() {
        var path = window.location.pathname;
        // Check if the URL contains the substring '/news/'
        if (path.indexOf('/news/') !== -1) {
          $('a.su-multi-menu__link[href="/news"]').closest('li').addClass('su-multi-menu__item--active-trail');
        }
      }
      function fixNewsSubnavActivePath() {
        var path = window.location.pathname;
        var windowWidth = $(window).width();
        if (windowWidth >= 768) {
          if (path.indexOf('/future-everything-podcast') !== -1) {
            $('.foe_item').addClass('active');
          } else {
            $('.foe_item').removeClass('active');
          }
          if (path.indexOf('/news/topic/') !== -1) {
            $('.topics_item').addClass('active');
          } else {
            $('.topics_item').removeClass('active');
          }
        }
      }

      // Run on document ready
      reorderElements();
      fixNewsActivePath();
      fixNewsSubnavActivePath();

      // Run on window resize
      $(window).resize(function () {
        reorderElements();
        fixNewsSubnavActivePath();
      });
    }
  };
})(jQuery, Drupal);
/******/ })()
;