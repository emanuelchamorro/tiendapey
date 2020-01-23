<script>
    var all_lang_req = "{l s='Field can not be empty for any language.' mod='kbreviewincentives'}";
    var empty_field = "{l s='Field cannot be empty.' mod='kbreviewincentives'}";
     var can_not_zero = "{l s='Incentive amount can not be zero.' mod='kbreviewincentives'}";
    var audit_log = "{l s='Audit Log' mod='kbreviewincentives'}";
    var sync_reviews = "{l s='Sync Reviews' mod='kbreviewincentives'}";
    var error_msg_multiselect = "{l s='Field cannot be empty. Please select at least one order state.' mod='kbreviewincentives'}";
    var controller_path = "{$controller_path nofilter}"; {* Variable contains URL, can not escape this *}
    var lang_id = "{$lang_id}";
    var audit_log_link = "{$audit_log_link nofilter}";{* Variable contains URL, can not escape this *}
    var method = "{$method}";
    var review_report_link = "{$review_report_link nofilter}"; {* Variable contains URL, can not escape this *}
    velovalidation.setErrorLanguage({
        empty_fname: "{l s='Please enter First name.' mod='kbreviewincentives'}",
        maxchar_fname: "{l s='First name cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_fname: "{l s='First name cannot be less than # characters.' mod='kbreviewincentives'}",
        empty_mname: "{l s='Please enter middle name.' mod='kbreviewincentives'}",
        maxchar_mname: "{l s='Middle name cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_mname: "{l s='Middle name cannot be less than # characters.' mod='kbreviewincentives'}",
        only_alphabet: "{l s='Only alphabets are allowed.' mod='kbreviewincentives'}",
        empty_lname: "{l s='Please enter Last name.' mod='kbreviewincentives'}",
        maxchar_lname: "{l s='Last name cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_lname: "{l s='Last name cannot be less than # characters.' mod='kbreviewincentives'}",
        alphanumeric: "{l s='Field should be alphanumeric.' mod='kbreviewincentives'}",
        empty_pass: "{l s='Please enter Password.' mod='kbreviewincentives'}",
        maxchar_pass: "{l s='Password cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_pass: "{l s='Password cannot be less than # characters.' mod='kbreviewincentives'}",
        specialchar_pass: "{l s='Password should contain atleast 1 special character.' mod='kbreviewincentives'}",
        alphabets_pass: "{l s='Password should contain alphabets.' mod='kbreviewincentives'}",
        capital_alphabets_pass: "{l s='Password should contain atleast 1 capital letter.' mod='kbreviewincentives'}",
        small_alphabets_pass: "{l s='Password should contain atleast 1 small letter.' mod='kbreviewincentives'}",
        digit_pass: "{l s='Password should contain atleast 1 digit.' mod='kbreviewincentives'}",
        empty_field: "{l s='Field cannot be empty.' mod='kbreviewincentives'}",
        number_field: "{l s='You can enter only numbers.' mod='kbreviewincentives'}",            
        positive_number: "{l s='Number should be greater than 0.' mod='kbreviewincentives'}",
        maxchar_field: "{l s='Field cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_field: "{l s='Field cannot be less than # character(s).' mod='kbreviewincentives'}",
        empty_email: "{l s='Please enter Email.' mod='kbreviewincentives'}",
        validate_email: "{l s='Please enter a valid Email.' mod='kbreviewincentives'}",
        empty_country: "{l s='Please enter country name.' mod='kbreviewincentives'}",
        maxchar_country: "{l s='Country cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_country: "{l s='Country cannot be less than # characters.' mod='kbreviewincentives'}",
        empty_city: "{l s='Please enter city name.' mod='kbreviewincentives'}",
        maxchar_city: "{l s='City cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_city: "{l s='City cannot be less than # characters.' mod='kbreviewincentives'}",
        empty_state: "{l s='Please enter state name.' mod='kbreviewincentives'}",
        maxchar_state: "{l s='State cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_state: "{l s='State cannot be less than # characters.' mod='kbreviewincentives'}",
        empty_proname: "{l s='Please enter product name.' mod='kbreviewincentives'}",
        maxchar_proname: "{l s='Product cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_proname: "{l s='Product cannot be less than # characters.' mod='kbreviewincentives'}",
        empty_catname: "{l s='Please enter category name.' mod='kbreviewincentives'}",
        maxchar_catname: "{l s='Category cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_catname: "{l s='Category cannot be less than # characters.' mod='kbreviewincentives'}",
        empty_zip: "{l s='Please enter zip code.' mod='kbreviewincentives'}",
        maxchar_zip: "{l s='Zip cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_zip: "{l s='Zip cannot be less than # characters.' mod='kbreviewincentives'}",
        empty_username: "{l s='Please enter Username.' mod='kbreviewincentives'}",
        maxchar_username: "{l s='Username cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_username: "{l s='Username cannot be less than # characters.' mod='kbreviewincentives'}",
        invalid_date: "{l s='Invalid date format.' mod='kbreviewincentives'}",
        maxchar_sku: "{l s='SKU cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_sku: "{l s='SKU cannot be less than # characters.' mod='kbreviewincentives'}",
        invalid_sku: "{l s='Invalid SKU format.' mod='kbreviewincentives'}",
        empty_sku: "{l s='Please enter SKU.' mod='kbreviewincentives'}",
        validate_range: "{l s='Number is not in the valid range. It should be betwen # and ##' mod='kbreviewincentives'}",
        empty_address: "{l s='Please enter address.' mod='kbreviewincentives'}",
        minchar_address: "{l s='Address cannot be less than # characters.' mod='kbreviewincentives'}",
        maxchar_address: "{l s='Address cannot be greater than # characters.' mod='kbreviewincentives'}",
        empty_company: "{l s='Please enter company name.' mod='kbreviewincentives'}",
        minchar_company: "{l s='Company name cannot be less than # characters.' mod='kbreviewincentives'}",
        maxchar_company: "{l s='Company name cannot be greater than # characters.' mod='kbreviewincentives'}",
        invalid_phone: "{l s='Phone number is invalid.' mod='kbreviewincentives'}",
        empty_phone: "{l s='Please enter phone number.' mod='kbreviewincentives'}",
        minchar_phone: "{l s='Phone number cannot be less than # characters.' mod='kbreviewincentives'}",
        maxchar_phone: "{l s='Phone number cannot be greater than # characters.' mod='kbreviewincentives'}",
        empty_brand: "{l s='Please enter brand name.' mod='kbreviewincentives'}",
        maxchar_brand: "{l s='Brand name cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_brand: "{l s='Brand name cannot be less than # characters.' mod='kbreviewincentives'}",
        empty_shipment: "{l s='Please enter Shimpment.' mod='kbreviewincentives'}",
        maxchar_shipment: "{l s='Shipment cannot be greater than # characters.' mod='kbreviewincentives'}",
        minchar_shipment: "{l s='Shipment cannot be less than # characters.' mod='kbreviewincentives'}",
        invalid_ip: "{l s='Invalid IP format.' mod='kbreviewincentives'}",
        invalid_url: "{l s='Invalid URL format.' mod='kbreviewincentives'}",
        empty_url: "{l s='Please enter URL.' mod='kbreviewincentives'}",
        valid_amount: "{l s='Field should be numeric.' mod='kbreviewincentives'}",
        valid_decimal: "{l s='Field can have only upto two decimal values.' mod='kbreviewincentives'}",
        max_email: "{l s='Email cannot be greater than # characters.' mod='kbreviewincentives'}",
        specialchar_zip: "{l s='Zip should not have special characters.' mod='kbreviewincentives'}",
        specialchar_sku: "{l s='SKU should not have special characters.' mod='kbreviewincentives'}",
        max_url: "{l s='URL cannot be greater than # characters.' mod='kbreviewincentives'}",
        valid_percentage: "{l s='Percentage should be in number.' mod='kbreviewincentives'}",
        between_percentage: "{l s='Percentage should be between 0 and 100.' mod='kbreviewincentives'}",
        maxchar_size: "{l s='Size cannot be greater than # characters.' mod='kbreviewincentives'}",
        specialchar_size: "{l s='Size should not have special characters.' mod='kbreviewincentives'}",
        specialchar_upc: "{l s='UPC should not have special characters.' mod='kbreviewincentives'}",
        maxchar_upc: "{l s='UPC cannot be greater than # characters.' mod='kbreviewincentives'}",
        specialchar_ean: "{l s='EAN should not have special characters.' mod='kbreviewincentives'}",
        maxchar_ean: "{l s='EAN cannot be greater than # characters.' mod='kbreviewincentives'}",
        specialchar_bar: "{l s='Barcode should not have special characters.' mod='kbreviewincentives'}",
        maxchar_bar: "{l s='Barcode cannot be greater than # characters.' mod='kbreviewincentives'}",
        positive_amount: "{l s='Field should be positive.' mod='kbreviewincentives'}",
        maxchar_color: "{l s='Color could not be greater than # characters.' mod='kbreviewincentives'}",
        invalid_color: "{l s='Color is not valid.' mod='kbreviewincentives'}",
        specialchar: "{l s='Special characters are not allowed.' mod='kbreviewincentives'}",
        script: "{l s='Script tags are not allowed.' mod='kbreviewincentives'}",
        style: "{l s='Style tags are not allowed.' mod='kbreviewincentives'}",
        iframe: "{l s='Iframe tags are not allowed.' mod='kbreviewincentives'}",
        not_image: "{l s='Uploaded file is not an image.' mod='kbreviewincentives'}",
        image_size: "{l s='Uploaded file size must be less than #.' mod='kbreviewincentives'}",
        html_tags: "{l s='Field should not contain HTML tags.' mod='kbreviewincentives'}",
        number_pos: "{l s='You can enter only positive numbers.' mod='kbreviewincentives'}",
        invalid_separator:"{l s='Invalid comma (#) separated values.' mod='kbreviewincentives'}",
    });
</script>

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