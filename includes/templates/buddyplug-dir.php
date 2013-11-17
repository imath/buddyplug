<div id="buddypress">

	<?php do_action( 'buddyplug_before_directory_content' ); ?>

	<?php do_action( 'template_notices' ); ?>


		<div class="buddyplug" role="main">
			
			<p><?php _e( 'Hello BuddyPlug Directory!', 'buddyplug' );?></p>
			<div id="buddyplug-content"></div>
			<!-- this is the div our script.js will populate -->

		</div><!-- .buddyplug -->

	<?php do_action( 'buddyplug_after_directory_content' ); ?>

</div>