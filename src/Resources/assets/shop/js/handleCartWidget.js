import triggerCustomEvent from '../../common/triggerCustomEvent';

export class handleCartWidget {
    constructor(config) {
        const defaults = {
            update: this.updateCartWidget,
            widget: '[data-bb-mc-widget]',
            changeCartElement: '[data-bb-mc-change]',
            cartChangeUrl: 'data-url-change',
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

    updateCartWidget() {
        const button = document.querySelector(this.config.cartWidgetButton);
        const carts = document.querySelector(this.config.widgetCarts);
        const items = document.querySelector(this.config.widgetItems);

        fetch('/en_US/ajax/cart-contents')
            .then((response) => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Something went wrong');
                }
            })
            .then((data) => {
                button.innerHTML = data.ajaxButton;
                carts.innerHTML = data.popupCarts;
                items.innerHTML = data.popupItems;
                this.addSwitchEvents();
            })
            .catch((error) => {
                console.error(
                    'There has been a problem with your fetch operation:',
                    error
                );
            });
    }
}

export default handleCartWidget;
