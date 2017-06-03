<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

remove_filter('the_title', 'wc_page_endpoint_title');
$order=wc_get_order($order_id);
?>
<table class="profile-fields">
	<tbody>
		<tr>
			<th><?php _e('Nomor', 'makery'); ?></th>
			<td>
				<?php echo $order->get_order_number(); ?>
			</td>
		</tr>
		<tr>
			<th><?php _e('Tanggal', 'makery'); ?></th>
			<td>
				<?php echo date_i18n(get_option('date_format'), strtotime($order->order_date)); ?>
			</td>
		</tr>
		<tr>
			<th><?php _e('Status', 'makery'); ?></th>
			<td>
				<?php echo wc_get_order_status_name($order->get_status()); ?>
			</td>
		</tr>
		<?php 
		if(!ThemexCore::checkOption('shop_multiple')) { 
			$shop=ThemexUser::getShop($order->post->post_author);	
			if(!empty($shop)) {
			?>
			<tr>
				<th><?php _e('Toko', 'makery'); ?></th>
				<td>
					<a href="<?php echo get_permalink($shop); ?>"><?php echo get_the_title($shop); ?></a>
				</td>
			</tr>
			<?php 
			}
		}
		?>
	</tbody>
</table>
<table class="profile-table shop_table order_details">
	<thead>
		<tr>
			<th class="product-name"><?php _e('Produk', 'makery' ); ?></th>
			<th class="product-total"><?php _e('Total', 'makery' ); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	if(sizeof($order->get_items())>0){
		foreach($order->get_items() as $item){
			$_product=apply_filters('woocommerce_order_item_product', $order->get_product_from_item($item), $item);
			$item_meta=new WC_Order_Item_Meta($item, $_product);
			?>
			<tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $item, $order)); ?>">
				<td class="product-name">
					<?php
						if($_product && !$_product->is_visible() )
							echo apply_filters('woocommerce_order_item_name', $item['name'], $item );
						else
							echo apply_filters('woocommerce_order_item_name', sprintf('<a href="%s">%s</a>', get_permalink($item['product_id'] ), $item['name'] ), $item );

						echo apply_filters('woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf('&times; %s', $item['qty'] ) . '</strong>', $item );

						$item_meta->display();

						if($_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted()){

							$download_files = $order->get_item_downloads($item );
							$i              = 0;
							$links          = array();

							foreach($download_files as $download_id => $file ){
								$i++;

								$links[] = '<small><a href="' . esc_url($file['download_url'] ) . '">' . sprintf(__('Download file%s', 'makery' ),(count($download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html($file['name'] ) . '</a></small>';
							}

							echo '<br/>' . implode('<br/>', $links);
						}
					?>
				</td>
				<td class="product-total">
					<?php echo $order->get_formatted_line_subtotal($item); ?>
				</td>
			</tr>
			<?php
			if($order->has_status(array('completed', 'processing')) &&($purchase_note = get_post_meta($_product->id, '_purchase_note', true))){
				?>
				<tr class="product-purchase-note">
					<td colspan="3"><?php echo wpautop(do_shortcode($purchase_note)); ?></td>
				</tr>
				<?php
			}
		}
	}

	do_action('woocommerce_order_items_table', $order);
	?>
	</tbody>
	<tfoot>
	<?php
	if($totals = $order->get_order_item_totals()){
		foreach($totals as $total){
			?>
			<tr>
				<th scope="row"><?php echo $total['label']; ?></th>
				<td><?php echo $total['value']; ?></td>
			</tr>
			<?php
		}
	}
	?>
	</tfoot>
</table>	
<table class="profile-table">
	<tbody>
		<tr>
			<th><?php _e('Detail Pelanggan', 'makery'); ?></th>
			<td>
				<?php if(!empty($order->billing_email)) { ?>
				<strong><?php _e('Email:', 'makery'); ?></strong>&nbsp;<?php echo $order->billing_email; ?><br />
				<?php } ?>
				<?php if(!empty($order->billing_phone)) { ?>
				<strong><?php _e('No. HP:', 'makery'); ?></strong>&nbsp;<?php echo $order->billing_phone; ?>
				<?php } ?>
				<?php do_action('woocommerce_order_details_after_customer_details', $order); ?>
			</td>
		</tr>
		<tr>
			<?php if(!wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option('woocommerce_calc_shipping')!=='no' && $order->get_formatted_shipping_address()!=$order->get_formatted_billing_address()){ ?>
			<th><?php _e('Alamat Tagihan', 'makery'); ?></th>
			<?php } else { ?>				
			<th><?php _e('Alamat Pelanggan', 'makery'); ?></th>
			<?php } ?>					
			<td>
				<address><?php echo $order->get_formatted_billing_address(); ?></address>
			</td>
		</tr>
		<?php if(!wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option('woocommerce_calc_shipping')!=='no' && $order->get_formatted_shipping_address()!=$order->get_formatted_billing_address()){ ?>
		<tr>
			<th><?php _e('Alamat Pengiriman', 'makery'); ?></th>
			<td>
				<address><?php echo $order->get_formatted_shipping_address(); ?></address>
			</td>
		</tr>
		<?php } ?>
		<?php if(!empty($order->post->post_excerpt)) { ?>
		<tr>
			<th><?php _e('Catatan Pelanggan', 'makery'); ?></th>
			<td>
				<?php echo nl2br(esc_html($order->post->post_excerpt)); ?>
			</td>
		</tr>		
		<?php } ?>
		<?php
		$note=ThemexWoo::getNote($order->id);
		if(!empty($note)) { 
		?>
		<tr>
			<th><?php _e('Catatan Orderan', 'makery'); ?></th>
			<td>
				<?php echo nl2br(esc_html($note)); ?>
			</td>
		</tr>		
		<?php } ?>
	</tbody>
</table>
<?php do_action('woocommerce_order_details_after_order_table', $order); ?>