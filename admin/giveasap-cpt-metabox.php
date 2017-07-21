<?php

if( ! defined( 'ABSPATH' ) ) {
    return;
}

add_action( 'giveasap_metabox_object_giveasap_text', 'giveasap_free_metabox_text' );

$metabox = new GiveASAP_Metabox( 'Scheduling', 'giveasap_schedule', array( 'giveasap' ) );

$metabox_prize = new GiveASAP_Metabox( 'Prize Info', 'giveasap_prize', array( 'giveasap' ) );

$metabox_display = new GiveASAP_Metabox( 'Display', 'giveasap_display', array( 'giveasap' ), 'side' );

$metabox_text = new GiveASAP_Metabox( 'Text & Email', 'giveasap_text', array( 'giveasap' ) );

add_action( 'gasap_metabox_giveasap_schedule_before_fields', 'gasap_schedule_text');
function gasap_schedule_text() {
    echo '<p> ' . __( 'The Date will be formatted on the front end with the format that is set in Settings > General.', 'giveasap' ) . '</p>';
    echo '<p>' . __( 'The format used here is Day-Month-Year', 'giveasap' ) . '</p>';
}

$timeArray = array();

for( $i = 0; $i < 24; $i++ ){
	$hour = $i;
	if( $hour < 10 ) {
		$hour = "0" . $hour;
	}

	for( $j = 0; $j < 4; $j++ ){

		$minute = "00";

		if( $j == 1 ) {
			$minute = "15";
		} 

		if( $j == 2 ) {
			$minute = "30";
		} 

		if( $j == 3 ) {
			$minute = "45";
		} 

		$timeArray[$hour . ":" . $minute] = $hour . ":" . $minute;
	}
}

$today = date( 'd-m-Y' );
$tomorrow = date( 'd-m-Y', strtotime( "Tomorrow" ) );

$metabox->add_field(
  array(
    'name' => 'start_date',
    'title' =>	__( 'Start Date', 'giveasap' ),
    'type' => 'datetime',
    'default' => $today ));

$metabox->add_field(
  array(
    'name' => 'start_time',
    'title' =>	__( 'Start Time', 'giveasap' ),
    'type' => 'select',
    'options' => $timeArray,
    'default' => '00:00' ));

$metabox->add_field(
  array(
    'name' => 'end_date',
    'title' =>	__( 'End Date', 'giveasap' ),
    'type' => 'datetime',
    'default' => $tomorrow ));

$metabox->add_field(
  array(
    'name' => 'end_time',
    'title' =>	__( 'End Time', 'giveasap' ),
    'type' => 'select',
    'options' => $timeArray,
    'default' => '00:00' ));

$metabox->add_field(
  array(
    'name' => 'winner_date',
    'title' =>	__( 'Winner Announcement Date', 'giveasap' ),
    'type' => 'datetime',
    'default' => $tomorrow ));

$metabox->add_field(
  array(
    'name' => 'winner_time',
    'title' =>	__( 'Winner Announcement Time', 'giveasap' ),
    'type' => 'select',
    'options' => $timeArray,
    'default' => '00:00' ));


$metabox_prize->add_field(
  array(
    'name' => 'winners',
    'type' => 'text',
    'default' => 1,
    'title' =>	__( 'Number of winners', 'giveasap' ),
    'desc' => __( 'How many winners can be selected', 'giveasap') ));


function giveasap_free_metabox_text( $metabox_text ) {

    $metabox_text->add_field(
      array(
        'name' => 'rules_text',
        'title' => __('Rules', 'giveasap'),
        'type' => 'wpeditor',
        'desc' => __( 'Rules will show at the bottom', 'giveasap'),
        'default' => 'This is a rule' ));

    $metabox_text->add_field(
      array(
        'name' => 'winner_email',
        'title' => 'Winner Email',
        'type' => 'wpeditor',
        'desc' => 'This text will be sent to the winner(s). {{TITLE}} - placeholder for prize name',
        'default' => 'Congratulations, <br/> you have won {{TITLE}}'));


}


$metabox_prize->add_field(
  array(
    'name' => 'prize',
    'title' => __( 'Prize', 'giveasap'),
    'type' => 'text',
    'desc' => 'Enter the name of the prize (example: Product Title)',
    'placeholder' => __( '1 year Subscription Plan', 'giveasap' ) ) );

$metabox_prize->add_field(
  array(
    'name' => 'value',
    'title' => __( 'Value', 'giveasap'),
    'type' => 'text',
    'desc' => 'Enter the value of the prize (example: $1,000.00)',
    'placeholder' => __( '$100.00', 'giveasap' ) ) );

$metabox_display->add_field(
  array(
    'name' => 'logo',
    'title' => __( 'Logo', 'giveasap'),
    'type' => 'image',
    'desc' => __( 'Logo of the sponsor, you or the prize', 'giveasap' ) ));

$metabox_display->add_field(
  array(
    'name' => 'logo_link',
    'title' => __( 'Logo Link', 'giveasap'),
    'type' => 'text',
    'desc' => __( 'Where will the logo link to? Leave empty for no link', 'giveasap' ) ) );


$metabox_display->add_field(
  array(
    'name' => 'images',
    'title' => __( 'Images', 'giveasap'),
    'type' => 'gallery',
    'desc' => 'Add images and order their display. First image will be used as share images if those are not defined below' ) );

$metabox_display->add_field(
  array(
    'name' => 'background_color',
    'title' => __( 'Background Color', 'giveasap'),
    'type' => 'color',
    'desc' => 'This color is used when there is no Background Image' ) );

$metabox_display->add_field(
  array(
    'name' => 'pinterest_image',
    'title' => __( 'Pinterest Image', 'giveasap'),
    'type' => 'image',
    'desc' => 'This image will be used when people pin this giveaway' ) );

$metabox_display->add_field(
  array(
    'name' => 'facebook_image',
    'title' => __( 'Facebook Image', 'giveasap'),
    'type' => 'image',
    'desc' => 'This image will be used when people share this giveaway on Facebook' ) );

$metabox_display->add_field(
  array(
    'name' => 'twitter_image',
    'title' => __( 'Twitter Image', 'giveasap'),
    'type' => 'image',
    'desc' => 'This image will be used when people share this giveaway on Twitter' ) );

$metabox_display->add_field(
  array(
    'name' => 'google_image',
    'title' => __( 'Google+ Image', 'giveasap'),
    'type' => 'image',
    'desc' => 'This image will be used when people share this giveaway on Google+' ) );


