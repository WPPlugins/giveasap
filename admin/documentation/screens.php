<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

function giveasap_documentation(){
	$giveasap_documentation_url = GASAP_URI . '/admin/documentation/';
	$giveasap_documentation_images_url = $giveasap_documentation_url . 'images/';
	$active_tab = 'general';

	if( isset($_GET['tab'])) {
		$active_tab = $_GET['tab'];
	}
	?>
	<div class="wrap">
		<h2><?php _e( 'Simple Giveaways Documentation', 'giveasap'); ?></h2>
		<p class="description"><?php _e( 'Read documentation and find out how to use Simple Giveaways to create giveaways', 'giveasap'); ?></p>
		<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">

			<a href="<?php echo admin_url('edit.php?post_type=giveasap&page=giveasap_documentation&tab=general' ); ?>" class="nav-tab <?php if( $active_tab == 'general' ) { echo 'nav-tab-active'; } ?>"><?php _e( 'General', 'giveasap'); ?></a>
			<a href="<?php echo admin_url('edit.php?post_type=giveasap&page=giveasap_documentation&tab=settings' ); ?>" class="nav-tab <?php if( $active_tab == 'settings' ) { echo 'nav-tab-active'; } ?>"><?php _e( 'Settings', 'giveasap'); ?></a>
			<a href="<?php echo admin_url('edit.php?post_type=giveasap&page=giveasap_documentation&tab=pro' ); ?>" class="nav-tab <?php if( $active_tab == 'pro' ) { echo 'nav-tab-active'; } ?>"><?php _e( 'PRO', 'giveasap'); ?></a>

		</h2>
		<br/>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" style="position: relative;">
					<?php call_user_func( 'giveasap_documentation_' . $active_tab ); ?>
				</div>
				<div id="postbox-container-1" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<a href="https://wordpress.org/support/view/plugin-reviews/giveasap" target="_blank">
							<img style="max-width: 100%;height:auto"; width="280" src="<?php echo $giveasap_documentation_images_url; ?>giveasap-rate.png" />
			
						</a>
						 <?php if( ! defined( 'GIVEASAP_PRO')) { ?>
						<br/>
						<!-- Begin MailChimp Signup Form -->
						<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
						<style type="text/css">
							#mc_embed_signup{ background:#35d294; color:white; clear:left; font:14px Helvetica,Arial,sans-serif;padding:10px; }
							#mc_embed_signup h2 {
								font-size:16px;
								text-align: center;
								color:white;
							}
							#mc_embed_signup form {
								padding:0;
							}
							#mc_embed_signup .mc-field-group {
								width: 100%;
							}
							#mc-embedded-subscribe.button {
								background:#333;
								box-shadow: none;
								width: 100%;
								line-height: 40px;
								height:40px;
							}
							/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
							   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
						</style>
						<div id="mc_embed_signup">
						<form action="//lakotuts.us9.list-manage.com/subscribe/post?u=c2145daf9da8bf458a01a637e&amp;id=de0583d98c" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						    <div id="mc_embed_signup_scroll">
							<h2><?php _e( 'Get a 30% Discount on PRO', 'giveasap' ); ?></h2>
							<p><?php _e( 'You will subscribe to Simple Giveaways list and receive updates about this and others plugins which I have built', 'giveasap' ); ?></p>
						<div class="mc-field-group">
							<label for="mce-EMAIL"><?php _e( 'Email Address', 'giveasap' ); ?></label>
							<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
						</div>
							<div id="mce-responses" class="clear">
								<div class="response" id="mce-error-response" style="display:none"></div>
								<div class="response" id="mce-success-response" style="display:none"></div>
							</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
						    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_c2145daf9da8bf458a01a637e_de0583d98c" tabindex="-1" value=""></div>
						    <div class="clear"><input type="submit" value="<?php _e( 'Subscribe', 'giveasap' ); ?>" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
						    </div>
						</form>
						</div>
						<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
						<!--End mc_embed_signup-->
						<?php } ?>
						<br/>
						<div class="postbox">
							<h2 class="hndle"><span><?php _e( 'About the Author', 'giveasap'); ?></span></h2>
							<div class="inside"> 

								<img style="max-width: 100%;height:auto; display:block; margin:0 auto;border-radius:50%;"; width="100" style="display:block;margin:0 auto;" src="<?php echo $giveasap_documentation_images_url; ?>author.png" />
								<strong>Igor Benić</strong>
								<p><?php _e( 'I am a Web developer who works mostly with WordPress. I am developing free & premium themes and plugins.', 'giveasap' ); ?></p>
								<p><?php printf( __( 'WordPress is more than a CMS to me. I look on it as an application framework on which I love to build custom solutions. One of them is %s.', 'giveasap' ), '<a href="http://www.bookcoverpedia.com" target="_blank">Bookcoverpedia.com</a>' ); ?></p>

								<p><?php printf( __( 'I also teach about WordPress and how to develop with it on my own websites: %s.', 'giveasap' ), '<a href="http://www.ibenic.com" target="_blank">ibenic.com</a>' ); ?></p>

							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
		 
	</div>
	<?php
}

function giveasap_documentation_general() {
	$giveasap_documentation_url = GASAP_URI . '/admin/documentation/';
	$giveasap_documentation_images_url = $giveasap_documentation_url . 'images/';
	?>
	<iframe style="margin: 0 auto; display:block;margin-bottom: 20px;" width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLObNInvLiEdrIMA6y8ifG7a4ImG0kXjS6" frameborder="0" allowfullscreen></iframe>
	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'Giveaway Content', 'giveasap'); ?></span></h2>
		<div class="inside">
			<strong><?php _e( 'Title & URL', 'giveasap'); ?></strong><br/>
			<img style="max-width: 100%;height:auto"; width="600" src="<?php echo $giveasap_documentation_images_url; ?>giveasap_title.png" />
			<ul>
				<li><strong><?php _e( 'Title', 'giveasap'); ?></strong> - <?php _e( 'This will show on the front side as the main giveaway title', 'giveasap'); ?></li>
				<li><strong><?php _e( 'URL', 'giveasap'); ?></strong> - <?php _e( 'Regular WordPress permalink that will show the giveaway. Each giveaway will go under yoursite.com/giveaway', 'giveasap'); ?></li>
			</ul>
		</div>

		<div class="inside">
			<strong><?php _e( 'Content', 'giveasap'); ?></strong><br/>
			<img style="max-width: 100%;height:auto"; width="600" src="<?php echo $giveasap_documentation_images_url; ?>giveasap_content.jpg" />
			<ul>
				<li><strong><?php _e( 'Content', 'giveasap'); ?></strong> - <?php _e( 'Regular WordPress content as used in any post. This will be used for description of your giveaway. Be descriptive and show what your visitor will get if they win.', 'giveasap'); ?></li>
			</ul>
		</div>

	</div>

	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'Giveaway Scheduling', 'giveasap'); ?></span></h2>
		<div class="inside">
			<img style="max-width: 100%;height:auto"; width="600" src="<?php echo $giveasap_documentation_images_url; ?>schedule.png" />
			<ul>
				<li><strong><?php _e( 'Start, End & Winner Date', 'giveasap'); ?></strong> - <?php _e( 'Select a date with the datepicker when the giveaway starts, ends and when the winner is announced', 'giveasap'); ?></li>
				<li><strong><?php _e( 'Start, End & Winner Time', 'giveasap'); ?></strong> - <?php _e( 'Select the time for each of them also using the dropdown.', 'giveasap'); ?></li>
			</ul>
		</div>

	</div>

	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'Prize', 'giveasap'); ?></span></h2>
		<div class="inside">
			<img style="max-width: 100%;height:auto"; width="600" src="<?php echo $giveasap_documentation_images_url; ?>prize.png" />
			<ul>
				<li><strong><?php _e( 'Number of winners', 'giveasap'); ?></strong> - <?php _e( 'Insert a number to define how many winners will be selected.', 'giveasap'); ?></li>
				<li><strong><?php _e( 'Prize', 'giveasap'); ?></strong> - <?php _e( 'Insert the prize title that will be used in emails to your subscribers when they sign up or win.', 'giveasap'); ?></li>
				<li><strong><?php _e( 'Value', 'giveasap'); ?></strong> - <?php _e( 'Insert a value which will be shown on the page of this giveaway.', 'giveasap'); ?></li>
			</ul>
		</div>

	</div>

	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'Text Settings', 'giveasap'); ?></span></h2>
		<div class="inside">
			<strong><?php _e( 'Rules', 'giveasap'); ?></strong><br/>
			<img style="max-width: 100%;height:auto"; width="600" src="<?php echo $giveasap_documentation_images_url; ?>rules.png" />
			<p><?php _e( 'Write your own rules as you want. The more the better. You can hide most of the text by using the "more" tag to dislay a button which when clicked will show the other text.', 'giveasap'); ?></p>
		</div>

		<div class="inside">
			<strong><?php _e( 'Winner Email', 'giveasap'); ?></strong><br/>
			<img style="max-width: 100%;height:auto"; width="600" src="<?php echo $giveasap_documentation_images_url; ?>winner.png" />
			<p><?php _e( 'This email will be sent to the winner or winners. You can write what ever you want here but be very descriptive and precise when it comes to send emails to winners. This is important for them to know how to get their prize.', 'giveasap'); ?></p>
		</div>

	</div>

	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'Users', 'giveasap'); ?></span></h2>
		<div class="inside">
			<strong><?php _e( 'Running Giveaway', 'giveasap'); ?></strong><br/>
			<img style="max-width: 100%;height:auto"; width="250" src="<?php echo $giveasap_documentation_images_url; ?>users.png" />
			<p><?php _e( 'While the giveaway is still running, you can only view which visitors subscribed and also export them into an CSV file.', 'giveasap'); ?></p>
		</div>

		<div class="inside">
			<strong><?php _e( 'Ended Giveaway', 'giveasap'); ?></strong><br/>
			<img style="max-width: 100%;height:auto"; width="250" src="<?php echo $giveasap_documentation_images_url; ?>users-2.png" />
			<p><?php _e( 'When the giveaway has ended, you can click to select Winners alongside everything you could do before.', 'giveasap'); ?></p>
		</div>

		<div class="inside">
			<strong><?php _e( 'Winners Selected', 'giveasap'); ?></strong><br/>
			<img style="max-width: 100%;height:auto"; width="250" src="<?php echo $giveasap_documentation_images_url; ?>users-3.png" />
			<p><?php _e( 'After you have selected the winners, you can also notify them. You can see the winners here and if they were notified already.', 'giveasap'); ?></p>
		</div>

	</div>

	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'Display', 'giveasap'); ?></span></h2>
		<div class="inside">
			<img style="max-width: 100%;height:auto;float:left;margin-right:10px;"; width="250" src="<?php echo $giveasap_documentation_images_url; ?>display.jpg" />
			<ul>
				<li><strong><?php _e( 'Logo', 'giveasap'); ?></strong> - <?php _e( 'Choose the logo image to show on the giveaway page. This can be a logo of the sponsor or even of your site.', 'giveasap'); ?></li>
				<li><strong><?php _e( 'Images', 'giveasap'); ?></strong> - <?php _e( 'Select as many images as you want. You may even order them how you want them to appear.', 'giveasap'); ?></li>
				<li><strong><?php _e( 'Background Color', 'giveasap'); ?></strong> - <?php _e( 'Choose a simple color for the background. If there is no background image, this color will be diplayed. So choose a color that will be gentle to the eyes of your visitors.', 'giveasap'); ?></li>
			</ul>
		</div>
		<br style="clear:both;"/>
		<div class="inside">
			<img style="max-width: 100%;height:auto;float:left;margin-right:10px;"; width="250" src="<?php echo $giveasap_documentation_images_url; ?>display-2.jpg" />
			<p><?php _e( 'All this images are used for social networks when your subscribers share them on their profiles. Create different and funny images so that people will click them. Be aware for the colors to stand out and that are different than the colors of the social network.', 'giveasap'); ?></p>
		</div>
		<br style="clear:both;"/>
		<div class="inside">
			<img style="max-width: 100%;height:auto;float:left;margin-right:10px;"; width="250" src="<?php echo $giveasap_documentation_images_url; ?>display-3.jpg" />
			<p><?php _e( 'You can choose a nice image to show as the background image.', 'giveasap'); ?></p>
		</div>
		<br style="clear:both;"/>
		<br style="clear:both;"/>
	</div>
	<?php

}

function giveasap_documentation_settings() {
	$giveasap_documentation_url = GASAP_URI . '/admin/documentation/';
	$giveasap_documentation_images_url = $giveasap_documentation_url . 'images/';
	?>
	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'General', 'giveasap'); ?></span></h2>
		<div class="inside">
			<img style="max-width: 100%;height:auto"; width="600" src="<?php echo $giveasap_documentation_images_url; ?>settings-email.jpg" />
			<p><?php _e( 'You can use the default text for the email that will welcome your new subscribers and give them both the link to view their entries and also the sharing link. Be sure to use those placeholders if you are writing your own welcome email.', 'giveasap'); ?></p>
		</div>
	</div>

	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'Google Captcha', 'giveasap'); ?></span></h2>
		<div class="inside">
			<img style="max-width: 100%;height:auto"; width="600" src="<?php echo $giveasap_documentation_images_url; ?>google.jpg" />
			<p><?php echo sprintf( __( 'If you want to prevent spammers from subscribing, you can easily set a google captcha by providing your own google catpcha public and secret key. Click here to get it: %s', 'giveasap'), '<a href="https://www.google.com/recaptcha/intro/index.html" target="_blank">Google Captcha</a>'); ?></p>
		</div>
	</div>
	<?php
}

function giveasap_documentation_pro() {
	$giveasap_documentation_url = GASAP_URI . '/admin/documentation/';
	$giveasap_documentation_images_url = $giveasap_documentation_url . 'images/';
	?>
	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'Automate', 'giveasap'); ?></span></h2>
		<div class="inside">
			<img style="max-width: 100%;height:auto"; width="600" src="<?php echo $giveasap_documentation_images_url; ?>automate.png" />
			<p><?php _e( 'Automate everything. Traveling, spending time with your family or something else? You can let WordPress select the Winners and notify them while you are away. Don\'t let your subscribers wait.', 'giveasap'); ?></p>
		</div>
	</div>

	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'Entries', 'giveasap'); ?></span></h2>
		<div class="inside">
			<img style="max-width: 100%;height:auto"; width="600" src="<?php echo $giveasap_documentation_images_url; ?>entries.png" />
			<p><?php _e( 'Reward your subscribers who share your giveaways with extra entries.', 'giveasap' ); ?></p>
		</div>
	</div>

	<div class="postbox">
		<h2 class="hndle"><span><?php _e( 'Templates', 'giveasap'); ?></span></h2>
		<div class="inside">
			<img style="max-width: 100%;height:auto"; width="250" src="<?php echo $giveasap_documentation_images_url; ?>template.png" />
			<p><?php _e( 'Choose different templates and host different giveaways.', 'giveasap' ); ?></p>
		</div>
	</div>
	<?php
}