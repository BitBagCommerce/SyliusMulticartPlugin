// function refreshPageTest(){
//     alert("I am an alert box!");
// }

console.log('TESTO')
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

// global.changeCart = function() {
//     console.log('testy')
// }

function changeCart() {
    console.log('testy')
}

// $(document).ready(function() {
//     console.log('super test')
//     $('#korek-test').click(function() {
//         // alert("costam dziala");
//     });
// })

var refreshButton = document.getElementById('korek-test');

refreshButton.addEventListener('click', function () {
    alert('proper event listener')
})