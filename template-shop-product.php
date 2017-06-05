<?php
/*
Template Name: Shop Product
*/
?>
<?php get_header(); ?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title indented">
		<?php if(empty(ThemexWoo::$data['product']) || ThemexWoo::$data['product']['status']!='draft') { ?>
		<h1><?php _e('Ubah Item', 'makery'); ?></h1>
		<?php } else { ?>
		<h1><?php _e('Ubah Item', 'makery'); ?></h1>
		<?php } ?>
	</div>
	<?php ThemexInterface::renderTemplateContent('shop-product'); ?>
	<?php if(ThemexCore::checkOption('shop_multiple')) { ?>
	<span class="secondary"><?php _e('Toko Ini Tidak Tersedia.', 'makery'); ?></span>
	<?php } else if(themex_status(ThemexUser::$data['current']['shop'])!='publish') { ?>
	<span class="secondary"><?php _e('Toko Ini Sedang Direview.', 'makery'); ?></span>
	<?php } else if(empty(ThemexWoo::$data['product']) || (!empty(ThemexWoo::$data['product']['ID']) && ThemexWoo::$data['product']['author']!=ThemexUser::$data['current']['ID'])) { ?>
	<span class="secondary"><?php _e('Item Ini Tidak Tersedia.', 'makery'); ?></span>
	<?php } else if(ThemexWoo::$data['product']['status']=='pending') { ?>
	<span class="secondary"><?php _e('Item Ini Sedang Direview.', 'makery'); ?></span>
	<?php } else { ?>
	<form action="" enctype="multipart/form-data" method="POST">
		<input type="file" id="product_image" name="product_image" class="element-upload shifted" />
		<input type="hidden" name="product_id" value="<?php echo ThemexWoo::$data['product']['ID']; ?>" />
		<input type="hidden" name="woo_action" value="add_image" />
	</form>
	<?php foreach(ThemexWoo::$data['product']['images'] as $image) { ?>
	<form id="image_<?php echo $image; ?>" action="" method="POST">
		<input type="hidden" name="image_id" value="<?php echo $image; ?>" />
		<input type="hidden" name="product_id" value="<?php echo ThemexWoo::$data['product']['ID']; ?>" />
		<input type="hidden" name="woo_action" value="remove_image" />
	</form>
	<?php } ?>
	<?php if(!empty(ThemexWoo::$data['product']['ID'])) { ?>
	<form action="" enctype="multipart/form-data" method="POST" id="product_form_<?php echo ThemexWoo::$data['product']['ID']; ?>" class="site-form element-autosave" data-default="product_form">
	<?php } else { ?>
	<form action="" enctype="multipart/form-data" method="POST" id="product_form" class="site-form element-autosave">
	<?php } ?>
		<div class="message">
			<?php ThemexInterface::renderMessages(themex_value('success', $_POST, false)); ?>
		</div>
		<table class="profile-fields">
			<tbody>
				<tr>
					<th><?php _e('Nama', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="title" value="<?php echo esc_attr(ThemexWoo::$data['product']['title']); ?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Gambar', 'makery'); ?></th>
					<td>
						<div class="element-gallery">
							<label for="product_image" class="element-button secondary small square" title="<?php _e('Tambah Gambar', 'makery'); ?>">
								<span class="fa fa-plus"></span>
							</label>
							<?php foreach(ThemexWoo::$data['product']['images'] as $image) { ?>
							<a href="#image_<?php echo $image; ?>" title="<?php _e('Hapus Gambar', 'makery'); ?>" class="gallery-image element-submit">
								<div class="image-border none">
									<img src="<?php echo themex_resize(wp_get_attachment_url($image), 150, 150); ?>" class="fullwidth" alt="" />
								</div>
							</a>
							<?php } ?>
						</div>
					</td>
				</tr>
				<?php if(ThemexCore::getOption('product_type', 'all')=='all') { ?>

				<?php } ?>
				<?php if(themex_taxonomy('product_cat')) { ?>
				<tr>
					<th <?php if(ThemexCore::checkOption('product_category')) { ?>class="large"<?php } ?>><?php _e('Kategori', 'makery'); ?></th>
					<td>
						<?php if(ThemexCore::checkOption('product_category')) { ?>
						<?php 
						echo ThemexInterface::renderOption(array(
							'id' => 'category',
							'type' => 'select_category',
							'taxonomy' => 'product_cat',
							'value' => ThemexWoo::$data['product']['category'],
							'wrap' => false,
							'attributes' => array(
								'multiple' => 'multiple',
							),							
						));
						?>
						<?php } else { ?>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'category',
								'type' => 'select_category',
								'taxonomy' => 'product_cat',
								'value' => ThemexWoo::$data['product']['category'],
								'wrap' => false,				
							));
							?>
						</div>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
				<?php if(!ThemexCore::checkOption('product_tags')) { ?>
				<tr>
					<th><?php _e('Tag', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="tags" value="<?php echo esc_attr(ThemexWoo::$data['product']['tags']); ?>" />
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php if(!ThemexCore::checkOption('product_price') && ThemexWoo::$data['product']['form']!='variable') { ?>
				<tr>
					<th><?php _e('Harga Normal', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="regular_price" value="<?php echo ThemexWoo::formatPrice(ThemexWoo::$data['product']['regular_price']); ?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><?php _e('Harga Setelah Diskon', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="sale_price" value="<?php echo ThemexWoo::formatPrice(ThemexWoo::$data['product']['sale_price']); ?>" />
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php if(in_array(ThemexCore::getOption('product_type', 'all'), array('all', 'physical'))) { ?>
				<?php if(themex_taxonomy('product_shipping_class') && ThemexWoo::isShipping()) { ?>
				<tr class="trigger-type-physical">
					<th><?php _e('Shipping Class', 'makery'); ?></th>
					<td>
						<div class="element-select">
							<span></span>
							<?php 
							echo ThemexInterface::renderOption(array(
								'id' => 'shipping_class',
								'type' => 'select_category',
								'taxonomy' => 'product_shipping_class',
								'value' => ThemexWoo::$data['product']['shipping_class'],
								'wrap' => false,
							));
							?>
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php if(ThemexWoo::$data['product']['form']!='variable') { ?>
				<tr class="trigger-type-physical">
					<th><?php _e('Stok', 'makery'); ?></th>
					<td>
						<div class="field-wrap">
							<input type="text" name="stock" class="exclude" value="<?php echo esc_attr(ThemexWoo::$data['product']['stock']); ?>" />
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php } ?>
				<?php if(in_array(ThemexCore::getOption('product_type', 'all'), array('all', 'virtual'))) { ?>

				<?php } ?>
				<?php 
				if(!ThemexCore::checkOption('product_attributes')) {
					$attributes=ThemexWoo::getAttributes();
					foreach($attributes as $attribute) {
					$value=themex_value($attribute['name'], ThemexWoo::$data['product']['attributes']);
					?>
					<tr>
						<th><?php echo $attribute['label']; ?></th>
						<td>
							<?php if($attribute['type']=='select') { ?>
							<div class="element-select">
								<span></span>
								<?php 
								echo ThemexInterface::renderOption(array(
									'id' => $attribute['name'],
									'type' => 'select_category',
									'taxonomy' => $attribute['name'],
									'value' => $value,
									'wrap' => false,				
								));
								?>
							</div>
							<?php } else { ?>
							<div class="field-wrap">
								<input type="text" name="<?php echo esc_attr($attribute['name']); ?>" value="<?php echo esc_attr($value); ?>" />
							</div>
							<?php } ?>
						</td>
					</tr>
					<?php 
					}
				}
				?>
				<?php
				if(!ThemexCore::checkOption('product_variations') && in_array(ThemexCore::getOption('product_type', 'all'), array('all', 'physical'))) {
					$options=ThemexWoo::getOptions(ThemexWoo::$data['product']['ID']);
					?>
					<tr class="trigger-type-physical">
						<th><?php _e('Attributes', 'makery'); ?></th>
						<td>
							<div class="element-options" id="attribute">
								<?php 
								$index=0;
								foreach($options as $option) {
								$index++;
								?>
								<div class="option">
									<a href="#<?php echo $index; ?>" title="<?php _e('Edit Attribute', 'makery'); ?>"><?php echo $option['name']; ?></a>
									<a href="#<?php echo $index; ?>" class="element-remove" title="<?php _e('Hapus Atribut', 'makery'); ?>">
										<span class="fa fa-times"></span>
									</a>
								</div>
								<?php } ?>
								<a href="#add" class="element-button element-clone secondary small square" title="<?php _e('Tambah Atribut', 'makery'); ?>">
									<span class="fa fa-plus"></span>
								</a>
							</div>
						</td>
					</tr>
					<tr class="option-attribute-add hidden">
						<td colspan="2">
							<div class="option-wrap clearfix">
								<div class="fivecol column">
									<div class="field-wrap">
										<input type="text" name="option_names[]" value="" placeholder="<?php _e('Nama', 'makery'); ?>" />
									</div>
								</div>
								<div class="sevencol column last">
									<div class="field-wrap">
										<input type="text" name="option_values[]" value="" placeholder="<?php _e('Pilihan (comma separated)', 'makery'); ?>" />
									</div>
								</div>
							</div>
						</td>
					</tr>
					<?php 
					$index=0;
					foreach($options as $option) {
					$index++;
					?>
					<tr class="option-attribute-<?php echo $index; ?> hidden">
						<td colspan="2">
							<div class="option-wrap clearfix">
								<div class="fivecol column">
									<div class="field-wrap">
										<input type="text" name="option_names[]" value="<?php echo $option['name']; ?>" placeholder="<?php _e('Nama', 'makery'); ?>" />
									</div>
								</div>
								<div class="sevencol column last">
									<div class="field-wrap">
										<input type="text" name="option_values[]" value="<?php echo $option['value']; ?>" placeholder="<?php _e('Pilihan (comma separated)', 'makery'); ?>" />
									</div>
								</div>
							</div>
						</td>
					</tr>
					<?php 
					}
					$variations=ThemexWoo::getVariations(ThemexWoo::$data['product']['ID']);
					?>
					<tr class="trigger-type-physical">
						<th><?php _e('Variations', 'makery'); ?></th>
						<td>
							<div class="element-options" id="variation">
								<?php foreach($variations as $index => $variation) { ?>
								<div class="option">
									<a href="#<?php echo $variation->ID; ?>" title="<?php _e('Edit Variation', 'makery'); ?>"><?php echo sprintf(__('Variation #%s', 'makery'), $index+1); ?></a>
									<a href="#<?php echo $variation->ID; ?>" class="element-remove" title="<?php _e('Hapus Variasi', 'makery'); ?>">
										<span class="fa fa-times"></span>
									</a>
								</div>
								<?php } ?>
								<a href="#add" class="element-button element-clone secondary small square" title="<?php _e('Tambah Variasi', 'makery'); ?>">
									<span class="fa fa-plus"></span>
								</a>
							</div>
						</td>
					</tr>
					<tr class="option-variation-add hidden">
						<td colspan="2">
							<div class="option-wrap clearfix">
								<?php foreach($options as $name => $option) { ?>
								<div class="element-select">
									<span></span>
									<select name="variation_<?php echo $name; ?>s[]">
										<option value=""><?php echo $option['name']; ?></option>
										<?php
										$items=explode(',', $option['value']);
										foreach($items as $item) {
										$item=trim($item);
										?>
										<option value="<?php echo $item; ?>"><?php echo $item; ?></option>
										<?php } ?>
									</select>
								</div>
								<?php } ?>
								<div class="fourcol column">
									<div class="field-wrap">
										<input type="text" name="variation_stocks[]" class="exclude" value="" placeholder="<?php _e('Stok', 'makery'); ?>" />
									</div>
								</div>
								<div class="fourcol column">
									<div class="field-wrap">
										<input type="text" name="variation_regular_prices[]" value="" placeholder="<?php _e('Harga Normal', 'makery'); ?>" />
									</div>
								</div>
								<div class="fourcol column last">
									<div class="field-wrap">
										<input type="text" name="variation_sale_prices[]" value="" placeholder="<?php _e('Harga Setelah Diskon', 'makery'); ?>" />
									</div>
								</div>
								<input type="hidden" name="variation_ids[]" value="" />
							</div>
						</td>
					</tr>
					<?php foreach($variations as $variation) { ?>
					<tr class="option-variation-<?php echo $variation->ID; ?> hidden">
						<td colspan="2">
							<div class="option-wrap clearfix">
								<?php foreach($options as $name => $option) { ?>
								<div class="element-select">
									<span></span>
									<select name="variation_<?php echo $name; ?>s[]">
										<option value=""><?php echo $option['name']; ?></option>
										<?php
										$value=get_post_meta($variation->ID, 'attribute_'.$name, true);
										$items=explode(',', $option['value']);
										foreach($items as $item) {
										$item=trim($item);
										?>
										<option value="<?php echo $item; ?>" <?php if($item==$value) { ?>selected="selected"<?php } ?>><?php echo $item; ?></option>
										<?php } ?>
									</select>
								</div>
								<?php } ?>
								<div class="fourcol column">
									<div class="field-wrap">
										<input type="text" name="variation_stocks[]" class="exclude" value="<?php echo intval($variation->_stock); ?>" placeholder="<?php _e('Stok', 'makery'); ?>" />
									</div>
								</div>
								<div class="fourcol column">
									<div class="field-wrap">
										<input type="text" name="variation_regular_prices[]" value="<?php echo ThemexWoo::formatPrice($variation->_regular_price); ?>" placeholder="<?php _e('Harga Normal', 'makery'); ?>" />
									</div>
								</div>
								<div class="fourcol column last">
									<div class="field-wrap">
										<input type="text" name="variation_sale_prices[]" value="<?php echo ThemexWoo::formatPrice($variation->_sale_price); ?>" placeholder="<?php _e('Harga Setelah Diskon', 'makery'); ?>" />
									</div>
								</div>
								<input type="hidden" name="variation_ids[]" value="<?php echo $variation->ID; ?>" />
							</div>
						</td>
					</tr>
					<?php 
					}
				}
				?>
			</tbody>
		</table>
		<div class="profile-description">
			<?php ThemexInterface::renderEditor('content', ThemexWoo::$data['product']['content']); ?>
		</div>
		<?php if(ThemexWoo::$data['product']['status']=='draft') { ?>
			<?php if(ThemexCore::checkOption('product_approve')) { ?>
			<a href="#" class="element-button element-submit primary"><?php _e('Simpan Perubahan', 'makery'); ?></a>
			<?php } else { ?>
			<a href="#" class="element-button element-submit primary"><?php _e('Submit Untuk Direview', 'makery'); ?></a>
			<?php } ?>
		<?php } else { ?>
		<a href="#" class="element-button element-submit primary"><?php _e('Simpan Perubahan', 'makery'); ?></a>		
		<a href="<?php echo get_permalink(ThemexWoo::$data['product']['ID']); ?>" target="_blank" title="<?php _e('Lihat', 'makery'); ?>" class="element-button secondary square">
			<span class="fa fa-eye"></span>
		</a>
		<a href="#product_form" title="<?php _e('Hapus', 'makery'); ?>" class="element-button element-colorbox secondary square">
			<span class="fa fa-times"></span>
		</a>
		<?php } ?>
		<input type="hidden" name="product_id" value="<?php echo ThemexWoo::$data['product']['ID']; ?>" />
		<input type="hidden" name="woo_action" value="update_product" />
	</form>
	<div class="site-popups hidden">
		<div id="product_form">
			<div class="site-popup small">
				<form class="site-form" method="POST" action="">
					<p><?php _e('Apakah Anda Yakin Ingin Menghapus Item Ini Secara Permanen?', 'makery'); ?></p>
					<a href="#" class="element-button element-submit primary"><?php _e('Hapus Item', 'makery'); ?></a>
					<input type="hidden" name="product_id" value="<?php echo ThemexWoo::$data['product']['ID']; ?>" />
					<input type="hidden" name="woo_action" value="remove_product" />
				</form>
			</div>
		</div>
	</div>
	<!-- /popups -->
	<?php } ?>
</div>
<?php get_sidebar('profile-right'); ?>
<?php get_footer(); ?>