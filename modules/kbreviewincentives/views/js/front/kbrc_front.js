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
function countWord() {
    var word_count = $('textarea[name="review_description"]').val().length;
//    count = parseInt(count) + parseInt(word_count);
//    alert(count);
    $('#kbrc_word_count').html(word_count);
}

var read_review = (typeof read_review === 'undefined') ? '' : read_review;
$(document).ready(function () {
    if (read_review != '') {
        $('html, body').animate({
            scrollTop: $("#review_incentive_fieldset").offset().top
        }, 500);
    }
    $('.star').on('click', function () {
        var value = $(this).prev().children().children().val();
        for (var i = value; i >= 1; i--) {
            $('.star-' + i + '').addClass('dark');
        }
        for (var j = Number(value) + 1; j <= 5; j++) {
            $('.star-' + j + '').removeClass('dark');
        }
//        alert(value);
    });

    /*Knowband validation start*/
    $('#kbrc_addreview_button').click(function () {
        if (veloValidateAddReviewForms(this) == false) {
            return false;
        }
        if (!$('input[name="kb_gdpr_tnc_accept"]').is(':checked') && $('input[name="kb_gdpr_tnc_accept"]').length) {
            if (typeof msg_tnc_request != 'undefined')
            {
                if (!!$.prototype.fancybox) {
                    $.fancybox.open([
                        {
                            type: 'inline',
                            autoScale: true,
                            minHeight: 30,
                            content: '<p class="fancybox-error">' + msg_tnc_request + '</p>'
                        }],
                            {
                                padding: 0
                            });
                } else {
                    alert(msg_tnc_request);
                }
            }
            return false;
        }
        $.ajax({
            url: front_cont_link,
            type: 'post',
            data: $("#velsof_add_review_form").serialize() + '&ratings=' + $("input[name='star']:checked").val(),
            dataType: 'json',
            beforeSend: function () {
//                    $('#kbrc_addreview_button').attr('disabled', 'disabled');
                $('.kbrc_msg').remove();
                $("#kbrc_addreview_button").attr("disabled", true);
                $("#velsof_loader").show();
            },
            success: function (json) {
                if (json['res'] == true) {
                    $('<span class="alert alert-success kbrc_msg">' + json['msg'] + '</span><br/><br><br>').insertBefore($('#kbrc_success_msg'));
                    if (json['link'] != '') {
                        $('<a href=' + json['link'] + ' class="btn btn-default">' + Check_review + '</a>').insertBefore($('#kbrc_success_msg'));
                    }
                } else {
                    $('<span class="alert alert-danger kbrc_msg">' + json['msg'] + '</span>').insertBefore('#kbrc_success_msg');
                }
            },
            complete: function () {
                $('#kbrc_addreview_button').removeAttr('disabled');
                $('#review_incentive_fieldset').hide();
                $("#velsof_loader").hide();
                $('.star').prop('checked', false);
                $('.star').removeClass('dark');
                $('#kbrc_info').hide();
            },
        });

//        $('#velsof_add_review_form').submit();
        return false;
    });
    /*Knowband validation end*/

// $('.kbrc_post_review').click(function () {
//   $('#velsofincentive_review_list').trigger('click');
//    });
    $(".kbrc_post_review").click(function () {
        $('html, body').animate({
            scrollTop: $("#review_incentive_fieldset").offset().top
        }, 500);
    });
});

function veloValidateAddReviewForms() {
    var is_error = false;
    $('.kb_error_message').remove();
    $('input[name="review_title"]').removeClass('kb_error_field');
    $('textarea[name="review_description"]').removeClass('kb_error_field');
    $('input[name="review_name"]').removeClass('kb_error_field');
    $('input[name="review_email"]').removeClass('kb_error_field');
    $('input[name="review_email"]').removeClass('kb_error_field');
       
    var review_title_mand = velovalidation.checkMandatory($('input[name="review_title"]'));
    if (review_title_mand !== true)
    {
        is_error = true;
        $('input[name="review_title"]').addClass('kb_error_field');
        $('input[name="review_title"]').parent().after('<span class="kb_error_message">' + review_title_mand + '</span>');
    }
    var review_description_mand = velovalidation.checkMandatory($('textarea[name="review_description"]'), 1000, 25);
    var review_description_tag = velovalidation.checkHtmlTags($('textarea[name="review_description"]'));
    if (review_description_mand !== true)
    {
        is_error = true;
        $('textarea[name="review_description"]').addClass('kb_error_field');
        $('textarea[name="review_description"]').after('<span class="kb_error_message">' + review_description_mand + '</span>');
    } else if (review_description_tag !== true) {
        is_error = true;
        $('textarea[name="review_description"]').addClass('kb_error_field');
        $('textarea[name="review_description"]').after('<span class="kb_error_message">' + review_description_tag + '</span>');
    }
    var review_name_mand = velovalidation.checkMandatory($('input[name="review_name"]'));
    if (review_name_mand !== true)
    {
        is_error = true;
        $('input[name="review_name"]').addClass('kb_error_field');
        $('input[name="review_name"]').parent().after('<span class="kb_error_message">' + review_name_mand + '</span>');
    }
    var review_email = velovalidation.checkEmail($('input[name="review_email"]'));
    var review_email_mand = velovalidation.checkMandatory($('input[name="review_email"]'));
    if (review_email_mand !== true)
    {
        is_error = true;
        $('input[name="review_email"]').addClass('kb_error_field');
        $('input[name="review_email"]').parent().after('<span class="kb_error_message">' + review_email_mand + '</span>');
    } else if (review_email !== true) {
        is_error = true;
        $('input[name="review_email"]').addClass('kb_error_field');
        $('input[name="review_email"]').parent().after('<span class="kb_error_message">' + review_email + '</span>');
    }
    if ($("input[name='star']:checked").val() == undefined) {
        is_error = true;
        $('.velsof_star_ratings').after('<span class="kb_error_message">' + rating_msg + '</span>');
    }
    if (is_error) {
        return false;
    }
}
function vote(review_id, vote, is_logged) {
    if (is_logged != 0) {
        $.ajax({
            url: product_cont_path,
            type: 'post',
            data: 'ajax=true&vote=' + vote + '&review_id=' + review_id + '&customer_id=' + is_logged,
            dataType: 'json',
            beforeSend: function () {
                $('.kb_error_message').remove();
                $('.kb_success_message').remove();
                $('#velsofincentive_review_list').addClass('kbrc_overlay');
            },
            success: function (json) {
                if (json['success'] == true) {
                    $('#velsofincentive_vote_helpful_review_' + json['review_id'] + '').after('<span class="kb_success_message">' + json['msg'] + '</span>');
                    $('#velsofincentive_vote_helpful_review_' + json['review_id'] + ' #kbrc_helpful').html(json['yes']);
                    $('#velsofincentive_vote_helpful_review_' + json['review_id'] + ' #kbrc_helpful_tot').html(json['sum']);
                } else if (json['success'] == false) {
                    $('#velsofincentive_vote_helpful_review_' + json['review_id'] + '').after('<span class="kb_error_message">' + json['msg'] + '</span>');
                } else {
                    $('#velsofincentive_vote_helpful_review_' + json['review_id'] + '').after('<span class="kb_error_message">' + json['msg'] + '</span>');
                }
            },
            complete: function () {
                $('#velsofincentive_review_list').removeClass('kbrc_overlay');
            },
        });

    } else if (is_logged == 0) {
        $('.kb_error_message').remove();
        $('#velsofincentive_vote_helpful_review_' + review_id + '').after('<span class="kb_error_message">' + vote_msg_log_in + '</span>');
    }

}