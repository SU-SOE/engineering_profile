!function(n){var o={};function r(e){if(o[e])return o[e].exports;var t=o[e]={i:e,l:!1,exports:{}};return n[e].call(t.exports,t,t.exports,r),t.l=!0,t.exports}r.m=n,r.c=o,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(t,e){if(1&e&&(t=r(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)r.d(n,o,function(e){return t[e]}.bind(null,o));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=0)}([function(e,t,n){"use strict";n.r(t);n(1)},function(e,t){var r;r=jQuery,Drupal.behaviors.engineeringTheme={attach:function(e,t){var n=window.location.pathname.split("/")[1];function o(){var e=["#00ece9","#ff525c","#ffbd54"];return e[Math.random()*e.length|0]}r(".engineering-accent-color__link a").each(function(){r(this).css("text-decoration","underline"),r(this).css("text-decoration-color",o())}),r(".engineering-accent-color__image img").each(function(){r(this).css("border-color",o())}),r(".soe-spotlight--cards .su-link").each(function(){r(this).removeClass("su-card__link su-link--action"),r(this).addClass("su-link--external")}),r(".engineering-accent-color__background").each(function(){r(this).css("background-color",o())}),"spotlight"===n&&0<document.getElementsByClassName("node-spotlight-su-spotlight-degrees").length&&0<document.getElementsByClassName("node-spotlight-su-soe-department").length&&(document.getElementsByClassName("su-spotlight-degrees")[0].innerHTML+=",&nbsp;"),r("#edit-submit-spotlights").attr("hidden",!0),r("#edit-submit-spotlights").clone().appendTo("#views-exposed-form-spotlights-block-1 .form-actions").attr("hidden",!1).addClass("show-spotlight-apply__button")},detach:function(){}}}]);
//# sourceMappingURL=engineering.script.js.map