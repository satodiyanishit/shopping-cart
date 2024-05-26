$(document).ready(function() {
    // Fetch latest 5 products
    $.ajax({
        url: 'fetch_product.php',
        method: 'GET',
        success: function(response) {
            var products = JSON.parse(response);
            products.forEach(function(product) {
                var html = '<div class="container__index-product">' +
                               '<div class="index__fetch-product">' +
                                   '<div class="fetch-product">' +
                                       '<div class="product-image">' +
                                           '<img src="./images/' + product.product_image + '" alt="product-image" class="product-image">' +
                                       '</div>' +
                                       '<div class="product-content">' +
                                           '<p class="product-name">' + product.product_name + '</p>' +
                                           '<div class="product-detail">' +
                                               '<p class="product-price"><span class="rupees-sign">₹ </span>' + product.product_price + '/-</p>' +
                                               '<a href="detail-product.php?id=' + product.id + '" class="detail-button">View details</a>' +
                                           '</div>' +
                                       '</div>' +
                                   '</div>' +
                               '</div>' +
                           '</div>';
                $('.slider').append(html);
            });
            // Initialize Slick Slider
            $('.slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2500,
                arrows: false,
                dots: true
            });
        }
    });
});