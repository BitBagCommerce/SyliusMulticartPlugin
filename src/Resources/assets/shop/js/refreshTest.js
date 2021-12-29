// function changeCart(){
//     $('#sylius-cart-total').html('TEST');
//     $.ajax({
//         type: "GET",
//         url: '/en_US/_partial/cart/summary',
//         data: {'template': '@SyliusShop/Cart/_widget.html.twig'},
//         success:function(response){
//         // $('#test_click').html(response);
//         $('#sylius-cart-total').content.replaceWith('test');
//         },
//     });
// }

const changeCartButtons = document.querySelectorAll('.change-cart');

changeCartButtons.forEach(item => {
    item.addEventListener('click', changeActiveCart)
})

function showAlert() {
    alert('proper event listener')
}

function changeActiveCart(e) {
    // fetch('/en_US/_partial/cart/summary', {
    //     method: 'GET',
    //     // headers: {
    //     //     'Content-Type': 'text/html',
    //     // },
    //     params: { template: '@SyliusShop/Cart/_widget.html.twig' },
    // }).then(data => alert(data));

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


    // changeCartButtons.forEach(item => {
    //     item.classList.remove('primary')
    // })
    // e.target.classList.add('primary')
}