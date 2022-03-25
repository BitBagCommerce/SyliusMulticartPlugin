import triggerCustomEvent from "../../common/triggerCustomEvent";

export class handleCartWidget {
    constructor(update) {
        this.update = update;
    }

    init = () => {
        this.addSwitchEvents();
    }

    addSwitchEvents = () => {
        const carts = document.querySelectorAll('.multi-cart-widget .change-cart');
        carts.forEach(element => {
            element.addEventListener('click', (e) => this.changeActiveCart(e));
        });
    }

    changeActiveCart = (e) => {
        const changeActiveCartUrl = e.currentTarget.getAttribute('data-url-change');

        fetch(changeActiveCartUrl, { method: 'POST' })
            .then(response => {
                if (response.ok) {
                    this.update()
                } else {
                    throw new Error('Something went wrong');
                }
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
            });
    }

    updateCartWidget() {

        const buttonCartWidget = document.getElementById('ajax-cart-button');
        const popupCartsWidget = document.getElementById('popup-carts');
        const popupCartItemsWidget = document.getElementById('popup-items');

        fetch("/en_US/ajax/cart-contents")
            .then(response => {
                if (response.ok) {
                    return response.json()
                }
                else {
                    throw new Error('Something went wrong');
                }
            })
            .then(jsonData => {
                buttonCartWidget.innerHTML = jsonData.ajaxButton;
                popupCartsWidget.innerHTML = jsonData.popupCarts;
                popupCartItemsWidget.innerHTML = jsonData.popupItems;
                this.addSwitchEvents()
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
            });
    }
}

export default handleCartWidget;