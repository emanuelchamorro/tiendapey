/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */
$(document).ready(function () {
var quantityAvailable;
    
    $('.product-add-to-cart').on("DOMSubtreeModified", function () {
        if ($('.add-to-cart').is(":disabled")) {
            quantityAvailable = 0;
            var kb_data = $.parseJSON($('input[name="kb_push_stock_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>'+kb_data.message+'</p>');
            $('#product_subscribe_type').val("stock");
            
        } else {
            quantityAvailable = 1;
            var kb_data = $.parseJSON($('input[name="kb_push_price_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>'+kb_data.message+'</p>');
            $('#product_subscribe_type').val("price");
        }
    });
    
   $('.kb-push-toggle-btn').on("click", function () {
        $('.kb-push-signup-content').toggle();
        if ($('.kb-push-signup-content').is(':visible')) {
//            $('.kb-push-toggle-btn i').removeClass('icon-plus').addClass('icon-minus');
            $('.kb-push-toggle-btn i').html('remove');
        } else {
            $('.kb-push-toggle-btn i').html('add');
        }
    }); 
    
        
    disableBuyNowButton();
if (typeof quantityAvailable != 'undefined') {
        if (quantityAvailable == 0) {
            var kb_data = $.parseJSON($('input[name="kb_push_stock_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>'+kb_data.message+'</p>');
            $('#product_subscribe_type').val("stock");
        } else {
            var kb_data = $.parseJSON($('input[name="kb_push_price_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>'+kb_data.message+'</p>');
            $('#product_subscribe_type').val("price");
        }
    }

    
    $(document).on('change', '.attribute_select', function (e) {
         findCombination();
        if (quantityAvailable == 0) {
            var kb_data = $.parseJSON($('input[name="kb_push_stock_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>'+kb_data.message+'</p>');
            $('#product_subscribe_type').val("stock");
        } else {
            var kb_data = $.parseJSON($('input[name="kb_push_price_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>'+kb_data.message+'</p>');
            $('#product_subscribe_type').val("price");
        }

    });
    
    $(document).on('click', '.color_pick', function (e) {
        findCombination();
        if (quantityAvailable == 0) {
            var kb_data = $.parseJSON($('input[name="kb_push_stock_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>'+kb_data.message+'</p>');
            $('#product_subscribe_type').val("stock");
        } else {
            var kb_data = $.parseJSON($('input[name="kb_push_price_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>'+kb_data.message+'</p>');
            $('#product_subscribe_type').val("price");
        }

    });
    $(document).on('click', '.attribute_radio', function (e) {
         findCombination();
        if (quantityAvailable == 0) {
            var kb_data = $.parseJSON($('input[name="kb_push_stock_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>'+kb_data.message+'</p>');
            $('#product_subscribe_type').val("stock");
        } else {
            var kb_data = $.parseJSON($('input[name="kb_push_price_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>'+kb_data.message+'</p>');
            $('#product_subscribe_type').val("price");
        }

    });
    
    
});
function disableBuyNowButton()
{
    if (typeof quantityAvailable != 'undefined') {
       var quantityAvailable = 0; 
    }
    if ($('#kb-push-heading-content').length) {
        if ($('.add-to-cart').is(":disabled")) {
            quantityAvailable = 0;
            var kb_data = $.parseJSON($('input[name="kb_push_stock_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>' + kb_data.message + '</p>');
            $('#product_subscribe_type').val("stock");
        } else {
            quantityAvailable = 1;
            var kb_data = $.parseJSON($('input[name="kb_push_price_info"]').val());
            $('#kb-push-heading-content').html(kb_data.heading);
            $('#kb-push-content-data').html('<p>' + kb_data.message + '</p>');
            $('#product_subscribe_type').val("price");
        }
    }
}

function setCookie(key, value) {
    var expires = new Date();
    expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
}

function getCookie(key) {
    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
}

function submitkbpushsignup()
{
    var id_product = $('input[name="id_product"]').val();
    var actual_price = $('.current-price span[content]').attr("content");
    var combi_id = $('#product-details').data('product')['id_product_attribute']
    var subscribe_type = $('input[name="product_subscribe_type"]').val();
    var reg_id = $('#product_alert_reg_id').val();
    if (reg_id != '') {
    if (typeof kb_push_signup_url != 'undefined') {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: kb_push_signup_url,
            data: 'ajax=true&id_product='+id_product+'&reg_id='+reg_id+'&actual_price='+actual_price+'&id_product_combination='+combi_id+'&subscribe_type='+subscribe_type,
            beforeSend: function () {
                $(".kb-push-signup-button").hide();
                $(".kb_push_loader").show();
                $('.kbsuccess').hide();
                $('.kberror').hide();
            },
            success: function (res) {
                if (res != '') {
                    console.log(res);
                    if (res['success']) {
                        $('.kbsuccess').html(res['success']);
                        $('.kbsuccess').show();
                    } else if (res['error']) {
                        $(".kb-push-signup-button").show();
                        $('.kberror').html(res['error']);
                        $('.kberror').show();
                    }
                }
            },
            complete: function () {
                $(".kb_push_loader").hide();
            }
        });
    }
    } else {
        //display error message of reg id empty
    }
//    $('#pal_attribute_id').val(combi_id);
}