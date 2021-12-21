setTimeout(function () {
    document.getElementById('greeting').innerHTML = document.getElementById('greeting').dataset.greeting;
}, 1000);


$.ajax({
    type: "GET",
    url: '#sylius_shop_partial_cart_summary',
    data: {'template': '@SyliusShop/Cart/_widget.html.twig'},
    success:function(response){
        $('#myElement').html(response);
    },
});

function refreshPageTest(){
    alert("I am an alert box!");
}