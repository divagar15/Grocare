<?php

function powerup_mp_groups( $settings ) {

	paf_options ( array(
		'eoi_powerup_mp_groups' => array(
			'type' => 'checkbox',
			'options' => array(
				'on' => __( 'Enabled' ),
			),
			'page' => 'eoi_powerups',
			'title' => __( 'MailChimp Groups' ),
			'description' => sprintf( '<p class="description eoi_powerup_description">%s</p>', __( 'Enable MailChimp Interest Groups.' ) ),
		)
	) );

	if( ! paf( 'eoi_powerup_mp_groups' ) ) {
		return;
	}

}