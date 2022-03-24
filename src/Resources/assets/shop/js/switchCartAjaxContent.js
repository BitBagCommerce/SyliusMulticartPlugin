const multicart = document.getElementById('multi-cart');

const changeCartButtons = document.querySelectorAll('.change-cart');
const deleteCartButtons = document.querySelectorAll('.delete-cart');
const newCartButton = document.getElementById('new-cart-button');

const buttonCartWidget = document.getElementById('ajax-cart-button');
const popupCartsWidget = document.getElementById('popup-carts');
const popupCartItemsWidget = document.getElementById('popup-items');

const addEvents = () => {
    const changeCartButtons = document.querySelectorAll('.change-cart');
    const deleteCartButtons = document.querySelectorAll('.delete-cart');
    const newCartButton = document.getElementById('new-cart-button');

    changeCartButtons.forEach(item => {
        item.addEventListener('click', changeActiveCart)
    })
    deleteCartButtons.forEach(item => {
        item.addEventListener('click', deleteCart)
    })
    newCartButton?.addEventListener('click', newCart)
}

function changeActiveCart(e) {
    const changeActiveCartUrl = e.currentTarget.getAttribute('data-url-change');
    const ajaxCartUrl = e.currentTarget.getAttribute('data-url-update');
    if (e.target !== e.currentTarget) return;

    fetch(changeActiveCartUrl, { method: 'POST' })
        .then(response => {
            if (response.ok) {
                updateCart(ajaxCartUrl);
            } else {
                throw new Error('Something went wrong');
            }
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

function updateCart(ajaxCartUrl) {
    fetch(ajaxCartUrl)
        .then(response => {
            if (response.ok) {
                return response.json()
            }
            else {
                throw new Error('Something went wrong');
            }
        })
        .then(jsonData => {
            updateWidgetCarts(jsonData);
            multicart && updateSummaryCarts();
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

function updateWidgetCarts(jsonData) {
    buttonCartWidget.innerHTML = jsonData.ajaxButton;
    popupCartsWidget.innerHTML = jsonData.popupCarts;
    popupCartItemsWidget.innerHTML = jsonData.popupItems;

    addEvents();
}

function updateSummaryCarts() {
    fetch("/en_US/carts/summary")
        .then(response => {
            if (response.ok) {
                return response.text()
            } else {
                throw new Error('Something went wrong');
            }
        }).then(response => {
            multicart.innerHTML = response;
            updateSummary()
            addEvents();
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

function updateSummary() {
    cartSummary = document.getElementById("summary-items")

    fetch("/en_US/cart-items")
        .then(response => {
            if (response.ok) {
                return response.text()
            } else {
                throw new Error('Something went wrong');
            }
        }).then(response => {
            cartSummary.innerHTML = response
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });

    fetch("/en_US/cart-total")
        .then(response => {
            if (response.ok) {
                return response.text()
            } else {
                throw new Error('Something went wrong');
            }
        }).then(response => {
            cartSummary.innerHTML += response
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

async function btnClick(btn) {
    return new Promise(resolve => btn.onclick = () => resolve());
}

async function deleteCart(e) {
    const deleteCartUrl = e.currentTarget.getAttribute('data-url-delete');
    const ajaxCartUrl = e.currentTarget.getAttribute("data-url-update");
    const dialog = document.getElementById('multi-cart-dialog');
    const btn = document.getElementById('deleteConfirmBtn')

    dialog.showModal();
    await btnClick(btn);

    fetch(deleteCartUrl, { method: 'POST' })
        .then(response => {
            if (response.ok) {
                updateCart(ajaxCartUrl);
            } else {
                throw new Error('Something went wrong');
            }
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

function newCart(e) {
    const newCartUrl = e.currentTarget.getAttribute('data-url-new');
    const ajaxCartUrl = e.currentTarget.getAttribute('data-url-update');

    fetch(newCartUrl, { method: "POST" })
        .then(response => {
            if (response.ok) {
                updateCart(ajaxCartUrl);
            } else {
                throw new Error('Something went wrong');
            }
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

const turnOnListeners = () => {
    if (changeCartButtons.length > 0) {
        addEvents();
    }
}

turnOnListeners();