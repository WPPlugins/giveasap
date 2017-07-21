<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage GiveASAP
 * @since GiveASAP 1.0
 */

global $post;

if( !defined( 'GASAP_URI') ){
	define( 'GASAP_URI', plugin_dir_url( __FILE__ ) );
}

$schedule = get_post_meta( $post->ID, 'giveasap_schedule', true );
$prize = get_post_meta( $post->ID, 'giveasap_prize', true );
$display = get_post_meta( $post->ID, 'giveasap_display', true ); 
$giveasap_settings = get_option( 'giveasap_settings' );
$giveasap_front = new GiveASAP_Front( $post, $display );

$text = get_post_meta( $post->ID, 'giveasap_text', true ); 
$end_date = DateTime::createFromFormat('d-m-Y', $schedule["end_date"]);
$ga_schedule = new GA_Schedule( $schedule );
if( $prize["winners"] == '' ) {
	$prize["winners"] = 1;
}
$giveasap_ended = false;

if( $ga_schedule->has_ended() ) {
	$giveasap_ended = true;
} 

$template_name = 'template1';
$template_name = apply_filters( 'giveasap_display_template', $template_name, $display );

$template_path = 'templates/';
$template_path = apply_filters( 'giveasap_display_template_path', $template_path );

$template_file = $template_path . $template_name . '.php';

$twitterImage = false;
if( isset( $display['twitter_image'] ) ) {
	$twitterImage = giveasap_get_image_url( $display['twitter_image'] ); 
}

$facebookImage = false;
if( isset( $display['facebook_image'] ) ) {
	$facebookImage = giveasap_get_image_url( $display['facebook_image'] ); 
}

$googleImage = false;
if( isset( $display['google_image'] ) ) {
	$googleImage = giveasap_get_image_url( $display['google_image'] ); 
}

setup_postdata( $post ); 

$description = get_the_excerpt();
$description = strip_tags( $description );
$title = get_the_title();
$timezone = get_option('gmt_offset');
$timezone_short = giveasap_get_timezone_short( $timezone );
$style_href = apply_filters( 'giveasap_template_style_href', GASAP_URI . 'public/assets/css/style1.css', $display );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<title><?php echo wp_title(); ?></title>
	<?php if( $description ) { ?>
		<meta name="description" content="<?php echo $description; ?>" />
	<?php } ?>
	<!-- Schema.org markup for Google+ -->
	<meta itemprop="name" content="<?php echo  $title; ?>">
	<?php if( $description ) { ?>
		<meta itemprop="description" content="<?php echo $description; ?>">
	<?php } ?>
	<?php if( $googleImage ) { ?> 
		<meta itemprop="image" content="<?php echo $googleImage; ?>">
	<?php } ?>

	<!-- Twitter Card data -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:site" content="@igorbenic">
	<meta name="twitter:title" content="<?php echo $title; ?>">
	
	<?php if( $description ) { ?>
		<meta name="twitter:description" content="<?php echo $description; ?>">
	<?php } ?>
	
	<meta name="twitter:creator" content="@igorbenic">
	
	<?php if( $twitterImage ) { ?> 

		<!-- Twitter summary card with large image must be at least 280x150px -->
		<meta name="twitter:image:src" content="<?php echo $twitterImage; ?>">

	<?php } ?>

	<!-- Open Graph data -->
	<meta property="og:title" content="<?php  echo $title; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php the_permalink(); ?>" />
	<?php if( $facebookImage ) { ?> 
		<meta property="og:image" content="<?php echo $facebookImage; ?>" />
	<?php } ?>
	<?php if( $description ) { ?>
		<meta property="og:description" content="<?php echo $description; ?>" />
	<?php } ?>
	<meta property="og:site_name" content="" />
	<link rel="stylesheet" href="<?php echo $style_href; ?>">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
	
	<?php if( $giveasap_front->get_step() == 1 && $giveasap_settings['google_site_key'] ) { ?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
	<?php
	}
		
		$thumb = get_post_thumbnail_id( $post->ID );

		$url = false;

		if( $thumb > 0 ) {
			$url = wp_get_attachment_url( $thumb );
		}

	?>
	<style>
		body, html {
			height:100%;
			max-height: 100%;
		}

		body {
			
			<?php if( $url ): ?>
				background: url('<?php echo $url; ?>') no-repeat center center;
				background-size:cover;
				background-attachment: fixed;
			<?php endif; ?>

			<?php if( isset( $display['background_color'] ) && $display['background_color'] != '' ){
				echo 'background-color: ' . $display['background_color'] . ';';
			} ?>
		}
	</style>
</head>

<body <?php body_class(); ?>>


<div class="giveasap <?php echo $template_name; ?>">

	<?php 

		require_once $template_file; 
	?>

</div>
<?php
 wp_reset_postdata(); ?>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>
<script src="<?php echo GASAP_URI . 'public/assets/js/jquery.plugin.min.js'; ?>"></script>
<script src="<?php echo GASAP_URI . 'public/assets/js/jquery.countdown.min.js'; ?>"></script>
<script src="<?php echo GASAP_URI . 'public/assets/js/giveasap.js'; ?>"></script>

</body>
</html>