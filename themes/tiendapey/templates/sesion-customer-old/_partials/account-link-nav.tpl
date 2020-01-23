<p class="titleAccount">
			<span>Mi cuenta</span>
			<span>¡hola {$customer.firstname}!</span>
			</p>
			<ul class="linksAccount">
			<li><a id="identity-link" href="{$urls.pages.identity}" class="active"><i class="material-icons">assignment_ind</i> {l s='Information' d='Shop.Theme.Customeraccount'}</a></li>
			{if $customer.addresses|count}
				<li><a id="addresses-link" href="{$urls.pages.addresses}"><i class="material-icons">person_pin</i> {l s='Addresses' d='Shop.Theme.Customeraccount'}</a></li>
			{else}
				<li><a id="address-link" href="{$urls.pages.address}"><i class="material-icons">person_pin</i> {l s='Add first address' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			{if !$configuration.is_catalog}
				<li><a id="history-link" href="{$urls.pages.history}"><i class="material-icons">wrap_text</i> {l s='Order history and details' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			{if !$configuration.is_catalog}
				<li><a id="order-slips-link" href="{$urls.pages.order_slip}"><i class="material-icons">list_alt</i> {l s='Pedidos cancelados' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			{if $configuration.voucher_enabled && !$configuration.is_catalog}
				<li><a id="discounts-link" href="{$urls.pages.discount}"><i class="material-icons">monetization_on</i> {l s='Vouchers' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			{if $configuration.return_enabled && !$configuration.is_catalog}
				<li><a id="returns-link" href="{$urls.pages.order_follow}"><i class="material-icons">flip_camera_android</i> {l s='Merchandise returns' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}
			<li><a id="discount-link" href="{$urls.pages.discount}"><i class="material-icons">local_activity</i> {l s='Vouchers' d='Shop.Theme.Customeraccount'}</a></li>
			</ul>