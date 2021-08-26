<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'themerex_shortcodes_is_used' ) ) {
	function themerex_shortcodes_is_used() {
		return themerex_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| themerex_vc_is_frontend();															// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'themerex_shortcodes_width' ) ) {
	function themerex_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'trx_addons'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'themerex_shortcodes_height' ) ) {
	function themerex_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'trx_addons'),
			"desc" => esc_html__("Width (in pixels or percent) and height (only in pixels) of element", 'trx_addons'),
			"value" => $h,
			"type" => "text"
		);
	}
}

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_shortcodes_settings_theme_setup' ) ) {
//	if ( themerex_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'themerex_action_before_init_theme', 'themerex_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'themerex_action_after_init_theme', 'themerex_shortcodes_settings_theme_setup' );
	function themerex_shortcodes_settings_theme_setup() {
		if (themerex_shortcodes_is_used()) {
			global $THEMEREX_GLOBALS;

			// Prepare arrays 
			$THEMEREX_GLOBALS['sc_params'] = array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'trx_addons'),
					"desc" => esc_html__("ID for current element", 'trx_addons'),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'trx_addons'),
					"desc" => esc_html__("CSS class for current element (optional)", 'trx_addons'),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'trx_addons'),
					"desc" => esc_html__("Any additional CSS rules (if need)", 'trx_addons'),
					"value" => "",
					"type" => "text"
				),
			
				// Margins params
				'top' => array(
					"title" => esc_html__("Top margin", 'trx_addons'),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				'bottom' => array(
					"title" => esc_html__("Bottom margin", 'trx_addons'),
					"value" => "",
					"type" => "text"
				),
			
				'left' => array(
					"title" => esc_html__("Left margin", 'trx_addons'),
					"value" => "",
					"type" => "text"
				),
			
				'right' => array(
					"title" => esc_html__("Right margin", 'trx_addons'),
					"desc" => esc_html__("Margins around list (in pixels).", 'trx_addons'),
					"value" => "",
					"type" => "text"
				),
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'trx_addons'),
					'ol'	=> esc_html__('Ordered', 'trx_addons'),
					'iconed'=> esc_html__('Iconed', 'trx_addons')
				),
				'yes_no'	=> themerex_get_list_yesno(),
				'on_off'	=> themerex_get_list_onoff(),
				'dir' 		=> themerex_get_list_directions(),
				'align'		=> themerex_get_list_alignments(),
				'float'		=> themerex_get_list_floats(),
				'show_hide'	=> themerex_get_list_showhide(),
				'sorting' 	=> themerex_get_list_sortings(),
				'ordering' 	=> themerex_get_list_orderings(),
				'sliders'	=> themerex_get_list_sliders(),
				'users'		=> themerex_get_list_users(),
				'members'	=> themerex_get_list_posts(false, array('post_type'=>'team', 'orderby'=>'title', 'order'=>'asc', 'return'=>'title')),
				'categories'=> themerex_get_list_categories(),
				'testimonials_groups'=> themerex_get_list_terms(false, 'testimonial_group'),
				'team_groups'=> themerex_get_list_terms(false, 'team_group'),
				'columns'	=> themerex_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), themerex_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), themerex_get_list_icons()),
				'locations'	=> themerex_get_list_dedicated_locations(),
				'filters'	=> themerex_get_list_portfolio_filters(),
				'formats'	=> themerex_get_list_post_formats_filters(),
				'hovers'	=> themerex_get_list_hovers(),
				'hovers_dir'=> themerex_get_list_hovers_directions(),
				'tint'		=> themerex_get_list_bg_tints(),
				'animations'=> themerex_get_list_animations_in(),
				'blogger_styles'	=> themerex_get_list_templates_blogger(),
				'posts_types'		=> themerex_get_list_posts_types(),
				'button_styles'		=> themerex_get_list_button_styles(),
				'googlemap_styles'	=> themerex_get_list_googlemap_styles(),
				'field_types'		=> themerex_get_list_field_types(),
				'label_positions'	=> themerex_get_list_label_positions(),
				'sidebar'	=> themerex_get_list_sidebars()
			);
			$THEMEREX_GLOBALS['sc_params']['animation'] = array(
				"title" => esc_html__("Animation",  'trx_addons'),
				"desc" => esc_html__('Select animation while object enter in the visible area of page',  'trx_addons'),
				"value" => "none",
				"type" => "select",
				"options" => $THEMEREX_GLOBALS['sc_params']['animations']
			);
	
			// Shortcodes list
			//------------------------------------------------------------------
			$THEMEREX_GLOBALS['shortcodes'] = array(
			
				// Accordion
				"trx_accordion" => array(
					"title" => esc_html__("Accordion", 'trx_addons'),
					"desc" => esc_html__("Accordion items", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Accordion style", 'trx_addons'),
							"desc" => esc_html__("Select style for display accordion", 'trx_addons'),
							"value" => 1,
							"options" => array(
								1 => esc_html__('Style 1', 'trx_addons'),
								2 => esc_html__('Style 2', 'trx_addons')
							),
							"type" => "radio"
						),
						"initial" => array(
							"title" => esc_html__("Initially opened item", 'trx_addons'),
							"desc" => esc_html__("Number of initially opened item", 'trx_addons'),
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"icon_closed" => array(
							"title" => esc_html__("Icon while closed",  'trx_addons'),
							"desc" => esc_html__('Select icon for the closed accordion item from Fontello icons set',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => esc_html__("Icon while opened",  'trx_addons'),
							"desc" => esc_html__('Select icon for the opened accordion item from Fontello icons set',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_accordion_item",
						"title" => esc_html__("Item", 'trx_addons'),
						"desc" => esc_html__("Accordion item", 'trx_addons'),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Accordion item title", 'trx_addons'),
								"desc" => esc_html__("Title for current accordion item", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"icon_closed" => array(
								"title" => esc_html__("Icon while closed",  'trx_addons'),
								"desc" => esc_html__('Select icon for the closed accordion item from Fontello icons set',  'trx_addons'),
								"value" => "",
								"type" => "icons",
								"options" => $THEMEREX_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => esc_html__("Icon while opened",  'trx_addons'),
								"desc" => esc_html__('Select icon for the opened accordion item from Fontello icons set',  'trx_addons'),
								"value" => "",
								"type" => "icons",
								"options" => $THEMEREX_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => esc_html__("Accordion item content", 'trx_addons'),
								"desc" => esc_html__("Current accordion item content", 'trx_addons'),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $THEMEREX_GLOBALS['sc_params']['id'],
							"class" => $THEMEREX_GLOBALS['sc_params']['class'],
							"css" => $THEMEREX_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Anchor
				"trx_anchor" => array(
					"title" => esc_html__("Anchor", 'trx_addons'),
					"desc" => esc_html__("Insert anchor for the TOC (table of content)", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => esc_html__("Anchor's icon",  'trx_addons'),
							"desc" => esc_html__('Select icon for the anchor from Fontello icons set',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"title" => array(
							"title" => esc_html__("Short title", 'trx_addons'),
							"desc" => esc_html__("Short title of the anchor (for the table of content)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Long description", 'trx_addons'),
							"desc" => esc_html__("Description for the popup (then hover on the icon). You can use '{' and '}' - make the text italic, '|' - insert line break", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"url" => array(
							"title" => esc_html__("External URL", 'trx_addons'),
							"desc" => esc_html__("External URL for this TOC item", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"separator" => array(
							"title" => esc_html__("Add separator", 'trx_addons'),
							"desc" => esc_html__("Add separator under item in the TOC", 'trx_addons'),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"id" => $THEMEREX_GLOBALS['sc_params']['id']
					)
				),
			
			
				// Audio
				"trx_audio" => array(
					"title" => esc_html__("Audio", 'trx_addons'),
					"desc" => esc_html__("Insert audio player", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for audio file", 'trx_addons'),
							"desc" => esc_html__("URL for audio file", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => esc_html__('Choose audio', 'trx_addons'),
								'action' => 'media_upload',
								'type' => 'audio',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => esc_html__('Choose audio file', 'trx_addons'),
									'update' => esc_html__('Select audio file', 'trx_addons')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"image" => array(
							"title" => esc_html__("Cover image", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for audio cover", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => esc_html__("Title", 'trx_addons'),
							"desc" => esc_html__("Title of the audio file", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"author" => array(
							"title" => esc_html__("Author", 'trx_addons'),
							"desc" => esc_html__("Author of the audio file", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => esc_html__("Show controls", 'trx_addons'),
							"desc" => esc_html__("Show controls in audio player", 'trx_addons'),
							"divider" => true,
							"size" => "medium",
							"value" => "show",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['show_hide']
						),
						"autoplay" => array(
							"title" => esc_html__("Autoplay audio", 'trx_addons'),
							"desc" => esc_html__("Autoplay audio on page load", 'trx_addons'),
							"value" => "off",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => esc_html__("Align", 'trx_addons'),
							"desc" => esc_html__("Select block alignment", 'trx_addons'),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Block
				"trx_block" => array(
					"title" => esc_html__("Block container", 'trx_addons'),
					"desc" => esc_html__("Container for any block ([section] analog - to enable nesting)", 'trx_addons'),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => esc_html__("Dedicated", 'trx_addons'),
							"desc" => esc_html__("Use this block as dedicated content - show it before post title on single page", 'trx_addons'),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Align", 'trx_addons'),
							"desc" => esc_html__("Select block alignment", 'trx_addons'),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => esc_html__("Columns emulation", 'trx_addons'),
							"desc" => esc_html__("Select width for columns emulation", 'trx_addons'),
							"value" => "none",
							"type" => "checklist",
							"options" => $THEMEREX_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => esc_html__("Use pan effect", 'trx_addons'),
							"desc" => esc_html__("Use pan effect to show section content", 'trx_addons'),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", 'trx_addons'),
							"desc" => esc_html__("Use scroller to show section content", 'trx_addons'),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => esc_html__("Scroll direction", 'trx_addons'),
							"desc" => esc_html__("Scroll direction (if Use scroller = yes)", 'trx_addons'),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => esc_html__("Scroll controls", 'trx_addons'),
							"desc" => esc_html__("Show scroll controls (if Use scroller = yes)", 'trx_addons'),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"color" => array(
							"title" => esc_html__("Fore color", 'trx_addons'),
							"desc" => esc_html__("Any color for objects in this section", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => esc_html__("Background tint", 'trx_addons'),
							"desc" => esc_html__("Main background tint: dark or light", 'trx_addons'),
							"value" => "",
							"type" => "checklist",
							"options" => $THEMEREX_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'trx_addons'),
							"desc" => esc_html__("Any background color for this section", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", 'trx_addons'),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", 'trx_addons'),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", 'trx_addons'),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", 'trx_addons'),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", 'trx_addons'),
							"desc" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", 'trx_addons'),
							"desc" => esc_html__("Font weight of the text", 'trx_addons'),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => esc_html__('Thin (100)', 'trx_addons'),
								'300' => esc_html__('Light (300)', 'trx_addons'),
								'400' => esc_html__('Normal (400)', 'trx_addons'),
								'700' => esc_html__('Bold (700)', 'trx_addons')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Container content", 'trx_addons'),
							"desc" => esc_html__("Content for section container", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Blogger
				"trx_blogger" => array(
					"title" => esc_html__("Blogger", 'trx_addons'),
					"desc" => esc_html__("Insert posts (pages) in many styles from desired categories or directly from ids", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Posts output style", 'trx_addons'),
							"desc" => esc_html__("Select desired style for posts output", 'trx_addons'),
							"value" => "regular",
							"type" => "select",
							"options" => $THEMEREX_GLOBALS['sc_params']['blogger_styles']
						),
						"filters" => array(
							"title" => esc_html__("Show filters", 'trx_addons'),
							"desc" => esc_html__("Use post's tags or categories as filter buttons", 'trx_addons'),
							"value" => "no",
							"dir" => "horizontal",
							"type" => "checklist",
							"options" => $THEMEREX_GLOBALS['sc_params']['filters']
						),
						"dir" => array(
							"title" => esc_html__("Posts direction", 'trx_addons'),
							"desc" => esc_html__("Display posts in horizontal or vertical direction", 'trx_addons'),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['dir']
						),
						"post_type" => array(
							"title" => esc_html__("Post type", 'trx_addons'),
							"desc" => esc_html__("Select post type to show", 'trx_addons'),
							"value" => "post",
							"type" => "select",
							"options" => $THEMEREX_GLOBALS['sc_params']['posts_types']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'trx_addons'),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"cat" => array(
							"title" => esc_html__("Categories list", 'trx_addons'),
							"desc" => esc_html__("Select the desired categories. If not selected - show posts from any category or from IDs list", 'trx_addons'),
							"dependency" => array(
								'ids' => array('is_empty'),
								'post_type' => array('refresh')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $THEMEREX_GLOBALS['sc_params']['categories']
						),
						"count" => array(
							"title" => esc_html__("Total posts to show", 'trx_addons'),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_addons'),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns number", 'trx_addons'),
							"desc" => esc_html__("How many columns used to show posts? If empty or 0 - equal to posts number", 'trx_addons'),
							"dependency" => array(
								'dir' => array('horizontal')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'trx_addons'),
							"desc" => esc_html__("Skip posts before select next part.", 'trx_addons'),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'trx_addons'),
							"desc" => esc_html__("Select desired posts sorting method", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => $THEMEREX_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", 'trx_addons'),
							"desc" => esc_html__("Select desired posts order", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						),
						"only" => array(
							"title" => esc_html__("Select posts only", 'trx_addons'),
							"desc" => esc_html__("Select posts only with reviews, videos, audios, thumbs or galleries", 'trx_addons'),
							"value" => "no",
							"type" => "select",
							"options" => $THEMEREX_GLOBALS['sc_params']['formats']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", 'trx_addons'),
							"desc" => esc_html__("Use scroller to show all posts", 'trx_addons'),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Show slider controls", 'trx_addons'),
							"desc" => esc_html__("Show arrows to control scroll slider", 'trx_addons'),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"location" => array(
							"title" => esc_html__("Dedicated content location", 'trx_addons'),
							"desc" => esc_html__("Select position for dedicated content (only for style=excerpt)", 'trx_addons'),
							"divider" => true,
							"dependency" => array(
								'style' => array('excerpt')
							),
							"value" => "default",
							"type" => "select",
							"options" => $THEMEREX_GLOBALS['sc_params']['locations']
						),
						"rating" => array(
							"title" => esc_html__("Show rating stars", 'trx_addons'),
							"desc" => esc_html__("Show rating stars under post's header", 'trx_addons'),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"info" => array(
							"title" => esc_html__("Show post info block", 'trx_addons'),
							"desc" => esc_html__("Show post info block (author, date, tags, etc.)", 'trx_addons'),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"links" => array(
							"title" => esc_html__("Allow links on the post", 'trx_addons'),
							"desc" => esc_html__("Allow links on the post from each blogger item", 'trx_addons'),
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"descr" => array(
							"title" => esc_html__("Description length", 'trx_addons'),
							"desc" => esc_html__("How many characters are displayed from post excerpt? If 0 - don't show description", 'trx_addons'),
							"value" => 0,
							"min" => 0,
							"step" => 10,
							"type" => "spinner"
						),
						"readmore" => array(
							"title" => esc_html__("More link text", 'trx_addons'),
							"desc" => esc_html__("Read more link text. If empty - show 'More', else - used as link text", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Br
				"trx_br" => array(
					"title" => esc_html__("Break", 'trx_addons'),
					"desc" => esc_html__("Line break with clear floating (if need)", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"clear" => 	array(
							"title" => esc_html__("Clear floating", 'trx_addons'),
							"desc" => esc_html__("Clear floating (if need)", 'trx_addons'),
							"value" => "",
							"type" => "checklist",
							"options" => array(
								'none' => esc_html__('None', 'trx_addons'),
								'left' => esc_html__('Left', 'trx_addons'),
								'right' => esc_html__('Right', 'trx_addons'),
								'both' => esc_html__('Both', 'trx_addons')
							)
						)
					)
				),
			
			
			
			
				// Button
				"trx_button" => array(
					"title" => esc_html__("Button", 'trx_addons'),
					"desc" => esc_html__("Button with link", 'trx_addons'),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Caption", 'trx_addons'),
							"desc" => esc_html__("Button caption", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"type" => array(
							"title" => esc_html__("Button's shape", 'trx_addons'),
							"desc" => esc_html__("Select button's shape", 'trx_addons'),
							"value" => "square",
							"size" => "medium",
							"options" => array(
								'square' => esc_html__('Square', 'trx_addons'),
								'round' => esc_html__('Round', 'trx_addons')
							),
							"type" => "switch"
						),
						"icon" => array(
							"title" => esc_html__("Button's icon",  'trx_addons'),
							"desc" => esc_html__('Select icon for the title from Fontello icons set',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"bg_style" => array(
							"title" => esc_html__("Button's color scheme", 'trx_addons'),
							"desc" => esc_html__("Select button's color scheme", 'trx_addons'),
							"value" => "custom",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['button_styles']
						), 
						"color" => array(
							"title" => esc_html__("Button's text color", 'trx_addons'),
							"desc" => esc_html__("Any color for button's caption", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Button's backcolor", 'trx_addons'),
							"desc" => esc_html__("Any color for button's background", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"align" => array(
							"title" => esc_html__("Button's alignment", 'trx_addons'),
							"desc" => esc_html__("Align button to left, center or right", 'trx_addons'),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => esc_html__("Link URL", 'trx_addons'),
							"desc" => esc_html__("URL for link on button click", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"target" => array(
							"title" => esc_html__("Link target", 'trx_addons'),
							"desc" => esc_html__("Target for link on button click", 'trx_addons'),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"popup" => array(
							"title" => esc_html__("Open link in popup", 'trx_addons'),
							"desc" => esc_html__("Open link target in popup window", 'trx_addons'),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						), 
						"rel" => array(
							"title" => esc_html__("Rel attribute", 'trx_addons'),
							"desc" => esc_html__("Rel attribute for button's link (if need)", 'trx_addons'),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Chat
				"trx_chat" => array(
					"title" => esc_html__("Chat", 'trx_addons'),
					"desc" => esc_html__("Chat message", 'trx_addons'),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Item title", 'trx_addons'),
							"desc" => esc_html__("Chat item title", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"photo" => array(
							"title" => esc_html__("Item photo", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for the item photo (avatar)", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"link" => array(
							"title" => esc_html__("Item link", 'trx_addons'),
							"desc" => esc_html__("Chat item link", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Chat item content", 'trx_addons'),
							"desc" => esc_html__("Current chat item content", 'trx_addons'),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Columns
				"trx_columns" => array(
					"title" => esc_html__("Columns", 'trx_addons'),
					"desc" => esc_html__("Insert up to 5 columns in your page (post)", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"autoheight" => array(
							"title" => esc_html__("Auto Height", 'trx_addons'),
							"desc" => esc_html__("Fit height to the larger value of child elements (for background images)", 'trx_addons'),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"indentation" => array(
							"title" => esc_html__("Indentation", 'trx_addons'),
							"desc" => esc_html__("Column is indented", 'trx_addons'),
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"fluid" => array(
							"title" => esc_html__("Fluid columns", 'trx_addons'),
							"desc" => esc_html__("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", 'trx_addons'),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						), 
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_column_item",
						"title" => esc_html__("Column", 'trx_addons'),
						"desc" => esc_html__("Column item", 'trx_addons'),
						"container" => true,
						"params" => array(
							"span" => array(
								"title" => esc_html__("Merge columns", 'trx_addons'),
								"desc" => esc_html__("Count merged columns from current", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"align" => array(
								"title" => esc_html__("Alignment", 'trx_addons'),
								"desc" => esc_html__("Alignment text in the column", 'trx_addons'),
								"value" => "",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $THEMEREX_GLOBALS['sc_params']['align']
							),
							"color" => array(
								"title" => esc_html__("Fore color", 'trx_addons'),
								"desc" => esc_html__("Any color for objects in this column", 'trx_addons'),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => esc_html__("Background color", 'trx_addons'),
								"desc" => esc_html__("Any background color for this column", 'trx_addons'),
								"value" => "",
								"type" => "color"
							),
							"bg_image" => array(
								"title" => esc_html__("URL for background image file", 'trx_addons'),
								"desc" => esc_html__("Select or upload image or write URL from other site for the background", 'trx_addons'),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Column item content", 'trx_addons'),
								"desc" => esc_html__("Current column item content", 'trx_addons'),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $THEMEREX_GLOBALS['sc_params']['id'],
							"class" => $THEMEREX_GLOBALS['sc_params']['class'],
							"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
							"css" => $THEMEREX_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Contact form
				"trx_contact_form" => array(
					"title" => esc_html__("Contact form", 'trx_addons'),
					"desc" => esc_html__("Insert contact form", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"custom" => array(
							"title" => esc_html__("Custom", 'trx_addons'),
							"desc" => esc_html__("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", 'trx_addons'),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"action" => array(
							"title" => esc_html__("Action", 'trx_addons'),
							"desc" => esc_html__("Contact form action (URL to handle form data). If empty - use internal action", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Align", 'trx_addons'),
							"desc" => esc_html__("Select form alignment", 'trx_addons'),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						),
						"title" => array(
							"title" => esc_html__("Title", 'trx_addons'),
							"desc" => esc_html__("Contact form title", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'trx_addons'),
							"desc" => esc_html__("Short description for contact form", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => themerex_shortcodes_width(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_form_item",
						"title" => esc_html__("Field", 'trx_addons'),
						"desc" => esc_html__("Custom field", 'trx_addons'),
						"container" => false,
						"params" => array(
							"type" => array(
								"title" => esc_html__("Type", 'trx_addons'),
								"desc" => esc_html__("Type of the custom field", 'trx_addons'),
								"value" => "text",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $THEMEREX_GLOBALS['sc_params']['field_types']
							), 
							"name" => array(
								"title" => esc_html__("Name", 'trx_addons'),
								"desc" => esc_html__("Name of the custom field", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => esc_html__("Default value", 'trx_addons'),
								"desc" => esc_html__("Default value of the custom field", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"label" => array(
								"title" => esc_html__("Label", 'trx_addons'),
								"desc" => esc_html__("Label for the custom field", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"label_position" => array(
								"title" => esc_html__("Label position", 'trx_addons'),
								"desc" => esc_html__("Label position relative to the field", 'trx_addons'),
								"value" => "top",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $THEMEREX_GLOBALS['sc_params']['label_positions']
							), 
							"top" => $THEMEREX_GLOBALS['sc_params']['top'],
							"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
							"left" => $THEMEREX_GLOBALS['sc_params']['left'],
							"right" => $THEMEREX_GLOBALS['sc_params']['right'],
							"id" => $THEMEREX_GLOBALS['sc_params']['id'],
							"class" => $THEMEREX_GLOBALS['sc_params']['class'],
							"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
							"css" => $THEMEREX_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Content block on fullscreen page
				"trx_content" => array(
					"title" => esc_html__("Content block", 'trx_addons'),
					"desc" => esc_html__("Container for main content block with desired class and style (use it only on fullscreen pages)", 'trx_addons'),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"font_size" => array(
							"title" => esc_html__("Font size", 'trx_addons'),
							"desc" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", 'trx_addons'),
							"desc" => esc_html__("Font weight of the text", 'trx_addons'),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => esc_html__('Thin (100)', 'trx_addons'),
								'300' => esc_html__('Light (300)', 'trx_addons'),
								'400' => esc_html__('Normal (400)', 'trx_addons'),
								'700' => esc_html__('Bold (700)', 'trx_addons')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Container content", 'trx_addons'),
							"desc" => esc_html__("Content for section container", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Countdown
				"trx_countdown" => array(
					"title" => esc_html__("Countdown", 'trx_addons'),
					"desc" => esc_html__("Insert countdown object", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"date" => array(
							"title" => esc_html__("Date", 'trx_addons'),
							"desc" => esc_html__("Upcoming date (format: yyyy-mm-dd)", 'trx_addons'),
							"value" => "",
							"format" => "yy-mm-dd",
							"type" => "date"
						),
						"time" => array(
							"title" => esc_html__("Time", 'trx_addons'),
							"desc" => esc_html__("Upcoming time (format: HH:mm:ss)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("Style", 'trx_addons'),
							"desc" => esc_html__("Countdown style", 'trx_addons'),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								1 => esc_html__('Style 1', 'trx_addons'),
								2 => esc_html__('Style 2', 'trx_addons')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_addons'),
							"desc" => esc_html__("Align counter to left, center or right", 'trx_addons'),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						), 
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Dropcaps
				"trx_dropcaps" => array(
					"title" => esc_html__("Dropcaps", 'trx_addons'),
					"desc" => esc_html__("Make first letter as dropcaps", 'trx_addons'),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", 'trx_addons'),
							"desc" => esc_html__("Dropcaps style", 'trx_addons'),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								1 => esc_html__('Style 1', 'trx_addons'),
								2 => esc_html__('Style 2', 'trx_addons'),
								3 => esc_html__('Style 3', 'trx_addons'),
								4 => esc_html__('Style 4', 'trx_addons')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Paragraph content", 'trx_addons'),
							"desc" => esc_html__("Paragraph with dropcaps content", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Emailer
				"trx_emailer" => array(
					"title" => esc_html__("E-mail collector", 'trx_addons'),
					"desc" => esc_html__("Collect the e-mail address into specified group", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"group" => array(
							"title" => esc_html__("Group", 'trx_addons'),
							"desc" => esc_html__("The name of group to collect e-mail address", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"open" => array(
							"title" => esc_html__("Open", 'trx_addons'),
							"desc" => esc_html__("Initially open the input field on show object", 'trx_addons'),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_addons'),
							"desc" => esc_html__("Align object to left, center or right", 'trx_addons'),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						), 
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),



				// Events
				"trx_events" => array(
					"title" => esc_html__("Events", 'trx_addons'),
					"desc" => esc_html__("Insert posts (events) from desired categories or directly from ids", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'trx_addons'),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"cat" => array(
							"title" => esc_html__("Category ID", 'trx_addons'),
							"desc" => esc_html__("Category ID", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"count" => array(
							"title" => esc_html__("Total posts to show", 'trx_addons'),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored. (max - 4)", 'trx_addons'),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 3,
							"min" => 1,
							"max" => 4,
							"type" => "spinner"
						),
						"col" => array(
							"title" => esc_html__("Column", 'trx_addons'),
							"desc" => esc_html__("How many columns will be displayed? (max - 4)", 'trx_addons'),
							"value" => 3,
							"min" => 1,
							"max" => 4,
							"type" => "spinner"
						),
						"order" => array(
							"title" => esc_html__("Post order", 'trx_addons'),
							"desc" => esc_html__("Select desired posts order", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						),

						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right']
					)
				),




				// Gallery
				"trx_gallery" => array(
					"title" => esc_html__("Gallery", 'trx_addons'),
					"desc" => esc_html__("Insert gallery into your post (page)", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"cat" => array(
							"title" => esc_html__("Category list", 'trx_addons'),
							"desc" => esc_html__("Comma separated list of category slugs. If empty - select posts from any category or from IDs list", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $THEMEREX_GLOBALS['sc_params']['categories']
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'trx_addons'),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_addons'),
							"value" => 10,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'trx_addons'),
							"desc" => esc_html__("Skip posts before select next part.", 'trx_addons'),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'trx_addons'),
							"desc" => esc_html__("Select desired posts sorting method", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => $THEMEREX_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", 'trx_addons'),
							"desc" => esc_html__("Select desired posts order", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'trx_addons'),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"descriptions" => array(
							"title" => esc_html__("Post descriptions", 'trx_addons'),
							"desc" => esc_html__("Show post's excerpt max length (characters)", 'trx_addons'),
							"value" => 100,
							"min" => 0,
							"max" => 1000,
							"step" => 10,
							"type" => "spinner"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'trx_addons'),
							"desc" => esc_html__("Select color for gallery background", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for the gallery background", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation']
					)
				),



			
				// Gap
				"trx_gap" => array(
					"title" => esc_html__("Gap", 'trx_addons'),
					"desc" => esc_html__("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", 'trx_addons'),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Gap content", 'trx_addons'),
							"desc" => esc_html__("Gap inner content", 'trx_addons'),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						)
					)
				),
			
			
			
			
			
				// Google map
				"trx_googlemap" => array(
					"title" => esc_html__("Google map", 'trx_addons'),
					"desc" => esc_html__("Insert Google map with desired address or coordinates", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"address" => array(
							"title" => esc_html__("Address", 'trx_addons'),
							"desc" => esc_html__("Address to show in map center", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"latlng" => array(
							"title" => esc_html__("Latitude and Longtitude", 'trx_addons'),
							"desc" => esc_html__("Comma separated map center coorditanes (instead Address)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"zoom" => array(
							"title" => esc_html__("Zoom", 'trx_addons'),
							"desc" => esc_html__("Map zoom factor", 'trx_addons'),
							"divider" => true,
							"value" => 16,
							"min" => 1,
							"max" => 20,
							"type" => "spinner"
						),
						"style" => array(
							"title" => esc_html__("Map style", 'trx_addons'),
							"desc" => esc_html__("Select map style", 'trx_addons'),
							"value" => "default",
							"type" => "checklist",
							"options" => $THEMEREX_GLOBALS['sc_params']['googlemap_styles']
						),
						"width" => themerex_shortcodes_width('100%'),
						"height" => themerex_shortcodes_height(240),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Hide or show any block
				"trx_hide" => array(
					"title" => esc_html__("Hide/Show any block", 'trx_addons'),
					"desc" => esc_html__("Hide or Show any block with desired CSS-selector", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"selector" => array(
							"title" => esc_html__("Selector", 'trx_addons'),
							"desc" => esc_html__("Any block's CSS-selector", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"hide" => array(
							"title" => esc_html__("Hide or Show", 'trx_addons'),
							"desc" => esc_html__("New state for the block: hide or show", 'trx_addons'),
							"value" => "yes",
							"size" => "small",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						)
					)
				),
			
			
			
				// Highlght text
				"trx_highlight" => array(
					"title" => esc_html__("Highlight text", 'trx_addons'),
					"desc" => esc_html__("Highlight text with selected color, background color and other styles", 'trx_addons'),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"type" => array(
							"title" => esc_html__("Type", 'trx_addons'),
							"desc" => esc_html__("Highlight type", 'trx_addons'),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								0 => esc_html__('Custom', 'trx_addons'),
								1 => esc_html__('Type 1', 'trx_addons'),
								2 => esc_html__('Type 2', 'trx_addons'),
								3 => esc_html__('Type 3', 'trx_addons')
							)
						),
						"color" => array(
							"title" => esc_html__("Color", 'trx_addons'),
							"desc" => esc_html__("Color for the highlighted text", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'trx_addons'),
							"desc" => esc_html__("Background color for the highlighted text", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", 'trx_addons'),
							"desc" => esc_html__("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Highlighting content", 'trx_addons'),
							"desc" => esc_html__("Content for highlight", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Icon
				"trx_icon" => array(
					"title" => esc_html__("Icon", 'trx_addons'),
					"desc" => esc_html__("Insert icon", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => esc_html__('Icon',  'trx_addons'),
							"desc" => esc_html__('Select font icon from the Fontello icons set',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => esc_html__("Icon's color", 'trx_addons'),
							"desc" => esc_html__("Icon's color", 'trx_addons'),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "color"
						),
						"bg_shape" => array(
							"title" => esc_html__("Background shape", 'trx_addons'),
							"desc" => esc_html__("Shape of the icon background", 'trx_addons'),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "none",
							"type" => "radio",
							"options" => array(
								'none' => esc_html__('None', 'trx_addons'),
								'round' => esc_html__('Round', 'trx_addons'),
								'square' => esc_html__('Square', 'trx_addons')
							)
						),
						"bg_color" => array(
							"title" => esc_html__("Icon's background color", 'trx_addons'),
							"desc" => esc_html__("Icon's background color", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", 'trx_addons'),
							"desc" => esc_html__("Overlay bg color opacity (from 0.0 to 1.0)", 'trx_addons'),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", 'trx_addons'),
							"desc" => esc_html__("Icon's font size", 'trx_addons'),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "spinner",
							"min" => 8,
							"max" => 240
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", 'trx_addons'),
							"desc" => esc_html__("Icon font weight", 'trx_addons'),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => esc_html__('Thin (100)', 'trx_addons'),
								'300' => esc_html__('Light (300)', 'trx_addons'),
								'400' => esc_html__('Normal (400)', 'trx_addons'),
								'700' => esc_html__('Bold (700)', 'trx_addons')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_addons'),
							"desc" => esc_html__("Icon text alignment", 'trx_addons'),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => esc_html__("Link URL", 'trx_addons'),
							"desc" => esc_html__("Link URL from this icon (if not empty)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Image
				"trx_image" => array(
					"title" => esc_html__("Image", 'trx_addons'),
					"desc" => esc_html__("Insert image into your post (page)", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for image file", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => esc_html__("Title", 'trx_addons'),
							"desc" => esc_html__("Image title (if need)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => esc_html__("Icon before title",  'trx_addons'),
							"desc" => esc_html__('Select icon for the title from Fontello icons set',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"align" => array(
							"title" => esc_html__("Float image", 'trx_addons'),
							"desc" => esc_html__("Float image to left or right side", 'trx_addons'),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							//"options" => $THEMEREX_GLOBALS['sc_params']['float']
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						), 
						"shape" => array(
							"title" => esc_html__("Image Shape", 'trx_addons'),
							"desc" => esc_html__("Shape of the image: square (rectangle) or round", 'trx_addons'),
							"value" => "square",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"square" => esc_html__('Square', 'trx_addons'),
								"round" => esc_html__('Round', 'trx_addons')
							)
						),
						"link" => array(
							"title" => esc_html__("Link URL", 'trx_addons'),
							"desc" => esc_html__("Link URL for the title", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Infobox
				"trx_infobox" => array(
					"title" => esc_html__("Infobox", 'trx_addons'),
					"desc" => esc_html__("Insert infobox into your post (page)", 'trx_addons'),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", 'trx_addons'),
							"desc" => esc_html__("Infobox style", 'trx_addons'),
							"value" => "regular",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'regular' => esc_html__('Regular', 'trx_addons'),
								'info' => esc_html__('Info', 'trx_addons'),
								'success' => esc_html__('Success', 'trx_addons'),
								'error' => esc_html__('Error', 'trx_addons')
							)
						),
						"closeable" => array(
							"title" => esc_html__("Closeable box", 'trx_addons'),
							"desc" => esc_html__("Create closeable box (with close button)", 'trx_addons'),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"icon" => array(
							"title" => esc_html__("Custom icon",  'trx_addons'),
							"desc" => esc_html__('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => esc_html__("Text color", 'trx_addons'),
							"desc" => esc_html__("Any color for text and headers", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'trx_addons'),
							"desc" => esc_html__("Any background color for this infobox", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"_content_" => array(
							"title" => esc_html__("Infobox content", 'trx_addons'),
							"desc" => esc_html__("Content for infobox", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Line
				"trx_line" => array(
					"title" => esc_html__("Line", 'trx_addons'),
					"desc" => esc_html__("Insert Line into your post (page)", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", 'trx_addons'),
							"desc" => esc_html__("Line style", 'trx_addons'),
							"value" => "solid",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'solid' => esc_html__('Solid', 'trx_addons'),
								'dashed' => esc_html__('Dashed', 'trx_addons'),
								'dotted' => esc_html__('Dotted', 'trx_addons'),
								'double' => esc_html__('Double', 'trx_addons')
							)
						),
						"color" => array(
							"title" => esc_html__("Color", 'trx_addons'),
							"desc" => esc_html__("Line color", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// List
				"trx_list" => array(
					"title" => esc_html__("List", 'trx_addons'),
					"desc" => esc_html__("List items with specific bullets", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Bullet's style", 'trx_addons'),
							"desc" => esc_html__("Bullet's style for each list item", 'trx_addons'),
							"value" => "ul",
							"type" => "checklist",
							"options" => $THEMEREX_GLOBALS['sc_params']['list_styles']
						), 
						"color" => array(
							"title" => esc_html__("Color", 'trx_addons'),
							"desc" => esc_html__("List items color", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => esc_html__('List icon',  'trx_addons'),
							"desc" => esc_html__("Select list icon from Fontello icons set (only for style=Iconed)",  'trx_addons'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"icon_color" => array(
							"title" => esc_html__("Icon color", 'trx_addons'),
							"desc" => esc_html__("List icons color", 'trx_addons'),
							"value" => "",
							"dependency" => array(
								'style' => array('iconed')
							),
							"type" => "color"
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_list_item",
						"title" => esc_html__("Item", 'trx_addons'),
						"desc" => esc_html__("List item with specific bullet", 'trx_addons'),
						"decorate" => false,
						"container" => true,
						"params" => array(
							"_content_" => array(
								"title" => esc_html__("List item content", 'trx_addons'),
								"desc" => esc_html__("Current list item content", 'trx_addons'),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"title" => array(
								"title" => esc_html__("List item title", 'trx_addons'),
								"desc" => esc_html__("Current list item title (show it as tooltip)", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"color" => array(
								"title" => esc_html__("Color", 'trx_addons'),
								"desc" => esc_html__("Text color for this item", 'trx_addons'),
								"value" => "",
								"type" => "color"
							),
							"icon" => array(
								"title" => esc_html__('List icon',  'trx_addons'),
								"desc" => esc_html__("Select list item icon from Fontello icons set (only for style=Iconed)",  'trx_addons'),
								"value" => "",
								"type" => "icons",
								"options" => $THEMEREX_GLOBALS['sc_params']['icons']
							),
							"icon_color" => array(
								"title" => esc_html__("Icon color", 'trx_addons'),
								"desc" => esc_html__("Icon color for this item", 'trx_addons'),
								"value" => "",
								"type" => "color"
							),
							"link" => array(
								"title" => esc_html__("Link URL", 'trx_addons'),
								"desc" => esc_html__("Link URL for the current list item", 'trx_addons'),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"target" => array(
								"title" => esc_html__("Link target", 'trx_addons'),
								"desc" => esc_html__("Link target for the current list item", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"id" => $THEMEREX_GLOBALS['sc_params']['id'],
							"class" => $THEMEREX_GLOBALS['sc_params']['class'],
							"css" => $THEMEREX_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
				// Number
				"trx_number" => array(
					"title" => esc_html__("Number", 'trx_addons'),
					"desc" => esc_html__("Insert number or any word as set separate characters", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"value" => array(
							"title" => esc_html__("Value", 'trx_addons'),
							"desc" => esc_html__("Number or any word", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Align", 'trx_addons'),
							"desc" => esc_html__("Select block alignment", 'trx_addons'),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Parallax
				"trx_parallax" => array(
					"title" => esc_html__("Parallax", 'trx_addons'),
					"desc" => esc_html__("Create the parallax container (with asinc background image)", 'trx_addons'),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"gap" => array(
							"title" => esc_html__("Create gap", 'trx_addons'),
							"desc" => esc_html__("Create gap around parallax container", 'trx_addons'),
							"value" => "no",
							"size" => "small",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						), 
						"dir" => array(
							"title" => esc_html__("Dir", 'trx_addons'),
							"desc" => esc_html__("Scroll direction for the parallax background", 'trx_addons'),
							"value" => "up",
							"size" => "medium",
							"options" => array(
								'up' => esc_html__('Up', 'trx_addons'),
								'down' => esc_html__('Down', 'trx_addons')
							),
							"type" => "switch"
						), 
						"speed" => array(
							"title" => esc_html__("Speed", 'trx_addons'),
							"desc" => esc_html__("Image motion speed (from 0.0 to 1.0)", 'trx_addons'),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0.3",
							"type" => "spinner"
						),
						"color" => array(
							"title" => esc_html__("Text color", 'trx_addons'),
							"desc" => esc_html__("Select color for text object inside parallax block", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => esc_html__("Bg tint", 'trx_addons'),
							"desc" => esc_html__("Select tint of the parallax background (for correct font color choise)", 'trx_addons'),
							"value" => "light",
							"size" => "medium",
							"options" => array(
								'light' => esc_html__('Light', 'trx_addons'),
								'dark' => esc_html__('Dark', 'trx_addons')
							),
							"type" => "switch"
						), 
						"bg_color" => array(
							"title" => esc_html__("Background color", 'trx_addons'),
							"desc" => esc_html__("Select color for parallax background", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for the parallax background", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image_x" => array(
							"title" => esc_html__("Image X position", 'trx_addons'),
							"desc" => esc_html__("Image horizontal position (as background of the parallax block) - in percent", 'trx_addons'),
							"min" => "0",
							"max" => "100",
							"value" => "50",
							"type" => "spinner"
						),
						"bg_video" => array(
							"title" => esc_html__("Video background", 'trx_addons'),
							"desc" => esc_html__("Select video from media library or paste URL for video file from other site to show it as parallax background", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => esc_html__('Choose video', 'trx_addons'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => esc_html__('Choose video file', 'trx_addons'),
									'update' => esc_html__('Select video file', 'trx_addons')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"bg_video_ratio" => array(
							"title" => esc_html__("Video ratio", 'trx_addons'),
							"desc" => esc_html__("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", 'trx_addons'),
							"value" => "16:9",
							"type" => "text"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", 'trx_addons'),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", 'trx_addons'),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", 'trx_addons'),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", 'trx_addons'),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"_content_" => array(
							"title" => esc_html__("Content", 'trx_addons'),
							"desc" => esc_html__("Content for the parallax container", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Popup
				"trx_popup" => array(
					"title" => esc_html__("Popup window", 'trx_addons'),
					"desc" => esc_html__("Container for any html-block with desired class and style for popup window", 'trx_addons'),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Container content", 'trx_addons'),
							"desc" => esc_html__("Content for section container", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Price
				"trx_price" => array(
					"title" => esc_html__("Price", 'trx_addons'),
					"desc" => esc_html__("Insert price with decoration", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"money" => array(
							"title" => esc_html__("Money", 'trx_addons'),
							"desc" => esc_html__("Money value (dot or comma separated)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => esc_html__("Currency", 'trx_addons'),
							"desc" => esc_html__("Currency character", 'trx_addons'),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => esc_html__("Period", 'trx_addons'),
							"desc" => esc_html__("Period text (if need). For example: monthly, daily, etc.", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_addons'),
							"desc" => esc_html__("Align price to left or right side", 'trx_addons'),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['float']
						), 
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Price block
				"trx_price_block" => array(
					"title" => esc_html__("Price block", 'trx_addons'),
					"desc" => esc_html__("Insert price block with title, price and description", 'trx_addons'),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Price block style", 'trx_addons'),
							"desc" => esc_html__("Select style of Price block", 'trx_addons'),
							"value" => "1",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'1' => esc_html__('Style 1', 'trx_addons'),
								'2' => esc_html__('Style 2', 'trx_addons')
							)
						),
						"title" => array(
							"title" => esc_html__("Title", 'trx_addons'),
							"desc" => esc_html__("Block title", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Link URL", 'trx_addons'),
							"desc" => esc_html__("URL for link from button (at bottom of the block)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"link_text" => array(
							"title" => esc_html__("Link text", 'trx_addons'),
							"desc" => esc_html__("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => esc_html__("Icon",  'trx_addons'),
							"desc" => esc_html__('Select icon from Fontello icons set (placed before/instead price)',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"money" => array(
							"title" => esc_html__("Money", 'trx_addons'),
							"desc" => esc_html__("Money value (dot or comma separated)", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => esc_html__("Currency", 'trx_addons'),
							"desc" => esc_html__("Currency character", 'trx_addons'),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => esc_html__("Period", 'trx_addons'),
							"desc" => esc_html__("Period text (if need). For example: monthly, daily, etc.", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_addons'),
							"desc" => esc_html__("Align price to left or right side", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['float']
						), 
						"_content_" => array(
							"title" => esc_html__("Description", 'trx_addons'),
							"desc" => esc_html__("Description for this price block", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Quote
				"trx_quote" => array(
					"title" => esc_html__("Quote", 'trx_addons'),
					"desc" => esc_html__("Quote text", 'trx_addons'),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"cite" => array(
							"title" => esc_html__("Quote cite", 'trx_addons'),
							"desc" => esc_html__("URL for quote cite", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"title" => array(
							"title" => esc_html__("Title (author)", 'trx_addons'),
							"desc" => esc_html__("Quote title (author name)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("Quote style", 'trx_addons'),
							"desc" => esc_html__("Select style of Quote block", 'trx_addons'),
							"value" => "1",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'1' => esc_html__('Style 1', 'trx_addons'),
								'2' => esc_html__('Style 2', 'trx_addons')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Quote content", 'trx_addons'),
							"desc" => esc_html__("Quote content", 'trx_addons'),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => themerex_shortcodes_width(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Reviews
				"trx_reviews" => array(
					"title" => esc_html__("Reviews", 'trx_addons'),
					"desc" => esc_html__("Insert reviews block in the single post", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_addons'),
							"desc" => esc_html__("Align counter to left, center or right", 'trx_addons'),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						), 
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Search
				"trx_search" => array(
					"title" => esc_html__("Search", 'trx_addons'),
					"desc" => esc_html__("Show search form", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'trx_addons'),
							"desc" => esc_html__("Title (placeholder) for the search field", 'trx_addons'),
							"value" => esc_html__("Search &hellip;", 'trx_addons'),
							"divider" => true,
							"type" => "text"
						),
						"ajax" => array(
							"title" => esc_html__("AJAX", 'trx_addons'),
							"desc" => esc_html__("Search via AJAX or reload page", 'trx_addons'),
							"value" => "yes",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Section
				"trx_section" => array(
					"title" => esc_html__("Section container", 'trx_addons'),
					"desc" => esc_html__("Container for any block with desired class and style", 'trx_addons'),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => esc_html__("Dedicated", 'trx_addons'),
							"desc" => esc_html__("Use this block as dedicated content - show it before post title on single page", 'trx_addons'),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Align", 'trx_addons'),
							"desc" => esc_html__("Select block alignment", 'trx_addons'),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => esc_html__("Columns emulation", 'trx_addons'),
							"desc" => esc_html__("Select width for columns emulation", 'trx_addons'),
							"value" => "none",
							"type" => "checklist",
							"options" => $THEMEREX_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => esc_html__("Use pan effect", 'trx_addons'),
							"desc" => esc_html__("Use pan effect to show section content", 'trx_addons'),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", 'trx_addons'),
							"desc" => esc_html__("Use scroller to show section content", 'trx_addons'),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => esc_html__("Scroll and Pan direction", 'trx_addons'),
							"desc" => esc_html__("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'trx_addons'),
							"dependency" => array(
								'pan' => array('yes'),
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => esc_html__("Scroll controls", 'trx_addons'),
							"desc" => esc_html__("Show scroll controls (if Use scroller = yes)", 'trx_addons'),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"color" => array(
							"title" => esc_html__("Fore color", 'trx_addons'),
							"desc" => esc_html__("Any color for objects in this section", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_tint" => array(
							"title" => esc_html__("Background tint", 'trx_addons'),
							"desc" => esc_html__("Main background tint: dark or light", 'trx_addons'),
							"value" => "",
							"type" => "checklist",
							"options" => $THEMEREX_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'trx_addons'),
							"desc" => esc_html__("Any background color for this section", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", 'trx_addons'),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", 'trx_addons'),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", 'trx_addons'),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", 'trx_addons'),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", 'trx_addons'),
							"desc" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", 'trx_addons'),
							"desc" => esc_html__("Font weight of the text", 'trx_addons'),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => esc_html__('Thin (100)', 'trx_addons'),
								'300' => esc_html__('Light (300)', 'trx_addons'),
								'400' => esc_html__('Normal (400)', 'trx_addons'),
								'700' => esc_html__('Bold (700)', 'trx_addons')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Container content", 'trx_addons'),
							"desc" => esc_html__("Content for section container", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),




				// Services block
				"trx_services_block" => array(
					"title" => esc_html__("Services block", 'trx_addons'),
					"desc" => esc_html__("Insert Services block with title, icon and description", 'trx_addons'),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'trx_addons'),
							"desc" => esc_html__("Block title", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"count" => array(
							"title" => esc_html__("Count", 'trx_addons'),
							"desc" => esc_html__("Count title", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Link URL", 'trx_addons'),
							"desc" => esc_html__("URL for link from button (at bottom of the block)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"link_text" => array(
							"title" => esc_html__("Link text", 'trx_addons'),
							"desc" => esc_html__("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => esc_html__("Icon",  'trx_addons'),
							"desc" => esc_html__('Select icon from Fontello icons set (placed before/instead price)',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_addons'),
							"desc" => esc_html__("Align services to left or right side", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['float']
						),
						"_content_" => array(
							"title" => esc_html__("Description", 'trx_addons'),
							"desc" => esc_html__("Description for this services block", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),




				// Skills
				"trx_skills" => array(
					"title" => esc_html__("Skills", 'trx_addons'),
					"desc" => esc_html__("Insert skills diagramm in your page (post)", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"max_value" => array(
							"title" => esc_html__("Max value", 'trx_addons'),
							"desc" => esc_html__("Max value for skills items", 'trx_addons'),
							"value" => 100,
							"min" => 1,
							"type" => "spinner"
						),
						"type" => array(
							"title" => esc_html__("Skills type", 'trx_addons'),
							"desc" => esc_html__("Select type of skills block", 'trx_addons'),
							"value" => "bar",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'bar' => esc_html__('Bar', 'trx_addons'),
								'bar2' => esc_html__('Bar 2', 'trx_addons'),
								'pie' => esc_html__('Pie chart', 'trx_addons'),
								'pie_2' => esc_html__('Pie chart 2', 'trx_addons'),
								'counter' => esc_html__('Counter', 'trx_addons'),
								'arc' => esc_html__('Arc', 'trx_addons')
							)
						), 
						"layout" => array(
							"title" => esc_html__("Skills layout", 'trx_addons'),
							"desc" => esc_html__("Select layout of skills block", 'trx_addons'),
							"dependency" => array(
								'type' => array('counter','pie','pie_2','bar')
							),
							"value" => "rows",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'rows' => esc_html__('Rows', 'trx_addons'),
								'columns' => esc_html__('Columns', 'trx_addons')
							)
						),
						"dir" => array(
							"title" => esc_html__("Direction", 'trx_addons'),
							"desc" => esc_html__("Select direction of skills block", 'trx_addons'),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "horizontal",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['dir']
						), 
						"style" => array(
							"title" => esc_html__("Counters style", 'trx_addons'),
							"desc" => esc_html__("Select style of skills items (only for type=counter)", 'trx_addons'),
							"dependency" => array(
								'type' => array('counter')
							),
							"value" => 1,
							"min" => 1,
							"max" => 4,
							"type" => "spinner"
						), 
						// "columns" - autodetect, not set manual
						"color" => array(
							"title" => esc_html__("Skills items color", 'trx_addons'),
							"desc" => esc_html__("Color for all skills items", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'trx_addons'),
							"desc" => esc_html__("Background color for all skills items (only for type=pie)", 'trx_addons'),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"border_color" => array(
							"title" => esc_html__("Border color", 'trx_addons'),
							"desc" => esc_html__("Border color for all skills items (only for type=pie)", 'trx_addons'),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"title" => array(
							"title" => esc_html__("Skills title", 'trx_addons'),
							"desc" => esc_html__("Skills block title", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Skills subtitle", 'trx_addons'),
							"desc" => esc_html__("Skills block subtitle - text in the center (only for type=arc)", 'trx_addons'),
							"dependency" => array(
								'type' => array('arc')
							),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Align skills block", 'trx_addons'),
							"desc" => esc_html__("Align skills block to left or right side", 'trx_addons'),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['float']
						), 
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_skills_item",
						"title" => esc_html__("Skill", 'trx_addons'),
						"desc" => esc_html__("Skills item", 'trx_addons'),
						"container" => false,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", 'trx_addons'),
								"desc" => esc_html__("Current skills item title", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => esc_html__("Value", 'trx_addons'),
								"desc" => esc_html__("Current skills level", 'trx_addons'),
								"value" => 50,
								"min" => 0,
								"step" => 1,
								"type" => "spinner"
							),
							"color" => array(
								"title" => esc_html__("Color", 'trx_addons'),
								"desc" => esc_html__("Current skills item color", 'trx_addons'),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => esc_html__("Background color", 'trx_addons'),
								"desc" => esc_html__("Current skills item background color (only for type=pie)", 'trx_addons'),
								"value" => "",
								"type" => "color"
							),
							"border_color" => array(
								"title" => esc_html__("Border color", 'trx_addons'),
								"desc" => esc_html__("Current skills item border color (only for type=pie)", 'trx_addons'),
								"value" => "",
								"type" => "color"
							),
							"style" => array(
								"title" => esc_html__("Counter tyle", 'trx_addons'),
								"desc" => esc_html__("Select style for the current skills item (only for type=counter)", 'trx_addons'),
								"value" => 1,
								"min" => 1,
								"max" => 4,
								"type" => "spinner"
							), 
							"id" => $THEMEREX_GLOBALS['sc_params']['id'],
							"class" => $THEMEREX_GLOBALS['sc_params']['class'],
							"css" => $THEMEREX_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Slider
				"trx_slider" => array(
					"title" => esc_html__("Slider", 'trx_addons'),
					"desc" => esc_html__("Insert slider into your post (page)", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array_merge(array(
						"engine" => array(
							"title" => esc_html__("Slider engine", 'trx_addons'),
							"desc" => esc_html__("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'trx_addons'),
							"value" => "swiper",
							"type" => "checklist",
							"options" => $THEMEREX_GLOBALS['sc_params']['sliders']
						),
						"align" => array(
							"title" => esc_html__("Float slider", 'trx_addons'),
							"desc" => esc_html__("Float slider to left or right side", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['float']
						),
						"custom" => array(
							"title" => esc_html__("Custom slides", 'trx_addons'),
							"desc" => esc_html__("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'trx_addons'),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						)
						),
						themerex_exists_revslider() || themerex_exists_royalslider() ? array(
						"alias" => array(
							"title" => esc_html__("Revolution slider alias or Royal Slider ID", 'trx_addons'),
							"desc" => esc_html__("Alias for Revolution slider or Royal slider ID", 'trx_addons'),
							"dependency" => array(
								'engine' => array('revo','royal')
							),
							"divider" => true,
							"value" => "",
							"type" => "text"
						)) : array(), array(
						"cat" => array(
							"title" => esc_html__("Swiper: Category list", 'trx_addons'),
							"desc" => esc_html__("Comma separated list of category slugs. If empty - select posts from any category or from IDs list", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $THEMEREX_GLOBALS['sc_params']['categories']
						),
						"count" => array(
							"title" => esc_html__("Swiper: Number of posts", 'trx_addons'),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Swiper: Offset before select posts", 'trx_addons'),
							"desc" => esc_html__("Skip posts before select next part.", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Swiper: Post order by", 'trx_addons'),
							"desc" => esc_html__("Select desired posts sorting method", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "date",
							"type" => "select",
							"options" => $THEMEREX_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Swiper: Post order", 'trx_addons'),
							"desc" => esc_html__("Select desired posts order", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Swiper: Post IDs list", 'trx_addons'),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => esc_html__("Swiper: Show slider controls", 'trx_addons'),
							"desc" => esc_html__("Show arrows inside slider", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"pagination" => array(
							"title" => esc_html__("Swiper: Show slider pagination", 'trx_addons'),
							"desc" => esc_html__("Show bullets for switch slides", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "checklist",
							"options" => array(
								'yes'  => esc_html__('Dots', 'trx_addons'),
								'full' => esc_html__('Side Titles', 'trx_addons'),
								'over' => esc_html__('Over Titles', 'trx_addons'),
								'no'   => esc_html__('None', 'trx_addons')
							)
						),
						"titles" => array(
							"title" => esc_html__("Swiper: Show titles section", 'trx_addons'),
							"desc" => esc_html__("Show section with post's title and short post's description", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"options" => array(
								"no"    => esc_html__('Not show', 'trx_addons'),
								"slide" => esc_html__('Show/Hide info', 'trx_addons'),
								"fixed" => esc_html__('Fixed info', 'trx_addons')
							)
						),
						"descriptions" => array(
							"title" => esc_html__("Swiper: Post descriptions", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"desc" => esc_html__("Show post's excerpt max length (characters)", 'trx_addons'),
							"value" => 0,
							"min" => 0,
							"max" => 1000,
							"step" => 10,
							"type" => "spinner"
						),
						"links" => array(
							"title" => esc_html__("Swiper: Post's title as link", 'trx_addons'),
							"desc" => esc_html__("Make links from post's titles", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"crop" => array(
							"title" => esc_html__("Swiper: Crop images", 'trx_addons'),
							"desc" => esc_html__("Crop images in each slide or live it unchanged", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"autoheight" => array(
							"title" => esc_html__("Swiper: Autoheight", 'trx_addons'),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => esc_html__("Swiper: Slides change interval", 'trx_addons'),
							"desc" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_addons'),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 5000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)),
					"children" => array(
						"name" => "trx_slider_item",
						"title" => esc_html__("Slide", 'trx_addons'),
						"desc" => esc_html__("Slider item", 'trx_addons'),
						"container" => false,
						"params" => array(
							"src" => array(
								"title" => esc_html__("URL (source) for image file", 'trx_addons'),
								"desc" => esc_html__("Select or upload image or write URL from other site for the current slide", 'trx_addons'),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"id" => $THEMEREX_GLOBALS['sc_params']['id'],
							"class" => $THEMEREX_GLOBALS['sc_params']['class'],
							"css" => $THEMEREX_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Socials
				"trx_socials" => array(
					"title" => esc_html__("Social icons", 'trx_addons'),
					"desc" => esc_html__("List of social icons (with hovers)", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"size" => array(
							"title" => esc_html__("Icon's size", 'trx_addons'),
							"desc" => esc_html__("Size of the icons", 'trx_addons'),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								"tiny" => esc_html__('Tiny', 'trx_addons'),
								"small" => esc_html__('Small', 'trx_addons'),
								"large" => esc_html__('Large', 'trx_addons')
							)
						), 
						"socials" => array(
							"title" => esc_html__("Manual socials list", 'trx_addons'),
							"desc" => esc_html__("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"custom" => array(
							"title" => esc_html__("Custom socials", 'trx_addons'),
							"desc" => esc_html__("Make custom icons from inner shortcodes (prepare it on tabs)", 'trx_addons'),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"style" => array(
							"title" => esc_html__("Socials style", 'trx_addons'),
							"desc" => esc_html__("Socials style", 'trx_addons'),
							"value" => "main",
							"type" => "select",
							"options" => array(
								'main' => esc_html__('Regular', 'trx_addons'),
								'color' => esc_html__('Color', 'trx_addons')
							)
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_social_item",
						"title" => esc_html__("Custom social item", 'trx_addons'),
						"desc" => esc_html__("Custom social item: name, profile url and icon url", 'trx_addons'),
						"decorate" => false,
						"container" => false,
						"params" => array(
							"url" => array(
								"title" => esc_html__("Your profile URL", 'trx_addons'),
								"desc" => esc_html__("URL of your profile in specified social network", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__('Icon',  'trx_addons'),
								"desc" => esc_html__("Select font icon from Fontello icons set (if style=iconed)",  'trx_addons'),
								"value" => "",
								"type" => "icons",
								"options" => $THEMEREX_GLOBALS['sc_params']['icons']
							)
						)
					)
				),





				// Sidebar
				"trx_sidebar" => array(
					"title" => esc_html__("Sidebar", 'trx_addons'),
					"desc" => esc_html__("Insert Sidebar into the page/post content. ", 'trx_addons'),
					"id" => "trx_sidebar",
					"decorate" => false,
					"container" => false,
					"params" => array(
						"name" => array(
							"title" => esc_html__("Select sidebar", 'trx_addons'),
							"options" => $THEMEREX_GLOBALS['sc_params']['sidebar'],
							"type" => "select",
							"divider" => true,
							"value" => ""
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right']
					)
				),



			
				// Table
				"trx_table" => array(
					"title" => esc_html__("Table", 'trx_addons'),
					"desc" => esc_html__("Insert a table into post (page). ", 'trx_addons'),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"align" => array(
							"title" => esc_html__("Content alignment", 'trx_addons'),
							"desc" => esc_html__("Select alignment for each table cell", 'trx_addons'),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						),
						"_content_" => array(
							"title" => esc_html__("Table content", 'trx_addons'),
							"desc" => esc_html__("Content, created with any table-generator", 'trx_addons'),
							"divider" => true,
							"rows" => 8,
							"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
							"type" => "textarea"
						),
						"width" => themerex_shortcodes_width(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Tabs
				"trx_tabs" => array(
					"title" => esc_html__("Tabs", 'trx_addons'),
					"desc" => esc_html__("Insert tabs in your page (post)", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Tabs style", 'trx_addons'),
							"desc" => esc_html__("Select style for tabs items", 'trx_addons'),
							"value" => 1,
							"options" => array(
								1 => esc_html__('Style 1', 'trx_addons'),
								2 => esc_html__('Style 2', 'trx_addons')
							),
							"type" => "radio"
						),
						"initial" => array(
							"title" => esc_html__("Initially opened tab", 'trx_addons'),
							"desc" => esc_html__("Number of initially opened tab", 'trx_addons'),
							"divider" => true,
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", 'trx_addons'),
							"desc" => esc_html__("Use scroller to show tab content (height parameter required)", 'trx_addons'),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_tab",
						"title" => esc_html__("Tab", 'trx_addons'),
						"desc" => esc_html__("Tab item", 'trx_addons'),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Tab title", 'trx_addons'),
								"desc" => esc_html__("Current tab title", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Tab content", 'trx_addons'),
								"desc" => esc_html__("Current tab content", 'trx_addons'),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $THEMEREX_GLOBALS['sc_params']['id'],
							"class" => $THEMEREX_GLOBALS['sc_params']['class'],
							"css" => $THEMEREX_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Team
				"trx_team" => array(
					"title" => esc_html__("Team", 'trx_addons'),
					"desc" => esc_html__("Insert team in your page (post)", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Team style", 'trx_addons'),
							"desc" => esc_html__("Select style to display team members", 'trx_addons'),
							"value" => "1",
							"type" => "select",
							"options" => array(
								1 => esc_html__('Style 1', 'trx_addons'),
								2 => esc_html__('Style 2', 'trx_addons')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns use to show team members", 'trx_addons'),
							"value" => 3,
							"min" => 2,
							"max" => 5,
							"step" => 1,
							"type" => "spinner"
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'trx_addons'),
							"desc" => esc_html__("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'trx_addons'),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'trx_addons'),
							"desc" => esc_html__("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $THEMEREX_GLOBALS['sc_params']['team_groups']
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'trx_addons'),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'trx_addons'),
							"desc" => esc_html__("Skip posts before select next part.", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'trx_addons'),
							"desc" => esc_html__("Select desired posts sorting method", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => "title",
							"type" => "select",
							"options" => $THEMEREX_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", 'trx_addons'),
							"desc" => esc_html__("Select desired posts order", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'trx_addons'),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => "",
							"type" => "text"
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_team_item",
						"title" => esc_html__("Member", 'trx_addons'),
						"desc" => esc_html__("Team member", 'trx_addons'),
						"container" => true,
						"params" => array(
							"user" => array(
								"title" => esc_html__("Registerd user", 'trx_addons'),
								"desc" => esc_html__("Select one of registered users (if present) or put name, position, etc. in fields below", 'trx_addons'),
								"value" => "",
								"type" => "select",
								"options" => $THEMEREX_GLOBALS['sc_params']['users']
							),
							"member" => array(
								"title" => esc_html__("Team member", 'trx_addons'),
								"desc" => esc_html__("Select one of team members (if present) or put name, position, etc. in fields below", 'trx_addons'),
								"value" => "",
								"type" => "select",
								"options" => $THEMEREX_GLOBALS['sc_params']['members']
							),
							"link" => array(
								"title" => esc_html__("Link", 'trx_addons'),
								"desc" => esc_html__("Link on team member's personal page", 'trx_addons'),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"name" => array(
								"title" => esc_html__("Name", 'trx_addons'),
								"desc" => esc_html__("Team member's name", 'trx_addons'),
								"divider" => true,
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", 'trx_addons'),
								"desc" => esc_html__("Team member's position", 'trx_addons'),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", 'trx_addons'),
								"desc" => esc_html__("Team member's e-mail", 'trx_addons'),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", 'trx_addons'),
								"desc" => esc_html__("Team member's photo (avatar)", 'trx_addons'),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"socials" => array(
								"title" => esc_html__("Socials", 'trx_addons'),
								"desc" => esc_html__("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", 'trx_addons'),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'trx_addons'),
								"desc" => esc_html__("Team member's short description", 'trx_addons'),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $THEMEREX_GLOBALS['sc_params']['id'],
							"class" => $THEMEREX_GLOBALS['sc_params']['class'],
							"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
							"css" => $THEMEREX_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Testimonials
				"trx_testimonials" => array(
					"title" => esc_html__("Testimonials", 'trx_addons'),
					"desc" => esc_html__("Insert testimonials into post (page)", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Testimonials style", 'trx_addons'),
							"desc" => esc_html__("Select style to display testimonials members", 'trx_addons'),
							"value" => "1",
							"type" => "select",
							"options" => array(
								1 => esc_html__('Style 1', 'trx_addons'),
								2 => esc_html__('Style 2', 'trx_addons')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns use to show team members", 'trx_addons'),
							"value" => 3,
							"min" => 2,
							"max" => 4,
							"step" => 1,
							"type" => "spinner",
							"dependency" => array(
								'style' => array('2')
							),
						),
						"controls" => array(
							"title" => esc_html__("Show arrows", 'trx_addons'),
							"desc" => esc_html__("Show control buttons", 'trx_addons'),
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no'],
							"dependency" => array(
								'style' => array('1')
							),
						),
						"interval" => array(
							"title" => esc_html__("Testimonials change interval", 'trx_addons'),
							"desc" => esc_html__("Testimonials change interval (in milliseconds: 1000ms = 1s)", 'trx_addons'),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner",
							"dependency" => array(
								'style' => array('1')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_addons'),
							"desc" => esc_html__("Alignment of the testimonials block", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'trx_addons'),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", 'trx_addons'),
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no'],
							"dependency" => array(
								'style' => array('1')
							)
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'trx_addons'),
							"desc" => esc_html__("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'trx_addons'),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'trx_addons'),
							"desc" => esc_html__("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => $THEMEREX_GLOBALS['sc_params']['testimonials_groups']
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'trx_addons'),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'trx_addons'),
							"desc" => esc_html__("Skip posts before select next part.", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'trx_addons'),
							"desc" => esc_html__("Select desired posts sorting method", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => "date",
							"type" => "select",
							"options" => $THEMEREX_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", 'trx_addons'),
							"desc" => esc_html__("Select desired posts order", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'trx_addons'),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
							"dependency" => array(
								'custom' => array('yes')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_tint" => array(
							"title" => esc_html__("Background tint", 'trx_addons'),
							"desc" => esc_html__("Main background tint: dark or light", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"options" => $THEMEREX_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'trx_addons'),
							"desc" => esc_html__("Any background color for this section", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", 'trx_addons'),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", 'trx_addons'),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", 'trx_addons'),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", 'trx_addons'),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_testimonials_item",
						"title" => esc_html__("Item", 'trx_addons'),
						"desc" => esc_html__("Testimonials item", 'trx_addons'),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => esc_html__("Author", 'trx_addons'),
								"desc" => esc_html__("Name of the testimonmials author", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'trx_addons'),
								"desc" => esc_html__("Link URL to the testimonmials author page", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", 'trx_addons'),
								"desc" => esc_html__("E-mail of the testimonmials author (to get gravatar)", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", 'trx_addons'),
								"desc" => esc_html__("Select or upload photo of testimonmials author or write URL of photo from other site", 'trx_addons'),
								"value" => "",
								"type" => "media"
							),
							"field" => array(
								"title" => esc_html__("Field", 'trx_addons'),
								"desc" => esc_html__("Additional field (for Style 2)", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Testimonials text", 'trx_addons'),
								"desc" => esc_html__("Current testimonials text", 'trx_addons'),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $THEMEREX_GLOBALS['sc_params']['id'],
							"class" => $THEMEREX_GLOBALS['sc_params']['class'],
							"css" => $THEMEREX_GLOBALS['sc_params']['css']
						)
					)
				),
			
			

				// Title
				"trx_title" => array(
					"title" => esc_html__("Title", 'trx_addons'),
					"desc" => esc_html__("Create header tag (1-6 level) with many styles", 'trx_addons'),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Title content", 'trx_addons'),
							"desc" => esc_html__("Title content", 'trx_addons'),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"type" => array(
							"title" => esc_html__("Title type", 'trx_addons'),
							"desc" => esc_html__("Title type (header level)", 'trx_addons'),
							"divider" => true,
							"value" => "1",
							"type" => "select",
							"options" => array(
								'1' => esc_html__('Header 1', 'trx_addons'),
								'2' => esc_html__('Header 2', 'trx_addons'),
								'3' => esc_html__('Header 3', 'trx_addons'),
								'4' => esc_html__('Header 4', 'trx_addons'),
								'5' => esc_html__('Header 5', 'trx_addons'),
								'6' => esc_html__('Header 6', 'trx_addons'),
							)
						),
						"style" => array(
							"title" => esc_html__("Title style", 'trx_addons'),
							"desc" => esc_html__("Title style", 'trx_addons'),
							"value" => "regular",
							"type" => "select",
							"options" => array(
								'regular' => esc_html__('Regular', 'trx_addons'),
								'underline' => esc_html__('Underline', 'trx_addons'),
								'divider' => esc_html__('Divider', 'trx_addons'),
								'iconed' => esc_html__('With icon (image)', 'trx_addons')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_addons'),
							"desc" => esc_html__("Title text alignment", 'trx_addons'),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						), 
						"font_size" => array(
							"title" => esc_html__("Font_size", 'trx_addons'),
							"desc" => esc_html__("Custom font size. If empty - use theme default", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", 'trx_addons'),
							"desc" => esc_html__("Custom font weight. If empty or inherit - use theme default", 'trx_addons'),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'inherit' => esc_html__('Default', 'trx_addons'),
								'100' => esc_html__('Thin (100)', 'trx_addons'),
								'300' => esc_html__('Light (300)', 'trx_addons'),
								'400' => esc_html__('Normal (400)', 'trx_addons'),
								'600' => esc_html__('Semibold (600)', 'trx_addons'),
								'700' => esc_html__('Bold (700)', 'trx_addons'),
								'900' => esc_html__('Black (900)', 'trx_addons')
							)
						),
						"color" => array(
							"title" => esc_html__("Title color", 'trx_addons'),
							"desc" => esc_html__("Select color for the title", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => esc_html__('Title font icon',  'trx_addons'),
							"desc" => esc_html__("Select font icon for the title from Fontello icons set (if style=iconed)",  'trx_addons'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"image" => array(
							"title" => esc_html__('or image icon',  'trx_addons'),
							"desc" => esc_html__("Select image icon for the title instead icon above (if style=iconed)",  'trx_addons'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "images",
							"size" => "small",
							"options" => $THEMEREX_GLOBALS['sc_params']['images']
						),
						"picture" => array(
							"title" => esc_html__('or URL for image file', 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site (if style=iconed)", 'trx_addons'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"image_size" => array(
							"title" => esc_html__('Image (picture) size', 'trx_addons'),
							"desc" => esc_html__("Select image (picture) size (if style='iconed')", 'trx_addons'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								'small' => esc_html__('Small', 'trx_addons'),
								'medium' => esc_html__('Medium', 'trx_addons'),
								'large' => esc_html__('Large', 'trx_addons')
							)
						),
						"position" => array(
							"title" => esc_html__('Icon (image) position', 'trx_addons'),
							"desc" => esc_html__("Select icon (image) position (if style=iconed)", 'trx_addons'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "left",
							"type" => "checklist",
							"options" => array(
								'top' => esc_html__('Top', 'trx_addons'),
								'left' => esc_html__('Left', 'trx_addons')
							)
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Toggles
				"trx_toggles" => array(
					"title" => esc_html__("Toggles", 'trx_addons'),
					"desc" => esc_html__("Toggles items", 'trx_addons'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Toggles style", 'trx_addons'),
							"desc" => esc_html__("Select style for display toggles", 'trx_addons'),
							"value" => 1,
							"options" => array(
								1 => esc_html__('Style 1', 'trx_addons'),
								2 => esc_html__('Style 2', 'trx_addons')
							),
							"type" => "radio"
						),
						"icon_closed" => array(
							"title" => esc_html__("Icon while closed",  'trx_addons'),
							"desc" => esc_html__('Select icon for the closed toggles item from Fontello icons set',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => esc_html__("Icon while opened",  'trx_addons'),
							"desc" => esc_html__('Select icon for the opened toggles item from Fontello icons set',  'trx_addons'),
							"value" => "",
							"type" => "icons",
							"options" => $THEMEREX_GLOBALS['sc_params']['icons']
						),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_toggles_item",
						"title" => esc_html__("Toggles item", 'trx_addons'),
						"desc" => esc_html__("Toggles item", 'trx_addons'),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Toggles item title", 'trx_addons'),
								"desc" => esc_html__("Title for current toggles item", 'trx_addons'),
								"value" => "",
								"type" => "text"
							),
							"open" => array(
								"title" => esc_html__("Open on show", 'trx_addons'),
								"desc" => esc_html__("Open current toggles item on show", 'trx_addons'),
								"value" => "no",
								"type" => "switch",
								"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
							),
							"icon_closed" => array(
								"title" => esc_html__("Icon while closed",  'trx_addons'),
								"desc" => esc_html__('Select icon for the closed toggles item from Fontello icons set',  'trx_addons'),
								"value" => "",
								"type" => "icons",
								"options" => $THEMEREX_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => esc_html__("Icon while opened",  'trx_addons'),
								"desc" => esc_html__('Select icon for the opened toggles item from Fontello icons set',  'trx_addons'),
								"value" => "",
								"type" => "icons",
								"options" => $THEMEREX_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => esc_html__("Toggles item content", 'trx_addons'),
								"desc" => esc_html__("Current toggles item content", 'trx_addons'),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $THEMEREX_GLOBALS['sc_params']['id'],
							"class" => $THEMEREX_GLOBALS['sc_params']['class'],
							"css" => $THEMEREX_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Tooltip
				"trx_tooltip" => array(
					"title" => esc_html__("Tooltip", 'trx_addons'),
					"desc" => esc_html__("Create tooltip for selected text", 'trx_addons'),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'trx_addons'),
							"desc" => esc_html__("Tooltip title (required)", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Tipped content", 'trx_addons'),
							"desc" => esc_html__("Highlighted content with tooltip", 'trx_addons'),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Twitter
				"trx_twitter" => array(
					"title" => esc_html__("Twitter", 'trx_addons'),
					"desc" => esc_html__("Insert twitter feed into post (page)", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"user" => array(
							"title" => esc_html__("Twitter Username", 'trx_addons'),
							"desc" => esc_html__("Your username in the twitter account. If empty - get it from Theme Options.", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"consumer_key" => array(
							"title" => esc_html__("Consumer Key", 'trx_addons'),
							"desc" => esc_html__("Consumer Key from the twitter account", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"consumer_secret" => array(
							"title" => esc_html__("Consumer Secret", 'trx_addons'),
							"desc" => esc_html__("Consumer Secret from the twitter account", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"token_key" => array(
							"title" => esc_html__("Token Key", 'trx_addons'),
							"desc" => esc_html__("Token Key from the twitter account", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"token_secret" => array(
							"title" => esc_html__("Token Secret", 'trx_addons'),
							"desc" => esc_html__("Token Secret from the twitter account", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"count" => array(
							"title" => esc_html__("Tweets number", 'trx_addons'),
							"desc" => esc_html__("Tweets number to show", 'trx_addons'),
							"divider" => true,
							"value" => 3,
							"max" => 20,
							"min" => 1,
							"type" => "spinner"
						),
						"controls" => array(
							"title" => esc_html__("Show arrows", 'trx_addons'),
							"desc" => esc_html__("Show control buttons", 'trx_addons'),
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => esc_html__("Tweets change interval", 'trx_addons'),
							"desc" => esc_html__("Tweets change interval (in milliseconds: 1000ms = 1s)", 'trx_addons'),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'trx_addons'),
							"desc" => esc_html__("Alignment of the tweets block", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'trx_addons'),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", 'trx_addons'),
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						),
						"bg_tint" => array(
							"title" => esc_html__("Background tint", 'trx_addons'),
							"desc" => esc_html__("Main background tint: dark or light", 'trx_addons'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"options" => $THEMEREX_GLOBALS['sc_params']['tint']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'trx_addons'),
							"desc" => esc_html__("Any background color for this section", 'trx_addons'),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", 'trx_addons'),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", 'trx_addons'),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", 'trx_addons'),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", 'trx_addons'),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Video
				"trx_video" => array(
					"title" => esc_html__("Video", 'trx_addons'),
					"desc" => esc_html__("Insert video player", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for video file", 'trx_addons'),
							"desc" => esc_html__("Select video from media library or paste URL for video file from other site", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => esc_html__('Choose video', 'trx_addons'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => esc_html__('Choose video file', 'trx_addons'),
									'update' => esc_html__('Select video file', 'trx_addons')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"ratio" => array(
							"title" => esc_html__("Ratio", 'trx_addons'),
							"desc" => esc_html__("Ratio of the video", 'trx_addons'),
							"value" => "16:9",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"16:9" => esc_html__("16:9", 'trx_addons'),
								"4:3" => esc_html__("4:3", 'trx_addons')
							)
						),
						"autoplay" => array(
							"title" => esc_html__("Autoplay video", 'trx_addons'),
							"desc" => esc_html__("Autoplay video on page load", 'trx_addons'),
							"value" => "off",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => esc_html__("Align", 'trx_addons'),
							"desc" => esc_html__("Select block alignment", 'trx_addons'),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['align']
						),
						"image" => array(
							"title" => esc_html__("Cover image", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for video preview", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'trx_addons'),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => esc_html__("Top offset", 'trx_addons'),
							"desc" => esc_html__("Top offset (padding) inside background image to video block (in percent). For example: 3%", 'trx_addons'),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => esc_html__("Bottom offset", 'trx_addons'),
							"desc" => esc_html__("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", 'trx_addons'),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => esc_html__("Left offset", 'trx_addons'),
							"desc" => esc_html__("Left offset (padding) inside background image to video block (in percent). For example: 20%", 'trx_addons'),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => esc_html__("Right offset", 'trx_addons'),
							"desc" => esc_html__("Right offset (padding) inside background image to video block (in percent). For example: 12%", 'trx_addons'),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				),





				// Zoom
				"trx_zoom" => array(
					"title" => esc_html__("Zoom", 'trx_addons'),
					"desc" => esc_html__("Insert the image with zoom/lens effect", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"effect" => array(
							"title" => esc_html__("Effect", 'trx_addons'),
							"desc" => esc_html__("Select effect to display overlapping image", 'trx_addons'),
							"value" => "lens",
							"size" => "medium",
							"type" => "switch",
							"options" => array(
								"lens" => esc_html__('Lens', 'trx_addons'),
								"zoom" => esc_html__('Zoom', 'trx_addons')
							)
						),
						"url" => array(
							"title" => esc_html__("Main image", 'trx_addons'),
							"desc" => esc_html__("Select or upload main image", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"over" => array(
							"title" => esc_html__("Overlaping image", 'trx_addons'),
							"desc" => esc_html__("Select or upload overlaping image", 'trx_addons'),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"align" => array(
							"title" => esc_html__("Float zoom", 'trx_addons'),
							"desc" => esc_html__("Float zoom to left or right side", 'trx_addons'),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $THEMEREX_GLOBALS['sc_params']['float']
						), 
						"bg_image" => array(
							"title" => esc_html__("Background image", 'trx_addons'),
							"desc" => esc_html__("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", 'trx_addons'),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => esc_html__("Top offset", 'trx_addons'),
							"desc" => esc_html__("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", 'trx_addons'),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => esc_html__("Bottom offset", 'trx_addons'),
							"desc" => esc_html__("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", 'trx_addons'),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => esc_html__("Left offset", 'trx_addons'),
							"desc" => esc_html__("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", 'trx_addons'),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => esc_html__("Right offset", 'trx_addons'),
							"desc" => esc_html__("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", 'trx_addons'),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => themerex_shortcodes_width(),
						"height" => themerex_shortcodes_height(),
						"top" => $THEMEREX_GLOBALS['sc_params']['top'],
						"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
						"left" => $THEMEREX_GLOBALS['sc_params']['left'],
						"right" => $THEMEREX_GLOBALS['sc_params']['right'],
						"id" => $THEMEREX_GLOBALS['sc_params']['id'],
						"class" => $THEMEREX_GLOBALS['sc_params']['class'],
						"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
						"css" => $THEMEREX_GLOBALS['sc_params']['css']
					)
				)
			);


	
			// Woocommerce Shortcodes list
			//------------------------------------------------------------------
			if (themerex_exists_woocommerce()) {
				
				// WooCommerce - Cart
				$THEMEREX_GLOBALS['shortcodes']["woocommerce_cart"] = array(
					"title" => esc_html__("Woocommerce: Cart", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show Cart page", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Checkout
				$THEMEREX_GLOBALS['shortcodes']["woocommerce_checkout"] = array(
					"title" => esc_html__("Woocommerce: Checkout", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show Checkout page", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - My Account
				$THEMEREX_GLOBALS['shortcodes']["woocommerce_my_account"] = array(
					"title" => esc_html__("Woocommerce: My Account", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show My Account page", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Order Tracking
				$THEMEREX_GLOBALS['shortcodes']["woocommerce_order_tracking"] = array(
					"title" => esc_html__("Woocommerce: Order Tracking", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show Order Tracking page", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Shop Messages
				$THEMEREX_GLOBALS['shortcodes']["shop_messages"] = array(
					"title" => esc_html__("Woocommerce: Shop Messages", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show shop messages", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Product Page
				$THEMEREX_GLOBALS['shortcodes']["product_page"] = array(
					"title" => esc_html__("Woocommerce: Product Page", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: display single product page", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => esc_html__("SKU", 'trx_addons'),
							"desc" => esc_html__("SKU code of displayed product", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => esc_html__("ID", 'trx_addons'),
							"desc" => esc_html__("ID of displayed product", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"posts_per_page" => array(
							"title" => esc_html__("Number", 'trx_addons'),
							"desc" => esc_html__("How many products showed", 'trx_addons'),
							"value" => "1",
							"min" => 1,
							"type" => "spinner"
						),
						"post_type" => array(
							"title" => esc_html__("Post type", 'trx_addons'),
							"desc" => esc_html__("Post type for the WP query (leave 'product')", 'trx_addons'),
							"value" => "product",
							"type" => "text"
						),
						"post_status" => array(
							"title" => esc_html__("Post status", 'trx_addons'),
							"desc" => esc_html__("Display posts only with this status", 'trx_addons'),
							"value" => "publish",
							"type" => "select",
							"options" => array(
								"publish" => esc_html__('Publish', 'trx_addons'),
								"protected" => esc_html__('Protected', 'trx_addons'),
								"private" => esc_html__('Private', 'trx_addons'),
								"pending" => esc_html__('Pending', 'trx_addons'),
								"draft" => esc_html__('Draft', 'trx_addons')
							)
						)
					)
				);
				
				// WooCommerce - Product
				$THEMEREX_GLOBALS['shortcodes']["product"] = array(
					"title" => esc_html__("Woocommerce: Product", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: display one product", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => esc_html__("SKU", 'trx_addons'),
							"desc" => esc_html__("SKU code of displayed product", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => esc_html__("ID", 'trx_addons'),
							"desc" => esc_html__("ID of displayed product", 'trx_addons'),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Best Selling Products
				$THEMEREX_GLOBALS['shortcodes']["best_selling_products"] = array(
					"title" => esc_html__("Woocommerce: Best Selling Products", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show best selling products", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", 'trx_addons'),
							"desc" => esc_html__("How many products showed", 'trx_addons'),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						)
					)
				);
				
				// WooCommerce - Recent Products
				$THEMEREX_GLOBALS['shortcodes']["recent_products"] = array(
					"title" => esc_html__("Woocommerce: Recent Products", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show recent products", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", 'trx_addons'),
							"desc" => esc_html__("How many products showed", 'trx_addons'),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'trx_addons'),
								"title" => esc_html__('Title', 'trx_addons')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Related Products
				$THEMEREX_GLOBALS['shortcodes']["related_products"] = array(
					"title" => esc_html__("Woocommerce: Related Products", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show related products", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"posts_per_page" => array(
							"title" => esc_html__("Number", 'trx_addons'),
							"desc" => esc_html__("How many products showed", 'trx_addons'),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'trx_addons'),
								"title" => esc_html__('Title', 'trx_addons')
							)
						)
					)
				);
				
				// WooCommerce - Featured Products
				$THEMEREX_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => esc_html__("Woocommerce: Featured Products", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show featured products", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", 'trx_addons'),
							"desc" => esc_html__("How many products showed", 'trx_addons'),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'trx_addons'),
								"title" => esc_html__('Title', 'trx_addons')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Top Rated Products
				$THEMEREX_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => esc_html__("Woocommerce: Top Rated Products", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show top rated products", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", 'trx_addons'),
							"desc" => esc_html__("How many products showed", 'trx_addons'),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'trx_addons'),
								"title" => esc_html__('Title', 'trx_addons')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Sale Products
				$THEMEREX_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => esc_html__("Woocommerce: Sale Products", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: list products on sale", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", 'trx_addons'),
							"desc" => esc_html__("How many products showed", 'trx_addons'),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'trx_addons'),
								"title" => esc_html__('Title', 'trx_addons')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product Category
				$THEMEREX_GLOBALS['shortcodes']["product_category"] = array(
					"title" => esc_html__("Woocommerce: Products from category", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: list products in specified category(-ies)", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", 'trx_addons'),
							"desc" => esc_html__("How many products showed", 'trx_addons'),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'trx_addons'),
								"title" => esc_html__('Title', 'trx_addons')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						),
						"category" => array(
							"title" => esc_html__("Categories", 'trx_addons'),
							"desc" => esc_html__("Comma separated category slugs", 'trx_addons'),
							"value" => '',
							"type" => "text"
						),
						"operator" => array(
							"title" => esc_html__("Operator", 'trx_addons'),
							"desc" => esc_html__("Categories operator", 'trx_addons'),
							"value" => "IN",
							"type" => "checklist",
							"size" => "medium",
							"options" => array(
								"IN" => esc_html__('IN', 'trx_addons'),
								"NOT IN" => esc_html__('NOT IN', 'trx_addons'),
								"AND" => esc_html__('AND', 'trx_addons')
							)
						)
					)
				);
				
				// WooCommerce - Products
				$THEMEREX_GLOBALS['shortcodes']["products"] = array(
					"title" => esc_html__("Woocommerce: Products", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: list all products", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"skus" => array(
							"title" => esc_html__("SKUs", 'trx_addons'),
							"desc" => esc_html__("Comma separated SKU codes of products", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => esc_html__("IDs", 'trx_addons'),
							"desc" => esc_html__("Comma separated ID of products", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'trx_addons'),
								"title" => esc_html__('Title', 'trx_addons')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product attribute
				$THEMEREX_GLOBALS['shortcodes']["product_attribute"] = array(
					"title" => esc_html__("Woocommerce: Products by Attribute", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show products with specified attribute", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", 'trx_addons'),
							"desc" => esc_html__("How many products showed", 'trx_addons'),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'trx_addons'),
								"title" => esc_html__('Title', 'trx_addons')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						),
						"attribute" => array(
							"title" => esc_html__("Attribute", 'trx_addons'),
							"desc" => esc_html__("Attribute name", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"filter" => array(
							"title" => esc_html__("Filter", 'trx_addons'),
							"desc" => esc_html__("Attribute value", 'trx_addons'),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Products Categories
				$THEMEREX_GLOBALS['shortcodes']["product_categories"] = array(
					"title" => esc_html__("Woocommerce: Product Categories", 'trx_addons'),
					"desc" => esc_html__("WooCommerce shortcode: show categories with products", 'trx_addons'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"number" => array(
							"title" => esc_html__("Number", 'trx_addons'),
							"desc" => esc_html__("How many categories showed", 'trx_addons'),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'trx_addons'),
							"desc" => esc_html__("How many columns per row use for categories output", 'trx_addons'),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => esc_html__('Date', 'trx_addons'),
								"title" => esc_html__('Title', 'trx_addons')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", 'trx_addons'),
							"desc" => esc_html__("Sorting order for products output", 'trx_addons'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $THEMEREX_GLOBALS['sc_params']['ordering']
						),
						"parent" => array(
							"title" => esc_html__("Parent", 'trx_addons'),
							"desc" => esc_html__("Parent category slug", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => esc_html__("IDs", 'trx_addons'),
							"desc" => esc_html__("Comma separated ID of products", 'trx_addons'),
							"value" => "",
							"type" => "text"
						),
						"hide_empty" => array(
							"title" => esc_html__("Hide empty", 'trx_addons'),
							"desc" => esc_html__("Hide empty categories", 'trx_addons'),
							"value" => "yes",
							"type" => "switch",
							"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
						)
					)
				);

			}
			
			do_action('themerex_action_shortcodes_list');

		}
	}
}
?>