{if isset($notification_type)}
    <div class="kb-admin-shortcodes alert-info" style="display: none;">
        <h4 style="margin-bottom: 0;padding: 6px;">{l s='Shortcodes can be used in Notification Message' mod='kbpushnotification'}</h4>
        <table class="table table-fill">
            <tbody>
                <tr></tr>
                {if $notification_type == 'price'}
                    <tr>
                        <td style="width: 30%">
                            <b>{'{{kb_item_name}}'}{*escape not required*}</b>
                        </td>
                        <td>{l s='Product Name' mod='kbpushnotification'}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">
                            <b>{'{{kb_item_current_price}}'}{*escape not required*}</b>
                        </td>
                        <td>{l s='Current price' mod='kbpushnotification'}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">
                            <b>{'{{kb_item_old_price}}'}{*escape not required*}</b>
                        </td>
                        <td>{l s='Before Price' mod='kbpushnotification'}</td>
                    </tr>
                {elseif $notification_type == 'cart'}
                    <tr>
                        <td style="width: 30%">
                            <b>{'{{kb_cart_amount}}'}{*escape not required*}</b>
                        </td>
                        <td>{l s='Cart Amount' mod='kbpushnotification'}</td>
                    </tr>
                {elseif $notification_type == 'stock'}
                    <tr>
                        <td style="width: 30%">
                            <b>{'{{kb_item_name}}'}{*escape not required*}</b>
                        </td>
                        <td>{l s='Product Name' mod='kbpushnotification'}</td>
                    </tr>
                     <tr>
                        <td style="width: 30%">
                            <b>{'{{kb_item_current_price}}'}{*escape not required*}</b>
                        </td>
                        <td>{l s='Current price' mod='kbpushnotification'}</td>
                    </tr>
                {elseif $notification_type == 'orderstatus'}
                    <tr>
                        <td style="width: 30%">
                            <b>{'{{kb_order_reference}}'}{*escape not required*}</b>
                        </td>
                        <td>{l s='Order Reference' mod='kbpushnotification'}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">
                            <b>{'{{kb_order_amount}}'}{*escape not required*}</b>
                        </td>
                        <td>{l s='Order Amount' mod='kbpushnotification'}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">
                            <b>{'{{kb_order_before_status}}'}{*escape not required*}</b>
                        </td>
                        <td>{l s='Order Before Status' mod='kbpushnotification'}</td>
                    </tr>
                    <tr>
                        <td style="width: 30%">
                            <b>{'{{kb_order_after_status}}'}{*escape not required*}</b>
                        </td>
                        <td>{l s='Order After Status' mod='kbpushnotification'}</td>
                    </tr>
                {/if}   
            </tbody>
        </table>
    </div>

{/if}
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