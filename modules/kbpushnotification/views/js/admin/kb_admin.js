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




(function () {
    $(".kb-push-buttons-form").each(function () {
        var ele = $(this);
        if ($(".kb-push-btn-bck:eq(0) input", ele).val() != '') {
            $(".kb-push-btn-bck:eq(0)", ele).show();
        }
        if ($(".kb-push-btn-bck:eq(1) input", ele).val() != '') {
            $(".kb-push-btn-bck:eq(1)", ele).show();
        }
    });
})();
$(document).ready(function () {
    
    
    if (typeof large_kpi_icon != 'undefined') {
        if (large_kpi_icon == 'yes') {
            $('#total-subscribers').parent().css({'background': 'rgba(255, 192, 203, 0.55)', 'padding': '10px'});
            $('#total-campaign').parent().css({'background': 'rgba(255, 255, 0, 0.36)', 'padding': '10px'});
            $('#total-mobile-user').parent().css({'background': '#EAFFCF', 'padding': '10px'});
            $('#total-desktop-user').parent().css({'background': 'rgba(0, 0, 255, 0.17)', 'padding': '10px'});
            $('.kpi-container .value').css('font-size', ' 1.5em');
        } else {
            $('#total-subscribers').css({'background': 'rgba(255, 192, 203, 0.55)', 'padding': '10px'});
            $('#total-campaign').css({'background': 'rgba(255, 255, 0, 0.36)', 'padding': '10px'});
            $('#total-mobile-user').css({'background': '#EAFFCF', 'padding': '10px'});
            $('#total-desktop-user').css({'background': 'rgba(0, 0, 255, 0.17)', 'padding': '10px'});
        }
    }
    if (typeof chrome_label != 'undefined') {
        var data = [];
        var obj = {value: 33.33, label: other_label, formatted: otherSubscribers + ' ' + out_of_label + ' ' + totalSubscribers};
        data.push(obj);
        var obj = {value: 33.33, label: firefox_label, formatted: firefoxSubscribers + ' ' + out_of_label + ' ' + totalSubscribers};
        data.push(obj);
        var obj = {value: 33.33, label: chrome_label, formatted: chromeSubscribers + ' ' + out_of_label + ' ' + totalSubscribers};
        data.push(obj);

        Morris.Donut({
            element: 'graph-donut',
            data: data,
            backgroundColor: false,
            labelColor: '#000',
            colors: [
                '#fe8676','#5ab6df', '#6a8bbe' 
            ],
            formatter: function (x, data) {
                return data.formatted;
            },
            resize:true,
        })
    }
    if (typeof currentText != 'undefined') {
        $('input[name="send_at_time_date"]').datetimepicker({
            beforeShow: function (input, inst) {
                setTimeout(function () {
                    inst.dpDiv.css({
                        'z-index': 1031
                    });
                }, 0);
            },
            prevText: '',
            nextText: '',
            dateFormat: 'yy-mm-dd',
            // Define a custom regional settings in order to use PrestaShop translation tools
            currentText: currentText,
            closeText: closeText,
            ampm: false,
            amNames: ['AM', 'A'],
            pmNames: ['PM', 'P'],
            timeFormat: 'hh:mm:ss tt',
            timeSuffix: '',
            timeOnlyTitle: timeOnlyTitle,
            timeText: timeText,
            hourText: hourText,
            minuteText: minuteText,
        });
    }

    if ($("input[name='send_push_time']:checked").val() == '0') {
        $(".error_message").remove();
        $('input[name="send_push_time"]').closest('.kb-radio-field').removeClass('error_field');
        $('input[name="send_at_time_date"]').hide();

    }
    if ($("input[name='send_push_time']:checked").val() == '1') {
        $(".error_message").remove();
        $('input[name="send_push_time"]').closest('.kb-radio-field').removeClass('error_field');
        $('input[name="send_at_time_date"]').show();
    }

    $('input[name="send_push_time"]').click(function () {
        $('input[name="send_push_time"]').closest('.kb-radio-field').removeClass('error_field');
        $(".error_message").remove();
        if ($('input[name="send_push_time"]:checked').val() == '1') {
            $('input[name="send_at_time_date"]').show();
        } else {
            $('input[name="send_at_time_date"]').hide();
        }
    });

    if ($("input[name='kbwelcomenotify[display_logo]']:checked").val() == 0) {
        $("input[name='kb_welcome_logo']").parents('.form-group').hide();
    }
    if ($("input[name='kbwelcomenotify[display_logo]']:checked").val() == 1) {
        $("input[name='kb_welcome_logo']").parents('.form-group').show();
    }

    $("input[name='kbwelcomenotify[display_logo]']").click(function () {
        if ($("input[name='kbwelcomenotify[display_logo]']:checked").val() == 0) {
            $("input[name='kb_welcome_logo']").parents('.form-group').hide();
        } else {
            $("input[name='kb_welcome_logo']").parents('.form-group').show();
        }
    });


    $('#module_config').addClass('col-lg-10 col-md-9');
    $('#welcome_notification_setting').addClass('col-lg-10 col-md-9');
//    $('#pdf_settings').addClass('col-lg-10 col-md-9');
    $('#product_signup_setting').addClass('col-lg-10 col-md-9');

    $('#module_config').show();
    $('#product_signup_setting').hide();
//    $('#pdf_settings').hide();
    $('#welcome_notification_setting').hide();

    $('button.form_kb-push-admin').closest('form').find('.form-wrapper').append($('.kb-push-buttons-form'));
    if ($('button.form_kb-push-admin').closest('form').find('.kb-push-buttons-form').length) {
        $('.kb-push-add').show();

    }
    $(".kb-push-buttons-form").each(function () {
        var ele = $(this);
        check_buttons(ele);
    });

    $(".kb-push-add").on("click", function () {
        var ele = $(this).closest('div .kb-push-buttons-form');
        var button_rows = count_button_rows(ele);
        if (button_rows === 0) {
            $(".kb-push-btn-bck:eq(0)", ele).show();
            $(".kb-push-btn-bck:eq(1)", ele).hide();
        } else if (button_rows === 1) {
            $(".kb-push-btn-bck:eq(0)", ele).show();
            $(".kb-push-btn-bck:eq(1)", ele).show();
        }
        check_buttons(ele);
    });

    $(".kb-push-remove").on("click", function () {
        var ele = $(this).closest('div .kb-push-buttons-form');
        var button_rows = count_button_rows(ele);
        if (button_rows === 1) {
            $(".kb-push-btn-bck:eq(0)", ele).hide();
            $(".kb-push-btn-bck:eq(1)", ele).hide();
            $(".kb-push-btn-bck:eq(0) input", ele).val('');
            $(".kb-push-btn-bck:eq(1) input", ele).val('');
        } else if (button_rows === 2) {
            $(".kb-push-btn-bck:eq(0)", ele).show();
            $(".kb-push-btn-bck:eq(1)", ele).hide();
            $(".kb-push-btn-bck:eq(1) input", ele).val('');
        }
        check_buttons(ele);
    });

    function check_buttons(ele) {
        var button_rows = count_button_rows(ele);
        if (button_rows === 0) {
            $(".kb-push-add", ele).show();
            $(".kb-push-remove", ele).hide();
        } else if (button_rows === 1) {
            $(".kb-push-add", ele).show();
            $(".kb-push-remove", ele).show();
        } else if (button_rows === 2) {
            $(".kb-push-remove", ele).show();
            $(".kb-push-add", ele).hide();
        }
    }

    function count_button_rows(ele) {
        var count = 0;
        if ($(".kb-push-btn-bck:eq(0)", ele).is(":visible")) {
            count++;
        }
        if ($(".kb-push-btn-bck:eq(1)", ele).is(":visible")) {
            count++;
        }
        return count;
    }


    $('.kb-push-add').on('click', function () {
        $('.kb-push-buttons-form .kb-push-btn-bck-1').show();
        $('.kb-push-buttons-form .kb-push-remove').show();
    });


    $('input[name="notification_icon"]').on('change', function () {
        $("input[name='notification_icon']").closest('.form-group').find('.input-group').removeClass('error_field');
        var imgPath = $(this)[0].value;
        $('.error_message').remove();
        var image_holder = $("#kbslmarker");
        if (($("input[name='notification_icon']").prop("files").length)) {
            var validate_image = velovalidation.checkImage($(this), 2097152, 'kb');
            if (validate_image != true) {
                $('input[name="filename"]').val('');
                showErrorMessage(validate_image);
                $("input[name='notification_icon']").closest('.form-group').find('.input-group').addClass('error_field');
                $('input[name="notification_icon"]').closest('.form-group').after('<span class="error_message">' + validate_image + '</span>');
            } else {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#kbslmarker').attr('src', e.target.result);
                }
                image_holder.show();
                reader.readAsDataURL($(this)[0].files[0]);
            }
        }
    });

    $('input[name="kb_welcome_logo"]').on('change', function () {
        $("input[name='kb_welcome_logo']").closest('.form-group').find('.input-group').removeClass('error_field');
        var imgPath = $(this)[0].value;
        $('.error_message').remove();
        var image_holder = $("#kbslmarker");
        if (($("input[name='kb_welcome_logo']").prop("files").length)) {
            var validate_image = velovalidation.checkImage($(this), 2097152, 'kb');
            if (validate_image != true) {
                $('input[name="filename"]').val('');
                showErrorMessage(validate_image);
                $("input[name='kb_welcome_logo']").closest('.form-group').find('.input-group').addClass('error_field');
                $('input[name="kb_welcome_logo"]').closest('.form-group').after('<span class="error_message">' + validate_image + '</span>');
            } else {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#kbslmarker').attr('src', e.target.result);
                }
                image_holder.show();
                reader.readAsDataURL($(this)[0].files[0]);
            }
        }
    });

    $('textarea[name^="notification_message_"]').parents('.col-lg-9').last().append($('.kb-admin-shortcodes'));
    $('.kb-admin-shortcodes').show();
//    var kb_price_shortcodes = $('.kb-admin-shortcodes-price'); 
    $('textarea[name^="kbsignup_price_message_"]').parents('.col-lg-9').last().append($('.kb-admin-shortcodes-price'));
    $('textarea[name^="kbsignup_stock_message_"]').parents('.col-lg-9').last().append($('.kb-admin-shortcodes-stock'));
    $('textarea[name^="kbsignup_price_message_"]').parents('.col-lg-9').find('.kb-admin-shortcodes-price').show();
    $('textarea[name^="kbsignup_stock_message_"]').parents('.col-lg-9').find('.kb-admin-shortcodes-stock').show();
});

/*
 * Function for handling the swithcing of tabs in the admin configuration form
 */
function switchModuleTabs(ele, sort_order)
{
    $('.list-group-item').removeClass('active');
    $(ele).addClass('active');
//    $('#cron_instructions').hide();
//    $('#manual_cron_link').remove();
    if (sort_order == 1) {
        $('#module_config').show();
        $('#product_signup_setting').hide();
//        $('#pdf_settings').hide();
        $('#welcome_notification_setting').hide();
    } else if (sort_order == 2) {
        $('#module_config').hide();
        $('#product_signup_setting').show();
//        $('#pdf_settings').hide();
        $('#welcome_notification_setting').hide();
    } else if (sort_order == 3) {
        $('#module_config').hide();
        $('#product_signup_setting').hide();
//        $('#pdf_settings').show();
        $('#welcome_notification_setting').show();
    } else {
        $('#module_config').show();
        $('#product_signup_setting').hide();
//        $('#pdf_settings').hide();
        $('#welcome_notification_setting').hide();
    }
}