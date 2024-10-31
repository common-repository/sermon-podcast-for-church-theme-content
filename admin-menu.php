<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct file access

add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' ); 
add_action( 'admin_menu', 'churchly_podcast_site_setup' );

function churchly_podcast_site_setup() {
	
	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function)
	add_options_page( 'Podcast Settings', 'Podcast Settings', 'manage_options', 'churchly-podcast', 'churchly_podcast');
}

function churchly_podcast() { ?>

	<div class="wrap">
		<h1>Podcast Options</h1>
		<form method="post" action="options.php"> 
		
		<?php settings_fields( 'churchly_podcast_options' ); ?>
		<?php do_settings_sections( 'churchly-podcast' ); ?>
		<?php submit_button(); ?>
		</form>
		
		<h3>How to submit your podcast to iTunes</h3>
		<p><label for="churchly-podcast-url">Podcast Feed URL </label><input type="text" name="churchly-podcast-author" size="150" value="<?php echo get_site_url()."?feed=podcast"; ?>" readonly/></p>
		<p>Review the <a href="https://www.apple.com/itunes/podcasts/specs.html" target="_BLANK">specifications</a> for an iTunes podcast.</p>
		<p>Follow <a href="http://www.apple.com/itunes/podcasts/creatorfaq.html" target="_BLANK">these instructions</a> to submit your podcast to the iTunes store.</p>

	</div>
	
	<script type="text/javascript">
		var uploader;
		function upload_image(id) {

		  //Extend the wp.media object
		  uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
			  text: 'Choose Image'
			},
			multiple: false
		  });

		  //When a file is selected, grab the URL and set it as the text field's value
		  uploader.on('select', function() {
			attachment = uploader.state().get('selection').first().toJSON();
			var url = attachment['url'];
			jQuery('.'+id).val(url);
		  });

		  //Open the uploader dialog
		  uploader.open();
		}
	</script>
		
<?php
}

add_action('admin_init', 'churchly_podcast_admin_init');

function churchly_podcast_admin_init(){
	
	if(!is_network_admin()) {
		
		// register_setting( $option_group, $option_name, $sanitize_callback )
		register_setting( 'churchly_podcast_options', 'churchly_podcast_options', 'churchly_podcast_options_validate' );
		
		// add_settings_section( $id, $title, $callback, $page )
		add_settings_section('churchly_podcast_details', 'Podcast Details', 'churchly_podcast_details_callback', 'churchly-podcast');
		
		// add_settings_field( $id, $title, $callback, $page, $section, $args )
		add_settings_field('churchly_podcast_title', 'Podcast Title', 'churchly_podcast_setting_string', 'churchly-podcast', 'churchly_podcast_details', array('churchly_podcast_title'));
		add_settings_field('churchly_podcast_subtitle', 'Podcast Subtitle', 'churchly_podcast_setting_string', 'churchly-podcast', 'churchly_podcast_details', array('churchly_podcast_subtitle'));
		add_settings_field('churchly_podcast_description', 'Podcast Description', 'churchly_podcast_setting_textarea', 'churchly-podcast', 'churchly_podcast_details', array('churchly_podcast_description', 'Up to 4,000 characters'));
		
		
		add_settings_field('churchly_podcast_category', 'Podcast Category', 'churchly_podcast_setting_category', 'churchly-podcast', 'churchly_podcast_details', array('churchly_podcast_category'));
		add_settings_field('churchly_podcast_subcategory', 'Podcast Subcategory', 'churchly_podcast_setting_category', 'churchly-podcast', 'churchly_podcast_details', array('churchly_podcast_subcategory'));
		
		
		add_settings_field('churchly_podcast_author', 'Podcast Author Name', 'churchly_podcast_setting_string', 'churchly-podcast', 'churchly_podcast_details', array('churchly_podcast_author'));
		add_settings_field('churchly_podcast_email', 'Podcast Author Email', 'churchly_podcast_setting_string', 'churchly-podcast', 'churchly_podcast_details', array('churchly_podcast_email'));
		add_settings_field('churchly_podcast_image', 'Podcast Image', 'churchly_podcast_setting_image', 'churchly-podcast', 'churchly_podcast_details', array('churchly_podcast_image'));
	}
}



function churchly_podcast_details_callback() {
	echo "Your sermons are formatted for an iTunes ready podcast. Use the options below to customize your podcast feed.";
}

// Callback for string settings
function churchly_podcast_setting_string($args) {
	$options = get_option('churchly_podcast_options');
	echo "<input id='".$args[0]."' name='churchly_podcast_options[".$args[0]."]' size='40' type='text' value='".(isset($options[''  . $args[0] . '']) ? $options[''  . $args[0] . ''] : null)."' />";
	if(!empty($args[1])) echo "<p><em>".$args[1]."</em></p>";
}

// Callback for textarea settings
function churchly_podcast_setting_textarea($args) {
	$options = get_option('churchly_podcast_options');
	echo "<textarea id='".$args[0]."' name='churchly_podcast_options[".$args[0]."]' cols='42' rows='5'>".(isset($options[''  . $args[0] . '']) ? $options[''  . $args[0] . ''] : null)."</textarea>";
	if(!empty($args[1])) echo "<p><em>".$args[1]."</em></p>";
}

// Callback for image settings
function churchly_podcast_setting_image($args) {
	$options = get_option('churchly_podcast_options');
	echo "<input id='".$args[0]."' name='churchly_podcast_options[".$args[0]."]' class='image_upload' size='40' type='text' value='".(isset($options[''  . $args[0] . '']) ? $options[''  . $args[0] . ''] : null)."' /><a class='button' onclick=\"upload_image('image_upload');\">Upload Image</a>";
	if(!empty($args[1])) echo "<p><em>".$args[1]."</em></p>";
}

// Callback for category settings
function churchly_podcast_setting_category($args) {
	$options = get_option('churchly_podcast_options');
	$categories = churchly_podcast_categories();
	if($args[0] == "churchly_podcast_category") { ?>
		<select name="churchly_podcast_options[<?= $args[0]; ?>]">
			<?php foreach($categories as $category => $subcategories) { ?>
				<option value="<?= $category; ?>"<?= (isset($options[''  . $args[0] . '']) && $options[''  . $args[0] . ''] == $category ? " selected" : null) ?>><?= $category; ?></option>
			<?php } ?>
		</select> <?php
	}
	else if($args[0] == "churchly_podcast_subcategory") { ?>
		<select name="churchly_podcast_options[<?= $args[0]; ?>]">
			<?php foreach($categories as $category => $subcategories) { ?>
				<option value="<?= $category; ?>" disabled="disabled" style="font-weight:bold;"><?= $category; ?></option>
				<?php foreach($subcategories as $subcategory) { ?>
					<option value="<?= $subcategory; ?>"<?= (isset($options[''  . $args[0] . '']) && $options[''  . $args[0] . ''] == $subcategory ? " selected" : null) ?>>--- <?= $subcategory; ?></option>
				<?php } ?>
			<?php } ?>
		</select> <?php
	}
	
	if(!empty($args[1])) echo "<p><em>".$args[1]."</em></p>";
}

function churchly_podcast_options_validate($inputs) {
	$options = get_option('churchly_podcast_options');
	
	foreach($inputs as $input => $value) {
		$options[$input] = sanitize_text_field(trim($inputs[$input]));
		//if(!preg_match('/^[a-z0-9 :~@.\/\-]+$/i', $options[$input])) $options[$input] = '';
	}
	
	return $options;
}