/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 3:
/***/ (function() {

(function ($, Drupal) {
  Drupal.behaviors.engineeringNewsSocialMedia = {
    attach: function attach(context, settings) {
      $('.news-social-media', context).empty();
      $('.news-social-media', context).prepend('<div class="widget-wrapper-print"><a href="/' + settings.path.currentPath + '/printable/print" class="share-print su-news-header__social-print"><img src="dist/assets/print-icon.png" alt="Print Article"/><span>' + Drupal.t('Print Article') + '</span></a></div>');
      $('.news-social-media', context).prepend('<div class="widget-wrapper-copylink"><a href="" class="su-news-header__copylink share-copylink"><img src="dist/assets/copy-link-icon.png" alt="Copy Link"/></a></div>');

      // Get the current URL.
      var pathname = window.location;

      // Going native rather than using forward module.
      var forurl = "mailto:?subject=" + document.title + "&body=" + encodeURI(document.location);

      // Going native rather than using print_pdf module.
      var prurl = 'window.print();return false;';

      // Copy url to clipboard.
      $('.share-copylink', context).click(function (e) {
        e.preventDefault();
        navigator.clipboard.writeText(pathname).then(function () {
          alert(Drupal.t('Link copied to clipboard!'));
        }, function (err) {
          alert(Drupal.t('Failed to copy link: ', err));
        });
      });
    }
  };
})(jQuery, Drupal);

/***/ }),

/***/ 402:
/***/ (function() {

(function ($, Drupal) {
  Drupal.behaviors.engineeringTheme = {
    // Attach Drupal Behavior.
    attach: function attach(context, settings) {
      // Variables
      var firstPath = window.location.pathname.split("/")[1];

      // Color map for highlights
      ///  #00ece9 - teal
      ///  #ff525c - orange
      ///  #ffbd54 - yellow

      function getAccentColor() {
        var accentColors = ["teal", "orange", "yellow"];
        return accentColors[Math.random() * accentColors.length | 0];
      }
      $(".engineering-accent-color__link a").each(function () {
        var variantColor = "engineering-accent-color__link-accent__" + getAccentColor();
        $(this).addClass(variantColor);
      });

      //Adds different highlight color to spotlight headshot images.
      $(".engineering-accent-color__image img").each(function () {
        var variantColor = "engineering-accent-color__border-accent__" + getAccentColor();
        $(this).addClass(variantColor);
      });
      $(".soe-spotlight--cards .su-link").each(function () {
        $(this).removeClass("su-card__link");
        $(this).addClass("su-link--action");
        $(this).hover(function () {
          var element = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : this;
          $(element).css("text-decoration-color", "black");
        });
      });
      $(".engineering-accent-color__background").each(function () {
        var variantColor = "engineering-accent-color__background-accent__" + getAccentColor();
        $(this).addClass(variantColor);
      });

      // Adds comma and space to individual spotlight pages where both
      if (firstPath === "spotlight") {
        if (document.getElementsByClassName("node-spotlight-su-spotlight-degrees").length > 0 && document.getElementsByClassName("node-spotlight-su-soe-department").length > 0) {
          var divSpotlightDegree = document.getElementsByClassName("su-spotlight-degrees");
          divSpotlightDegree[0].innerHTML += ",&nbsp;";
        }
      }

      // This is a less than ideal solution for removing ajax call from firing from clicking Spotlight filter button.
      // Thankfully a solution is in the works: https://www.drupal.org/project/drupal/issues/2904754
      // After this moves into Core, this can be removed.
      if (!document.getElementById("edit-submit-spotlights-hidden")) {
        var oldApplyButton = $("#edit-submit-spotlights").attr("hidden", true);
        var newApplyButton = $(oldApplyButton).clone().attr("hidden", false);
        $(oldApplyButton).prop("id", "edit-submit-spotlights-hidden");
        $(newApplyButton).appendTo("#views-exposed-form-spotlights-block-1 .form-actions").attr("hidden", false).addClass("show-spotlight-apply__button");
      }
    },
    // Detach Example.
    detach: function detach() {}
  };
})(jQuery, Drupal);

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/* harmony import */ var _engineering_behavior_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(402);
/* harmony import */ var _engineering_behavior_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_engineering_behavior_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _engineering_stanford_news_override_behavior_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(3);
/* harmony import */ var _engineering_stanford_news_override_behavior_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_engineering_stanford_news_override_behavior_js__WEBPACK_IMPORTED_MODULE_1__);
// Main Webpack entry file.



// Your code goes below.
}();
/******/ })()
;