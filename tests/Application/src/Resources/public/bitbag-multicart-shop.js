/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "../../src/Resources/assets/shop/js/handleCartWidget.js":
/*!**************************************************************!*\
  !*** ../../src/Resources/assets/shop/js/handleCartWidget.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   "handleCartWidget": () => (/* binding */ handleCartWidget)
/* harmony export */ });
class handleCartWidget {
  constructor(config) {
    const defaults = {
      update: this.updateCartWidget,
      widget: '[data-bb-mc-widget]',
      changeCartElement: '[data-bb-mc-change]',
      cartChangeUrl: 'data-url-change',
      cartUpdateUrl: 'data-url-update',
      cartWidgetButton: '[data-bb-mc-widget-button]',
      widgetCarts: '[data-bb-mc-widget-carts]',
      widgetItems: '[data-bb-mc-widget-items]'
    };
    this.config = { ...defaults,
      ...config
    };
  }

  init = () => {
    this.addSwitchEvents();
  };
  addSwitchEvents = () => {
    const widget = document.querySelector(this.config.widget);
    const carts = widget.querySelectorAll(this.config.changeCartElement);
    carts.forEach(element => {
      element.addEventListener('click', e => this.changeActiveCart(e));
    });
  };
  changeActiveCart = async e => {
    const changeActiveCartUrl = e.currentTarget.getAttribute(this.config.cartChangeUrl);

    try {
      const res = await fetch(changeActiveCartUrl, {
        method: 'POST'
      });

      if (res.ok) {
        this.config.update();
      } else {
        throw new Error('Fetch failed');
      }
    } catch (error) {
      console.error('There has been a problem with your fetch operation:', error);
    }
  };
  updateCartWidget = async () => {
    const widget = document.querySelector(this.config.widget);
    const newCartsUrl = widget.getAttribute(this.config.cartUpdateUrl);
    const button = document.querySelector(this.config.cartWidgetButton);
    const carts = document.querySelector(this.config.widgetCarts);
    const items = document.querySelector(this.config.widgetItems);

    try {
      const res = await fetch(newCartsUrl);

      if (res.ok) {
        const data = await res.json();
        button.innerHTML = data.ajaxButton;
        carts.innerHTML = data.popupCarts;
        items.innerHTML = data.popupItems;
        this.addSwitchEvents();
      } else {
        throw new Error('Fetch failed');
      }
    } catch (error) {
      console.error('There has been a problem with your fetch operation:', error);
    }
  };
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (handleCartWidget);

/***/ }),

/***/ "../../src/Resources/assets/shop/js/handleMultiCart.js":
/*!*************************************************************!*\
  !*** ../../src/Resources/assets/shop/js/handleMultiCart.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   "handleMultiCart": () => (/* binding */ handleMultiCart)
/* harmony export */ });
/* harmony import */ var _handleCartWidget__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./handleCartWidget */ "../../src/Resources/assets/shop/js/handleCartWidget.js");
/* harmony import */ var _handleSummary__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./handleSummary */ "../../src/Resources/assets/shop/js/handleSummary.js");


class handleMultiCart {
  constructor(config) {
    const defaults = {
      update: this.update,
      widgetCarts: '[data-bb-mc-widget-carts]',
      multiCart: '[data-bb-mc]'
    };
    this.config = { ...defaults,
      ...config
    };
    this.widget;
    this.summary;
  }

  init = () => {
    if (document.querySelector(this.config.widgetCarts)) {
      this.widget = new _handleCartWidget__WEBPACK_IMPORTED_MODULE_0__["default"](this.config);
      this.widget.init();
    }

    if (document.querySelector(this.config.multiCart)) {
      this.summary = new _handleSummary__WEBPACK_IMPORTED_MODULE_1__["default"](this.config);
      this.summary.init();
    }
  };
  update = () => {
    this.widget?.updateCartWidget();
    this.summary?.updateSummaryCarts();
  };
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (handleMultiCart);

/***/ }),

/***/ "../../src/Resources/assets/shop/js/handleSummary.js":
/*!***********************************************************!*\
  !*** ../../src/Resources/assets/shop/js/handleSummary.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   "handleCartWidget": () => (/* binding */ handleCartWidget)
/* harmony export */ });
class handleCartWidget {
  constructor(config) {
    const defaults = {
      update: this.updateSummaryCarts,
      multiCart: '[data-bb-mc]',
      newCartElement: '[data-bb-mc-newcart]',
      changeCartElement: '[data-bb-mc-change]',
      deleteCartElement: '[data-bb-mc-delete]',
      cartSummary: '[data-bb-mc-summary-items]',
      cartsCurrent: 'data-bb-mc-current',
      cartsMax: 'data-bb-mc-max',
      cartNewUrl: 'data-url-new',
      cartChangeUrl: 'data-url-change',
      cartDeleteUrl: 'data-url-delete',
      cartsSummaryUrl: 'data-url-carts-summary',
      cartSummaryItemUrl: 'data-url-cart-item',
      cartSummaryTotalUrl: 'data-url-cart-total',
      cartSummaryActiveUrl: 'data-url-carts-active-cart'
    };
    this.config = { ...defaults,
      ...config
    };
  }

  init = () => {
    const newCartElement = document.querySelector(this.config.newCartElement);
    newCartElement?.addEventListener('click', e => this.newCart(e));
    this.addCartEvents();
  };
  addCartEvents = () => {
    const cartsParent = document.querySelector(this.config.multiCart);
    const multicartElements = cartsParent.querySelectorAll(this.config.changeCartElement);
    const deleteCartElements = cartsParent.querySelectorAll(this.config.deleteCartElement);
    multicartElements.forEach(element => {
      element.addEventListener('click', e => this.changeActiveCart(e));
    });
    deleteCartElements.forEach(element => element.addEventListener('click', e => this.deleteCart(e)));
  };
  changeActiveCart = async e => {
    const changeActiveCartUrl = e.currentTarget.getAttribute(this.config.cartChangeUrl);

    try {
      const res = await fetch(changeActiveCartUrl, {
        method: 'POST'
      });

      if (res.ok) {
        this.config.update();
        location.reload();
      } else {
        throw new Error('Fetch failed');
      }
    } catch (error) {
      console.error('There has been a problem with your fetch operation:', error);
    }
  };
  updateSummaryCarts = async () => {
    const multiCart = document.querySelector(this.config.multiCart);
    const urlSummary = multiCart.getAttribute(this.config.cartsSummaryUrl);

    try {
      const res = await fetch(urlSummary);

      if (res.ok) {
        const data = await res.text();
        multiCart.innerHTML = data;
        this.addCartEvents();
        this.updateSummary();
      } else {
        throw new Error('Fetch failed');
      }
    } catch (error) {
      console.error('There has been a problem with your fetch operation:', error);
    }
  };
  updateSummary = async () => {
    const cartSummary = document.querySelector(this.config.cartSummary);
    const carts = document.querySelector(this.config.multiCart);
    const cartItem = carts.getAttribute(this.config.cartSummaryItemUrl);
    const cartTotal = carts.getAttribute(this.config.cartSummaryTotalUrl);
    const getActive = carts.getAttribute(this.config.cartSummaryActiveUrl);

    try {
      const res = await fetch(getActive);

      if (res.status === 204) {
        cartSummary.replaceChildren(this.showBanner());
      } else if (res.ok) {
        const [items, total] = await Promise.all([fetch(cartItem).then(items => items.text()), fetch(cartTotal).then(total => total.text())]);
        cartSummary.innerHTML = items + total;
      } else {
        throw new Error('Fetch failed');
      }
    } catch (error) {
      console.error('There has been a problem with your fetch operation:', error);
    }
  };
  showBanner = () => {
    const notification = document.createElement('div');
    notification.classList.add('multi-cart-banner-empty');
    notification.innerHTML = `<p>Your cart is empty</p>`;
    return notification;
  };
  deleteCart = async e => {
    e.stopPropagation();
    const newCartElement = document.querySelector(this.config.newCartElement);
    const currentCarts = parseInt(newCartElement.getAttribute(this.config.cartsCurrent));
    const maxCarts = parseInt(newCartElement.getAttribute(this.config.cartsMax));
    const deleteCartUrl = e.currentTarget.getAttribute(this.config.cartDeleteUrl);

    try {
      const res = await fetch(deleteCartUrl, {
        method: 'POST'
      });

      if (res.ok) {
        newCartElement.setAttribute(this.config.cartsCurrent, currentCarts - 1);

        if (currentCarts - 1 < maxCarts) {
          newCartElement.disabled = false;
        }

        this.config.update();
      } else {
        throw new Error('Fetch failed');
      }
    } catch (error) {
      console.error('There has been a problem with your fetch operation:', error);
    }
  };
  newCart = async e => {
    const newCartUrl = e.currentTarget.getAttribute(this.config.cartNewUrl);
    const current = parseInt(e.currentTarget.getAttribute(this.config.cartsCurrent));
    const max = parseInt(e.currentTarget.getAttribute(this.config.cartsMax));

    if (current < max) {
      try {
        const res = await fetch(newCartUrl, {
          method: 'POST'
        });

        if (res.ok) {
          e.target.setAttribute(this.config.cartsCurrent, current + 1);

          if (current + 1 === max) {
            e.target.disabled = true;
          }

          this.config.update();
        } else {
          throw new Error('Fetch failed');
        }
      } catch (error) {
        console.error('There has been a problem with your fetch operation:', error);
      }
    }
  };
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (handleCartWidget);

/***/ }),

/***/ "../../src/Resources/assets/shop/js/index.js":
/*!***************************************************!*\
  !*** ../../src/Resources/assets/shop/js/index.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _handleMultiCart__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./handleMultiCart */ "../../src/Resources/assets/shop/js/handleMultiCart.js");

new _handleMultiCart__WEBPACK_IMPORTED_MODULE_0__.handleMultiCart().init();

/***/ }),

/***/ "../../src/Resources/assets/shop/scss/main.scss":
/*!******************************************************!*\
  !*** ../../src/Resources/assets/shop/scss/main.scss ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!************************************************!*\
  !*** ../../src/Resources/assets/shop/entry.js ***!
  \************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./scss/main.scss */ "../../src/Resources/assets/shop/scss/main.scss");
/* harmony import */ var _js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/ */ "../../src/Resources/assets/shop/js/index.js");


})();

/******/ })()
;