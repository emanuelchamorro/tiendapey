{capture name=path}
    {l s='Write a Review' mod='kbreviewincentives'}
{/capture}
{extends file=$layout}
{block name = 'content'}
    <script>
    var front_cont_link = "{$front_cont_link nofilter}"; {* Variables contains URL, can not escape this *}
    var rating_msg = "{l s='Ratings are mandatory.' mod='kbreviewincentives'}";
    var Check_review = "{l s='Check Your Review Here!.' mod='kbreviewincentives'}";
    document.addEventListener("DOMContentLoaded", function (event) {
        velovalidation.setErrorLanguage({
            empty_field: "{l s='Field cannot be empty.' mod='kbreviewincentives'}",
            empty_email: "{l s='Please enter Email.' mod='kbreviewincentives'}",
            validate_email: "{l s='Please enter a valid Email.' mod='kbreviewincentives'}",
            script: "{l s='Script tags are not allowed.' mod='kbreviewincentives'}",
            style: "{l s='Style tags are not allowed.' mod='kbreviewincentives'}",
            iframe: "{l s='Iframe tags are not allowed.' mod='kbreviewincentives'}",
            html_tags: "{l s='Field should not contain HTML tags.' mod='kbreviewincentives'}",
            number_pos: "{l s='You can enter only positive numbers.' mod='kbreviewincentives'}",
            maxchar_field: "{l s='Field cannot be greater than 1000 characters.' mod='kbreviewincentives'}",
            minchar_field: "{l s='Field cannot be less than 25 character(s).' mod='kbreviewincentives'}"
        });
    });
    </script>
    <div id="kbrc_success_msg"></div>
    <div id="review_incentive_fieldset">
        <div class="velsofincentive_write_review_form  card card-block">
            <form id="velsof_add_review_form" action="{$link->getModuleLink('kbreviewincentives', 'kbwritenewreview', [], true)|escape:'html'}" method="post" enctype="multipart/form-data">
                <div class="content">
                    <div class="velsofincentive_write_review_page_header">
                        <h2>¡Queremos escucharte!</h2>
                        <div class="flex boxDataReview">
                            <div class="imgReview">
                                <img src="{$product_image nofilter}" alt="{l s='Product Image' mod='kbreviewincentives'}" id="kbrc_pro_img"/>{* Variables contains URL, can not escape this *}
                            </div>
                            <div class="dataReview">
                                <p>
                                    Dejanos tu opinión sobre:
                                    <span class="productName">{$product_name}</span>
                                </p>
                            </div>
                        </div>
                        <div class="velsofincentive_clear"></div>
                    </div>
                    <div class="formReview">
                        <p class="text-center">Calificá el producto<span style="color:red;">*</span></p>
                        <div class='velsof_star_ratings'>
                            <div class="stars">
                                <input class="star star-5" id="star-5" type="radio" name="star" value="5"/>
                                <label class="star star-5" for="star-5"></label>
                                <input class="star star-4" id="star-4" type="radio" name="star" value="4"/>
                                <label class="star star-4" for="star-4"></label>
                                <input class="star star-3" id="star-3" type="radio" name="star" value="3"/>
                                <label class="star star-3" for="star-3"></label>
                                <input class="star star-2" id="star-2" type="radio" name="star" value="2"/>
                                <label class="star star-2" for="star-2"></label>
                                <input class="star star-1" id="star-1" type="radio" name="star" value="1"/>
                                <label class="star star-1" for="star-1"></label>
                            </div>
                        </div>
                        <div class="flex">
                            <label class="col-sm-6 col-xs-6 col-lg-6">
                                <span class="title">Nombre<span style="color:red;">*</span></span>
                                <input id="display_name" name="review_name" class="velsofincentive_user-input-field velsofincentive_input" type="text" value="" size="40">
                            </label>
                            <label class="col-sm-6 col-xs-6 col-lg-6">
                                <span class="title">Email<span style="color:red;">*</span>{l s='(Your Email will not be shown on the review.)' mod='kbreviewincentives'}</span>
                                <input id="display_email" name="review_email" class="velsofincentive_user-input-field velsofincentive_input" type="text" value="{$customer_email}" size="100">
                            </label>
                        </div>
                        <label>
                            <span class="title">Título de tu opinión<span style="color:red;">*</span></span>
                            <input id="review_title" name="review_title" class="txt user-input-field fk-input" type="text" value="" size="40">
                        </label>
                        <label>
                            <span class="title">Tu opinión<span style="color:red;">*</span></span>
                            <textarea id="review_text" maxlength="1000" name="review_description" class="velsofincentive_user-input-field velsofincentive_input" cols="" rows="" onkeyup="countWord()" ></textarea>
                            <div id="review_text_help_message" class="velsofincentive_help_message"><b>{l s='The review should have at least 25 characters.' mod='kbreviewincentives'}</b><span id="kbrc_word_count" style="float:right">0</span><span id="kbrc_word_count_text" style="float:right">{l s='Caracteres: ' mod='kbreviewincentives'} &nbsp;</span></div>
                        </label>
                        <div class="flex">
                            <button type="submit" value="1" class="velsofincentive_button" id="kbrc_addreview_button" name='kb_submit_review'>{l s='Submit' mod='kbreviewincentives'}</button>
                            <a href="{$product_link nofilter}"><input type="button" value="{l s='Cancel' mod='kbreviewincentives'}" class="velsofincentive_button"></a>{* Variables contains URL, can not escape this *}
                            <img src='{$module_path nofilter}show_loader.gif' id='velsof_loader'/>{* Variables contains URL, can not escape this *}
                        </div>
                    </div>
                    {*<table class="velsofincentive_write_review_table_form">
                        <tbody><tr>
                                <td class="lbl velsofincentive_boldtext">
                                    <label class="velsofincentive_write_review_label" for="review_title">
                                        <div class="velsofincentive_review_step1 velsofincentive_review_steps"></div>
                                        {l s='Review Title' mod='kbreviewincentives'}:<span style="color:red;">*</span>
                                    </label>
                                </td>
                                <td class="velsofincentive_user_input">
                                    <div  class="kbrc_inline">
                                        <span >
                                            <input id="review_title" name="review_title" class="txt user-input-field fk-input" type="text" value="" size="40">
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="lbl velsofincentive_boldtext">
                                    <label class="velsofincentive_write_review_label" for="review_text">
                                        <div class="velsofincentive_review_step2 velsofincentive_review_steps"></div>
                                        {l s='Your Review' mod='kbreviewincentives'}:<span style="color:red;">*</span>
                                    </label>
                                </td>
                                <td class="velsofincentive_user_input">
                                    <div class="velsofincentive_review_text-holder velsofincentive_position-relative">
                                        <div class="velsofincentive_review_text_message">
                                            <div class="velsofincentive_review_description_text">
                                                <strong>{l s='Please do not include' mod='kbreviewincentives'}:</strong>
                                                {l s='HTML, references to other retailers, pricing, personal information, any profane, inflammatory or copyrighted comments, or any copied content.' mod='kbreviewincentives'}
                                            </div>
                                            <textarea id="review_text" maxlength="1000" name="review_description" class="velsofincentive_user-input-field velsofincentive_input" cols="" rows="" onkeyup="countWord()" ></textarea>
                                            <div id="review_text_help_message" class="velsofincentive_help_message"><b>{l s='The review should have at least 25 characters.' mod='kbreviewincentives'}</b><span id="kbrc_word_count" style="float:right">0</span><span id="kbrc_word_count_text" style="float:right">{l s='WORD COUNT' mod='kbreviewincentives'} &nbsp;</span></div>
                                        </div>
                                    </div></td>
                            </tr>
                            <tr>
                                <td class="lbl velsofincentive_boldtext">
                                    <label class="velsofincentive_write_review_label">
                                        <div class="velsofincentive_review_step3 velsofincentive_review_steps"></div>
                                        {l s='Rating' mod='kbreviewincentives'}:<span style="color:red;">*</span>
                                    </label>
                                </td>
                                <td>
                                    <div class='velsof_star_ratings'>
                                        <div class="stars">
                                            <input class="star star-5" id="star-5" type="radio" name="star" value="5"/>
                                            <label class="star star-5" for="star-5"></label>
                                            <input class="star star-4" id="star-4" type="radio" name="star" value="4"/>
                                            <label class="star star-4" for="star-4"></label>
                                            <input class="star star-3" id="star-3" type="radio" name="star" value="3"/>
                                            <label class="star star-3" for="star-3"></label>
                                            <input class="star star-2" id="star-2" type="radio" name="star" value="2"/>
                                            <label class="star star-2" for="star-2"></label>
                                            <input class="star star-1" id="star-1" type="radio" name="star" value="1"/>
                                            <label class="star star-1" for="star-1"></label>

                                        </div>
                                  </div>


                                </td>
                            </tr>
                            <tr>
                                <td class="lbl velsofincentive_boldtext">
                                    <label class="velsofincentive_write_review_label" for="display_name">
                                        <div class="velsofincentive_review_step4 velsofincentive_review_steps"></div>
                                        {l s='Name' mod='kbreviewincentives'}:<span style="color:red;">*</span>
                                    </label>
                                </td>
                                <td>
                                    <div  class="kbrc_inline">
                                        <span >
                                            <input id="display_name" name="review_name" class="velsofincentive_user-input-field velsofincentive_input" type="text" value="" size="40">
                                        </span>
                                    </div>
                                    <div class="velsofincentive_help_message"> {l s='(This name will appear on your review. You can put your nick name or any name you like.)' mod='kbreviewincentives'} </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="lbl velsofincentive_boldtext">
                                    <label class="velsofincentive_write_review_label" for="display_email">
                                        <div class="velsofincentive_review_step5 velsofincentive_review_steps"></div>
                                        {l s='Email' mod='kbreviewincentives'}:<span style="color:red;">*</span>
                                    </label>
                                </td>
                                <td>
                                    <div  class="kbrc_inline">
                                        <span >
                                            <input id="display_email" name="review_email" class="velsofincentive_user-input-field velsofincentive_input" type="text" value="{$customer_email}" size="100">
                                        </span>
                                    </div>

                                    <div class="velsofincentive_help_message"> {l s='(Your Email will not be shown on the review.)' mod='kbreviewincentives'}:</div>
                                </td>
                            </tr>
                            {if $enable_gdpr_policy && !empty($gdpr_policy_text)}
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="condition-label">
                                            <label class="js-terms" for="kb_gdpr_tnc_accept">
                                                <div class="pull-xs-left">
                                                    <span class="custom-checkbox">
                                                        <input type="checkbox" name="kb_gdpr_tnc_accept" class="ps-shown-by-js" id="kb_gdpr_tnc_accept" value="1">
                                                        <span><i class="material-icons checkbox-checked">&#xE5CA;</i></span>
                                                        
                                                    </span>
                                                    {$gdpr_policy_text|escape:'htmlall':'UTF-8'} {if !empty($gdpr_policy_url)}<a href="{$gdpr_policy_url|escape:'quotes':'UTF-8'}" target="_blank">{l s='(Read the Terms of Service)' mod='kbreviewincentives'}</a>{/if}
                                                </div>
                                            </label>
                                                <style>
                                                    .condition-label #kb_gdpr_tnc_accept{
                                                        font-size: initial;
                                                    }
                                                    .custom-checkbox input[type=checkbox]+span {
                                                            border: 2px solid #414141 !important;
                                                    }
                                                </style>
                                        </div>
                                    </td>
                                </tr>
                            {/if}
                            <tr>
                                <td>
                                </td>
                                <td>
                                    <button type="submit" value="1" class="velsofincentive_button" id="kbrc_addreview_button" name='kb_submit_review'>{l s='Submit' mod='kbreviewincentives'}</button>
                                    <a href="{$product_link nofilter}"><input type="button" value="{l s='Cancel' mod='kbreviewincentives'}" class="velsofincentive_button"></a>
                                    <img src='{$module_path nofilter}show_loader.gif' id='velsof_loader'/>
                                </td>
                            </tr>
                        </tbody></table>*}
                    <input type="hidden" name="review_submit" value="1"><span></span>
                    <input type="hidden" name="review_product_id" value="{$product_id}"><span></span>
                </div>
            </form>
        </div>

    </div>
    {* {if $customer_id eq 0}
        <div>
            <span class="alert alert-info" id='kbrc_info'>{l s='You may get amazing amount of incentive for this review if you get registered yourself to this shop.' mod='kbreviewincentives'} <a href="{$link->getPageLink('authentication') nofilter}"><strong>{l s='Create Account' mod='kbreviewincentives'}</strong></a></span>
        </div>
    {/if} *}

    <script>
            var msg_tnc_request = "{l s='Please accept the terms of service and privacy policy before proceedings.' mod='kbreviewincentives'}";
    </script>
{/block}
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2016 knowband
* @license   see file: LICENSE.txt
*}