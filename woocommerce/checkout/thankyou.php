<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}
?>
<?php get_sidebar('profile-left'); ?>
<div class="column fivecol">
	<div class="element-title">
		<h1><?php _e('Lihat Orderan', 'makery' ); ?></h1>
	</div>
	<?php if($order){ ?>
		<?php if ( $order->has_status( 'failed' ) ) : ?>
			<p><?php _e( 'Sayangnya orderan anda tidak bisa diproses, karena toko menolak transaksi anda.', 'makery' ); ?></p>
			<p><?php
				if ( is_user_logged_in() )
					_e( 'Silakan coba pembelian Anda lagi atau buka halaman akun Anda.', 'makery' );
				else
					_e( 'Silakan coba pembelian Anda lagi.', 'makery' );
			?></p>
			<p>
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Bayar', 'makery' ) ?></a>
				<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button pay"><?php _e( 'Akun Saya', 'makery' ); ?></a>
				<?php endif; ?>
			</p>
		<?php else : ?>
			<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Terimakasih. Orderan Anda Telah Diterima.', 'makery' ), $order ); ?></p>
		<?php endif; ?>
		<div class="method_details">
			<?php do_action('woocommerce_thankyou_' . $order->payment_method, $order->id); ?>
			<?php do_action('woocommerce_thankyou', $order->id); ?>
		</div>
	<?php } else { ?>
		<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Terimakasih. Orderan Anda Telah Diterima.', 'makery' ), null ); ?></p>
	<?php } ?>
</div>
<?php remove_filter('the_title', 'wc_page_endpoint_title'); ?>
<?php get_sidebar('profile-right'); ?>