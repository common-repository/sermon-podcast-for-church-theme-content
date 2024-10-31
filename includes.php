<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct file access

// Get podcast settings from plugin options

function churchly_feed_vars() {
	$options = get_option('churchly_podcast_options');
	
	$title = $options['churchly_podcast_title'];
		if(!$title) $title = get_bloginfo('name');
		
	$subtitle = $options['churchly_podcast_subtitle'];
	
	$description = $options['churchly_podcast_description'];
		if(!$description) $description = get_bloginfo('description');
	
	$category = $options['churchly_podcast_category'];
		if(!$category) $category = 'Religion & Spirituality';
	
	$subcategory = $options['churchly_podcast_subcategory'];
		if(!$subcategory) $subcategory = 'Christianity';
		
	$author = $options['churchly_podcast_author'];
		if(!$author) $author = get_bloginfo('name');
	
	$email = $options['churchly_podcast_email'];
		if(!$email) $email = get_bloginfo('admin_email');
	
	$image = $options['churchly_podcast_image'];
		
	$siteurl = get_site_url();
	
	$vars = array(
		'title' => $title,
		'subtitle' => $subtitle,
		'link' => $siteurl,
		'description' => $description,
		'category' => $category,
		'subcategory' => $subcategory,
		'copyright' => 'Copyright '.date('Y').' '.$author,
		'author' => $author,
		'email' => $email,
		'image' => $image,
	);
	
	return apply_filters("churchly_feed_vars", $vars);
}

// Return an array of valid iTunes podcast categories
function churchly_podcast_categories() {

	$categories = array(
		"Arts & Entertainment" => array(
			"Architecture",
			"Books",
			"Design",
			"Entertainment",
			"Games",
			"Performing Arts",
			"Photography",
			"Poetry",
			"Science Fiction",
			"Audio Blogs"),
		"Business" => array(
			"Careers",
			"Finance",
			"Investing",
			"Management",
			"Marketing",
			"Comedy"),
		"Education" => array(
			"K-12",
			"Higher Education"),
		"Food" => array(),
		"Health" => array(
			"Diet & Nutrition",
			"Fitness",
			"Relationships",
			"Self-Help",
			"Sexuality"),
		"International" => array(
			"Australian",
			"Belgian",
			"Brazilian",
			"Canadian",
			"Chinese",
			"Dutch",
			"French",
			"German",
			"Hebrew",
			"Italian",
			"Japanese",
			"Norwegian",
			"Polish",
			"Portuguese",
			"Spanish",
			"Swedish"),
		"Movies & Television" => array(),
		"Music" => array(),
		"News" => array(),
		"Politics" => array(),
		"Public Radio" => array(),
		"Religion & Spirituality" => array(
			"Buddhism",
			"Christianity",
			"Islam",
			"Judaism",
			"New Age",
			"Philosophy",
			"Spirituality"),
		"Science" => array(),
		"Sports" => array(),
		"Talk Radio" => array(),
		"Technology" => array(
			"Computers",
			"Developers",
			"Gadgets",
			"Information Technology",
			"News",
			"Operating Systems",
			"Podcasting",
			"Smart Phones",
			"Text/Speech"),
		"Travel" => array()
	);

	return $categories;
}