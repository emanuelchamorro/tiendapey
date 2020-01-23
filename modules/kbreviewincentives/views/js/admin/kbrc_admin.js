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
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 */
function getSelectedIds()
{
    if ($('#inputPackItems').val() === undefined)
        return '';
    var ids = '';
    if (typeof (id_product) != 'undefined')
        ids += id_product + ',';
    ids += $('#inputPackItems').val().replace(/\d*x/g, '').replace(/\-/g, ',');
    ids = ids.replace(/\,$/, '');
    return ids;
}
var method = (typeof method != 'undefined') ? method : '';
var persist = (typeof persist != 'undefined') ? persist : '';
$(document).ready(function () {
    
    if ($("input[name='kbreviewincentives[enable_gdpr_policy]']:checked").val() == 0) {
        $("input[name^='gdpr_policy_text_']").parents('.form-group').hide();
        $("input[name^='gdpr_policy_url_']").parents('.form-group').hide();
    }
    if ($("input[name='kbreviewincentives[enable_gdpr_policy]']:checked").val() == 1) {
       $("input[name^='gdpr_policy_text_']").parents('.form-group').show();
        $("input[name^='gdpr_policy_url_']").parents('.form-group').show();
    }

    $("input[name='kbreviewincentives[enable_gdpr_policy]']").click(function () {
        if ($("input[name='kbreviewincentives[enable_gdpr_policy]']:checked").val() == 0) {
            $("input[name^='gdpr_policy_text_']").parents('.form-group').hide();
            $("input[name^='gdpr_policy_url_']").parents('.form-group').hide();
        } else {
            $("input[name^='gdpr_policy_text_']").parents('.form-group').show();
            $("input[name^='gdpr_policy_url_']").parents('.form-group').show();
        }
    });
    
    
    
//    alert(persist);
//    if (persist == 1) {
//        $('#submitFiltervelsof_product_reviews').val('1');
//    }
    $('<input type="hidden" value="'+method+'" name="velsof_method_name" >').insertAfter('.pagination');
    $('[name="kbrc_report_option"]').on('change', function () {
        $('<input type="hidden" id="submitFiltervelsof_product_reviews" name="submitFiltervelsof_product_reviews" value="1">').insertAfter($('#kbrc_report_option'));
//        $.post( review_report_link, { submitFiltervelsof_product_reviews: "1"} );
        $('.kbrc_show_report').trigger('click');
    });
    $('#kbrc_audit_link').insertAfter('#fieldset_form .panel-heading');
    
    $('<div style="float:right;" ><a class="btn btn-warning pull-right" style="margin-left: 10px;" href="'+audit_log_link+'">' + audit_log + '</a></div><div style="clear:both;"></div>').appendTo('#fieldset_form .panel-heading');
//    $('<div style="padding-bottom:3%;float:right;" ><a class="btn btn-warning pull-right">' + sync_reviews + '</a></div>').appendTo('#fieldset_form .panel-heading');
    $("#product_name").prop("readonly", true);
    $("#author").prop("readonly", true);
    $("#incentive_amount").prop("readonly", true);
    $("#kb_review_author_email").prop("readonly", true);
    if ($('[name="current_status"]').val() == 3) {
        $('[name="current_status"]').closest('.form-group').show();
    } else {
        $('[name="current_status"]').closest('.form-group').hide();
    }
    $('#kbrc_product').autocomplete(controller_path, {
        delay: 100,
        minChars: 1,
        autoFill: true,
        max: 10,
        matchContains: true,
        mustMatch: true,
        scroll: false,
        cacheLength: 0,
        // param multipleSeparator:'||' ajouté à cause de bug dans lib autocomplete
        multipleSeparator: '||',
        formatItem: function(item) {
            return item[1] + ' - ' + item[0];
        },
        extraParams: {
            excludeIds: getSelectedIds(),
            excludeVirtuals: 1,
            exclude_packs: 1
        }
    }).result(function(event, item) {
        $('#product_id').val(item[1]);
        $('#kbrc_product').val(item[0]);
    });
    $('#velsof_products_review_incentive-empty-filters-alert').addClass('col-lg-10 col-md-9 velsof_product_cond');
    $('.velsof_products_review_incentive').closest('.panel').show();
    $('#velsof_review_incentive_category').hide();
//    $('#velsof_review_incentive_category').show();
//    $('#kbrc_general_settings').addClass('col-lg-10 col-md-9');
//    $('.velsof_reminder_profile').closest('.panel').removeClass('col-lg-12');
//    $('.velsof_reminder_profile').closest('.panel').addClass('col-lg-10 col-md-9');
//    $('.velsof_products_review_incentive').closest('.panel').removeClass('col-lg-12');
//    $('.velsof_products_review_incentive').closest('.panel').addClass('col-lg-10 col-md-9 velsof_product_cond');
//    $('.velsof_product_cond').css('float', 'right');
//    $('#velsof_reminder_profile_form').find('.panel').addClass('col-lg-10 col-md-9');
//    $('#velsof_reminder_profile_form').find('.tab-content').removeClass('panel');
    $('#velsof_products_review_incentive_form').find('.panel').addClass('col-lg-10 col-md-9 velsof_product_cond');
    $('#velsof_products_review_incentive_form').find('.tab-content').removeClass('panel');
    $('.velsof_products_review_incentive').closest('.panel').removeClass('col-lg-12');
    $('.velsof_products_review_incentive').closest('.panel').addClass('col-lg-10 col-md-9 velsof_product_cond');
        $('.velsof_product_cond').css('float', 'right');

//    $('.velsof_incentive_audit_log').closest('.panel').removeClass('col-lg-12');
//    $('.velsof_incentive_audit_log').closest('.panel').addClass('col-lg-10 col-md-9');
//    $('#velsof_show_report_form').find('.panel').addClass('col-lg-10 col-md-9');
//    $('#velsof_show_report_form').find('.tab-content').removeClass('panel');
        if ($('[id^="kbreviewincentives[incentive_enable]_on"]').is(':checked') === true) {
            $("[name='kbreviewincentives[incentive_amount]").closest('.form-group').show();
            $("[name='WITH_COUPON_EMAIL_SUBJECT_1").parents('.form-group').show();
            $("[name='WITH_COUPON_EMAIL_TEMP_1").parents('.form-group').show();
        }
        else {
            $("[name='kbreviewincentives[incentive_amount]").closest('.form-group').hide();
            $("[name='WITH_COUPON_EMAIL_SUBJECT_1").parents('.form-group').hide();
            $("[name='WITH_COUPON_EMAIL_TEMP_1").parents('.form-group').hide();
        }
    $('[name="kbreviewincentives[incentive_enable]"]').on('change', function () {
        if ($(this).val() == 1) {
            $("[name='kbreviewincentives[incentive_amount]").closest('.form-group').show();
            $("[name='WITH_COUPON_EMAIL_SUBJECT_1").parents('.form-group').show();
            $("[name='WITH_COUPON_EMAIL_TEMP_1").parents('.form-group').show();
        }
        else if ($(this).val() == 0) {
            $("[name='kbreviewincentives[incentive_amount]").closest('.form-group').hide();
            $("[name='WITH_COUPON_EMAIL_SUBJECT_1").parents('.form-group').hide();
            $("[name='WITH_COUPON_EMAIL_TEMP_1").parents('.form-group').hide();
        }
    });
        if ($('[name="kbreviewincentives[moderation]"]').val() == 1) {
            $("[name='REJECT_REVIEW_EMAIL_SUBJECT_1").parents('.form-group').hide();
            $("[name='REJECT_REVIEW_EMAIL_TEMP_1").parents('.form-group').hide();
        }
        else if ($('[name="kbreviewincentives[moderation]"]').val() == 2){
            $("[name='REJECT_REVIEW_EMAIL_SUBJECT_1").parents('.form-group').show();
            $("[name='REJECT_REVIEW_EMAIL_TEMP_1").parents('.form-group').show();
        }
        
        
        if ($('[id^="enable_order_create_reminder_on"]').is(':checked') === true) {
            $("[name='select_type[]").parents('.form-group').hide();
        }
        else{
            $("[name='select_type[]").parents('.form-group').show();
        }
        $('[name="enable_order_create_reminder"]').on('change', function () {
        if ($(this).val() == 1) {
            $("[name='select_type[]").parents('.form-group').hide();
        }
        else if ($(this).val() == 0) {
            $("[name='select_type[]").parents('.form-group').show();
        }
    });
    $('[name="kbreviewincentives[moderation]"]').on('change', function () {
        if ($(this).val() == 1) {
            $("[name='REJECT_REVIEW_EMAIL_SUBJECT_1").parents('.form-group').hide();
            $("[name='REJECT_REVIEW_EMAIL_TEMP_1").parents('.form-group').hide();
        }
        else if ($(this).val() == 2) {
            $("[name='REJECT_REVIEW_EMAIL_SUBJECT_1").parents('.form-group').show();
            $("[name='REJECT_REVIEW_EMAIL_TEMP_1").parents('.form-group').show();
        }
    });
/*Knowband validation start*/
$('.kbrc_general_settings_btn').click(function () {
        if (veloValidateConfigurationForms(this) == false) {
            return false;
        }
        $('.kbrc_general_settings_btn').attr('disabled', 'disabled');
        $('#kbrc_general_settings').submit();

    });
 /*Knowband validation end*/
 /*Knowband validation start*/
$('.velsof_banned_products_incentives').click(function () {
        if (veloValidateProductForms(this) == false) {
            return false;
        }
        $('.velsof_banned_products_incentives').attr('disabled', 'disabled');
        $('#velsof_products_review_incentive_form').submit();

    });
 /*Knowband validation end*/
 /*Knowband validation start*/
$('.velsof_reminder_profile_btn').click(function () {
        if (veloValidateReminderForms(this) == false) {
            return false;
        }
        $('.velsof_reminder_profile_btn').attr('disabled', 'disabled');
        $('#velsof_reminder_profile_form').submit();

    });
 /*Knowband validation end*/
 /*Knowband validation start*/
$('.velsof_review_incentives').click(function () {
        if (veloValidateReviewForms(this) == false) {
            return false;
        }
        $('.velsof_review_incentives').attr('disabled', 'disabled');
        $('#velsof_product_reviews_form').submit();

    });
 /*Knowband validation end*/
  /*Knowband validation start*/
  $('.kbrc_show_report').click(function () {
        $('.kbrc_show_report').attr('disabled', 'disabled');
        $('#velsof_show_report_form').submit();

    });
  /*Knowband validation end*/
});

function veloValidateProductForms() {
    var is_error = false;
    $('.kb_error_message').remove();
    var product_name_mand = velovalidation.checkMandatory($('input[name="kbrc_product"]'));
    if (product_name_mand !== true)
    {
        is_error = true;
        $('input[name="kbrc_product"]').addClass('kb_error_field');
        $('input[name="kbrc_product"]').after('<span class="kb_error_message">' + product_name_mand + '</span>');
    }
    if (is_error) {
        jQuery('html, body').animate({
            scrollTop: jQuery(".kb_error_message").offset().top - 200
        }, 1000);
        return false;
    }
}
function veloValidateReviewForms(button_ele)
{
    var is_error = false;
    $('.kb_error_message').remove();
    var product_name_mand = velovalidation.checkMandatory($('input[name="product_name"]'));
    if (product_name_mand !== true)
    {
        is_error = true;
        $('input[name="product_name"]').addClass('kb_error_field');
        $('input[name="product_name"]').after('<span class="kb_error_message">' + product_name_mand + '</span>');
    }
    var review_title_mand = velovalidation.checkMandatory($('input[name="review_title"]'));
    if (review_title_mand !== true) {
        is_error = true;
        $('input[name="review_title"]').addClass('kb_error_field');
        $('input[name="review_title"]').after('<span class="kb_error_message">' + review_title_mand + '</span>');
    }
    var helpful_votes_mand = velovalidation.checkMandatory($('input[name="helpful_votes"]'));
    var helpful_votes_num = velovalidation.isNumeric($('input[name="helpful_votes"]'));
    if (helpful_votes_mand !== true) {
        is_error = true;
        $('input[name="helpful_votes"]').addClass('kb_error_field');
        $('input[name="helpful_votes"]').after('<span class="kb_error_message">' + helpful_votes_mand + '</span>');
    } else if(helpful_votes_num !== true) {
        is_error = true;
        $('input[name="helpful_votes"]').addClass('kb_error_field');
        $('input[name="helpful_votes"]').after('<span class="kb_error_message">' + helpful_votes_num + '</span>');
    }
    var not_helpful_votes_mand = velovalidation.checkMandatory($('input[name="not_helpful_votes"]'));
    var not_helpful_votes_num = velovalidation.isNumeric($('input[name="not_helpful_votes"]'));
    if (not_helpful_votes_mand !== true) {
        is_error = true;
        $('input[name="not_helpful_votes"]').addClass('kb_error_field');
        $('input[name="not_helpful_votes"]').after('<span class="kb_error_message">' + not_helpful_votes_mand + '</span>');
    } else if (not_helpful_votes_num != true) {
        is_error = true;
        $('input[name="not_helpful_votes"]').addClass('kb_error_field');
        $('input[name="not_helpful_votes"]').after('<span class="kb_error_message">' + not_helpful_votes_num + '</span>');
    }
    var description_name_mand = velovalidation.checkMandatory($('textarea[name="description"]'), 1000, 25);
    if (description_name_mand !== true)
    {
        is_error = true;
        $('textarea[name="description"]').addClass('kb_error_field');
        $('textarea[name="description"]').after('<span class="kb_error_message">' + description_name_mand + '</span>');
    }
    var fix_amount_mand = velovalidation.checkAmount($('input[name="incentive_amount"]'));
    var fix_amount_mand_only = velovalidation.checkMandatory($('input[name="incentive_amount"]'));
    if (fix_amount_mand !== true)
    {
        is_error = true;
        $('input[name="incentive_amount"]').addClass('kb_error_field');
        $('input[name="incentive_amount"]').after('<span class="kb_error_message">' + fix_amount_mand + '</span>');
    } else if(fix_amount_mand_only !== true) {
        is_error = true;
        $('input[name="incentive_amount"]').addClass('kb_error_field');
        $('input[name="incentive_amount"]').after('<span class="kb_error_message">' + fix_amount_mand_only + '</span>');
    }
    
    if (is_error) {
        jQuery('html, body').animate({
            scrollTop: jQuery(".kb_error_message").offset().top - 200
        }, 1000);
        return false;
    }
    
}
function veloValidateReminderForms(button_ele)
{
    var is_error = false;
    $('.kb_error_message').remove();
   if ($('[id^="enable_order_create_reminder_off"]').is(':checked') === true) {
        var mutliselect_order_state = $("[name='select_type[]']").val();
        if ( mutliselect_order_state === null ) {
            is_error = true;
            $("[name='select_type[]']").addClass('kb_error_field');
            $("[name='select_type[]']").after($('<span class="kb_error_message">'+ error_msg_multiselect +'</span>'));
        }
    }
    var fix_amount_mand = velovalidation.isNumeric($('input[name="no_of_days_after"]'));
    var fix_amount_mand_only = velovalidation.checkMandatory($('input[name="no_of_days_after"]'));
    if (fix_amount_mand !== true)
    {
        is_error = true;
        $('input[name="no_of_days_after"]').addClass('kb_error_field');
        $('input[name="no_of_days_after"]').parent().after('<span class="kb_error_message">' + fix_amount_mand + '</span>');
    } else if(fix_amount_mand_only !== true) {
        is_error = true;
        $('input[name="no_of_days_after"]').addClass('kb_error_field');
        $('input[name="no_of_days_after"]').parent().after('<span class="kb_error_message">' + fix_amount_mand_only + '</span>');
    }
    /*Knowband validation start*/
    var first_err_flag_bottom = 0;
    $("input[name^=REMINDER_EMAIL_SUBJECT]").each(function () {
        var banner1 = $.trim($(this).val()).length;
        if (banner1 < 1) {
            if (first_err_flag_bottom == 0) {
                is_error = true;
                $('input[name="REMINDER_EMAIL_SUBJECT_' + lang_id + '"]').addClass('kb_error_field');
                $('input[name="REMINDER_EMAIL_SUBJECT_' + lang_id + '"]').parent().parent().parent().after('<span class="kb_error_message">' + all_lang_req + '</span>');
            }
            first_err_flag_bottom = 1;
        }
    });
    /*Knowband button validation start*/
     var first_err_flag_top = 0;
    $("[name^=REMINDER_EMAIL_TEMP]").each(function () {
        var text_err1 = tinyMCE.get($(this).attr("id")).getContent().trim();
        if (text_err1 == '') {

            if (first_err_flag_top == 0) {
                $('textarea[name^="REMINDER_EMAIL_TEMP_"]').addClass('kb_error_field');
                if (first_err_flag_top == 0) {


                    $('<p class="kb_error_message ">' + all_lang_req + '</p>').insertAfter($('textarea[name^="REMINDER_EMAIL_TEMP"]'));


                }
            }
            first_err_flag_top = 1;
            is_error = true;
        }
    });
    /*Knowband button validation end*/
    if (is_error) {
        jQuery('html, body').animate({
            scrollTop: jQuery(".kb_error_message").offset().top - 200
        }, 1000);
        return false;
    }
}
function veloValidateConfigurationForms(button_ele)
{
    var is_error = false;
    var is_error_1 = 0;
    var is_error2 = 0;
    var is_error3 = 0;
    var is_error1 = 0;
    $('.kb_error_message').remove();
    $('input[name^="gdpr_policy_text_"]').removeClass('kb_error_field');
    $('input[name^="gdpr_policy_url_"]').removeClass('kb_error_field');
    /*Knowband validation start*/
    
    if ($('[id^="kbreviewincentives[enable_gdpr_policy]_on"]').is(':checked') === true) {
        
        $('input[name^="gdpr_policy_text_"]').each(function () {
            var policy_text_err = velovalidation.checkMandatory($(this));
            if (policy_text_err != true) {
                is_error = true;
                if (is_error_1 < 1) {
                    $(this).parents('.col-lg-9').last().append('<span class="kb_error_message">' + policy_text_err + ' ' + check_for_all + '</span>');
                    is_error_1++;
                }
                $(this).addClass('kb_error_field');
            }
        });
        var url_value = '';
        $('input[name^="gdpr_policy_url_"]').each(function () {
            if ($(this).val().trim() != '') {
                url_value = 'hasValue';
            }
        });
        $('input[name^="gdpr_policy_url_"]').each(function () {
            if (url_value == 'hasValue') {
                var policy_url_mand = velovalidation.checkMandatory($(this));
                if (policy_url_mand != true) {
                    is_error = true;
                    if (is_error3 < 1) {
                        $(this).parents('.col-lg-9').last().append('<span class="kb_error_message">' + policy_url_mand + ' ' + check_for_all + '</span>');
                        is_error3++;
                    }
                    is_policy_url_error = true;
                    $(this).addClass('kb_error_field');
                }
            }
        });
        $('input[name^="gdpr_policy_url_"]').each(function () {
            if (!is_policy_url_error) {
                var url_valid_error = velovalidation.checkUrl($(this));
                if (url_valid_error != true) {
                    is_error = true;
                    if (is_error2 < 1) {
                        $(this).parents('.col-lg-9').last().append('<span class="kb_error_message">' + url_valid_error + ' ' + check_for_all + '</span>');
                        is_error2++;
                    }
                    $(this).addClass('kb_error_field');
                }
            }

        });
        var is_policy_url_error = false;
    }
    
    
    if ($('[id^="kbreviewincentives[incentive_enable]_on"]').is(':checked') === true) {
    var incentive_amount = $('input[name="kbreviewincentives[incentive_amount]"]').val();
    var fix_amount_mand = velovalidation.checkAmount($('input[name="kbreviewincentives[incentive_amount]"]'));
    var fix_amount_mand_only = velovalidation.checkMandatory($('input[name="kbreviewincentives[incentive_amount]"]'));
    if (fix_amount_mand !== true)
    {
        is_error = true;
        $('input[name="kbreviewincentives[incentive_amount]"]').addClass('kb_error_field');
        $('input[name="kbreviewincentives[incentive_amount]"]').after('<span class="kb_error_message">' + fix_amount_mand + '</span>');
    } else if(fix_amount_mand_only !== true) {
        is_error = true;
        $('input[name="kbreviewincentives[incentive_amount]"]').addClass('kb_error_field');
        $('input[name="kbreviewincentives[incentive_amount]"]').after('<span class="kb_error_message">' + fix_amount_mand_only + '</span>');
    } else if (incentive_amount == 0) {
        is_error = true;
        $('input[name="kbreviewincentives[incentive_amount]"]').addClass('kb_error_field');
        $('input[name="kbreviewincentives[incentive_amount]"]').after('<span class="kb_error_message">' + can_not_zero + '</span>');
    }
    }
    /*Knowband validation end*/
     if ($('[id^="kbreviewincentives[incentive_enable]_on"]').is(':checked') === true) {
    /*Knowband validation start*/
    var first_err_flag_bottom = 0;
    $("input[name^=WITH_COUPON_EMAIL_SUBJECT]").each(function () {
        var banner1 = $.trim($(this).val()).length;
        if (banner1 < 1) {
            if (first_err_flag_bottom == 0) {
                is_error = true;
                $('input[name="WITH_COUPON_EMAIL_SUBJECT_' + lang_id + '"]').addClass('kb_error_field');
                $('input[name="WITH_COUPON_EMAIL_SUBJECT_' + lang_id + '"]').parent().parent().parent().after('<span class="kb_error_message">' + all_lang_req + '</span>');
            }
            first_err_flag_bottom = 1;
        }
    });
    /*Knowband button validation start*/
     var first_err_flag_top = 0;
    $("[name^=WITH_COUPON_EMAIL_TEMP]").each(function () {
        var text_err1 = tinyMCE.get($(this).attr("id")).getContent().trim();
        if (text_err1 == '') {

            if (first_err_flag_top == 0) {
                $('textarea[name^="WITH_COUPON_EMAIL_TEMP_"]').addClass('kb_error_field');
                if (first_err_flag_top == 0) {


                    $('<p class="kb_error_message ">' + all_lang_req + '</p>').insertAfter($('textarea[name^="WITH_COUPON_EMAIL_TEMP"]'));


                }
            }
            first_err_flag_top = 1;
            is_error = true;
        }
    });
    /*Knowband button validation end*/
     }
    /*Knowband validation end*/
   
    /*Knowband validation start*/
    var first_err_flag_bottom = 0;
    $("input[name^=WITHOUT_COUPON_EMAIL_SUBJECT]").each(function () {
        var banner1 = $.trim($(this).val()).length;
        if (banner1 < 1) {
            if (first_err_flag_bottom == 0) {
                is_error = true;
                $('input[name="WITHOUT_COUPON_EMAIL_SUBJECT_' + lang_id + '"]').addClass('kb_error_field');
                $('input[name="WITHOUT_COUPON_EMAIL_SUBJECT_' + lang_id + '"]').parent().parent().parent().after('<span class="kb_error_message">' + all_lang_req + '</span>');
            }
            first_err_flag_bottom = 1;
        }
    });
    /*Knowband button validation start*/
     var first_err_flag_top = 0;
    $("[name^=WITHOUT_COUPON_EMAIL_TEMP]").each(function () {
        var text_err1 = tinyMCE.get($(this).attr("id")).getContent().trim();
        if (text_err1 == '') {

            if (first_err_flag_top == 0) {
                $('textarea[name^="WITHOUT_COUPON_EMAIL_TEMP_"]').addClass('kb_error_field');
                if (first_err_flag_top == 0) {


                    $('<p class="kb_error_message ">' + all_lang_req + '</p>').insertAfter($('textarea[name^="WITHOUT_COUPON_EMAIL_TEMP"]'));


                }
            }
            first_err_flag_top = 1;
            is_error = true;
        }
    });
    /*Knowband button validation end*/
    /*Knowband validation end*/
    
    /*Knowband validation start*/
    var first_err_flag_bottom = 0;
    $("input[name^=ADMIN_EMAIL_SUBJECT]").each(function () {
        var banner1 = $.trim($(this).val()).length;
        if (banner1 < 1) {
            if (first_err_flag_bottom == 0) {
                is_error = true;
                $('input[name="ADMIN_EMAIL_SUBJECT_' + lang_id + '"]').addClass('kb_error_field');
                $('input[name="ADMIN_EMAIL_SUBJECT_' + lang_id + '"]').parent().parent().parent().after('<span class="kb_error_message">' + all_lang_req + '</span>');
            }
            first_err_flag_bottom = 1;
        }
    });
    /*Knowband validation end*/
    
    
    if ($('[id^="kbreviewincentives[moderation]').val() == 2) {
        /*Knowband validation start*/
    var first_err_flag_bottom = 0;
    $("input[name^=REJECT_REVIEW_EMAIL_SUBJECT]").each(function () {
        var banner1 = $.trim($(this).val()).length;
        if (banner1 < 1) {
            if (first_err_flag_bottom == 0) {
                is_error = true;
                $('input[name="REJECT_REVIEW_EMAIL_SUBJECT_' + lang_id + '"]').addClass('kb_error_field');
                $('input[name="REJECT_REVIEW_EMAIL_SUBJECT_' + lang_id + '"]').parent().parent().parent().after('<span class="kb_error_message">' + all_lang_req + '</span>');
            }
            first_err_flag_bottom = 1;
        }
    });
    /*Knowband validation end*/
    /*Knowband button validation start*/
     var first_err_flag_top = 0;
    $("[name^=REJECT_REVIEW_EMAIL_TEMP]").each(function () {
        var text_err1 = tinyMCE.get($(this).attr("id")).getContent().trim();
        if (text_err1 == '') {

            if (first_err_flag_top == 0) {
                $('textarea[name^="REJECT_REVIEW_EMAIL_TEMP_"]').addClass('kb_error_field');
                if (first_err_flag_top == 0) {


                    $('<p class="kb_error_message ">' + all_lang_req + '</p>').insertAfter($('textarea[name^="REJECT_REVIEW_EMAIL_TEMP"]'));


                }
            }
            first_err_flag_top = 1;
            is_error = true;
        }
    });
    }
    /*Knowband button validation end*/
    /*Knowband button validation start*/
     var first_err_flag_top = 0;
    $("[name^=ADMIN_EMAIL_TEMP]").each(function () {
        var text_err1 = tinyMCE.get($(this).attr("id")).getContent().trim();
        if (text_err1 == '') {

            if (first_err_flag_top == 0) {
                $('textarea[name^="ADMIN_EMAIL_TEMP_"]').addClass('kb_error_field');
                if (first_err_flag_top == 0) {


                    $('<p class="kb_error_message ">' + all_lang_req + '</p>').insertAfter($('textarea[name^="ADMIN_EMAIL_TEMP"]'));


                }
            }
            first_err_flag_top = 1;
            is_error = true;
        }
    });
    /*Knowband button validation end*/
    if (is_error) {
        $('html, body').animate({
            scrollTop: $(".kb_error_message").offset().top - 200
        }, 1000);
        return false;
    }
}
function switchModuleTabs(ele, sort_order)
{
    $('.list-group-item').removeClass('active');
    $(ele).addClass('active');
    if (sort_order == 1) {
        $('.velsof_products_review_incentive').closest('.panel').show();
        $('#velsof_review_incentive_category').hide();
        $('.velsof_product_cond').show();
    } else if (sort_order == 2) {
        $('#velsof_review_incentive_category').show();
        $('.velsof_products_review_incentive').closest('.panel').hide();
        $('.velsof_product_cond').hide();
    }
}