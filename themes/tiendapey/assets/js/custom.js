$(document).ready(function(){
	$('.LinkCuenta').click(function(){
        $(".Right").toggleClass("active");
    });
    $("#product #wrapper .featured-products").addClass("container");
    
    
    $('.show_list').click(function(){
        document.cookie = "show_list=true; expires=Thu, 30 Jan 2100 12:00:00 UTC; path=/";
        $('#content-wrapper #js-product-list .product-miniature').addClass('product_show_list');
        $(this).addClass('active');
        $('.show_grid').removeClass('active');
    });
     
    $('.show_grid').click(function(){
        document.cookie = "show_list=; expires=Thu, 30 Jan 1970 12:00:00 UTC; path=/";
        $('#content-wrapper #js-product-list .product-miniature').removeClass('product_show_list');
		$(this).addClass('active');
		$('.show_list').removeClass('active');
    });
     
    prestashop.on('updateProductList', function (event) {
        $('.show_list').click(function(){
            $('#content-wrapper #js-product-list .product-miniature').addClass('product_show_list');
			$(this).addClass('active');
			$('.show_grid').removeClass('active');
        });
         
        $('.show_grid').click(function(){
            $('#content-wrapper #js-product-list .product-miniature').removeClass('product_show_list');
			$(this).addClass('active');
			$('.show_list').removeClass('active');
        });
    });
    if (!!$.prototype.bxSlider)
		$('.bxslider-hometabs').bxSlider({
			minSlides: 1,
			maxSlides: 8,
			slideWidth: 270,
			slideMargin: 10,
			pager: false,
			controls: true,
			nextText: 'Next',
			prevText: 'Prev',
			moveSlides:2,
			infiniteLoop:false,
			hideControlOnEnd: false
		});
});