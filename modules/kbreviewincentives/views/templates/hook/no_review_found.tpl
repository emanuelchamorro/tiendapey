<fieldset id="review_incentive_fieldset">
    <div class="velsofincentive_product_review_write">
                <div class="velsofincentive_message">{l s='Have you used this Product' mod='kbreviewincentives'}?</div>
        <div>
            <a target="_blank" href="{$write_new_review_link nofilter}" class="velsofincentive_button">{l s='Write a review' mod='kbreviewincentives'}</a>  {* Variable contains URL, can not escape this *}
        </div>
                
    </div>
        <div class="velsofincentive_review_list" id="velsofincentive_review_list">
            <div class="velsofincentive_review">
            {l s='No Reviews Found' mod='kbreviewincentives'}
            </div>
        </div>
</fieldset>

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
* @copyright 2017 Knowband
* @license   see file: LICENSE.txt
*
* Description
*
* Admin tpl file
*}