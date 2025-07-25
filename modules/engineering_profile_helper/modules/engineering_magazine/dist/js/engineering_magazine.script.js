/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
(function ($, Drupal) {
  Drupal.behaviors.engineeringMagazine = {
    attach: function attach(context, settings) {
      var toggleButton = $('#magazine-landing-nav__topics-toggle');
      var topicsPanel = $('.magazine-landing-nav__topics-panel');
      var isDropdownOpen = false;

      // Enhanced toggle functionality with accessibility improvements
      toggleButton.click(function (e) {
        e.preventDefault();
        topicsPanel.slideToggle(300, function () {
          // Update dropdown state after animation completes
          isDropdownOpen = topicsPanel.is(':visible');
          if (isDropdownOpen) {
            // Focus first link when dropdown opens for immediate keyboard access
            setTimeout(function () {
              var firstLink = topicsPanel.find('a').first();
              if (firstLink.length) {
                firstLink.focus();
              }
            }, 50);
            topicsPanel.attr('aria-hidden', 'false');
          } else {
            topicsPanel.attr('aria-hidden', 'true');
          }
        });
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

      // Close dropdown when clicking outside
      $(document).on('click', function (e) {
        if (!$(e.target).closest('.topics_item').length && !$(e.target).closest('.magazine-landing-nav__topics-panel').length && isDropdownOpen) {
          closeTopicsDropdown();
        }
      });

      // Handle escape key to close dropdown
      $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && isDropdownOpen) {
          closeTopicsDropdown();
          toggleButton.focus();
        }
      });

      // Function to close topics dropdown
      function closeTopicsDropdown() {
        topicsPanel.slideUp(300, function () {
          isDropdownOpen = false;
          topicsPanel.attr('aria-hidden', 'true');
        });
        toggleButton.attr('aria-expanded', 'false');
        toggleButton.removeClass('soe-magazine__navigation-rotate-up');
        toggleButton.addClass('soe-magazine__navigation-rotate-down');
      }

      // Enhanced keyboard navigation for topics dropdown
      function setupTopicsKeyboardNavigation() {
        var topicLinks = topicsPanel.find('a');
        topicLinks.each(function (index) {
          $(this).on('keydown', function (e) {
            switch (e.key) {
              case 'ArrowDown':
                e.preventDefault();
                var nextIndex = (index + 1) % topicLinks.length;
                topicLinks.eq(nextIndex).focus();
                break;
              case 'ArrowUp':
                e.preventDefault();
                var prevIndex = index === 0 ? topicLinks.length - 1 : index - 1;
                topicLinks.eq(prevIndex).focus();
                break;
              case 'Tab':
                // Handle tabbing out of dropdown
                if (e.shiftKey && index === 0) {
                  setTimeout(function () {
                    closeTopicsDropdown();
                    toggleButton.focus();
                  }, 0);
                } else if (!e.shiftKey && index === topicLinks.length - 1) {
                  setTimeout(function () {
                    closeTopicsDropdown();
                  }, 0);
                }
                break;
            }
          });
        });

        // Handle arrow down on toggle button when dropdown is open
        toggleButton.on('keydown', function (e) {
          if (e.key === 'ArrowDown' && isDropdownOpen) {
            e.preventDefault();
            var firstLink = topicsPanel.find('a').first();
            if (firstLink.length) {
              firstLink.focus();
            }
          }
        });
      }

      // Initialize keyboard navigation
      setupTopicsKeyboardNavigation();
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
        if (windowWidth < 992) {
          // Reorder for mobile view (less than 992px)
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

      // Enhanced function with additional active state logic
      function fixNewsSubnavActivePath() {
        var path = window.location.pathname;
        var windowWidth = $(window).width();
        if (windowWidth >= 992) {
          // Future of Everything podcast
          if (path.indexOf('/future-everything-podcast') !== -1 || path.indexOf('/news/collection/future-everything-podcast') !== -1) {
            $('.foe_item').addClass('active');
          } else {
            $('.foe_item').removeClass('active');
          }

          // Spotlights
          if (path.indexOf('/spotlight') !== -1) {
            $('.spotlight_item').addClass('active');
          } else {
            $('.spotlight_item').removeClass('active');
          }

          // Media Coverage
          if (path.indexOf('/news/media-coverage') !== -1) {
            $('.mediacoverage_item').addClass('active');
          } else {
            $('.mediacoverage_item').removeClass('active');
          }

          // Topics - general topic section
          if (path.indexOf('/news/topic/') !== -1) {
            $('.topics_item').addClass('active');
          } else {
            $('.topics_item').removeClass('active');
          }

          // Individual topic links - enhanced logic
          setTopicLinkActiveStates(path);
        }
      }

      // New function to set active states for individual topic links
      function setTopicLinkActiveStates(currentPath) {
        var topicLinks = topicsPanel.find('.magazine-term a');
        var hasActiveTopicLink = false;
        topicLinks.each(function () {
          var $link = $(this);
          var linkPath = $link.attr('href');

          // Remove existing active class
          $link.removeClass('active');

          // Check if current path matches or starts with the topic path
          if (linkPath && (currentPath === linkPath || currentPath.startsWith(linkPath + '/'))) {
            $link.addClass('active');
            hasActiveTopicLink = true;

            // Also add active class to the Topics dropdown button
            $('.topics_item').addClass('active');
          }
        });
      }

      // Initialize ARIA attributes
      function initializeAccessibility() {
        // Set initial ARIA states
        topicsPanel.attr('aria-hidden', 'true');

        // Hide topics panel initially if not already hidden
        if (topicsPanel.is(':visible')) {
          topicsPanel.hide();
        }
      }

      // Run on document ready
      initializeAccessibility();
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