export class handleCartWidget {
    constructor(config) {
        const defaults = {
            update: this.updateCartWidget,
            widget: '[data-bb-mc-widget]',
            changeCartElement: '[data-bb-mc-change]',
            cartChangeUrl: 'data-url-change',
            cartUpdateUrl: 'data-url-update',
            cartWidgetButton: '[data-bb-mc-widget-button]',
            widgetCarts: '[data-bb-mc-widget-carts]',
            widgetItems: '[data-bb-mc-widget-items]',
        };
        this.config = { ...defaults, ...config };
    }

    init = () => {
        this.addSwitchEvents();
    };

    addSwitchEvents = () => {
        const widget = document.querySelector(this.config.widget);
        const carts = widget.querySelectorAll(this.config.changeCartElement);

        carts.forEach((element) => {
            element.addEventListener('click', (e) => this.changeActiveCart(e));
        });
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
    }
}

export default handleCartWidget;
