const changeCartButtons = document.querySelectorAll('.change-cart');

changeCartButtons.forEach(item => {
    item.addEventListener('click', changeActiveCart)
})

function showAlert() {
    alert('proper event listener')
}

function changeActiveCart(e) {

    const changeActiveCartUrl = '/en_US/ajax/cart/get-active';
    const ajaxCartUrl = '/en_US/ajax/cart/get-active';

    const changeActiveCartRequest = new Request(changeActiveCartUrl, { method: 'POST'})
    const ajaxCartRequest = new Request(ajaxCartUrl, { method: 'GET'});

    fetch(ajaxCartRequest)
        .then(response => {
            if (response.ok) {
                response.json().then(jsonData => {
                    alert('test')
                })
                // throw new Error('Network response was not OK');
                // console.log(data.formattedItemsTotal)
            }
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });



    // fetch(url, {
    //     method: 'GET',
    // }).then(response => {
    //     // console.log(response)
    // });

    // var numberString = e.target.getAttribute("data-value")
    // var changeCartUrl = '/en_US/ajax/cart/change-active-cart/' + numberString;


    // changeCartButtons.forEach(item => {
    //     item.classList.remove('primary')
    // })
    // e.target.classList.add('primary')
}