<?php

class AMP_Options_Menu_Page {
	public function render() {
		?>
		<div class="ampoptions-admin-page">
			<h1><?php echo __( 'AMP Plugin Options', 'amp' ) ?></h1>
				<p>
					This admin panel menu contains configuration options
					for the AMP Plugin.
				</p>
		</div>
		<?php
	}
}
