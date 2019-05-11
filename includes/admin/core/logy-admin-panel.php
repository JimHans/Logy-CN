<?php

class Logy_Panel {

	public function __construct() {
	}

	/**
	 * # Panel Form.
	 */
	function admin_panel( $menu = null, $settings = null ) {

	?>

	<?php do_action( 'logy_panel_before_form' ); ?>

	<div id="klabs-panel" class="<?php echo logy_options( 'logy_panel_scheme' ); ?>">

	    <div class="klabs-sidebar">

	        <div class="klabs-logo">
	        	<a href="http://www.kainelabs.com">
	        		<img src="<?php echo LOGY_AA . 'images/logo.png'; ?>" alt="">
	        	</a>
	        </div>

			<div class="kl-responsive-menu">
				<?php _e( 'menu', 'quicket' ); ?>
				<input class="kl-toggle-btn" type="checkbox" id="kl-toggle-btn" />
	  			<label class="kl-toggle-icon" for="kl-toggle-btn"></i><span class="kl-icon-bars"></span></label>
			</div>

			<!-- Panel Menu. -->
	        <?php $this->get_menu( $menu ); ?>

	    </div>

	    <div id="klabs-panel-content" class="klabs-panel">
	        <div class="klabs-main-content">
	            <?php
	            	// Get Panel Settings
	            	echo $settings;
	            ?>
			</div>
	    </div>

	</div>

	<div class="klabs-md-overlay"></div>

	<!-- Reset Dialog -->
	<?php logy_popup_dialog( 'reset_tab' ); ?>

	<!-- Errors Dialog -->
	<?php logy_popup_dialog( 'error' ); ?>

	<?php do_action( 'logy_panel_after_form' ); ?>

	<?php

	}

	/**
	 * # Get Menu Content.
	 */
	function get_menu( $tabs_list ) {

		// Get Current Page Url.
		$current_url = get_permalink();

		echo '<ul class="klabs-panel-menu klabs-form-menu">';

		foreach ( $tabs_list as $tab ) {

			// Add Tab ID to url.
			$tab_url = add_query_arg( 'tab', $tab['id'] , $current_url );

			// Get Tab Class Name.
			$class = isset( $tab[ 'class' ] ) ? 'class="klabs-active-tab"' : null;

		?>
			<li>
				<a href="<?php echo $tab_url; ?>" <?php echo $class; ?>><i class="fa fa-<?php echo $tab['icon']; ?>" aria-hidden="true"></i><?php echo $tab['title']; ?></a>
			</li>
		<?php

		}

	    echo '</ul>';
	}

}