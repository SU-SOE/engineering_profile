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
          topicsItem.addClass('active');
        } else {
          toggleButton.attr('aria-expanded', 'false');
          toggleButton.removeClass('soe-magazine__navigation-rotate-up');
          toggleButton.addClass('soe-magazine__navigation-rotate-down');
          topicsItem.removeClass('active');
        }
      });
    }
  };
})(jQuery, Drupal);
/******/ })()
;