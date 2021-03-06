<ul class="kb-form-list">
    <li class="kb-form-fwidth">
        <div class="kb-form-field-block label-inside">
            <span class="kblabel">{l s='Account Owner' mod='kbmarketplace'}<em>*</em></span>
            <input type="hidden" name="payment_info[bankwire][owner_name][label]" value="Owner Name">
            <input data-tab="paymentinfo" type="text" class="kb-inpfield required"  name="payment_info[bankwire][owner_name][value]" value="{$owner_name|escape:'htmlall':'UTF-8'}">
        </div>
    </li>
    <li class="kb-form-fwidth">
        <div class="kb-form-field-block label-inside">
            <span class="kblabel">{l s='DNI/CUIT/CUIL' mod='kbmarketplace'}<em>*</em></span>
            <input type="hidden" name="payment_info[bankwire][add_info][label]" value="Additional Information">
            <input type="text" data-tab="paymentinfo" class="kb-inpfield required"  name="payment_info[bankwire][add_info][value]" value="{$add_info|escape:'htmlall':'UTF-8'}" />
        </div>
    </li>
    <li class="kb-form-fwidth">
        <div class="kb-form-field-block label-inside">
            <span class="kblabel">{l s='Banco' mod='kbmarketplace'}<em>*</em></span>
            <input type="hidden" name="payment_info[bankwire][details][label]" value="Details">
            <input data-tab="paymentinfo" type="text" class="kb-inpfield required"  name="payment_info[bankwire][details][value]" value="{$details|escape:'htmlall':'UTF-8'}">
            {*<p class="form-inp-help">{l s='Such as bank branch, IBAN number, BIC, etc.' mod='kbmarketplace'}</p>*}
        </div>
    </li>
    <li class="kb-form-fwidth">
        <div class="kb-form-field-block label-inside">
            <span class="kblabel">{l s='CBU' mod='kbmarketplace'}<em>*</em></span>
            <input type="hidden" name="payment_info[bankwire][address][label]" value="Bank Address">
            <input type="text" data-tab="paymentinfo" rows="5" class="kb-inpfield required"  name="payment_info[bankwire][address][value]" value="{$address|escape:'htmlall':'UTF-8'}"/>
        </div>
    </li>
</ul>
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