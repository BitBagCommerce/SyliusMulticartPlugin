export class handleCartWidget {
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
            cartSummaryActiveUrl: 'data-url-carts-active-cart',
        };
        this.config = { ...defaults, ...config };
    }

    init = () => {
        const newCartElement = document.querySelector(
            this.config.newCartElement
        );

        newCartElement?.addEventListener('click', (e) => this.newCart(e));
        this.addCartEvents();
    };

    addCartEvents = () => {
        const cartsParent = document.querySelector(
            this.config.multiCart
        );
        const multicartElements = cartsParent.querySelectorAll(
            this.config.changeCartElement
        );
        const deleteCartElements = cartsParent.querySelectorAll(
            this.config.deleteCartElement
        );

        multicartElements.forEach((element) => {
            element.addEventListener('click', (e) => this.changeActiveCart(e));
        });

        deleteCartElements.forEach((element) =>
            element.addEventListener('click', (e) => this.deleteCart(e))
        );
    };

    changeActiveCart = async (e) => {
        const changeActiveCartUrl = e.currentTarget.getAttribute(
            this.config.cartChangeUrl
        );

        try {
            const res = await fetch(changeActiveCartUrl, { method: 'POST' });

            if (res.ok) {
                this.config.update();
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
                const [items, total] = await Promise.all([
                    fetch(cartItem).then((items) => items.text()),
                    fetch(cartTotal).then((total) => total.text()),
                ]);

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

    deleteCart = async (e) => {
        e.stopPropagation();

        const newCartElement = document.querySelector(this.config.newCartElement);
        const currentCarts = parseInt(newCartElement.getAttribute(this.config.cartsCurrent));
        const maxCarts = parseInt(newCartElement.getAttribute(this.config.cartsMax));
        const deleteCartUrl = e.currentTarget.getAttribute(
            this.config.cartDeleteUrl
        );

        try {
            const res = await fetch(deleteCartUrl, { method: 'POST' });

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

    newCart = async (e) => {
        const newCartUrl = e.currentTarget.getAttribute(this.config.cartNewUrl);
        const current = parseInt(e.currentTarget.getAttribute(this.config.cartsCurrent));
        const max = parseInt(e.currentTarget.getAttribute(this.config.cartsMax));

        if (current < max) {
            try {
                const res = await fetch(newCartUrl, { method: 'POST' });

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

export default handleCartWidget;
