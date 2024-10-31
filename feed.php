<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct file access

add_action('init', 'churchly_add_podcast_feed');

function churchly_add_podcast_feed(){
	add_feed('podcast', 'churchly_output_podcast_feed');
}

function churchly_output_podcast_feed() {
	
	// Get feed settings
	$feed = churchly_feed_vars();	
	
	// Output feed header
	header('Content-Type: application/xml; charset=utf-8');
	$xml = new DOMDocument("1.0", "UTF-8"); // Create new DOM document.

	// Create RSS element
	$rss = $xml->createElement("rss"); 
	$rss_node = $xml->appendChild($rss); //add RSS element to XML node
	$rss_node->setAttribute("version","2.0"); //set RSS version
	$rss_node->setAttribute("xml:lang","en-us"); //set language
	$rss_node->setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:itunes','http://www.itunes.com/dtds/podcast-1.0.dtd');
	$rss_node->setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:atom','http://www.w3.org/2005/Atom');
	
	/*********************************************
	* CHANNEL
	*********************************************/
	
	// Create channel element
	$channel = $xml->createElement("channel");
    $channel = $rss_node->appendChild($channel);
	
	// Output channel header
	$channel->appendChild($xml->createElement('title',$feed['title']));
    $channel->appendChild($xml->createElement('link',$feed['link']));
    $channel->appendChild($xml->createElement('description',$feed['description']));
    $channel->appendChild($xml->createElement('copyright',$feed['copyright']));
    $channel->appendChild($xml->createElement('language','en-us'));
	if($feed['subtitle'] !== "") {
    $channel->appendChild($xml->createElement('itunes:subtitle',$feed['subtitle']));
	}
    $channel->appendChild($xml->createElement('itunes:author',$feed['author']));
    $channel->appendChild($xml->createElement('itunes:summary',$feed['description']));
    $channelowner = $channel->appendChild($xml->createElement('itunes:owner'));
		$channelowner->appendChild($xml->createElement('itunes:name', $feed['author']));
		$channelowner->appendChild($xml->createElement('itunes:email', $feed['email']));
		
	if($feed['image'] !== "") {
	$channelimage = $channel->appendChild($xml->createElement('itunes:image'));
		$channelimage->setAttribute('href',$feed['image']);
	}
	
	if($feed['category'] !== "") {
	$channelcategory = $channel->appendChild($xml->createElement('itunes:category'));
		$channelcategory->setAttribute('text',$feed['category']);
		
		if($feed['subcategory'] !== "") {
		$channelsubcategory = $channelcategory->appendChild($xml->createElement('itunes:category'));
			$channelsubcategory->setAttribute('text',$feed['subcategory']); 
		}
	}
			
	$channel->appendChild($xml->createElement('itunes:explicit','no'));
	$channel->appendChild($xml->createElement('lastBuildDate', date('r')));
	//$channel->appendChild($xml->createElement('pubDate',date('r')));
	$channel->appendChild($xml->createElement('webMaster',$feed['email']));
	
	
	/*********************************************
	* FEED ITEMS
	*********************************************/
	
	// Fetch sermons from database
	$args = array('post_type' => 'ctc_sermon', "posts_per_page" => 100); 
	$queryObject = new WP_Query( $args ); 
	if ( $queryObject->have_posts() ) :
		while ( $queryObject->have_posts() ) : $queryObject->the_post(); 
		
			global $post;
		
			// Output individual feed items
			if(function_exists('ctfw_sermon_data')) $sermonData = ctfw_sermon_data();
			
			// Only include items with audio
			if(!empty($sermonData['audio_extension']) && $sermonData['audio_extension'] !== "") :
			
				$speakers = ctfw_sermon_speakers(); 
				if ( !empty($speakers)) {
					$speaker = $speakers[0];
					$speakername = $speaker->name;
				} else {
					$speakername = $feed['author']; 
				}
				
				
				// Append item to DOM tree
				$item = $channel->appendChild($xml->createElement('item'));
				
					if($feed['image'] !== "") {
					$item->setAttribute('sdImg',$feed['image']); 
					$item->setAttribute('hdImg',$feed['image']); 
					}
				
				// Add media enclosure
				$enclosure = $item->appendChild($xml->createElement('enclosure'));
					$enclosure->setAttribute('type','audio/mpeg'); 
					$enclosure->setAttribute('length',$sermonData['audio_size_bytes']); 
					$enclosure->setAttribute('url',$sermonData['audio']); 
				
				// Add sermon meta info
				$item->appendChild($xml->createElement('guid', get_post_permalink($post->ID)));
				$item->appendChild($xml->createElement('title', $post->post_title));
				$item->appendChild($xml->createElement('pubDate', get_the_time( 'r', $post )));
				$item->appendChild($xml->createElement('itunes:author', $feed['author']));
				
				if($feed['image'] !== "") {
				$itemimage = $item->appendChild($xml->createElement('itunes:image'));
					$itemimage->setAttribute('href',$feed['image']); 
				}
				
				// Need to figure out a way to get this
				//$item->appendChild($xml->createElement('itunes:duration'));
				
				$item->appendChild($xml->createElement('itunes:subtitle', $speakername));
				$item->appendChild($xml->createElement('itunes:summary', strip_tags($post->post_content)));

			endif;
		endwhile;
	endif;

	echo($xml->saveXML());
	
}