/* ==========================================================
 * AdminKIT v1.5
 * tables.js
 * 
 * http://www.mosaicpro.biz
 * Copyright MosaicPro
 *
 * Built exclusively for sale @Envato Marketplaces
 * ========================================================== */ 

$(function()
{
	/* DataTables */
	if ($('.dynamicTable').size() > 0)
	{
		$('.dynamicTable').dataTable({
			"sPaginationType": "bootstrap",
                        "aaSorting": null,
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
			"oLanguage": {
				"sLengthMenu": "_MENU_ records per page"
			}
		});
	}
});