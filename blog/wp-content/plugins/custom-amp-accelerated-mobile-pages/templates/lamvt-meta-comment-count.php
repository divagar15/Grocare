<li>
	<?php
		printf(
			_n( 'One comment', '%1$s comments', $this->get( 'post' )->comment_count, 'lamvt' ), number_format_i18n( $this->get( 'post' )->comment_count )
		);
	?>
</li>