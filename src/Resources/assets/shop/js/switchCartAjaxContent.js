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
    newCartButton.addEventListener('click', newCart)
}

function changeActiveCart(e) {
    const changeActiveCartUrl = e.target.getAttribute('data-url-change');
    const changeActiveCartRequest = new Request(changeActiveCartUrl, {
        method: 'POST',
    })

    fetch(changeActiveCartRequest)
        .then(response => {
            if (response.ok) {
                updateCart(e);
            } else  {
                throw new Error('Something went wrong');
            }
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

function updateCart(e) {
    const ajaxCartUrl = e.target.getAttribute('data-url-update');
    const ajaxCartRequest = new Request(ajaxCartUrl, {
        method: 'GET'
    });

    fetch(ajaxCartRequest)
        .then(response => {
            if (response.ok) {
                response.json().then(jsonData => {
                    updateElements(e, jsonData);
                })
            } else  {
                throw new Error('Something went wrong');
            }
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

function updateElements(e, jsonData) {
    buttonCartWidget.innerHTML = jsonData.ajaxButton;
    popupCartsWidget.innerHTML = jsonData.popupCarts;
    popupCartItemsWidget.innerHTML = jsonData.popupItems;

    addEvents();
}

function deleteCart(e) {
    const deleteCartUrl = e.target.getAttribute('data-url-delete');
    const deleteCartRequest = new Request(deleteCartUrl, {
        method: 'POST',
    })

    fetch(deleteCartRequest)
        .then(response => {
            if (response.ok) {
                updateCart(e);
            } else  {
                throw new Error('Something went wrong');
            }
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

function newCart(e) {
    const newCartUrl = e.target.getAttribute('data-url-new');
    const newCartRequest = new Request(newCartUrl, {
        method: 'POST',
    })

    fetch(newCartRequest)
        .then(response => {
            if (response.ok) {
                updateCart(e);
            } else  {
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