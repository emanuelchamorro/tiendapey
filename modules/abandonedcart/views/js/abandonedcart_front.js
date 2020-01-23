/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @category  PrestaShop Module
 * @author    knowband.com <support@knowband.com>
 * @copyright 2016 knowband
 * @license   see file: LICENSE.txt
 */

var kb_abn_email_reg = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

$(document).ready(function () {

    $('#login-form').on('blur', 'input[name="email"]', function () {
        var email = $('#login-form input[name="email"]').val();
        if (kb_abn_email_reg.test(email)) {
            var url_with_params = abd_ajax_url + '&ajax=true&action=add_email&email=' + email;
            updateCustomerInfoInAbandonedCart(url_with_params);
        }
    });

    $('#velsof_supercheckout_form').on('blur', '#email', function () {
        var email = $('#velsof_supercheckout_form #email').val();
        if (kb_abn_email_reg.test(email)) {
            var url_with_params = abd_ajax_url + '&ajax=true&action=add_email&email=' + email;
            updateCustomerInfoInAbandonedCart(url_with_params);
        }
    });

    $('#customer-form').on('blur', 'input[name="email"], input[name="firstname"], input[name="lastname"]', function () {
        var email = $('#customer-form input[name="email"]').val();
        var fname = ($('#customer-form input[name="firstname"]').val() !== undefined) ? $('input[name="firstname"]').val() : '';
        var lname = ($('#customer-form input[name="lastname"]').val() !== undefined) ? $('input[name="lastname"]').val() : '';

        if (kb_abn_email_reg.test(email)) {
            var url_with_params = abd_ajax_url + '&ajax=true&action=add_email&email=' + email + '&fname=' + fname + '&lname=' + lname;
            updateCustomerInfoInAbandonedCart(url_with_params);
        }
    });

    $('#checkout-guest-form').on('blur', 'input[name="email"], input[name="firstname"], input[name="lastname"]', function () {
        var email = $('#checkout-guest-form input[name="email"]').val();
        var fname = ($('#checkout-guest-form input[name="firstname"]').val() !== undefined) ? $('input[name="firstname"]').val() : '';
        var lname = ($('#checkout-guest-form input[name="lastname"]').val() !== undefined) ? $('input[name="lastname"]').val() : '';
        if (kb_abn_email_reg.test(email)) {
            var url_with_params = abd_ajax_url + '&ajax=true&action=add_email&email=' + email + '&fname=' + fname + '&lname=' + lname;
            updateCustomerInfoInAbandonedCart(url_with_params);
        }
    });
});

function updateCustomerInfoInAbandonedCart(url)
{
    $.ajax({
        type: 'POST',
        url: url,
        cache: false,
        success: function (jsonData) {
        }
    });
}
