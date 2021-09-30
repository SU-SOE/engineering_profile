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
/******/ 	return __webpack_require__(__webpack_require__.s = "./lib/js/magazine_curtain.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./lib/js/magazine_curtain.js":
/*!************************************!*\
  !*** ./lib/js/magazine_curtain.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function ($, Drupal) {
  Drupal.behaviors.engineeringMagazineCurtain = {
    attach: function attach(context, settings) {
      var classes = ['hero-curtain', 'hero-static'];

      function setFocusOut() {
        $.each(classes, function (i, heroClass) {
          var hero = $('.' + heroClass); // If focus is moved away from the hero, scroll to the top of the normal page.

          $(hero).find('a').last().focusout(function (e) {
            if ($(this).is(':visible')) {
              var topPage = $(hero).height();
              $('body').scrollTop(topPage);
            }
          });
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
        $('.curtain-content__scroll-text').append($('<a>', {
          class: 'scroll-down',
          href: '#',
          title: Drupal.t('Scroll Down'),
          'aria-label': Drupal.t('Scroll Down'),
          html: '<div class="scroll-text">' + Drupal.t('Click to scroll') + '</div><div class="fa fa-arrow-circle-o-down"></div>'
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
        var scrollPos = 0 - curtainHeight + $(window).scrollTop(); // keeping this here for reference.  can be deleted.

        var curtainTop = $('.hero-curtain').offset().top;
        var curtainBottom = $('.hero-curtain').offset().top + $('.hero-curtain').height();
        var screenBottom = $(window).scrollTop() + $(window).innerHeight();
        var screenTop = $(window).scrollTop(); // end reference

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
          var clonedBlock = $(this).detach().prependTo('body');
          var wrapper = $('<div>', {
            class: heroClass + '-reveal'
          });
          $(clonedBlock).siblings().wrapAll(wrapper);
        });
      });
      $(window).on("load", function () {
        var classes = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : classes;

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
  };
})(jQuery, Drupal);

/***/ })

/******/ });
//# sourceMappingURL=engineering_magazine_curtain.script.js.map