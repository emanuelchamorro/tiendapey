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
    $body = $("body");
    $('.start_date,.end_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $(document).on({
        ajaxStart: function() {
            $body.addClass("loading");
        },
        ajaxStop: function() {
            $body.removeClass("loading");
        }
    });
    $('.kbph_general_btn').click(function(){
        return veloValidateConfigurationForms(this);
    });
    $('.kbph_welcome_setting_btn').click(function(){
        return veloValidateConfigurationForms(this);
    });
    
    $('.kbph_product_sign_setting_btn').click(function() {
        return veloValidateConfigurationForms(this);
    });
    
    $('button[name="sendkbAllSubscriber"]').click(function() {
        var error = false;
        $(".error_message").remove();
        $('select[name="template"]').removeClass('error_field');
        $('input[name="send_push_time"]').closest('.kb-radio-field').removeClass('error_field');
        
        if ($('select[name="template"]').val() == "") {
            error = true;
            $('select[name="template"]').addClass('error_field');
            $('select[name="template"]').after('<span class="error_message">'+kb_select_tempate+'</span>');
        }
        
        if (!$('input[name="send_push_time"]').is(':checked')) {
            error = true;
            $('input[name="send_push_time"]').closest('.kb-radio-field').addClass('error_field');
            $('input[name="send_push_time"]').closest('.kb-radio-field').after('<p class="error_message" style="clear:both;">'+kb_select_tempate+'</p>');
        } else {
            if (($('input[name="send_push_time"]:checked').val() == '1') && $('input[name="send_at_time_date"]').is(':visible')) {
                var time_date_empty = velovalidation.checkMandatory($('input[name="send_at_time_date"]'));
                if (time_date_empty != true) {
                    error = true;
                    $('input[name="send_at_time_date"]').addClass('error_field');
                    $('input[name="send_at_time_date"]').after('<span class="error_message">' + time_date_empty + '</span>');
                }
            }
        }
        
        if (error) {
            $('html, body').animate({
                scrollTop: $(".error_message").offset().top - 200
            }, 1000);
            return false;
        } else {
            var send_time = $('input[name="send_push_time"]:checked').val();
            var send_date_time = $('input[name="send_at_time_date"]').val();
            if (send_time == '1' && send_date_time !='') {
                $("button[name='sendkbAllSubscriber']").attr('disabled', 'disabled');
                $("button[name='sendkbTest']").attr('disabled', 'disabled');
                $('.kb_push_notification_form').append('<input type="hidden" name="sendkbAllSubscriber" value="1">');
                $('.kb_push_notification_form').submit();
                return;
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: kb_send_promotion_url,
                data: 'ajax_call=send_campaign&id_template=' + $('select[name="template"]').val() + '&reg_id=' + kbcurrentToken,
                beforeSend: function () {
                    $(".kb-success").hide();
                    $(".kb-error").hide();
                     $("body").addClass("loading");
                },
                success: function (json) {
                    if (json['success']) {
                        $(".kb-success").html(json['success']);
                        $(".kb-success").show();
                        $('html, body').animate({
                            scrollTop: $(".kb-success").offset().top - 200
                        }, 1000);
                    } else if (json['error']) {
                        $(".kb-error").html(json['error']);
                        $(".kb-error").show();
                         $('html, body').animate({
                            scrollTop: $(".kb-error").offset().top - 200
                        }, 1000);
                    }
                },
                complete: function () {
                     $("body").removeClass("loading");
                }
            });   
        }
        return false;
    });
    
    $('button[name="sendkbTest"]').click(function() {
        var error = false;
        $(".error_message").remove();
        $('select[name="template"]').removeClass('error_field');
        if ($('select[name="template"]').val() == "") {
            error = true;
            $('select[name="template"]').addClass('error_field');
            $('select[name="template"]').after('<span class="error_message">'+kb_select_tempate+'</span>');
        }
        
        if (error) {
            $('html, body').animate({
                scrollTop: $(".error_message").offset().top - 200
            }, 1000);
            return false;
        } else {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: kb_send_promotion_url,
                data: 'ajax_call=test_push&id_template=' + $('select[name="template"]').val() + '&reg_id=' + kbcurrentToken,
                beforeSend: function () {
                    $(".kb-success").hide();
                    $(".kb-error").hide();
                     $("body").addClass("loading");
                },
                success: function (json) {
                    if (json['success']) {
                        $(".kb-success").html(json['success']);
                        $(".kb-success").show();
                         $('html, body').animate({
                            scrollTop: $(".kb-success").offset().top - 200
                        }, 1000);
                    } else if (json['error']) {
                        $(".kb-error").html(json['error']);
                        $(".kb-error").show();
                         $('html, body').animate({
                            scrollTop: $(".kb-error").offset().top - 200
                        }, 1000);
                    }
                },
                complete: function () {
                     $("body").removeClass("loading");
                }
            });   
        }
        return false;
    });
    
    
    $('button[name="submitAddkb_web_push_template"]').click(function (){
        var error = false;
        var is_error = 0;
        var is_error1 = 0;
        $(".error_message").remove();
        $('input[name^="notification_title_"]').removeClass('error_field');
        $('textarea[name^="notification_message_"]').removeClass('error_field');
        $('input[name="primary_url"]').removeClass('error_field');
        $('input[name="notification_icon"]').closest('.form-group').find('.input-group').removeClass('error_field');
        
        $('input[name^="notification_title_"]').each(function () {
            var notification_title_error = velovalidation.checkMandatory($(this));
            if (notification_title_error != true) {
                error = true;
                if (is_error < 1) {
                    $(this).parents('.col-lg-9').last().append('<span class="error_message">' + notification_title_error + ' ' + check_for_all + '</span>');
                    is_error++;
                }
                $(this).addClass('error_field');
            }
        });
        
        $('textarea[name^="notification_message_"]').each(function () {
            var notification_message_error = velovalidation.checkMandatory($(this));
            if (notification_message_error != true) {
                error = true;
                if (is_error1 < 1) {
                    $(this).parents('.col-lg-9').last().append('<span class="error_message">' + notification_message_error + ' ' + check_for_all + '</span>');
                    is_error1++;
                }
                $(this).addClass('error_field');
            }
        });
        
        if ($('input[name="notification_icon"]').prop('files').length) {
            validate_image = velovalidation.checkImage($('input[name="notification_icon"]'));
            if (validate_image != true) {
                error = true;
                $('input[name="notification_icon"]').closest('.form-group').find('.input-group').addClass('error_field');
                $('input[name="notification_icon"]').closest('.form-group').after('<span class="error_message">' + validate_image + '</span>');
            }
        }
        if ($('input[name="primary_url"]').length) {
             var primary_url_mand = velovalidation.checkMandatory($('input[name="primary_url"]'));
            if (primary_url_mand != true) {
                error = true;
                $('input[name="primary_url"]').addClass('error_field');
                $('input[name="primary_url"]').after('<span class="error_message">' + primary_url_mand + '</span>');
            } else {
                var primary_url_error = velovalidation.checkUrl($('input[name="primary_url"]'));
                if (primary_url_error != true) {
                    error = true;
                    $('input[name="primary_url"]').addClass('error_field');
                    $('input[name="primary_url"]').after('<span class="error_message">' + primary_url_error + '</span>');
                }
            }
        }
        
        if ($('.kb-push-buttons-form .kb-push-btn-bck').length) {
            $('.kb-push-btn-bck').each(function () {
                $(this).find('.kb_action_btn_text').removeClass('error_field');
                $(this).find('.kb_action_btn_link').removeClass('error_field');
                $(this).find('.kb-btn-hidden').remove();
                if ($(this).is(":visible")) {
                    var is_error = 0;
                    $(this).find('[class^="kb_action_btn_text"]').each(function () {
                        var action_title_error = velovalidation.checkMandatory($(this));
                        if (action_title_error != true) {
                            error = true;
                            if (is_error < 1) {
                                $(this).parents('.col-lg-9').last().append('<p class="error_message" style="clear: both;width: 28%;">' + action_title_error + ' ' + check_for_all + '</p>');
                                is_error++;
                            }
                            $(this).addClass('error_field');
                        }
//                        
                    });
                    var btn_link_err = velovalidation.checkMandatory($(this).find('.kb_action_btn_link'));
                    if (btn_link_err != true) {
                        error = true;
                        $(this).find('.kb_action_btn_link').addClass('error_field');
                        $(this).find('.kb_action_btn_link').after('<span class="error_message">' + btn_link_err + '</span>');
                    } else {
                        var btn_link_valid_err = velovalidation.checkUrl($(this).find('.kb_action_btn_link'));
                        if (btn_link_valid_err != true) {
                            error = true;
                            $(this).find('.kb_action_btn_link').addClass('error_field');
                            $(this).find('.kb_action_btn_link').after('<span class="error_message">' + btn_link_valid_err + '</span>');
                        }
                    }
                    var block_name = $(this).find('.kb_action_btn_text').attr('name');
                    if (block_name != '') {
                        block_name = block_name +'_hidden';
                        $(this).append('<input type="hidden" name="'+block_name+'" class="kb-btn-hidden" value=1>');
                    }
                }
            });
        }
        
        if (error) {
            $('html, body').animate({
                scrollTop: $(".error_message").offset().top - 200
            }, 1000);
            return false;
        }

        $("button[name='submitAddkb_web_push_template']").attr('disabled', 'disabled');
        $('#kb_web_push_template_form').submit();
    });
    
    
    
    $('.filtersalereport').click(function () {
        var error = false;
        $('.error_message').remove();
        $('input[name="start_date"]').closest('.input-group').removeClass('error_field');
        $('input[name="end_date"]').closest('.input-group').removeClass('error_field');
        var start_date_mand = velovalidation.checkMandatory($('input[name="start_date"]'));
        if (start_date_mand != true) {
            error = true;
            $('input[name="start_date"]').addClass('error_field');
            $('input[name="start_date"]').closest('.input-group').after('<span class="error_message">' + start_date_mand + '</span>');
        }
        var end_date_mand = velovalidation.checkMandatory($('input[name="end_date"]'));
        if (end_date_mand != true) {
            error = true;
            $('input[name="end_date"]').addClass('error_field');
            $('input[name="end_date"]').closest('.input-group').after('<span class="error_message">' + end_date_mand + '</span>');
        } else {
            var start_date = Date.parse($('input[name="start_date"]').val());
            var end_date = Date.parse($('input[name="end_date"]').val());
            if (parseInt(end_date) < parseInt(start_date)) {
                error = true;
                $('input[name="end_date"]').closest('.input-group').addClass('error_field');
                $('input[name="end_date"]').closest('.input-group').after('<span class="error_message">' + end_date_error + '</span>');
            }
        }

        if (error) {
            $('html, body').animate({
                scrollTop: $(".error_message").offset().top - 200
            }, 1000);
            return false;
        }
        if (error) {
            return false;
        } else {
            $.ajax({
                url: module_path,
                data: "start=" + $('input[name="start_date"]').val() + "&end=" + $('input[name="end_date"]').val() + '&groupby=' + $('select[name="groupby"]').val() + '&ajax=true&getChart=true',
                type: 'post',
                datatype: 'json',
                success: function (json)
                {
                    console.log(json);
                    $('.flot_graph').html('');
                    kbDrawChart(json.graph);
                    $('.salereporttable').remove();
                    $('#show_loader_filter').hide();
                    $('.salereportgraph').append(json.table);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(technical_error);
                }
            });
        }

    });

    $('input[name="start_date"]').attr('readonly', true);
    $('input[name="end_date"]').attr('readonly', true);
    
    
});


function kbDrawChart(json)
{
    var data = [];
    $.each(json, function (key, value) {
        var push = value.push_totals;
        var subscribers = value.subscription_totals;
        var label = value.time;
        var obj = {x: label, y: subscribers, z: push};
        data.push(obj);

    });
    Morris.Bar({
        element: 'flot-placeholder',
        data: data,
        xkey: 'x',
        ykeys: ['y', 'z'],
        labels: [subscriber_label, push_label],
        barColors: ['#2dd006', '#61b0f5'],
        hideHover: true,
        resize:true,
    });
    
    $('#flot-placeholder').append(
        '<div class="legend" style="margin-top: 0px;margin-bottom: 51px;"><div style="position: absolute; width: 362px; height: 28px; top: 9px; right: 9px; background-color: rgb(255, 255, 255); opacity: 0.85;"> </div><table style="position:absolute;color:#545454"><tbody><tr><td class="legendColorBox"><div style="border:1px solid null;padding:1px"><div style="width:4px;height:0;border:5px solid #2dd006;overflow:hidden"></div></div></td><td class="legendLabel"> '+subscriber_label+'</td><td class="legendColorBox"><div style="border:1px solid null;padding:1px"><div style="width:4px;height:0;border:5px solid #61b0f5;overflow:hidden"></div></div></td><td class="legendLabel"> '+push_label+'</td></tr></tbody></table></div>');
    $('.salereportgraph').show();
}



/*
 * Function for validating the submitting the Admin Configurations form
 * @param button_ele
 * @returns {Boolean}
 */
function veloValidateConfigurationForms(button_ele)
{
    var error = false;
    var is_error = 0;
    var is_error1 = 0;
    var is_error2 = 0;
    var is_error3 = 0;
    var is_error4 = 0;
    var is_error5 = 0;
    var is_error6 = 0;
    var is_error7 = 0;

    $(".error_message").remove();
    $('#welcome_notification_setting input').removeClass('error_field');
    $('#welcome_notification_setting input').parent().removeClass('error_field');
    $('#product_signup_setting input').removeClass('error_field');
    $('#product_signup_setting textarea').removeClass('error_field');
    $('#product_signup_setting input').parent().removeClass('error_field');
    var module_config_error = false;
    var module_welcome_error = false;
    var module_signup_error = false;

    $('input[name^="kbwelcomenotify_action_message_"]').each(function () {
        var action_title_error = velovalidation.checkMandatory($(this));
        if (action_title_error != true) {
            error = true;
            module_welcome_error = true;
            if (is_error < 1) {
                $(this).parents('.col-lg-9').last().append('<span class="error_message">' + action_title_error + ' ' + check_for_all + '</span>');
                is_error++;
            }
            $(this).addClass('error_field');
        }
    });
    $('input[name^="kbwelcomenotify_action_btn_text_"]').each(function () {
        var action_btn_error = velovalidation.checkMandatory($(this));
        if (action_btn_error != true) {
            error = true;
            module_welcome_error = true;
            if (is_error1 < 1) {
                $(this).parents('.col-lg-9').last().append('<span class="error_message">' + action_btn_error + ' ' + check_for_all + '</span>');
                is_error1++;
            }
            $(this).addClass('error_field');
        }
    });
    $('input[name^="kbwelcomenotify_action_cancel_text_"]').each(function () {
        var cancel_btn_error = velovalidation.checkMandatory($(this));
        if (cancel_btn_error != true) {
            error = true;
            module_welcome_error = true;
            if (is_error2 < 1) {
                $(this).parents('.col-lg-9').last().append('<span class="error_message">' + cancel_btn_error + ' ' + check_for_all + '</span>');
                is_error2++;
            }
            $(this).addClass('error_field');
        }
    });

    if ($("input[name='kbwelcomenotify[display_logo]']:checked").val() == 1) {
        if ($('input[name="kb_welcome_logo"]').prop('files').length) {
            validate_image = velovalidation.checkImage($('input[name="kb_welcome_logo"]'));
            if (validate_image != true) {
                error = true;
                $('input[name="kb_welcome_logo"]').closest('.form-group').find('.input-group').addClass('error_field');
                $('input[name="kb_welcome_logo"]').closest('.form-group').after('<span class="error_message">' + validate_image + '</span>');
            }
        }
    }

    $('input[name^="kbsignup_price_heading_"]').each(function () {
        var price_heading_error = velovalidation.checkMandatory($(this));
        if (price_heading_error != true) {
            error = true;
            module_signup_error = true;
            if (is_error3 < 1) {
                $(this).parents('.col-lg-9').last().append('<span class="error_message">' + price_heading_error + ' ' + check_for_all + '</span>');
                is_error3++;
            }
            $(this).addClass('error_field');
        }
    });

    $('textarea[name^="kbsignup_price_message_"]').each(function () {
        var price_msg_error = velovalidation.checkMandatory($(this));
        if (price_msg_error != true) {
            error = true;
            module_signup_error = true;
            if (is_error4 < 1) {
                $(this).parents('.col-lg-9').last().append('<span class="error_message">' + price_msg_error + ' ' + check_for_all + '</span>');
                is_error4++;
            }
            $(this).addClass('error_field');
        }
    });

    $('input[name^="kbsignup_stock_heading_"]').each(function () {
        var stock_heading_error = velovalidation.checkMandatory($(this));
        if (stock_heading_error != true) {
            error = true;
            module_signup_error = true;
            if (is_error5 < 1) {
                $(this).parents('.col-lg-9').last().append('<span class="error_message">' + stock_heading_error + ' ' + check_for_all + '</span>');
                is_error5++;
            }
            $(this).addClass('error_field');
        }
    });
    $('input[name^="kbsignup_stock_message_"]').each(function () {
        var stock_msg_error = velovalidation.checkMandatory($(this));
        if (stock_msg_error != true) {
            error = true;
            module_signup_error = true;
            if (is_error6 < 1) {
                $(this).parents('.col-lg-9').last().append('<span class="error_message">' + stock_msg_error + ' ' + check_for_all + '</span>');
                is_error6++;
            }
            $(this).addClass('error_field');
        }
    });
    $('input[name^="kbsignup_button_text_"]').each(function () {
        var btn_text_error = velovalidation.checkMandatory($(this));
        if (btn_text_error != true) {
            error = true;
            module_signup_error = true;
            if (is_error7 < 1) {
                $(this).parents('.col-lg-9').last().append('<span class="error_message">' + btn_text_error + ' ' + check_for_all + '</span>');
                is_error7++;
            }
            $(this).addClass('error_field');
        }
    });

    var heading_bk_color_mand = velovalidation.checkMandatory($('input[name="kbproductsignup[heading_bk_color]"]'));
    if (heading_bk_color_mand != true) {
        error = true;
        module_signup_error = true;
        $('input[name="kbproductsignup[heading_bk_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
        $('input[name="kbproductsignup[heading_bk_color]"]').closest('.form-group').after('<span class="error_message">' + heading_bk_color_mand + '</span>');
    } else {
        var heading_bk_color = velovalidation.isColor($('input[name="kbproductsignup[heading_bk_color]"]'));
        if (heading_bk_color != true) {
            error = true;
            module_signup_error = true;
            $('input[name="kbproductsignup[heading_bk_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
            $('input[name="kbproductsignup[heading_bk_color]"]').closest('.form-group').after('<span class="error_message">' + heading_bk_color + '</span>');
        }
    }
    
    var content_bk_color_mand = velovalidation.checkMandatory($('input[name="kbproductsignup[content_bk_color]"]'));
    if (content_bk_color_mand != true) {
        error = true;
        module_signup_error = true;
        $('input[name="kbproductsignup[content_bk_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
        $('input[name="kbproductsignup[content_bk_color]"]').closest('.form-group').after('<span class="error_message">' + content_bk_color_mand + '</span>');
    } else {
        var content_bk_color_color = velovalidation.isColor($('input[name="kbproductsignup[content_bk_color]"]'));
        if (content_bk_color_color != true) {
            error = true;
            module_signup_error = true;
            $('input[name="kbproductsignup[content_bk_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
            $('input[name="kbproductsignup[content_bk_color]"]').closest('.form-group').after('<span class="error_message">' + content_bk_color_color + '</span>');
        }
    }

    var heading_font_color_mand = velovalidation.checkMandatory($('input[name="kbproductsignup[heading_font_color]"]'));
    if (heading_font_color_mand != true) {
        error = true;
        module_signup_error = true;
        $('input[name="kbproductsignup[heading_font_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
        $('input[name="kbproductsignup[heading_font_color]"]').closest('.form-group').after('<span class="error_message">' + heading_font_color_mand + '</span>');
    } else {
        var heading_font_color = velovalidation.isColor($('input[name="kbproductsignup[heading_font_color]"]'));
        if (heading_font_color != true) {
            error = true;
            module_signup_error = true;
            $('input[name="kbproductsignup[heading_font_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
            $('input[name="kbproductsignup[heading_font_color]"]').closest('.form-group').after('<span class="error_message">' + heading_font_color + '</span>');
        }
    }
    var content_font_color_mand = velovalidation.checkMandatory($('input[name="kbproductsignup[content_font_color]"]'));
    if (content_font_color_mand != true) {
        error = true;
        module_signup_error = true;
        $('input[name="kbproductsignup[content_font_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
        $('input[name="kbproductsignup[content_font_color]"]').closest('.form-group').after('<span class="error_message">' + content_font_color_mand + '</span>');
    } else {
        var content_font_color = velovalidation.isColor($('input[name="kbproductsignup[content_font_color]"]'));
        if (content_font_color != true) {
            error = true;
            module_signup_error = true;
            $('input[name="kbproductsignup[content_font_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
            $('input[name="kbproductsignup[content_font_color]"]').closest('.form-group').after('<span class="error_message">' + content_font_color + '</span>');
        }
    }
    var block_border_color_mand = velovalidation.checkMandatory($('input[name="kbproductsignup[block_border_color]"]'));
    if (block_border_color_mand != true) {
        error = true;
        module_signup_error = true;
        $('input[name="kbproductsignup[block_border_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
        $('input[name="kbproductsignup[block_border_color]"]').closest('.form-group').after('<span class="error_message">' + block_border_color_mand + '</span>');
    } else {
        var block_border_color = velovalidation.isColor($('input[name="kbproductsignup[block_border_color]"]'));
        if (block_border_color != true) {
            error = true;
            module_signup_error = true;
            $('input[name="kbproductsignup[block_border_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
            $('input[name="kbproductsignup[block_border_color]"]').closest('.form-group').after('<span class="error_message">' + block_border_color + '</span>');
        }
    }

    var button_bk_color_mand = velovalidation.checkMandatory($('input[name="kbproductsignup[button_bk_color]"]'));
    if (button_bk_color_mand != true) {
        error = true;
        module_signup_error = true;
        $('input[name="kbproductsignup[button_bk_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
        $('input[name="kbproductsignup[button_bk_color]"]').closest('.form-group').after('<span class="error_message">' + button_bk_color_mand + '</span>');
    } else {
        var button_bk_color = velovalidation.isColor($('input[name="kbproductsignup[button_bk_color]"]'));
        if (button_bk_color != true) {
            error = true;
            module_signup_error = true;
            $('input[name="kbproductsignup[button_bk_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
            $('input[name="kbproductsignup[button_bk_color]"]').closest('.form-group').after('<span class="error_message">' + button_bk_color + '</span>');
        }
    }

    var button_font_color_mand = velovalidation.checkMandatory($('input[name="kbproductsignup[button_font_color]"]'));
    if (button_font_color_mand != true) {
        error = true;
        module_signup_error = true;
        $('input[name="kbproductsignup[button_font_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
        $('input[name="kbproductsignup[button_font_color]"]').closest('.form-group').after('<span class="error_message">' + button_font_color_mand + '</span>');
    } else {
        var button_font_color = velovalidation.isColor($('input[name="kbproductsignup[button_font_color]"]'));
        if (button_font_color != true) {
            error = true;
            module_signup_error = true;
            $('input[name="kbproductsignup[button_font_color]"]').closest('.form-group').find('.input-group').addClass('error_field');
            $('input[name="kbproductsignup[button_font_color]"]').closest('.form-group').after('<span class="error_message">' + button_font_color + '</span>');
        }
    }


    if (module_config_error == true) {
        $('#link-ModuleConfiguration i.icon-exclamation-circle').show();
    } else {
        $('#link-ModuleConfiguration i.icon-exclamation-circle').hide();
    }

    if (module_welcome_error == true) {
        $('#link-WelcomeNotificationSettings i.icon-exclamation-circle').show();
    } else {
        $('#link-WelcomeNotificationSettings i.icon-exclamation-circle').hide();
    }

    if (module_signup_error == true) {
        $('#link-ProductUpdateSignUp i.icon-exclamation-circle').show();
    } else {
        $('#link-ProductUpdateSignUp i.icon-exclamation-circle').hide();
    }

   if (error) {
        $('html, body').animate({
            scrollTop: $(".error_message").offset().top - 200
        }, 1000);
        return false;
    }

    $('.kbph_general_btn').attr('disabled', 'disabled');
    $('.kbph_welcome_setting_btn').attr('disabled', 'disabled');
    $('.kbph_product_sign_setting_btn').attr('disabled', 'disabled');

    if ($(button_ele).hasClass('kbph_general_btn')) {
        $('#welcome_notification_setting :input').not(':submit').clone().hide().appendTo('#module_config');
        $('#product_signup_setting :input').not(':submit').clone().hide().appendTo('#module_config');
        $('#module_config').append('<input type="hidden" name="kbConfigSubmit" value="1">');
        $('#module_config').submit();
    }
    if ($(button_ele).hasClass('kbph_welcome_setting_btn')) {
        $('#module_config :input').not(':submit').clone().hide().appendTo('#welcome_notification_setting');
        $('#product_signup_setting :input').not(':submit').clone().hide().appendTo('#welcome_notification_setting');
        $('#welcome_notification_setting').append('<input type="hidden" name="kbConfigSubmit" value="1">');
        $('#welcome_notification_setting').submit();
    }
    if ($(button_ele).hasClass('kbph_product_sign_setting_btn')) {
        $('#module_config :input').not(':submit').clone().hide().appendTo('#product_signup_setting');
        $('#welcome_notification_setting :input').not(':submit').clone().hide().appendTo('#product_signup_setting');
        $('#product_signup_setting').append('<input type="hidden" name="kbConfigSubmit" value="1">');
        $('#product_signup_setting').submit();
    }
}