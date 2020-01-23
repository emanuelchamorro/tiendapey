{if isset($is_shortcodes)}
    <div class="kb-admin-shortcodes-price alert-info" style="display: none;">
        <h4 style="margin-bottom: 0;padding: 6px;">{l s='Shortcodes can be used for Price Alert Message' mod='kbpushnotification'}</h4>
        <table class="table table-fill">
            <tbody>
                <tr></tr>
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
                        <b>{'{{kb_item_reference}}'}{*escape not required*}</b>
                    </td>
                    <td>{l s='Product Reference' mod='kbpushnotification'}</td>
                </tr>    </tbody>
        </table>
    </div>
                    <div class="kb-admin-shortcodes-stock alert-info" style="display: none;">
        <h4 style="margin-bottom: 0;padding: 6px;">{l s='Shortcodes can be used for Back in Stock Alert Message' mod='kbpushnotification'}</h4>
        <table class="table table-fill">
            <tbody>
                <tr></tr>
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
                        <b>{'{{kb_item_reference}}'}{*escape not required*}</b>
                    </td>
                    <td>{l s='Product Reference' mod='kbpushnotification'}</td>
                </tr>    </tbody>
        </table>
    </div>
{/if}

<script>
    var check_for_all = "{l s='Kindly check for all available languages' mod='kbpushnotification'}";
    var empty_field = "{l s='Field cannot be empty' mod='kbpushnotification'}";
    velovalidation.setErrorLanguage({
        alphanumeric: "{l s='Field should be alphanumeric.' mod='kbpushnotification'}",
        digit_pass: "{l s='Password should contain atleast 1 digit.' mod='kbpushnotification'}",
        empty_field: "{l s='Field cannot be empty.' mod='kbpushnotification'}",
        number_field: "{l s='You can enter only numbers.' mod='kbpushnotification'}",
        positive_number: "{l s='Number should be greater than 0.' mod='kbpushnotification'}",
        maxchar_field: "{l s='Field cannot be greater than # characters.' mod='kbpushnotification'}",
        minchar_field: "{l s='Field cannot be less than # character(s).' mod='kbpushnotification'}",
        invalid_date: "{l s='Invalid date format.' mod='kbpushnotification'}",
        valid_amount: "{l s='Field should be numeric.' mod='kbpushnotification'}",
        valid_decimal: "{l s='Field can have only upto two decimal values.' mod='kbpushnotification'}",
        maxchar_size: "{l s='Size cannot be greater than # characters.' mod='kbpushnotification'}",
        specialchar_size: "{l s='Size should not have special characters.' mod='kbpushnotification'}",
        maxchar_bar: "{l s='Barcode cannot be greater than # characters.' mod='kbpushnotification'}",
        positive_amount: "{l s='Field should be positive.' mod='kbpushnotification'}",
        maxchar_color: "{l s='Color could not be greater than # characters.' mod='kbpushnotification'}",
        invalid_color: "{l s='Color is not valid.' mod='kbpushnotification'}",
        specialchar: "{l s='Special characters are not allowed.' mod='kbpushnotification'}",
        script: "{l s='Script tags are not allowed.' mod='kbpushnotification'}",
        style: "{l s='Style tags are not allowed.' mod='kbpushnotification'}",
        iframe: "{l s='Iframe tags are not allowed.' mod='kbpushnotification'}",
         not_image: "{l s='Uploaded file is not an image' mod='kbpushnotification'}",
        image_size: "{l s='Uploaded file size must be less than #.' mod='kbpushnotification'}",
        html_tags: "{l s='Field should not contain HTML tags.' mod='kbpushnotification'}",
        number_pos: "{l s='You can enter only positive numbers.' mod='kbpushnotification'}",
        empty_email: "{l s='Please enter Email.' mod='kbpushnotification'}",
        validate_email: "{l s='Please enter a valid Email.' mod='kbpushnotification'}",
        max_email: "{l s='Email cannot be greater than # characters.' mod='kbpushnotification'}",
    });
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