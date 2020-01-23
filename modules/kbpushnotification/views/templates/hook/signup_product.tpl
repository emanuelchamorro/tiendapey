<div class="kb-push-signup-block" style="display: none;">
    <div class="kb-push-signup-toggle-block">
        <a class="kb-push-signup-heading">
            <span id="kb-push-heading-content">
            {if isset($product_signup['kbsignup_price_heading'][$id_lang])}
                {$product_signup['kbsignup_price_heading'][$id_lang]|escape:'htmlall':'UTF-8'}
            {/if}
            </span>
            <span class="kb-push-toggle-btn">
                <i class="material-icons">remove</i>
            </span>
        </a>
    </div>
    <div class="kb-push-signup-content">
        <div class="kb-push-signup-image" >
            <img src="{$product_img|escape:'quotes':'UTF-8'}">
            <input type="hidden" id="product_alert_reg_id" name="product_alert_reg_id" value="{$reg_id|escape:'htmlall':'UTF-8'}">
            <input type="hidden" name="id_product" value="{$id_product|escape:'htmlall':'UTF-8'}">
            <input type="hidden" name="actual_price" value="{$product_price|escape:'htmlall':'UTF-8'}">
{*            <input type="hidden" name="combination_id" value="{$product_default_combination_id}">*}
            <input type="hidden" id="product_subscribe_type" name="product_subscribe_type" value="">
            <input type="hidden" name="product_price_wt_sign" value="{$product_price_wt_sign|escape:'htmlall':'UTF-8'}">
            <input type="hidden" name="kb_push_price_info" value='{$price_info|escape:'htmlall':'UTF-8'}'>
            <input type="hidden" name="kb_push_stock_info" value='{$stock_info|escape:'htmlall':'UTF-8'}'>
        </div>
        <div class="kb-push-signup-content-info">
            <div style="font-weight: bold; padding: 0 5px;" id="kb-push-content-data">
                <p>{$product_price_message|nl2br|escape:'htmlall':'UTF-8'}</p>
            </div>

            <div class="kbsuccess" style="display: none;"></div>
            <div class="kberror" style="display: none;"></div>
        </div>
        <button class="kb-push-signup-button btn" type="button" name="kbsubmitpushsignupbtn" onclick="return submitkbpushsignup();">
            <i class="material-icons">notifications</i>
            {if isset($product_signup['kbsignup_button_text'][$id_lang])}
                {$product_signup['kbsignup_button_text'][$id_lang]|escape:'htmlall':'UTF-8'}
            {/if}
        </button>

        <div class="kb_push_loader" style="display:none;">
            <img src="{$loader|escape:'quotes':'UTF-8'}">
        </div>

    </div>

</div>
<style>
    .kb-push-signup-toggle-block {
        {if isset($product_signup['heading_bk_color'])}
            background:{$product_signup['heading_bk_color']|escape:'htmlall':'UTF-8'} !important;
        {/if}
    }
    .kb-push-signup-heading {
        {if isset($product_signup['heading_font_color'])}
            color:{$product_signup['heading_font_color']|escape:'htmlall':'UTF-8'} !important;
        {/if}

    }
    .kb-push-toggle-btn {
        {if isset($product_signup['heading_font_color'])}
            background:{$product_signup['heading_font_color']|escape:'htmlall':'UTF-8'} !important;
        {/if}
        {if isset($product_signup['heading_bk_color'])}
            color:{$product_signup['heading_bk_color']|escape:'htmlall':'UTF-8'} !important;
        {/if}
    }
    .kb-push-signup-button {
        {if isset($product_signup['button_bk_color'])}
            background:{$product_signup['button_bk_color']|escape:'htmlall':'UTF-8'} !important;
        {/if}
        {if isset($product_signup['button_font_color'])}
            color:{$product_signup['button_font_color']|escape:'htmlall':'UTF-8'} !important;
        {/if}
    }
    .kb-push-signup-content {
        {if isset($product_signup['content_bk_color'])}
            background:{$product_signup['content_bk_color']|escape:'htmlall':'UTF-8'} !important;
        {/if}
        {if isset($product_signup['block_border_color'])}
            border-left-color: {$product_signup['block_border_color']|escape:'htmlall':'UTF-8'} !important;
            border-right-color: {$product_signup['block_border_color']|escape:'htmlall':'UTF-8'} !important;
        {/if}

    }
    .kb-push-signup-content-info {
        {if isset($product_signup['content_font_color'])}
            color:{$product_signup['content_font_color']|escape:'htmlall':'UTF-8'} !important;
        {/if}
    }
    .kb-push-signup-content-info p {
        {if isset($product_signup['content_font_color'])}
            color:{$product_signup['content_font_color']|escape:'htmlall':'UTF-8'} !important;
        {/if}
    }
</style>
<script>
{*    var kb_registered_type = "{$registered_type}";*}
    var productPriceTaxIncluded= {($product->getPriceWithoutReduct(false)|default:'null' - $product->ecotax * (1 + $ecotax_rate / 100))|floatval}

    {if isset($group_reduction)}
    var groupReduction = "{$group_reduction|floatval}";
    {else}
        var groupReduction = false;
    {/if}
    
    var kb_push_signup_url = "{$kb_push_signup_url|escape:'quotes':'UTF-8'}";
</script>
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2018 Knowband
* @license   see file: LICENSE.txt
*}