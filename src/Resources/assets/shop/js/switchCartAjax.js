const changeCartButtons = document.querySelectorAll('.change-cart');

const buttonCartTotal = document.getElementById('sylius-cart-total');
const buttonItemsCount = document.getElementById('sylius-cart-items-count');

changeCartButtons.forEach(item => {
    item.addEventListener('click', changeActiveCart)
})

function showAlert() {
    alert('proper event listener')
}

function changeActiveCart(e) {
    const changeActiveCartUrl = '/en_US/ajax/cart/change/1';
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
    const ajaxCartUrl = '/en_US/ajax/cart/get-active';
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
    console.log(buttonCartTotal.innerText)
    buttonCartTotal.innerText = jsonData.formattedItemsTotal;

    changeCartButtons.forEach(item => {
        item.classList.remove('primary')
    })
    e.target.classList.add('primary')
}
