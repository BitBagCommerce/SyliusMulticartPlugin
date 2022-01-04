const changeCartButtons = document.querySelectorAll('.change-cart');

changeCartButtons.forEach(item => {
    item.addEventListener('click', changeActiveCart)
})

function showAlert() {
    alert('proper event listener')
}

function changeActiveCart(e) {
    var numberString = e.target.getAttribute("data-value")
    var changeCartUrl = '/en_US/ajax/cart/change-active-cart/' + numberString;

    $.ajax({
        type: "POST",
        url: changeCartUrl,
        success: function(response){
            updateCart();
        },
    });

    function updateCart() {
        $.ajax({
            type: "GET",
            url: '/en_US/ajax/cart-contents',
            success: function(response){
                console.log(response.ajaxButton);
                $('#ajax-button').html(response.ajaxButton);
                $('#popup-carts').html(response.popupCarts);
                $('#popup-items').html(response.popupItems);

                var changeCartButtons = document.querySelectorAll('.change-cart');
                changeCartButtons.forEach(item => {
                    item.addEventListener('click', changeActiveCart)
                })
            },
        });
    }
}