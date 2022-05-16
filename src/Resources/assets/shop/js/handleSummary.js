import triggerCustomEvent from '../../common/triggerCustomEvent';

export class handleCartWidget {
    constructor(config) {
        const defaults = {
            update: this.updateSummaryCarts,
            multiCart: '[data-bb-mc]',
            newCartElement: '[data-bb-mc-newcart]',
            changeCartElement: '[data-bb-mc-change]',
            deleteCartElement: '[data-bb-mc-delete]',
            cartSummary: '[data-bb-mc-summary-items]',
            cartNewUrl: 'data-url-new',
            cartChangeUrl: 'data-url-change',
            cartDeleteUrl: 'data-url-delete',
            cartsSummaryUrl: 'data-url-carts-summary',
            cartSummaryItemUrl: 'data-url-cart-item',
            cartSummaryTotalUrl: 'data-url-cart-total',
            cartSummaryActiveUrl: 'data-url-carts-active-cart',
        };
        this.config = { ...defaults, ...config };
    }

    init = () => {
        this.addEvents();
    };

    addEvents = () => {
        const cartsParent = document.querySelector(
            this.config.multiCart
        );
        const multicartElements = cartsParent.querySelectorAll(
            this.config.changeCartElement
        );
        const deleteCartButtons = cartsParent.querySelectorAll(
            this.config.deleteCartElement
        );
        const newCartButton = document.querySelector(
            this.config.newCartElement
        );

        multicartElements.forEach((element) => {
            element.addEventListener('click', (e) => this.changeActiveCart(e));
        });

        deleteCartButtons.forEach((element) =>
            element.addEventListener('click', (e) => this.deleteCart(e))
        );

        newCartButton?.addEventListener('click', (e) => this.newCart(e));
    };

    changeActiveCart = (e) => {
        const changeActiveCartUrl = e.currentTarget.getAttribute(
            this.config.cartChangeUrl
        );

        fetch(changeActiveCartUrl, { method: 'POST' })
            .then((response) => {
                if (response.ok) {
                    this.config.update();
                } else {
                    throw new Error('Something went wrong');
                }
            })
            .catch((error) => {
                console.error(
                    'There has been a problem with your fetch operation:',
                    error
                );
            });
    };

    updateSummaryCarts = () => {
        const multicart = document.querySelector(this.config.multiCart);
        const urlSummary = multicart.getAttribute(this.config.cartsSummaryUrl);
        fetch(urlSummary)
            .then((response) => {
                if (response.ok) {
                    return response.text();
                } else {
                    throw new Error('Something went wrong');
                }
            })
            .then((response) => {
                multicart.innerHTML = response;
                this.addEvents();
                this.updateSummary();
            })
            .catch((error) => {
                console.error(
                    'There has been a problem with your fetch operation:',
                    error
                );
            });
    };

    updateSummary = () => {
        const cartSummary = document.querySelector(this.config.cartSummary);
        const carts = document.querySelector(this.config.multiCart);
        const cartItem = carts.getAttribute(this.config.cartSummaryItemUrl);
        const cartTotal = carts.getAttribute(this.config.cartSummaryTotalUrl);
        const getActive = carts.getAttribute(this.config.cartSummaryActiveUrl);

        fetch(getActive).then((response) => {
            if (response.status == 204) {
                cartSummary.innerHTML = '';
                cartSummary.appendChild(this.showNotif());
            } else {
                Promise.all([
                    fetch(cartItem).then((items) => items.text()),
                    fetch(cartTotal).then((total) => total.text()),
                ]).then(([items, total]) => {
                    cartSummary.innerHTML = items + total;
                });
            }
        });
    };

    showNotif = () => {
        const notification = document.createElement('div');

        notification.classList.add('multi-cart-notif');
        notification.innerHTML = `<p>Your cart is empty</p>`;

        return notification;
    };

    deleteCart = (e) => {
        e.stopPropagation();

        const deleteCartUrl = e.currentTarget.getAttribute(
            this.config.cartDeleteUrl
        );

        fetch(deleteCartUrl, { method: 'POST' })
            .then((response) => {
                if (response.ok) {
                    this.config.update();
                } else {
                    throw new Error('Something went wrong');
                }
            })
            .catch((error) => {
                console.error(
                    'There has been a problem with your fetch operation:',
                    error
                );
            });
    };

    newCart = (e) => {
        const newCartUrl = e.currentTarget.getAttribute(this.config.cartNewUrl);

        fetch(newCartUrl, { method: 'POST' })
            .then((response) => {
                if (response.ok) {
                    this.config.update();
                } else {
                    throw new Error('Something went wrong');
                }
            })
            .catch((error) => {
                console.error(
                    'There has been a problem with your fetch operation:',
                    error
                );
            });
    };
}

export default handleCartWidget;
