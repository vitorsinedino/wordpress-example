<?php

// Width and height params
if ( !function_exists( 'themerex_vc_width' ) ) {
	function themerex_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", 'trx_addons'),
			"description" => esc_html__("Width (in pixels or percent) of the current element", 'trx_addons'),
			"group" => esc_html__('Size &amp; Margins', 'trx_addons'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'themerex_vc_height' ) ) {
	function themerex_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", 'trx_addons'),
			"description" => esc_html__("Height (only in pixels) of the current element", 'trx_addons'),
			"group" => esc_html__('Size &amp; Margins', 'trx_addons'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'themerex_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'themerex_shortcodes_vc_scripts_admin' );
	function themerex_shortcodes_vc_scripts_admin() {
		// Include CSS 
		wp_enqueue_style ( 'shortcodes_vc-style', trx_addons_get_file_url('shortcodes/shortcodes_vc_admin.css'), array(), null );
		// Include JS
		wp_enqueue_script( 'shortcodes_vc-script', trx_addons_get_file_url('shortcodes/shortcodes_vc_admin.js'), array(), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'themerex_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'themerex_shortcodes_vc_scripts_front' );
	function themerex_shortcodes_vc_scripts_front() {
		if (themerex_vc_is_frontend()) {
			// Include CSS

			wp_enqueue_style ( 'shortcodes_vc-style', trx_addons_get_file_url('shortcodes/shortcodes_vc_front.css'), array(), null );
			// Include JS
            wp_enqueue_script( 'shortcodes_vc-script', trx_addons_get_file_url('shortcodes/shortcodes_vc_front.js'), array(), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'themerex_shortcodes_vc_add_init_script' ) ) {
	//add_filter('themerex_shortcode_output', 'themerex_shortcodes_vc_add_init_script', 10, 4);
	function themerex_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (themerex_strpos($output, 'themerex_vc_init_shortcodes')===false) {
				$id = "themerex_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							themerex_init_post_formats();
							themerex_init_shortcodes(jQuery("body").eq(0));
							themerex_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_shortcodes_vc_theme_setup' ) ) {
	//if ( themerex_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'themerex_action_before_init_theme', 'themerex_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'themerex_action_after_init_theme', 'themerex_shortcodes_vc_theme_setup' );
	function themerex_shortcodes_vc_theme_setup() {
		if (themerex_shortcodes_is_used()) {
			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'themerex_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'themerex_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('themerex_shortcode_output', 'themerex_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
			//vc_remove_element("vc_button");
			//vc_remove_element("vc_posts_slider");
			//vc_remove_element("vc_gmaps");
			//vc_remove_element("vc_teaser_grid");
			//vc_remove_element("vc_progress_bar");
			//vc_remove_element("vc_facebook");
			//vc_remove_element("vc_tweetmeme");
			//vc_remove_element("vc_googleplus");
			//vc_remove_element("vc_facebook");
			//vc_remove_element("vc_pinterest");
			//vc_remove_element("vc_message");
			//vc_remove_element("vc_posts_grid");
			//vc_remove_element("vc_carousel");
			//vc_remove_element("vc_flickr");
			//vc_remove_element("vc_tour");
			//vc_remove_element("vc_separator");
			//vc_remove_element("vc_single_image");
			//vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			//vc_remove_element("vc_toggle");
			//vc_remove_element("vc_tabs");
			//vc_remove_element("vc_tab");
			//vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			global $THEMEREX_GLOBALS;
			
			$THEMEREX_GLOBALS['vc_params'] = array(
				
				// Common arrays and strings
				'category' => esc_html__("ThemeREX shortcodes", 'trx_addons'),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", 'trx_addons'),
					"description" => esc_html__("ID for current element", 'trx_addons'),
					"group" => esc_html__('Size &amp; Margins', 'trx_addons'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", 'trx_addons'),
					"description" => esc_html__("CSS class for current element", 'trx_addons'),
					"group" => esc_html__('Size &amp; Margins', 'trx_addons'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", 'trx_addons'),
					"description" => esc_html__("Select animation while object enter in the visible area of page", 'trx_addons'),
					"class" => "",
					"value" => array_flip($THEMEREX_GLOBALS['sc_params']['animations']),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", 'trx_addons'),
					"description" => esc_html__("Any additional CSS rules (if need)", 'trx_addons'),
					"group" => esc_html__('Size &amp; Margins', 'trx_addons'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", 'trx_addons'),
					"description" => esc_html__("Top margin (in pixels).", 'trx_addons'),
					"group" => esc_html__('Size &amp; Margins', 'trx_addons'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", 'trx_addons'),
					"description" => esc_html__("Bottom margin (in pixels).", 'trx_addons'),
					"group" => esc_html__('Size &amp; Margins', 'trx_addons'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", 'trx_addons'),
					"description" => esc_html__("Left margin (in pixels).", 'trx_addons'),
					"group" => esc_html__('Size &amp; Margins', 'trx_addons'),
					"value" => "",
					"type" => "textfield"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", 'trx_addons'),
					"description" => esc_html__("Right margin (in pixels).", 'trx_addons'),
					"group" => esc_html__('Size &amp; Margins', 'trx_addons'),
					"value" => "",
					"type" => "textfield"
				)
			);
	
	
	
			// Accordion
			//-------------------------------------------------------------------------------------
			vc_map( array(
				"base" => "trx_accordion",
				"name" => esc_html__("Accordion", 'trx_addons'),
				"description" => esc_html__("Accordion items", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_accordion',
				"class" => "trx_sc_collection trx_sc_accordion",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Accordion style", 'trx_addons'),
						"description" => esc_html__("Select style for display accordion", 'trx_addons'),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => 1,
							esc_html__('Style 2', 'trx_addons') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "initial",
						"heading" => esc_html__("Initially opened item", 'trx_addons'),
						"description" => esc_html__("Number of initially opened item", 'trx_addons'),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", 'trx_addons'),
						"description" => esc_html__("Select icon for the closed accordion item from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", 'trx_addons'),
						"description" => esc_html__("Select icon for the opened accordion item from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'trx_addons' ) . '"][/trx_accordion_item]
					[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'trx_addons' ) . '"][/trx_accordion_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.esc_html__("Add item", 'trx_addons').'">'.esc_html__("Add item", 'trx_addons').'</button>
					</div>
				',
				'js_view' => 'VcTrxAccordionView'
			) );
			
			
			vc_map( array(
				"base" => "trx_accordion_item",
				"name" => esc_html__("Accordion item", 'trx_addons'),
				"description" => esc_html__("Inner accordion item", 'trx_addons'),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_accordion_item',
				"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_accordion'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => esc_html__("Title for current accordion item", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", 'trx_addons'),
						"description" => esc_html__("Select icon for the closed accordion item from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", 'trx_addons'),
						"description" => esc_html__("Select icon for the opened accordion item from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxAccordionTabView'
			) );

			class WPBakeryShortCode_Trx_Accordion extends THEMEREX_VC_ShortCodeAccordion {}
			class WPBakeryShortCode_Trx_Accordion_Item extends THEMEREX_VC_ShortCodeAccordionItem {}
			
			
			
			
			
			
			// Anchor
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_anchor",
				"name" => esc_html__("Anchor", 'trx_addons'),
				"description" => esc_html__("Insert anchor for the TOC (table of content)", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_anchor',
				"class" => "trx_sc_single trx_sc_anchor",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Anchor's icon", 'trx_addons'),
						"description" => esc_html__("Select icon for the anchor from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Short title", 'trx_addons'),
						"description" => esc_html__("Short title of the anchor (for the table of content)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Long description", 'trx_addons'),
						"description" => esc_html__("Description for the popup (then hover on the icon). You can use '{' and '}' - make the text italic, '|' - insert line break", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("External URL", 'trx_addons'),
						"description" => esc_html__("External URL for this TOC item", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "separator",
						"heading" => esc_html__("Add separator", 'trx_addons'),
						"description" => esc_html__("Add separator under item in the TOC", 'trx_addons'),
						"class" => "",
						"value" => array("Add separator" => "yes" ),
						"type" => "checkbox"
					),
					$THEMEREX_GLOBALS['vc_params']['id']
				),
			) );
			
			class WPBakeryShortCode_Trx_Anchor extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Audio
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_audio",
				"name" => esc_html__("Audio", 'trx_addons'),
				"description" => esc_html__("Insert audio player", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_audio',
				"class" => "trx_sc_single trx_sc_audio",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("URL for audio file", 'trx_addons'),
						"description" => esc_html__("Put here URL for audio file", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Cover image", 'trx_addons'),
						"description" => esc_html__("Select or upload image or write URL from other site for audio cover", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => esc_html__("Title of the audio file", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", 'trx_addons'),
						"description" => esc_html__("Author of the audio file", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'trx_addons'),
						"description" => esc_html__("Show/hide controls", 'trx_addons'),
						"class" => "",
						"value" => array("Hide controls" => "hide" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoplay",
						"heading" => esc_html__("Autoplay", 'trx_addons'),
						"description" => esc_html__("Autoplay audio on page load", 'trx_addons'),
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Select block alignment", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Audio extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_block",
				"name" => esc_html__("Block container", 'trx_addons'),
				"description" => esc_html__("Container for any block ([section] analog - to enable nesting)", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_block',
				"class" => "trx_sc_collection trx_sc_block",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => esc_html__("Dedicated", 'trx_addons'),
						"description" => esc_html__("Use this block as dedicated content - show it before post title on single page", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(esc_html__('Use as dedicated content', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Select block alignment", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns emulation", 'trx_addons'),
						"description" => esc_html__("Select width for columns emulation", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => esc_html__("Use pan effect", 'trx_addons'),
						"description" => esc_html__("Use pan effect to show section content", 'trx_addons'),
						"group" => esc_html__('Scroll', 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Content scroller', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", 'trx_addons'),
						"description" => esc_html__("Use scroller to show section content", 'trx_addons'),
						"group" => esc_html__('Scroll', 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(esc_html__('Content scroller', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => esc_html__("Scroll direction", 'trx_addons'),
						"description" => esc_html__("Scroll direction (if Use scroller = yes)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"group" => esc_html__('Scroll', 'trx_addons'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => esc_html__("Scroll controls", 'trx_addons'),
						"description" => esc_html__("Show scroll controls (if Use scroller = yes)", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Scroll', 'trx_addons'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", 'trx_addons'),
						"description" => esc_html__("Any color for objects in this section", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => esc_html__("Background tint", 'trx_addons'),
						"description" => esc_html__("Main background tint: dark or light", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'trx_addons'),
						"description" => esc_html__("Any background color for this section", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", 'trx_addons'),
						"description" => esc_html__("Select background image from library for this section", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", 'trx_addons'),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", 'trx_addons'),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", 'trx_addons'),
						"description" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", 'trx_addons'),
						"description" => esc_html__("Font weight of the text", 'trx_addons'),
						"class" => "",
						"value" => array(
							esc_html__('Default', 'trx_addons') => 'inherit',
							esc_html__('Thin (100)', 'trx_addons') => '100',
							esc_html__('Light (300)', 'trx_addons') => '300',
							esc_html__('Normal (400)', 'trx_addons') => '400',
							esc_html__('Bold (600)', 'trx_addons') => '600'
						),
						"type" => "dropdown"
					),
/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", 'trx_addons'),
						"description" => esc_html__("Content for section container", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Block extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Blogger
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_blogger",
				"name" => esc_html__("Blogger", 'trx_addons'),
				"description" => esc_html__("Insert posts (pages) in many styles from desired categories or directly from ids", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_blogger',
				"class" => "trx_sc_single trx_sc_blogger",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Output style", 'trx_addons'),
						"description" => esc_html__("Select desired style for posts output", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['blogger_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "filters",
						"heading" => esc_html__("Show filters", 'trx_addons'),
						"description" => esc_html__("Use post's tags or categories as filter buttons", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['filters']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "location",
						"heading" => esc_html__("Dedicated content location", 'trx_addons'),
						"description" => esc_html__("Select position for dedicated content (only for style=excerpt)", 'trx_addons'),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('excerpt')
						),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['locations']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Posts direction", 'trx_addons'),
						"description" => esc_html__("Display posts in horizontal or vertical direction", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "rating",
						"heading" => esc_html__("Show rating stars", 'trx_addons'),
						"description" => esc_html__("Show rating stars under post's header", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Show rating', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "info",
						"heading" => esc_html__("Show post info block", 'trx_addons'),
						"description" => esc_html__("Show post info block (author, date, tags, etc.)", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Show info', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "descr",
						"heading" => esc_html__("Description length", 'trx_addons'),
						"description" => esc_html__("How many characters are displayed from post excerpt? If 0 - don't show description", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => esc_html__("Allow links to the post", 'trx_addons'),
						"description" => esc_html__("Allow links to the post from each blogger item", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Allow links', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("More link text", 'trx_addons'),
						"description" => esc_html__("Read more link text. If empty - show 'More', else - used as link text", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", 'trx_addons'),
						"description" => esc_html__("Select post type to show", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['posts_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Post IDs list", 'trx_addons'),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories list", 'trx_addons'),
						"description" => esc_html__("Put here comma separated category slugs or ids. If empty - show posts from any category or from IDs list", 'trx_addons'),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => esc_html__('Query', 'trx_addons'),
						"class" => "",
						"value" => array_flip(themerex_array_merge(array(0 => esc_html__('- Select category -', 'trx_addons')), $THEMEREX_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Total posts to show", 'trx_addons'),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_addons'),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"group" => esc_html__('Query', 'trx_addons'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns number", 'trx_addons'),
						"description" => esc_html__("How many columns used to display posts?", 'trx_addons'),
						'dependency' => array(
							'element' => 'dir',
							'value' => 'horizontal'
						),
						"group" => esc_html__('Query', 'trx_addons'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'trx_addons'),
						"description" => esc_html__("Skip posts before select next part.", 'trx_addons'),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => esc_html__('Query', 'trx_addons'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post order by", 'trx_addons'),
						"description" => esc_html__("Select desired posts sorting method", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Query', 'trx_addons'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'trx_addons'),
						"description" => esc_html__("Select desired posts order", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Query', 'trx_addons'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "only",
						"heading" => esc_html__("Select posts only", 'trx_addons'),
						"description" => esc_html__("Select posts only with reviews, videos, audios, thumbs or galleries", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Query', 'trx_addons'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['formats']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", 'trx_addons'),
						"description" => esc_html__("Use scroller to show all posts", 'trx_addons'),
						"group" => esc_html__('Scroll', 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Use scroller', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Show slider controls", 'trx_addons'),
						"description" => esc_html__("Show arrows to control scroll slider", 'trx_addons'),
						"group" => esc_html__('Scroll', 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Show controls', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Blogger extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Br
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_br",
				"name" => esc_html__("Line break", 'trx_addons'),
				"description" => esc_html__("Line break or Clear Floating", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_br',
				"class" => "trx_sc_single trx_sc_br",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "clear",
						"heading" => esc_html__("Clear floating", 'trx_addons'),
						"description" => esc_html__("Select clear side (if need)", 'trx_addons'),
						"class" => "",
						"value" => "",
						"value" => array(
							esc_html__('None', 'trx_addons') => 'none',
							esc_html__('Left', 'trx_addons') => 'left',
							esc_html__('Right', 'trx_addons') => 'right',
							esc_html__('Both', 'trx_addons') => 'both'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Br extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Button
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_button",
				"name" => esc_html__("Button", 'trx_addons'),
				"description" => esc_html__("Button with link", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_button',
				"class" => "trx_sc_single trx_sc_button",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Caption", 'trx_addons'),
						"description" => esc_html__("Button caption", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Button's shape", 'trx_addons'),
						"description" => esc_html__("Select button's shape", 'trx_addons'),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Square', 'trx_addons') => 'square',
							esc_html__('Round', 'trx_addons') => 'round'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "style",
						"heading" => esc_html__("Button's style", 'trx_addons'),
						"description" => esc_html__("Select button's style", 'trx_addons'),
						"class" => "",
						"value" => array(
							esc_html__('Filled', 'trx_addons') => 'filled',
							esc_html__('Simple', 'trx_addons') => 'border'
						),
						"type" => "dropdown"
					),

					array(
						"param_name" => "size",
						"heading" => esc_html__("Button's size", 'trx_addons'),
						"description" => esc_html__("Select button's size", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Small', 'trx_addons') => 'mini',
							esc_html__('Medium', 'trx_addons') => 'medium',
							esc_html__('Large', 'trx_addons') => 'big'
						),
						"type" => "dropdown"
					),
					*/
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Button's icon", 'trx_addons'),
						"description" => esc_html__("Select icon for the title from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_style",
						"heading" => esc_html__("Button's color scheme", 'trx_addons'),
						"description" => esc_html__("Select button's color scheme", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['button_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Button's text color", 'trx_addons'),
						"description" => esc_html__("Any color for button's caption", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Button's backcolor", 'trx_addons'),
						"description" => esc_html__("Any color for button's background", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Button's alignment", 'trx_addons'),
						"description" => esc_html__("Align button to left, center or right", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", 'trx_addons'),
						"description" => esc_html__("URL for the link on button click", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Link', 'trx_addons'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => esc_html__("Link target", 'trx_addons'),
						"description" => esc_html__("Target for the link on button click", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Link', 'trx_addons'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "popup",
						"heading" => esc_html__("Open link in popup", 'trx_addons'),
						"description" => esc_html__("Open link target in popup window", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Link', 'trx_addons'),
						"value" => array(esc_html__('Open in popup', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "rel",
						"heading" => esc_html__("Rel attribute", 'trx_addons'),
						"description" => esc_html__("Rel attribute for the button's link (if need", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Link', 'trx_addons'),
						"value" => "",
						"type" => "textfield"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Button extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Chat
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_chat",
				"name" => esc_html__("Chat", 'trx_addons'),
				"description" => esc_html__("Chat message", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_chat',
				"class" => "trx_sc_container trx_sc_chat",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Item title", 'trx_addons'),
						"description" => esc_html__("Title for current chat item", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Item photo", 'trx_addons'),
						"description" => esc_html__("Select or upload image or write URL from other site for the item photo (avatar)", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", 'trx_addons'),
						"description" => esc_html__("URL for the link on chat title click", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
array(
						"param_name" => "content",
						"heading" => esc_html__("Chat item content", 'trx_addons'),
						"description" => esc_html__("Current chat item content", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			
			) );
			
			class WPBakeryShortCode_Trx_Chat extends THEMEREX_VC_ShortCodeContainer {}
			
			
			
			
			
			
			// Columns
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_columns",
				"name" => esc_html__("Columns", 'trx_addons'),
				"description" => esc_html__("Insert columns with margins", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_columns',
				"class" => "trx_sc_columns",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_column_item'),
				"params" => array(
					array(
						"param_name" => "count",
						"heading" => esc_html__("Columns count", 'trx_addons'),
						"description" => esc_html__("Number of the columns in the container.", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "fluid",
						"heading" => esc_html__("Fluid columns", 'trx_addons'),
						"description" => esc_html__("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Fluid columns', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Auto Height", 'trx_addons'),
						"description" => esc_html__("Fit height to the larger value of child elements (for background images)", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Auto Height', 'trx_addons') => 'yes'),
						"type" => "checkbox",
					),
					array(
						"param_name" => "indentation",
						"heading" => esc_html__("Indentation", 'trx_addons'),
						"description" => esc_html__("Column is indented", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Indentation', 'trx_addons') => 'yes'),
						"type" => "checkbox",
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_column_item][/trx_column_item]
					[trx_column_item][/trx_column_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_column_item",
				"name" => esc_html__("Column", 'trx_addons'),
				"description" => esc_html__("Column item", 'trx_addons'),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_column_item',
				"as_child" => array('only' => 'trx_columns'),
				"as_parent" => array('except' => 'trx_columns'),
				"params" => array(
					array(
						"param_name" => "span",
						"heading" => esc_html__("Merge columns", 'trx_addons'),
						"description" => esc_html__("Count merged columns from current", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Alignment text in the column", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", 'trx_addons'),
						"description" => esc_html__("Any color for objects in this column", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'trx_addons'),
						"description" => esc_html__("Any background color for this column", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("URL for background image file", 'trx_addons'),
						"description" => esc_html__("Select or upload image or write URL from other site for the background", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Column's content", 'trx_addons'),
						"description" => esc_html__("Content of the current column", 'trx_addons'),
						"class" => "",
						"value" => "",					
						"type" => "textarea_html"
					), 
*/
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
			class WPBakeryShortCode_Trx_Columns extends THEMEREX_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Column_Item extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Contact form
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_contact_form",
				"name" => esc_html__("Contact form", 'trx_addons'),
				"description" => esc_html__("Insert contact form", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_contact_form',
				"class" => "trx_sc_collection trx_sc_contact_form",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_form_item'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'trx_addons'),
						"description" => esc_html__("Use custom fields or create standard contact form (ignore info from 'Field' tabs)", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Create custom form', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "action",
						"heading" => esc_html__("Action", 'trx_addons'),
						"description" => esc_html__("Contact form action (URL to handle form data). If empty - use internal action", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Select form alignment", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => esc_html__("Title above contact form", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description (under the title)", 'trx_addons'),
						"description" => esc_html__("Contact form description", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					themerex_vc_width(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_form_item",
				"name" => esc_html__("Form item (custom field)", 'trx_addons'),
				"description" => esc_html__("Custom field for the contact form", 'trx_addons'),
				"class" => "trx_sc_item trx_sc_form_item",
				'icon' => 'icon_trx_form_item',
				"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_contact_form'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Type", 'trx_addons'),
						"description" => esc_html__("Select type of the custom field", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['field_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", 'trx_addons'),
						"description" => esc_html__("Name of the custom field", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => esc_html__("Default value", 'trx_addons'),
						"description" => esc_html__("Default value of the custom field", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label",
						"heading" => esc_html__("Label", 'trx_addons'),
						"description" => esc_html__("Label for the custom field", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label_position",
						"heading" => esc_html__("Label position", 'trx_addons'),
						"description" => esc_html__("Label position relative to the field", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['label_positions']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Contact_Form extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Form_Item extends THEMEREX_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Content block on fullscreen page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_content",
				"name" => esc_html__("Content block", 'trx_addons'),
				"description" => esc_html__("Container for main content block (use it only on fullscreen pages)", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_content',
				"class" => "trx_sc_collection trx_sc_content",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", 'trx_addons'),
						"description" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", 'trx_addons'),
						"description" => esc_html__("Font weight of the text", 'trx_addons'),
						"class" => "",
						"value" => array(
							esc_html__('Default', 'trx_addons') => 'inherit',
							esc_html__('Thin (100)', 'trx_addons') => '100',
							esc_html__('Light (300)', 'trx_addons') => '300',
							esc_html__('Normal (400)', 'trx_addons') => '400',
							esc_html__('Bold (700)', 'trx_addons') => '700'
						),
						"type" => "dropdown"
					),
/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", 'trx_addons'),
						"description" => esc_html__("Content for section container", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Content extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Countdown
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_countdown",
				"name" => esc_html__("Countdown", 'trx_addons'),
				"description" => esc_html__("Insert countdown object", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_countdown',
				"class" => "trx_sc_single trx_sc_countdown",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "date",
						"heading" => esc_html__("Date", 'trx_addons'),
						"description" => esc_html__("Upcoming date (format: yyyy-mm-dd)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "time",
						"heading" => esc_html__("Time", 'trx_addons'),
						"description" => esc_html__("Upcoming time (format: HH:mm:ss)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", 'trx_addons'),
						"description" => esc_html__("Countdown style", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => 1,
							esc_html__('Style 2', 'trx_addons') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Align counter to left, center or right", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Countdown extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Dropcaps
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_dropcaps",
				"name" => esc_html__("Dropcaps", 'trx_addons'),
				"description" => esc_html__("Make first letter of the text as dropcaps", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_dropcaps',
				"class" => "trx_sc_single trx_sc_dropcaps",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", 'trx_addons'),
						"description" => esc_html__("Dropcaps style", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => 1,
							esc_html__('Style 2', 'trx_addons') => 2,
							esc_html__('Style 3', 'trx_addons') => 3,
							esc_html__('Style 4', 'trx_addons') => 4
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Paragraph text", 'trx_addons'),
						"description" => esc_html__("Paragraph with dropcaps content", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_Dropcaps extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Emailer
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_emailer",
				"name" => esc_html__("E-mail collector", 'trx_addons'),
				"description" => esc_html__("Collect e-mails into specified group", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_emailer',
				"class" => "trx_sc_single trx_sc_emailer",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "group",
						"heading" => esc_html__("Group", 'trx_addons'),
						"description" => esc_html__("The name of group to collect e-mail address", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => esc_html__("Opened", 'trx_addons'),
						"description" => esc_html__("Initially open the input field on show object", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Initially opened', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Align field to left, center or right", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Emailer extends THEMEREX_VC_ShortCodeSingle {}



			vc_map( array(
				"base" => "trx_events",
				"name" => esc_html__("Events", 'trx_addons'),
				"description" => esc_html__("Insert posts (events) from desired categories or directly from ids", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_events',
				"class" => "trx_sc_single trx_sc_events",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Post IDs list", 'trx_addons'),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Category ID", 'trx_addons'),
						"description" => esc_html__("Category ID", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Total posts to show", 'trx_addons'),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored. (max - 4)", 'trx_addons'),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"group" => esc_html__('Query', 'trx_addons'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "col",
						"heading" => esc_html__("Column", 'trx_addons'),
						"description" => esc_html__("How many columns will be displayed? (max - 4)", 'trx_addons'),
						"admin_label" => true,
						"group" => esc_html__('Query', 'trx_addons'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'trx_addons'),
						"description" => esc_html__("Select desired posts order", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Query', 'trx_addons'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation']
				),
			) );


			class WPBakeryShortCode_Trx_Events extends THEMEREX_VC_ShortCodeSingle {}



			// Gallery
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_gallery",
				"name" => esc_html__("Gallery", 'trx_addons'),
				"description" => esc_html__("Insert gallery", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_slider',
				"class" => "trx_sc_single trx_sc_gallery",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
						array(
							"param_name" => "cat",
							"heading" => esc_html__("Categories list", 'trx_addons'),
							"description" => esc_html__("Select category. If empty - show posts from any category or from IDs list", 'trx_addons'),
							"class" => "",
							"value" => array_flip(themerex_array_merge(array(0 => esc_html__('- Select category -', 'trx_addons')), $THEMEREX_GLOBALS['sc_params']['categories'])),
							"type" => "dropdown",
							"admin_label" => true
						),
						array(
							"param_name" => "count",
							"heading" => esc_html__("Number of posts", 'trx_addons'),
							"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_addons'),
							"class" => "",
							"value" => "10",
							"type" => "textfield",
							"admin_label" => true
						),
						array(
							"param_name" => "offset",
							"heading" => esc_html__("Offset before select posts", 'trx_addons'),
							"description" => esc_html__("Skip posts before select next part.", 'trx_addons'),
							"class" => "",
							"value" => "0",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Post sorting", 'trx_addons'),
							"description" => esc_html__("Select desired posts sorting method", 'trx_addons'),
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sorting']),
							"type" => "dropdown",
							"admin_label" => true
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Post order", 'trx_addons'),
							"description" => esc_html__("Select desired posts order", 'trx_addons'),
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "ids",
							"heading" => esc_html__("Post IDs list", 'trx_addons'),
							"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "descriptions",
							"heading" => esc_html__("Post descriptions", 'trx_addons'),
							"description" => esc_html__("Show post's excerpt max length (characters)", 'trx_addons'),
							"group" => esc_html__('Details', 'trx_addons'),
							"class" => "",
							"value" => "100",
							"type" => "textfield"
						),
						array(
							"param_name" => "bg_color",
							"heading" => esc_html__("Backgroud color", 'trx_addons'),
							"description" => esc_html__("Select color for Gallery background", 'trx_addons'),
							"class" => "",
							"value" => "",
							"type" => "colorpicker",
							"admin_label" => true
						),
						array(
							"param_name" => "bg_image",
							"heading" => esc_html__("Background image", 'trx_addons'),
							"description" => esc_html__("Select or upload image or write URL from other site for the Gallery background", 'trx_addons'),
							"class" => "",
							"value" => "",
							"type" => "attach_image",
							"admin_label" => true
						),
						themerex_vc_height(),
						$THEMEREX_GLOBALS['vc_params']['margin_top'],
						$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
						$THEMEREX_GLOBALS['vc_params']['id'],
						$THEMEREX_GLOBALS['vc_params']['class'],
						$THEMEREX_GLOBALS['vc_params']['animation']
					)
			) );

			class WPBakeryShortCode_Trx_Gallery extends THEMEREX_VC_ShortCodeSingle {}



			
			
			// Gap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_gap",
				"name" => esc_html__("Gap", 'trx_addons'),
				"description" => esc_html__("Insert gap (fullwidth area) in the post content", 'trx_addons'),
				"category" => esc_html__('Structure', 'trx_addons'),
				'icon' => 'icon_trx_gap',
				"class" => "trx_sc_collection trx_sc_gap",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"params" => array(
/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Gap content", 'trx_addons'),
						"description" => esc_html__("Gap inner content", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					)
*/
				)
			) );
			
			class WPBakeryShortCode_Trx_Gap extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Googlemap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_googlemap",
				"name" => esc_html__("Google map", 'trx_addons'),
				"description" => esc_html__("Insert Google map with desired address or coordinates", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_googlemap',
				"class" => "trx_sc_single trx_sc_googlemap",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "address",
						"heading" => esc_html__("Address", 'trx_addons'),
						"description" => esc_html__("Address to show in map center", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "latlng",
						"heading" => esc_html__("Latitude and Longtitude", 'trx_addons'),
						"description" => esc_html__("Comma separated map center coorditanes (instead Address)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "zoom",
						"heading" => esc_html__("Zoom", 'trx_addons'),
						"description" => esc_html__("Map zoom factor", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "16",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", 'trx_addons'),
						"description" => esc_html__("Map custom style", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['googlemap_styles']),
						"type" => "dropdown"
					),
					themerex_vc_width('100%'),
					themerex_vc_height(240),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Googlemap extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Highlight
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_highlight",
				"name" => esc_html__("Highlight text", 'trx_addons'),
				"description" => esc_html__("Highlight text with selected color, background color and other styles", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_highlight',
				"class" => "trx_sc_single trx_sc_highlight",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Type", 'trx_addons'),
						"description" => esc_html__("Highlight type", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								esc_html__('Custom', 'trx_addons') => 0,
								esc_html__('Type 1', 'trx_addons') => 1,
								esc_html__('Type 2', 'trx_addons') => 2,
								esc_html__('Type 3', 'trx_addons') => 3
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", 'trx_addons'),
						"description" => esc_html__("Color for the highlighted text", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'trx_addons'),
						"description" => esc_html__("Background color for the highlighted text", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", 'trx_addons'),
						"description" => esc_html__("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Highlight text", 'trx_addons'),
						"description" => esc_html__("Content for highlight", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Highlight extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Icon
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_icon",
				"name" => esc_html__("Icon", 'trx_addons'),
				"description" => esc_html__("Insert the icon", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_icon',
				"class" => "trx_sc_single trx_sc_icon",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", 'trx_addons'),
						"description" => esc_html__("Select icon class from Fontello icons set", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", 'trx_addons'),
						"description" => esc_html__("Icon's color", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'trx_addons'),
						"description" => esc_html__("Background color for the icon", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", 'trx_addons'),
						"description" => esc_html__("Overlay bg color opacity (from 0.0 to 1.0)", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_shape",
						"heading" => esc_html__("Background shape", 'trx_addons'),
						"description" => esc_html__("Shape of the icon background", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('None', 'trx_addons') => 'none',
							esc_html__('Round', 'trx_addons') => 'round',
							esc_html__('Square', 'trx_addons') => 'square'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", 'trx_addons'),
						"description" => esc_html__("Icon's font size", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", 'trx_addons'),
						"description" => esc_html__("Icon's font weight", 'trx_addons'),
						"class" => "",
						"value" => array(
							esc_html__('Default', 'trx_addons') => 'inherit',
							esc_html__('Thin (100)', 'trx_addons') => '100',
							esc_html__('Light (300)', 'trx_addons') => '300',
							esc_html__('Normal (400)', 'trx_addons') => '400',
							esc_html__('Bold (600)', 'trx_addons') => '600'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Icon's alignment", 'trx_addons'),
						"description" => esc_html__("Align icon to left, center or right", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", 'trx_addons'),
						"description" => esc_html__("Link URL from this icon (if not empty)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Icon extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Image
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_image",
				"name" => esc_html__("Image", 'trx_addons'),
				"description" => esc_html__("Insert image", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_image',
				"class" => "trx_sc_single trx_sc_image",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("Select image", 'trx_addons'),
						"description" => esc_html__("Select image from library", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Image alignment", 'trx_addons'),
						"description" => esc_html__("Align image to left or right side", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						//"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => esc_html__("Image shape", 'trx_addons'),
						"description" => esc_html__("Shape of the image: square or round", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Square', 'trx_addons') => 'square',
							esc_html__('Round', 'trx_addons') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => esc_html__("Image's title", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Title's icon", 'trx_addons'),
						"description" => esc_html__("Select icon for the title from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", 'trx_addons'),
						"description" => esc_html__("Link URL from title", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Image extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Infobox
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_infobox",
				"name" => esc_html__("Infobox", 'trx_addons'),
				"description" => esc_html__("Box with info or error message", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_infobox',
				"class" => "trx_sc_container trx_sc_infobox",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", 'trx_addons'),
						"description" => esc_html__("Infobox style", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								esc_html__('Regular', 'trx_addons') => 'regular',
								esc_html__('Info', 'trx_addons') => 'info',
								esc_html__('Success', 'trx_addons') => 'success',
								esc_html__('Error', 'trx_addons') => 'error',
								esc_html__('Result', 'trx_addons') => 'result'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "closeable",
						"heading" => esc_html__("Closeable", 'trx_addons'),
						"description" => esc_html__("Create closeable box (with close button)", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Close button', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Custom icon", 'trx_addons'),
						"description" => esc_html__("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", 'trx_addons'),
						"description" => esc_html__("Any color for the text and headers", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'trx_addons'),
						"description" => esc_html__("Any background color for this infobox", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Message text", 'trx_addons'),
						"description" => esc_html__("Message for the infobox", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Infobox extends THEMEREX_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Line
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_line",
				"name" => esc_html__("Line", 'trx_addons'),
				"description" => esc_html__("Insert line (delimiter)", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				"class" => "trx_sc_single trx_sc_line",
				'icon' => 'icon_trx_line',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", 'trx_addons'),
						"description" => esc_html__("Line style", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								esc_html__('Solid', 'trx_addons') => 'solid',
								esc_html__('Dashed', 'trx_addons') => 'dashed',
								esc_html__('Dotted', 'trx_addons') => 'dotted',
								esc_html__('Double', 'trx_addons') => 'double',
								esc_html__('Shadow', 'trx_addons') => 'shadow'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Line color", 'trx_addons'),
						"description" => esc_html__("Line color", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Line extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// List
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_list",
				"name" => esc_html__("List", 'trx_addons'),
				"description" => esc_html__("List items with specific bullets", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				"class" => "trx_sc_collection trx_sc_list",
				'icon' => 'icon_trx_list',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_list_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Bullet's style", 'trx_addons'),
						"description" => esc_html__("Bullet's style for each list item", 'trx_addons'),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['list_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", 'trx_addons'),
						"description" => esc_html__("List items color", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("List icon", 'trx_addons'),
						"description" => esc_html__("Select list icon from Fontello icons set (only for style=Iconed)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => esc_html__("Icon color", 'trx_addons'),
						"description" => esc_html__("List icons color", 'trx_addons'),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => "",
						"type" => "colorpicker"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_list_item]' . esc_html__( 'Item 1', 'trx_addons' ) . '[/trx_list_item]
					[trx_list_item]' . esc_html__( 'Item 2', 'trx_addons' ) . '[/trx_list_item]
				'
			) );
			
			
			vc_map( array(
				"base" => "trx_list_item",
				"name" => esc_html__("List item", 'trx_addons'),
				"description" => esc_html__("List item with specific bullet", 'trx_addons'),
				"class" => "trx_sc_single trx_sc_list_item",
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_list_item',
				"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_list'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("List item title", 'trx_addons'),
						"description" => esc_html__("Title for the current list item (show it as tooltip)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", 'trx_addons'),
						"description" => esc_html__("Link URL for the current list item", 'trx_addons'),
						"admin_label" => true,
						"group" => esc_html__('Link', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => esc_html__("Link target", 'trx_addons'),
						"description" => esc_html__("Link target for the current list item", 'trx_addons'),
						"admin_label" => true,
						"group" => esc_html__('Link', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", 'trx_addons'),
						"description" => esc_html__("Text color for this item", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("List item icon", 'trx_addons'),
						"description" => esc_html__("Select list item icon from Fontello icons set (only for style=Iconed)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => esc_html__("Icon color", 'trx_addons'),
						"description" => esc_html__("Icon color for this item", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("List item text", 'trx_addons'),
						"description" => esc_html__("Current list item content", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_List extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_List_Item extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			
			
			// Number
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_number",
				"name" => esc_html__("Number", 'trx_addons'),
				"description" => esc_html__("Insert number or any word as set of separated characters", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				"class" => "trx_sc_single trx_sc_number",
				'icon' => 'icon_trx_number',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "value",
						"heading" => esc_html__("Value", 'trx_addons'),
						"description" => esc_html__("Number or any word to separate", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Select block alignment", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Number extends THEMEREX_VC_ShortCodeSingle {}


			
			
			
			
			
			// Parallax
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_parallax",
				"name" => esc_html__("Parallax", 'trx_addons'),
				"description" => esc_html__("Create the parallax container (with asinc background image)", 'trx_addons'),
				"category" => esc_html__('Structure', 'trx_addons'),
				'icon' => 'icon_trx_parallax',
				"class" => "trx_sc_collection trx_sc_parallax",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "gap",
						"heading" => esc_html__("Create gap", 'trx_addons'),
						"description" => esc_html__("Create gap around parallax container (not need in fullscreen pages)", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Create gap', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Direction", 'trx_addons'),
						"description" => esc_html__("Scroll direction for the parallax background", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								esc_html__('Up', 'trx_addons') => 'up',
								esc_html__('Down', 'trx_addons') => 'down'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "speed",
						"heading" => esc_html__("Speed", 'trx_addons'),
						"description" => esc_html__("Parallax background motion speed (from 0.0 to 1.0)", 'trx_addons'),
						"class" => "",
						"value" => "0.3",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", 'trx_addons'),
						"description" => esc_html__("Select color for text object inside parallax block", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => esc_html__("Bg tint", 'trx_addons'),
						"description" => esc_html__("Select tint of the parallax background (for correct font color choise)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								esc_html__('Light', 'trx_addons') => 'light',
								esc_html__('Dark', 'trx_addons') => 'dark'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Backgroud color", 'trx_addons'),
						"description" => esc_html__("Select color for parallax background", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", 'trx_addons'),
						"description" => esc_html__("Select or upload image or write URL from other site for the parallax background", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image_x",
						"heading" => esc_html__("Image X position", 'trx_addons'),
						"description" => esc_html__("Parallax background X position (in percents)", 'trx_addons'),
						"class" => "",
						"value" => "50%",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video",
						"heading" => esc_html__("Video background", 'trx_addons'),
						"description" => esc_html__("Paste URL for video file to show it as parallax background", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video_ratio",
						"heading" => esc_html__("Video ratio", 'trx_addons'),
						"description" => esc_html__("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", 'trx_addons'),
						"class" => "",
						"value" => "16:9",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", 'trx_addons'),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", 'trx_addons'),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Content", 'trx_addons'),
						"description" => esc_html__("Content for the parallax container", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Parallax extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Popup
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_popup",
				"name" => esc_html__("Popup window", 'trx_addons'),
				"description" => esc_html__("Container for any html-block with desired class and style for popup window", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_popup',
				"class" => "trx_sc_collection trx_sc_popup",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", 'trx_addons'),
						"description" => esc_html__("Content for popup container", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Popup extends THEMEREX_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Price
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price",
				"name" => esc_html__("Price", 'trx_addons'),
				"description" => esc_html__("Insert price with decoration", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_price',
				"class" => "trx_sc_single trx_sc_price",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "money",
						"heading" => esc_html__("Money", 'trx_addons'),
						"description" => esc_html__("Money value (dot or comma separated)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency symbol", 'trx_addons'),
						"description" => esc_html__("Currency character", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => esc_html__("Period", 'trx_addons'),
						"description" => esc_html__("Period text (if need). For example: monthly, daily, etc.", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Align price to left or right side", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Price extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Price block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price_block",
				"name" => esc_html__("Price block", 'trx_addons'),
				"description" => esc_html__("Insert price block with title, price and description", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_price_block',
				"class" => "trx_sc_single trx_sc_price_block",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Price block style", 'trx_addons'),
						"description" => esc_html__("Select style of Price block", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => '1',
							esc_html__('Style 2', 'trx_addons') => '2'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => esc_html__("Block title", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", 'trx_addons'),
						"description" => esc_html__("URL for link from button (at bottom of the block)", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_text",
						"heading" => esc_html__("Link text", 'trx_addons'),
						"description" => esc_html__("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", 'trx_addons'),
						"description" => esc_html__("Select icon from Fontello icons set (placed before/instead price)", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "money",
						"heading" => esc_html__("Money", 'trx_addons'),
						"description" => esc_html__("Money value (dot or comma separated)", 'trx_addons'),
						"admin_label" => true,
						"group" => esc_html__('Money', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency symbol", 'trx_addons'),
						"description" => esc_html__("Currency character", 'trx_addons'),
						"admin_label" => true,
						"group" => esc_html__('Money', 'trx_addons'),
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => esc_html__("Period", 'trx_addons'),
						"description" => esc_html__("Period text (if need). For example: monthly, daily, etc.", 'trx_addons'),
						"admin_label" => true,
						"group" => esc_html__('Money', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Align price to left or right side", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Description", 'trx_addons'),
						"description" => esc_html__("Description for this price block", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_PriceBlock extends THEMEREX_VC_ShortCodeSingle {}

			
			
			
			
			// Quote
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_quote",
				"name" => esc_html__("Quote", 'trx_addons'),
				"description" => esc_html__("Quote text", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_quote',
				"class" => "trx_sc_single trx_sc_quote",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "cite",
						"heading" => esc_html__("Quote cite", 'trx_addons'),
						"description" => esc_html__("URL for the quote cite link", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title (author)", 'trx_addons'),
						"description" => esc_html__("Quote title (author name)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Quote style", 'trx_addons'),
						"description" => esc_html__("Select style of Quote block", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => '1',
							esc_html__('Style 2', 'trx_addons') => '2'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Quote content", 'trx_addons'),
						"description" => esc_html__("Quote content", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					themerex_vc_width(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Quote extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Reviews
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_reviews",
				"name" => esc_html__("Reviews", 'trx_addons'),
				"description" => esc_html__("Insert reviews block in the single post", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_reviews',
				"class" => "trx_sc_single trx_sc_reviews",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Align counter to left, center or right", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Reviews extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Search
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_search",
				"name" => esc_html__("Search form", 'trx_addons'),
				"description" => esc_html__("Insert search form", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_search',
				"class" => "trx_sc_single trx_sc_search",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => esc_html__("Title (placeholder) for the search field", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => esc_html__("Search &hellip;", 'trx_addons'),
						"type" => "textfield"
					),
					array(
						"param_name" => "ajax",
						"heading" => esc_html__("AJAX", 'trx_addons'),
						"description" => esc_html__("Search via AJAX or reload page", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Use AJAX search', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Search extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Section
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_section",
				"name" => esc_html__("Section container", 'trx_addons'),
				"description" => esc_html__("Container for any block ([block] analog - to enable nesting)", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				"class" => "trx_sc_collection trx_sc_section",
				'icon' => 'icon_trx_block',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => esc_html__("Dedicated", 'trx_addons'),
						"description" => esc_html__("Use this block as dedicated content - show it before post title on single page", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(esc_html__('Use as dedicated content', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Select block alignment", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns emulation", 'trx_addons'),
						"description" => esc_html__("Select width for columns emulation", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => esc_html__("Use pan effect", 'trx_addons'),
						"description" => esc_html__("Use pan effect to show section content", 'trx_addons'),
						"group" => esc_html__('Scroll', 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Content scroller', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", 'trx_addons'),
						"description" => esc_html__("Use scroller to show section content", 'trx_addons'),
						"group" => esc_html__('Scroll', 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(esc_html__('Content scroller', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => esc_html__("Scroll and Pan direction", 'trx_addons'),
						"description" => esc_html__("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"group" => esc_html__('Scroll', 'trx_addons'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => esc_html__("Scroll controls", 'trx_addons'),
						"description" => esc_html__("Show scroll controls (if Use scroller = yes)", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Scroll', 'trx_addons'),
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", 'trx_addons'),
						"description" => esc_html__("Any color for objects in this section", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => esc_html__("Background tint", 'trx_addons'),
						"description" => esc_html__("Main background tint: dark or light", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'trx_addons'),
						"description" => esc_html__("Any background color for this section", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", 'trx_addons'),
						"description" => esc_html__("Select background image from library for this section", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", 'trx_addons'),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", 'trx_addons'),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", 'trx_addons'),
						"description" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", 'trx_addons'),
						"description" => esc_html__("Font weight of the text", 'trx_addons'),
						"class" => "",
						"value" => array(
							esc_html__('Default', 'trx_addons') => 'inherit',
							esc_html__('Thin (100)', 'trx_addons') => '100',
							esc_html__('Light (300)', 'trx_addons') => '300',
							esc_html__('Normal (400)', 'trx_addons') => '400',
							esc_html__('Bold (600)', 'trx_addons') => '600'
						),
						"type" => "dropdown"
					),
/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", 'trx_addons'),
						"description" => esc_html__("Content for section container", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
*/
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Section extends THEMEREX_VC_ShortCodeCollection {}






			// Services block
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_services_block",
				"name" => esc_html__("Services block", 'trx_addons'),
				"description" => esc_html__("Insert services block with title, icon and description", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => '',
				"class" => "trx_sc_single trx_sc_services_block",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => esc_html__("Block title", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Count", 'trx_addons'),
						"description" => esc_html__("Count title", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", 'trx_addons'),
						"description" => esc_html__("URL for link from button (at bottom of the block)", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_text",
						"heading" => esc_html__("Link text", 'trx_addons'),
						"description" => esc_html__("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", 'trx_addons'),
						"description" => esc_html__("Select icon from Fontello icons set (placed before/instead price)", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Align services to left or right side", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Description", 'trx_addons'),
						"description" => esc_html__("Description for this services block", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );

			class WPBakeryShortCode_Trx_ServicesBlock extends THEMEREX_VC_ShortCodeSingle {}

			
			
			// Skills
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_skills",
				"name" => esc_html__("Skills", 'trx_addons'),
				"description" => esc_html__("Insert skills diagramm", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_skills',
				"class" => "trx_sc_collection trx_sc_skills",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_skills_item'),
				"params" => array(
					array(
						"param_name" => "max_value",
						"heading" => esc_html__("Max value", 'trx_addons'),
						"description" => esc_html__("Max value for skills items", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "100",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Skills type", 'trx_addons'),
						"description" => esc_html__("Select type of skills block", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Bar', 'trx_addons') => 'bar',
							esc_html__('Bar 2', 'trx_addons') => 'bar2',
							esc_html__('Pie chart', 'trx_addons') => 'pie',
							esc_html__('Pie chart 2', 'trx_addons') => 'pie_2',
							esc_html__('Counter', 'trx_addons') => 'counter',
							esc_html__('Arc', 'trx_addons') => 'arc'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "layout",
						"heading" => esc_html__("Skills layout", 'trx_addons'),
						"description" => esc_html__("Select layout of skills block", 'trx_addons'),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter','bar','pie','pie_2')
						),
						"class" => "",
						"value" => array(
							esc_html__('Rows', 'trx_addons') => 'rows',
							esc_html__('Columns', 'trx_addons') => 'columns'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Direction", 'trx_addons'),
						"description" => esc_html__("Select direction of skills block", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Counters style", 'trx_addons'),
						"description" => esc_html__("Select style of skills items (only for type=counter)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => '1',
							esc_html__('Style 2', 'trx_addons') => '2',
							esc_html__('Style 3', 'trx_addons') => '3',
							esc_html__('Style 4', 'trx_addons') => '4'
						),
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns count", 'trx_addons'),
						"description" => esc_html__("Skills columns count (required)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", 'trx_addons'),
						"description" => esc_html__("Color for all skills items", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'trx_addons'),
						"description" => esc_html__("Background color for all skills items (only for type=pie)", 'trx_addons'),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => esc_html__("Border color", 'trx_addons'),
						"description" => esc_html__("Border color for all skills items (only for type=pie)", 'trx_addons'),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => esc_html__("Title of the skills block", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'trx_addons'),
						"description" => esc_html__("Default subtitle of the skills block (only if type=arc)", 'trx_addons'),
						'dependency' => array(
							'element' => 'type',
							'value' => array('arc')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Align skills block to left or right side", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_skills_item",
				"name" => esc_html__("Skill", 'trx_addons'),
				"description" => esc_html__("Skills item", 'trx_addons'),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_skills_item",
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_skills'),
				"as_parent" => array('except' => 'trx_skills'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => esc_html__("Title for the current skills item", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => esc_html__("Value", 'trx_addons'),
						"description" => esc_html__("Value for the current skills item", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "50",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", 'trx_addons'),
						"description" => esc_html__("Color for current skills item", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'trx_addons'),
						"description" => esc_html__("Background color for current skills item (only for type=pie)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => esc_html__("Border color", 'trx_addons'),
						"description" => esc_html__("Border color for current skills item (only for type=pie)", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Item style", 'trx_addons'),
						"description" => esc_html__("Select style for the current skills item (only for type=counter)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => '1',
							esc_html__('Style 2', 'trx_addons') => '2',
							esc_html__('Style 3', 'trx_addons') => '3',
							esc_html__('Style 4', 'trx_addons') => '4'
						),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Skills extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Skills_Item extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Slider
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_slider",
				"name" => esc_html__("Slider", 'trx_addons'),
				"description" => esc_html__("Insert slider", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_slider',
				"class" => "trx_sc_collection trx_sc_slider",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_slider_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "engine",
						"heading" => esc_html__("Engine", 'trx_addons'),
						"description" => esc_html__("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sliders']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Float slider", 'trx_addons'),
						"description" => esc_html__("Float slider to left or right side", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom slides", 'trx_addons'),
						"description" => esc_html__("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Custom slides', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					)
					),
					themerex_exists_revslider() || themerex_exists_royalslider() ? array(
					array(
						"param_name" => "alias",
						"heading" => esc_html__("Revolution slider alias or Royal Slider ID", 'trx_addons'),
						"description" => esc_html__("Alias for Revolution slider or Royal slider ID", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'engine',
							'value' => array('revo','royal')
						),
						"value" => "",
						"type" => "textfield"
					)) : array(), array(
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories list", 'trx_addons'),
						"description" => esc_html__("Select category. If empty - show posts from any category or from IDs list", 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip(themerex_array_merge(array(0 => esc_html__('- Select category -', 'trx_addons')), $THEMEREX_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Swiper: Number of posts", 'trx_addons'),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Swiper: Offset before select posts", 'trx_addons'),
						"description" => esc_html__("Skip posts before select next part.", 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Swiper: Post sorting", 'trx_addons'),
						"description" => esc_html__("Select desired posts sorting method", 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Swiper: Post order", 'trx_addons'),
						"description" => esc_html__("Select desired posts order", 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Swiper: Post IDs list", 'trx_addons'),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Swiper: Show slider controls", 'trx_addons'),
						"description" => esc_html__("Show arrows inside slider", 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(esc_html__('Show controls', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pagination",
						"heading" => esc_html__("Swiper: Show slider pagination", 'trx_addons'),
						"description" => esc_html__("Show bullets or titles to switch slides", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								esc_html__('Dots', 'trx_addons') => 'yes',
								esc_html__('Side Titles', 'trx_addons') => 'full',
								esc_html__('Over Titles', 'trx_addons') => 'over',
								esc_html__('None', 'trx_addons') => 'no'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "titles",
						"heading" => esc_html__("Swiper: Show titles section", 'trx_addons'),
						"description" => esc_html__("Show section with post's title and short post's description", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								esc_html__('Not show', 'trx_addons') => "no",
								esc_html__('Show/Hide info', 'trx_addons') => "slide",
								esc_html__('Fixed info', 'trx_addons') => "fixed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "descriptions",
						"heading" => esc_html__("Swiper: Post descriptions", 'trx_addons'),
						"description" => esc_html__("Show post's excerpt max length (characters)", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => esc_html__("Swiper: Post's title as link", 'trx_addons'),
						"description" => esc_html__("Make links from post's titles", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(esc_html__('Titles as a links', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "crop",
						"heading" => esc_html__("Swiper: Crop images", 'trx_addons'),
						"description" => esc_html__("Crop images in each slide or live it unchanged", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(esc_html__('Crop images', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Swiper: Autoheight", 'trx_addons'),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(esc_html__('Autoheight', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Swiper: Slides change interval", 'trx_addons'),
						"description" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", 'trx_addons'),
						"group" => esc_html__('Details', 'trx_addons'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "5000",
						"type" => "textfield"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_slider_item",
				"name" => esc_html__("Slide", 'trx_addons'),
				"description" => esc_html__("Slider item - single slide", 'trx_addons'),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_slider_item',
				"as_child" => array('only' => 'trx_slider'),
				"as_parent" => array('except' => 'trx_slider'),
				"params" => array(
					array(
						"param_name" => "src",
						"heading" => esc_html__("URL (source) for image file", 'trx_addons'),
						"description" => esc_html__("Select or upload image or write URL from other site for the current slide", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Slider extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Slider_Item extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Socials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_socials",
				"name" => esc_html__("Social icons", 'trx_addons'),
				"description" => esc_html__("Custom social icons", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_socials',
				"class" => "trx_sc_collection trx_sc_socials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_social_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "size",
						"heading" => esc_html__("Icon's size", 'trx_addons'),
						"description" => esc_html__("Size of the icons", 'trx_addons'),
						"class" => "",
						"value" => array(
							esc_html__('Tiny', 'trx_addons') => 'tiny',
							esc_html__('Small', 'trx_addons') => 'small',
							esc_html__('Large', 'trx_addons') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "socials",
						"heading" => esc_html__("Manual socials list", 'trx_addons'),
						"description" => esc_html__("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom socials", 'trx_addons'),
						"description" => esc_html__("Make custom icons from inner shortcodes (prepare it on tabs)", 'trx_addons'),
						"class" => "",
						"value" => array(esc_html__('Custom socials', 'trx_addons') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Socials style", 'trx_addons'),
						"description" => esc_html__("Socials style", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Main', 'trx_addons') => 'main',
							esc_html__('Color', 'trx_addons') => 'color'
						),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_social_item",
				"name" => esc_html__("Custom social item", 'trx_addons'),
				"description" => esc_html__("Custom social item: name, profile url and icon url", 'trx_addons'),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_social_item',
				"as_child" => array('only' => 'trx_socials'),
				"as_parent" => array('except' => 'trx_socials'),
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("Your profile URL", 'trx_addons'),
						"description" => esc_html__("URL of your profile in specified social network", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", 'trx_addons'),
						"description" => esc_html__("Select font icon from Fontello icons set (if style=iconed)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Socials extends THEMEREX_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Social_Item extends THEMEREX_VC_ShortCodeSingle {}
			

			
			
			
			
			
			// Table
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_table",
				"name" => esc_html__("Table", 'trx_addons'),
				"description" => esc_html__("Insert a table", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_table',
				"class" => "trx_sc_container trx_sc_table",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => esc_html__("Cells content alignment", 'trx_addons'),
						"description" => esc_html__("Select alignment for each table cell", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Table content", 'trx_addons'),
						"description" => esc_html__("Content, created with any table-generator", 'trx_addons'),
						"class" => "",
						"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
						"type" => "textarea_html"
					),
					themerex_vc_width(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Table extends THEMEREX_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Tabs
			//-------------------------------------------------------------------------------------
			
			$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
			$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
			vc_map( array(
				"base" => "trx_tabs",
				"name" => esc_html__("Tabs", 'trx_addons'),
				"description" => esc_html__("Tabs", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_tabs',
				"class" => "trx_sc_collection trx_sc_tabs",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_tab'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Tabs style", 'trx_addons'),
						"description" => esc_html__("Select style of tabs items", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => '1',
							esc_html__('Style 2', 'trx_addons') => '2'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "initial",
						"heading" => esc_html__("Initially opened tab", 'trx_addons'),
						"description" => esc_html__("Number of initially opened tab", 'trx_addons'),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Scroller", 'trx_addons'),
						"description" => esc_html__("Use scroller to show tab content (height parameter required)", 'trx_addons'),
						"class" => "",
						"value" => array("Use scroller" => "yes" ),
						"type" => "checkbox"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_tab title="' . esc_html__( 'Tab 1', 'trx_addons' ) . '" tab_id="'.esc_attr($tab_id_1).'"][/trx_tab]
					[trx_tab title="' . esc_html__( 'Tab 2', 'trx_addons' ) . '" tab_id="'.esc_attr($tab_id_2).'"][/trx_tab]
				',
				"custom_markup" => '
					<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
						<ul class="tabs_controls">
						</ul>
						%content%
					</div>
				',
				'js_view' => 'VcTrxTabsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_tab",
				"name" => esc_html__("Tab item", 'trx_addons'),
				"description" => esc_html__("Single tab item", 'trx_addons'),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_tab",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_tab',
				"as_child" => array('only' => 'trx_tabs'),
				"as_parent" => array('except' => 'trx_tabs'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Tab title", 'trx_addons'),
						"description" => esc_html__("Title for current tab", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "tab_id",
						"heading" => esc_html__("Tab ID", 'trx_addons'),
						"description" => esc_html__("ID for current tab (required). Please, start it from letter.", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxTabView'
			) );
			class WPBakeryShortCode_Trx_Tabs extends THEMEREX_VC_ShortCodeTabs {}
			class WPBakeryShortCode_Trx_Tab extends THEMEREX_VC_ShortCodeTab {}
			
			
			
			
			// Team
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_team",
				"name" => esc_html__("Team", 'trx_addons'),
				"description" => esc_html__("Insert team members", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_team',
				"class" => "trx_sc_columns trx_sc_team",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_team_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Team style", 'trx_addons'),
						"description" => esc_html__("Select style to display team members", 'trx_addons'),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => 1,
							esc_html__('Style 2', 'trx_addons') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'trx_addons'),
						"description" => esc_html__("How many columns use to show team members", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'trx_addons'),
						"description" => esc_html__("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'trx_addons'),
						"class" => "",
						"value" => array("Custom members" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'trx_addons'),
						"description" => esc_html__("Put here comma separated categories (ids or slugs) to show team members. If empty - select team members from any category (group) or from IDs list", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'trx_addons'),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'trx_addons'),
						"description" => esc_html__("Skip posts before select next part.", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'trx_addons'),
						"description" => esc_html__("Select desired posts sorting method", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'trx_addons'),
						"description" => esc_html__("Select desired posts order", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Team member's IDs list", 'trx_addons'),
						"description" => esc_html__("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_team_item user="' . esc_html__( 'Member 1', 'trx_addons' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 2', 'trx_addons' ) . '"][/trx_team_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_team_item",
				"name" => esc_html__("Team member", 'trx_addons'),
				"description" => esc_html__("Team member - all data pull out from it account on your site", 'trx_addons'),
				"show_settings_on_create" => true,
				"class" => "trx_sc_item trx_sc_column_item trx_sc_team_item",
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_team_item',
				"as_child" => array('only' => 'trx_team'),
				"as_parent" => array('except' => 'trx_team'),
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => esc_html__("Registered user", 'trx_addons'),
						"description" => esc_html__("Select one of registered users (if present) or put name, position, etc. in fields below", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['users']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "member",
						"heading" => esc_html__("Team member", 'trx_addons'),
						"description" => esc_html__("Select one of team members (if present) or put name, position, etc. in fields below", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['members']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'trx_addons'),
						"description" => esc_html__("Link on team member's personal page", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", 'trx_addons'),
						"description" => esc_html__("Team member's name", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Position", 'trx_addons'),
						"description" => esc_html__("Team member's position", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", 'trx_addons'),
						"description" => esc_html__("Team member's e-mail", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Member's Photo", 'trx_addons'),
						"description" => esc_html__("Team member's photo (avatar", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "socials",
						"heading" => esc_html__("Socials", 'trx_addons'),
						"description" => esc_html__("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Team extends THEMEREX_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Team_Item extends THEMEREX_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Testimonials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_testimonials",
				"name" => esc_html__("Testimonials", 'trx_addons'),
				"description" => esc_html__("Insert testimonials slider", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_collection trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Testimonials style", 'trx_addons'),
						"description" => esc_html__("Select style to display testimonials members", 'trx_addons'),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => 1,
							esc_html__('Style 2', 'trx_addons') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'trx_addons'),
						"description" => esc_html__("How many columns use to show team members (for Style 2)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Show arrows", 'trx_addons'),
						"description" => esc_html__("Show control buttons", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Testimonials change interval", 'trx_addons'),
						"description" => esc_html__("Testimonials change interval (in milliseconds: 1000ms = 1s)", 'trx_addons'),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Alignment of the testimonials block", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'trx_addons'),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", 'trx_addons'),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'trx_addons'),
						"description" => esc_html__("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'trx_addons'),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'trx_addons'),
						"description" => esc_html__("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'trx_addons'),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'trx_addons'),
						"description" => esc_html__("Skip posts before select next part.", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'trx_addons'),
						"description" => esc_html__("Select desired posts sorting method", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'trx_addons'),
						"description" => esc_html__("Select desired posts order", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Post IDs list", 'trx_addons'),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", 'trx_addons'),
						"group" => esc_html__('Query', 'trx_addons'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => esc_html__("Background tint", 'trx_addons'),
						"description" => esc_html__("Main background tint: dark or light", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'trx_addons'),
						"description" => esc_html__("Any background color for this section", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", 'trx_addons'),
						"description" => esc_html__("Select background image from library for this section", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", 'trx_addons'),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", 'trx_addons'),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => esc_html__("Testimonial", 'trx_addons'),
				"description" => esc_html__("Single testimonials item", 'trx_addons'),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", 'trx_addons'),
						"description" => esc_html__("Name of the testimonmials author", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'trx_addons'),
						"description" => esc_html__("Link URL to the testimonmials author page", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", 'trx_addons'),
						"description" => esc_html__("E-mail of the testimonmials author", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Photo", 'trx_addons'),
						"description" => esc_html__("Select or upload photo of testimonmials author or write URL of photo from other site", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "field",
						"heading" => esc_html__("Field", 'trx_addons'),
						"description" => esc_html__("Additional field (for Style 2)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Testimonials text", 'trx_addons'),
						"description" => esc_html__("Current testimonials text", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Testimonials extends THEMEREX_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Testimonials_Item extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Title
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_title",
				"name" => esc_html__("Title", 'trx_addons'),
				"description" => esc_html__("Create header tag (1-6 level) with many styles", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_title',
				"class" => "trx_sc_single trx_sc_title",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Title content", 'trx_addons'),
						"description" => esc_html__("Title content", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Title type", 'trx_addons'),
						"description" => esc_html__("Title type (header level)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Header 1', 'trx_addons') => '1',
							esc_html__('Header 2', 'trx_addons') => '2',
							esc_html__('Header 3', 'trx_addons') => '3',
							esc_html__('Header 4', 'trx_addons') => '4',
							esc_html__('Header 5', 'trx_addons') => '5',
							esc_html__('Header 6', 'trx_addons') => '6'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Title style", 'trx_addons'),
						"description" => esc_html__("Title style: only text (regular) or with icon/image (iconed)", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Regular', 'trx_addons') => 'regular',
							esc_html__('Underline', 'trx_addons') => 'underline',
							esc_html__('Divider', 'trx_addons') => 'divider',
							esc_html__('With icon (image)', 'trx_addons') => 'iconed'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Title text alignment", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", 'trx_addons'),
						"description" => esc_html__("Custom font size. If empty - use theme default", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", 'trx_addons'),
						"description" => esc_html__("Custom font weight. If empty or inherit - use theme default", 'trx_addons'),
						"class" => "",
						"value" => array(
							esc_html__('Default', 'trx_addons') => 'inherit',
							esc_html__('Thin (100)', 'trx_addons') => '100',
							esc_html__('Light (300)', 'trx_addons') => '300',
							esc_html__('Normal (400)', 'trx_addons') => '400',
							esc_html__('Semibold (600)', 'trx_addons') => '600',
							esc_html__('Bold (700)', 'trx_addons') => '700',
							esc_html__('Black (900)', 'trx_addons') => '900'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Title color", 'trx_addons'),
						"description" => esc_html__("Select color for the title", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Title font icon", 'trx_addons'),
						"description" => esc_html__("Select font icon for the title from Fontello icons set (if style=iconed)", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Icon &amp; Image', 'trx_addons'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("or image icon", 'trx_addons'),
						"description" => esc_html__("Select image icon for the title instead icon above (if style=iconed)", 'trx_addons'),
						"class" => "",
						"group" => esc_html__('Icon &amp; Image', 'trx_addons'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $THEMEREX_GLOBALS['sc_params']['images'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "picture",
						"heading" => esc_html__("or select uploaded image", 'trx_addons'),
						"description" => esc_html__("Select or upload image or write URL from other site (if style=iconed)", 'trx_addons'),
						"group" => esc_html__('Icon &amp; Image', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_size",
						"heading" => esc_html__("Image (picture) size", 'trx_addons'),
						"description" => esc_html__("Select image (picture) size (if style=iconed)", 'trx_addons'),
						"group" => esc_html__('Icon &amp; Image', 'trx_addons'),
						"class" => "",
						"value" => array(
							esc_html__('Small', 'trx_addons') => 'small',
							esc_html__('Medium', 'trx_addons') => 'medium',
							esc_html__('Large', 'trx_addons') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Icon (image) position", 'trx_addons'),
						"description" => esc_html__("Select icon (image) position (if style=iconed)", 'trx_addons'),
						"group" => esc_html__('Icon &amp; Image', 'trx_addons'),
						"class" => "",
						"value" => array(
							esc_html__('Top', 'trx_addons') => 'top',
							esc_html__('Left', 'trx_addons') => 'left'
						),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Title extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Toggles
			//-------------------------------------------------------------------------------------
				
			vc_map( array(
				"base" => "trx_toggles",
				"name" => esc_html__("Toggles", 'trx_addons'),
				"description" => esc_html__("Toggles items", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_toggles',
				"class" => "trx_sc_collection trx_sc_toggles",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_toggles_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Toggles style", 'trx_addons'),
						"description" => esc_html__("Select style for display toggles", 'trx_addons'),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Style 1', 'trx_addons') => 1,
							esc_html__('Style 2', 'trx_addons') => 2
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", 'trx_addons'),
						"description" => esc_html__("Select icon for the closed toggles item from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", 'trx_addons'),
						"description" => esc_html__("Select icon for the opened toggles item from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class']
				),
				'default_content' => '
					[trx_toggles_item title="' . esc_html__( 'Item 1 title', 'trx_addons' ) . '"][/trx_toggles_item]
					[trx_toggles_item title="' . esc_html__( 'Item 2 title', 'trx_addons' ) . '"][/trx_toggles_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.esc_html__("Add item", 'trx_addons').'">'.esc_html__("Add item", 'trx_addons').'</button>
					</div>
				',
				'js_view' => 'VcTrxTogglesView'
			) );
			
			
			vc_map( array(
				"base" => "trx_toggles_item",
				"name" => esc_html__("Toggles item", 'trx_addons'),
				"description" => esc_html__("Single toggles item", 'trx_addons'),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_toggles_item',
				"as_child" => array('only' => 'trx_toggles'),
				"as_parent" => array('except' => 'trx_toggles'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'trx_addons'),
						"description" => esc_html__("Title for current toggles item", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => esc_html__("Open on show", 'trx_addons'),
						"description" => esc_html__("Open current toggle item on show", 'trx_addons'),
						"class" => "",
						"value" => array("Opened" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", 'trx_addons'),
						"description" => esc_html__("Select icon for the closed toggles item from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", 'trx_addons'),
						"description" => esc_html__("Select icon for the opened toggles item from Fontello icons set", 'trx_addons'),
						"class" => "",
						"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTogglesTabView'
			) );
			class WPBakeryShortCode_Trx_Toggles extends THEMEREX_VC_ShortCodeToggles {}
			class WPBakeryShortCode_Trx_Toggles_Item extends THEMEREX_VC_ShortCodeTogglesItem {}
			
			
			
			
			
			
			// Twitter
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_twitter",
				"name" => esc_html__("Twitter", 'trx_addons'),
				"description" => esc_html__("Insert twitter feed into post (page)", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_twitter',
				"class" => "trx_sc_single trx_sc_twitter",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => esc_html__("Twitter Username", 'trx_addons'),
						"description" => esc_html__("Your username in the twitter account. If empty - get it from Theme Options.", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_key",
						"heading" => esc_html__("Consumer Key", 'trx_addons'),
						"description" => esc_html__("Consumer Key from the twitter account", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_secret",
						"heading" => esc_html__("Consumer Secret", 'trx_addons'),
						"description" => esc_html__("Consumer Secret from the twitter account", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_key",
						"heading" => esc_html__("Token Key", 'trx_addons'),
						"description" => esc_html__("Token Key from the twitter account", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_secret",
						"heading" => esc_html__("Token Secret", 'trx_addons'),
						"description" => esc_html__("Token Secret from the twitter account", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Tweets number", 'trx_addons'),
						"description" => esc_html__("Number tweets to show", 'trx_addons'),
						"class" => "",
						"divider" => true,
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Show arrows", 'trx_addons'),
						"description" => esc_html__("Show control buttons", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Tweets change interval", 'trx_addons'),
						"description" => esc_html__("Tweets change interval (in milliseconds: 1000ms = 1s)", 'trx_addons'),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Alignment of the tweets block", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'trx_addons'),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", 'trx_addons'),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "bg_tint",
						"heading" => esc_html__("Background tint", 'trx_addons'),
						"description" => esc_html__("Main background tint: dark or light", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['tint']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'trx_addons'),
						"description" => esc_html__("Any background color for this section", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", 'trx_addons'),
						"description" => esc_html__("Select background image from library for this section", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", 'trx_addons'),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", 'trx_addons'),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", 'trx_addons'),
						"group" => esc_html__('Colors and Images', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				),
			) );
			
			class WPBakeryShortCode_Trx_Twitter extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Video
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_video",
				"name" => esc_html__("Video", 'trx_addons'),
				"description" => esc_html__("Insert video player", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_video',
				"class" => "trx_sc_single trx_sc_video",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("URL for video file", 'trx_addons'),
						"description" => esc_html__("Paste URL for video file", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ratio",
						"heading" => esc_html__("Ratio", 'trx_addons'),
						"description" => esc_html__("Select ratio for display video", 'trx_addons'),
						"class" => "",
						"value" => array(
							esc_html__('16:9', 'trx_addons') => "16:9",
							esc_html__('4:3', 'trx_addons') => "4:3"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoplay",
						"heading" => esc_html__("Autoplay video", 'trx_addons'),
						"description" => esc_html__("Autoplay video on page load", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Select block alignment", 'trx_addons'),
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Cover image", 'trx_addons'),
						"description" => esc_html__("Select or upload image or write URL from other site for video preview", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", 'trx_addons'),
						"description" => esc_html__("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'trx_addons'),
						"group" => esc_html__('Background', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => esc_html__("Top offset", 'trx_addons'),
						"description" => esc_html__("Top offset (padding) from background image to video block (in percent). For example: 3%", 'trx_addons'),
						"group" => esc_html__('Background', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => esc_html__("Bottom offset", 'trx_addons'),
						"description" => esc_html__("Bottom offset (padding) from background image to video block (in percent). For example: 3%", 'trx_addons'),
						"group" => esc_html__('Background', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => esc_html__("Left offset", 'trx_addons'),
						"description" => esc_html__("Left offset (padding) from background image to video block (in percent). For example: 20%", 'trx_addons'),
						"group" => esc_html__('Background', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => esc_html__("Right offset", 'trx_addons'),
						"description" => esc_html__("Right offset (padding) from background image to video block (in percent). For example: 12%", 'trx_addons'),
						"group" => esc_html__('Background', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Video extends THEMEREX_VC_ShortCodeSingle {}








			// Sidebar
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_sidebar",
				"name" => esc_html__("Sidebar", 'trx_addons'),
				"description" => esc_html__("Insert Sidebar", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_sidebar',
				"class" => "trx_sc_single trx_sc_sidebar",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => esc_html__("Select sidebar", 'trx_addons'),
						"description" => esc_html__("Select sidebar", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['sidebar']),
						"type" => "dropdown"
					),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right']
				),
			) );

			class WPBakeryShortCode_Trx_Sidebar extends THEMEREX_VC_ShortCodeSingle {}








			// Zoom
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_zoom",
				"name" => esc_html__("Zoom", 'trx_addons'),
				"description" => esc_html__("Insert the image with zoom/lens effect", 'trx_addons'),
				"category" => esc_html__('Content', 'trx_addons'),
				'icon' => 'icon_trx_zoom',
				"class" => "trx_sc_single trx_sc_zoom",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "effect",
						"heading" => esc_html__("Effect", 'trx_addons'),
						"description" => esc_html__("Select effect to display overlapping image", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Lens', 'trx_addons') => 'lens',
							esc_html__('Zoom', 'trx_addons') => 'zoom'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("Main image", 'trx_addons'),
						"description" => esc_html__("Select or upload main image", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "over",
						"heading" => esc_html__("Overlaping image", 'trx_addons'),
						"description" => esc_html__("Select or upload overlaping image", 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'trx_addons'),
						"description" => esc_html__("Float zoom to left or right side", 'trx_addons'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($THEMEREX_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", 'trx_addons'),
						"description" => esc_html__("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'trx_addons'),
						"group" => esc_html__('Background', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => esc_html__("Top offset", 'trx_addons'),
						"description" => esc_html__("Top offset (padding) from background image to zoom block (in percent). For example: 3%", 'trx_addons'),
						"group" => esc_html__('Background', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => esc_html__("Bottom offset", 'trx_addons'),
						"description" => esc_html__("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", 'trx_addons'),
						"group" => esc_html__('Background', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => esc_html__("Left offset", 'trx_addons'),
						"description" => esc_html__("Left offset (padding) from background image to zoom block (in percent). For example: 20%", 'trx_addons'),
						"group" => esc_html__('Background', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => esc_html__("Right offset", 'trx_addons'),
						"description" => esc_html__("Right offset (padding) from background image to zoom block (in percent). For example: 12%", 'trx_addons'),
						"group" => esc_html__('Background', 'trx_addons'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					themerex_vc_width(),
					themerex_vc_height(),
					$THEMEREX_GLOBALS['vc_params']['margin_top'],
					$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
					$THEMEREX_GLOBALS['vc_params']['margin_left'],
					$THEMEREX_GLOBALS['vc_params']['margin_right'],
					$THEMEREX_GLOBALS['vc_params']['id'],
					$THEMEREX_GLOBALS['vc_params']['class'],
					$THEMEREX_GLOBALS['vc_params']['animation'],
					$THEMEREX_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Zoom extends THEMEREX_VC_ShortCodeSingle {}
			

			do_action('themerex_action_shortcodes_list_vc');
			
			
			if (themerex_exists_woocommerce()) {
			
				// WooCommerce - Cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_cart",
					"name" => esc_html__("Cart", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show cart page", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_wooc_cart',
					"class" => "trx_sc_alone trx_sc_woocommerce_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Cart extends THEMEREX_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Checkout
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_checkout",
					"name" => esc_html__("Checkout", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show checkout page", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_wooc_checkout',
					"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Checkout extends THEMEREX_VC_ShortCodeAlone {}
			
			
				// WooCommerce - My Account
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_my_account",
					"name" => esc_html__("My Account", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show my account page", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_wooc_my_account',
					"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_My_Account extends THEMEREX_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Order Tracking
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_order_tracking",
					"name" => esc_html__("Order Tracking", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show order tracking page", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_wooc_order_tracking',
					"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Woocommerce_Order_Tracking extends THEMEREX_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Shop Messages
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "shop_messages",
					"name" => esc_html__("Shop Messages", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show shop messages", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_wooc_shop_messages',
					"class" => "trx_sc_alone trx_sc_shop_messages",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array()
				) );
				
				class WPBakeryShortCode_Shop_Messages extends THEMEREX_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Product Page
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_page",
					"name" => esc_html__("Product Page", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: display single product page", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_product_page',
					"class" => "trx_sc_single trx_sc_product_page",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => esc_html__("SKU", 'trx_addons'),
							"description" => esc_html__("SKU code of displayed product", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => esc_html__("ID", 'trx_addons'),
							"description" => esc_html__("ID of displayed product", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "posts_per_page",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => esc_html__("How many products showed", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_type",
							"heading" => esc_html__("Post type", 'trx_addons'),
							"description" => esc_html__("Post type for the WP query (leave 'product')", 'trx_addons'),
							"class" => "",
							"value" => "product",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_status",
							"heading" => esc_html__("Post status", 'trx_addons'),
							"description" => esc_html__("Display posts only with this status", 'trx_addons'),
							"class" => "",
							"value" => array(
								esc_html__('Publish', 'trx_addons') => 'publish',
								esc_html__('Protected', 'trx_addons') => 'protected',
								esc_html__('Private', 'trx_addons') => 'private',
								esc_html__('Pending', 'trx_addons') => 'pending',
								esc_html__('Draft', 'trx_addons') => 'draft'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Page extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product",
					"name" => esc_html__("Product", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: display one product", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_product',
					"class" => "trx_sc_single trx_sc_product",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => esc_html__("SKU", 'trx_addons'),
							"description" => esc_html__("Product's SKU code", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => esc_html__("ID", 'trx_addons'),
							"description" => esc_html__("Product's ID", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product extends THEMEREX_VC_ShortCodeSingle {}
			
			
				// WooCommerce - Best Selling Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "best_selling_products",
					"name" => esc_html__("Best Selling Products", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show best selling products", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_best_selling_products',
					"class" => "trx_sc_single trx_sc_best_selling_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => esc_html__("How many products showed", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", 'trx_addons'),
							"description" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Best_Selling_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Recent Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "recent_products",
					"name" => esc_html__("Recent Products", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show recent products", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_recent_products',
					"class" => "trx_sc_single trx_sc_recent_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => esc_html__("How many products showed", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", 'trx_addons'),
							"description" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'trx_addons') => 'date',
								esc_html__('Title', 'trx_addons') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Recent_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Related Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "related_products",
					"name" => esc_html__("Related Products", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show related products", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_related_products',
					"class" => "trx_sc_single trx_sc_related_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "posts_per_page",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => esc_html__("How many products showed", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", 'trx_addons'),
							"description" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'trx_addons') => 'date',
								esc_html__('Title', 'trx_addons') => 'title'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Related_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Featured Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "featured_products",
					"name" => esc_html__("Featured Products", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show featured products", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_featured_products',
					"class" => "trx_sc_single trx_sc_featured_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => esc_html__("How many products showed", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", 'trx_addons'),
							"description" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'trx_addons') => 'date',
								esc_html__('Title', 'trx_addons') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Featured_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Top Rated Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "top_rated_products",
					"name" => esc_html__("Top Rated Products", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show top rated products", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_top_rated_products',
					"class" => "trx_sc_single trx_sc_top_rated_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => esc_html__("How many products showed", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", 'trx_addons'),
							"description" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'trx_addons') => 'date',
								esc_html__('Title', 'trx_addons') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Top_Rated_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Sale Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "sale_products",
					"name" => esc_html__("Sale Products", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: list products on sale", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_sale_products',
					"class" => "trx_sc_single trx_sc_sale_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => esc_html__("How many products showed", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", 'trx_addons'),
							"description" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'trx_addons') => 'date',
								esc_html__('Title', 'trx_addons') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Sale_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product Category
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_category",
					"name" => esc_html__("Products from category", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: list products in specified category(-ies)", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_product_category',
					"class" => "trx_sc_single trx_sc_product_category",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => esc_html__("How many products showed", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", 'trx_addons'),
							"description" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'trx_addons') => 'date',
								esc_html__('Title', 'trx_addons') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "category",
							"heading" => esc_html__("Categories", 'trx_addons'),
							"description" => esc_html__("Comma separated category slugs", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "operator",
							"heading" => esc_html__("Operator", 'trx_addons'),
							"description" => esc_html__("Categories operator", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('IN', 'trx_addons') => 'IN',
								esc_html__('NOT IN', 'trx_addons') => 'NOT IN',
								esc_html__('AND', 'trx_addons') => 'AND'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Category extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "products",
					"name" => esc_html__("Products", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: list all products", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_products',
					"class" => "trx_sc_single trx_sc_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "skus",
							"heading" => esc_html__("SKUs", 'trx_addons'),
							"description" => esc_html__("Comma separated SKU codes of products", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => esc_html__("IDs", 'trx_addons'),
							"description" => esc_html__("Comma separated ID of products", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", 'trx_addons'),
							"description" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'trx_addons') => 'date',
								esc_html__('Title', 'trx_addons') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Products extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
			
				// WooCommerce - Product Attribute
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_attribute",
					"name" => esc_html__("Products by Attribute", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show products with specified attribute", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_product_attribute',
					"class" => "trx_sc_single trx_sc_product_attribute",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => esc_html__("How many products showed", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", 'trx_addons'),
							"description" => esc_html__("How many columns per row use for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'trx_addons') => 'date',
								esc_html__('Title', 'trx_addons') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "attribute",
							"heading" => esc_html__("Attribute", 'trx_addons'),
							"description" => esc_html__("Attribute name", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "filter",
							"heading" => esc_html__("Filter", 'trx_addons'),
							"description" => esc_html__("Attribute value", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Attribute extends THEMEREX_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products Categories
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_categories",
					"name" => esc_html__("Product Categories", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: show categories with products", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_product_categories',
					"class" => "trx_sc_single trx_sc_product_categories",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "number",
							"heading" => esc_html__("Number", 'trx_addons'),
							"description" => esc_html__("How many categories showed", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", 'trx_addons'),
							"description" => esc_html__("How many columns per row use for categories output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								esc_html__('Date', 'trx_addons') => 'date',
								esc_html__('Title', 'trx_addons') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", 'trx_addons'),
							"description" => esc_html__("Sorting order for products output", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($THEMEREX_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "parent",
							"heading" => esc_html__("Parent", 'trx_addons'),
							"description" => esc_html__("Parent category slug", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "date",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => esc_html__("IDs", 'trx_addons'),
							"description" => esc_html__("Comma separated ID of products", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "hide_empty",
							"heading" => esc_html__("Hide empty", 'trx_addons'),
							"description" => esc_html__("Hide empty categories", 'trx_addons'),
							"class" => "",
							"value" => array("Hide empty" => "1" ),
							"type" => "checkbox"
						)
					)
				) );
				
				class WPBakeryShortCode_Products_Categories extends THEMEREX_VC_ShortCodeSingle {}
			
				/*
			
				// WooCommerce - Add to cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "add_to_cart",
					"name" => esc_html__("Add to cart", 'trx_addons'),
					"description" => esc_html__("WooCommerce shortcode: Display a single product price + cart button", 'trx_addons'),
					"category" => esc_html__('WooCommerce', 'trx_addons'),
					'icon' => 'icon_trx_add_to_cart',
					"class" => "trx_sc_single trx_sc_add_to_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "id",
							"heading" => esc_html__("ID", 'trx_addons'),
							"description" => esc_html__("Product's ID", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "sku",
							"heading" => esc_html__("SKU", 'trx_addons'),
							"description" => esc_html__("Product's SKU code", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "quantity",
							"heading" => esc_html__("Quantity", 'trx_addons'),
							"description" => esc_html__("How many item add", 'trx_addons'),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "show_price",
							"heading" => esc_html__("Show price", 'trx_addons'),
							"description" => esc_html__("Show price near button", 'trx_addons'),
							"class" => "",
							"value" => array("Show price" => "true" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "class",
							"heading" => esc_html__("Class", 'trx_addons'),
							"description" => esc_html__("CSS class", 'trx_addons'),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "style",
							"heading" => esc_html__("CSS style", 'trx_addons'),
							"description" => esc_html__("CSS style for additional decoration", 'trx_addons'),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Add_To_Cart extends THEMEREX_VC_ShortCodeSingle {}
				*/
			}

		}
	}
}
?>