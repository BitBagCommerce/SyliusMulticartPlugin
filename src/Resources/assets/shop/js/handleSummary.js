import triggerCustomEvent from "../../common/triggerCustomEvent"

export class handleCartWidget {
    constructor(update) {
        this.update = update;
    }

    init = () => {
        const newCartButton = document.getElementById('new-cart-button');
        newCartButton?.addEventListener('click', (e) => this.newCart(e))
        this.addEvents();
    }

    addEvents = () => {
        const carts = document.querySelectorAll('.multi-cart .change-cart');
        const deleteCartButtons = document.querySelectorAll('.multi-cart .change-cart .delete-cart');

        carts.forEach(element => {
            element.addEventListener('click', (e) => this.changeActiveCart(e));
        });

        deleteCartButtons.forEach(element => element.addEventListener('click', (e) => this.deleteCart(e)))
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

    updateSummaryCarts = () => {
        const multicart = document.querySelector('.multi-cart');

        fetch("/en_US/carts/summary")
            .then(response => {
                if (response.ok) {
                    return response.text()
                } else {
                    throw new Error('Something went wrong');
                }
            }).then(response => {
                multicart.innerHTML = response;
                this.addEvents()
                this.updateSummary()
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
            });
    }


    updateSummary = () => {
        const cartSummary = document.getElementById("summary-items")

        Promise.all([
            fetch('/en_US/cart-item').then(items => items.text()),
            fetch('/en_US/cart-total').then(total => total.text()),
        ]).then(([items, total]) => {
            fetch('/en_US/ajax/cart/get-active').then(response => {
                if (response.status == 204) {
                    cartSummary.innerHTML = ""
                    cartSummary.appendChild(this.showNotif())
                } else {
                    cartSummary.innerHTML = items + total
                }
            })
        })
    }

    showNotif = () => {
        const notification = document.createElement('div');
        notification.classList.add('multi-cart-notif');
        notification.innerHTML = `<p>Your cart is empty</p>`;

        return notification
    }

    deleteCart = (e) => {
        e.stopPropagation();
        const deleteCartUrl = e.currentTarget.getAttribute('data-url-delete');

        fetch(deleteCartUrl, { method: 'POST' })
            .then(response => {
                if (response.ok) {
                    this.update();
                } else {
                    throw new Error('Something went wrong');
                }
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
            });
    }

    newCart = (e) => {
        const newCartUrl = e.currentTarget.getAttribute('data-url-new');
        const ajaxCartUrl = e.currentTarget.getAttribute('data-url-update');

        fetch(newCartUrl, { method: "POST" })
            .then(response => {
                if (response.ok) {
                    this.update();
                } else {
                    throw new Error('Something went wrong');
                }
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
            });
    }


}

export default handleCartWidget;