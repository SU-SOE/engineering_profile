/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./lib/js/engineering.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./lib/js/engineering.behavior.js":
/*!****************************************!*\
  !*** ./lib/js/engineering.behavior.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($, Drupal) {
  Drupal.behaviors.engineeringTheme = {
    // Attach Drupal Behavior.
    attach: function attach(context, settings) {
      // Variables
      var firstPath = window.location.pathname.split('/')[1]; // Color map for highlights
      ///  #00ece9 - teal
      ///  #ff525c - orange
      ///  #ffbd54 - yellow

      function getAccentColor() {
        var accentColors = ["#00ece9", "#ff525c", "#ffbd54"]; //return accentColors[Math.floor(Math.random() * accentColors.length)];

        return accentColors[Math.random() * accentColors.length | 0];
      }

      $(".engineering-accent-color__link a").each(function () {
        $(this).css('text-decoration', 'underline');
        $(this).css('text-decoration-color', getAccentColor());
      }); //Adds different highlight color to spotlight headshot images.

      $(".engineering-accent-color__image img").each(function () {
        $(this).css('border-color', getAccentColor());
      });
      $(".soe-spotlight--cards .su-link").each(function () {
        $(this).removeClass('su-card__link su-link--action');
        $(this).addClass('su-link--external');
      });
      $(".engineering-accent-color__background").each(function () {
        $(this).css('background-color', getAccentColor());
      }); // Adds comma and space to individual spotlight pages where both

      if (firstPath === "spotlight") {
        if (document.getElementsByClassName('node-spotlight-su-spotlight-degrees').length > 0 && document.getElementsByClassName('node-spotlight-su-soe-department').length > 0) {
          var divSpotlightDegree = document.getElementsByClassName('su-spotlight-degrees');
          divSpotlightDegree[0].innerHTML += ',&nbsp;';
        }
      } // This is a less than ideal solution for removing ajax from Spotlight filter button.
      // Thankfully a solution is in the works: https://www.drupal.org/project/drupal/issues/2904754
      // After this moves into Core, this can be removed.


      $("#edit-submit-spotlights").attr("hidden", true);
      $('#edit-submit-spotlights').clone().appendTo("#views-exposed-form-spotlights-block-1 .form-actions").attr("hidden", false).addClass('show-spotlight-apply__button');
    },
    // Detach Example.
    detach: function detach() {}
  };
})(jQuery, Drupal);

/***/ }),

/***/ "./lib/js/engineering.js":
/*!*******************************!*\
  !*** ./lib/js/engineering.js ***!
  \*******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _engineering_behavior_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./engineering.behavior.js */ "./lib/js/engineering.behavior.js");
/* harmony import */ var _engineering_behavior_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_engineering_behavior_js__WEBPACK_IMPORTED_MODULE_0__);
// Main Webpack entry file.
 // Your code goes below.

/***/ })

/******/ });
//# sourceMappingURL=engineering.script.js.map