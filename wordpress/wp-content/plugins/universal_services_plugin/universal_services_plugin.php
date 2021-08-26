<?php
/*
  Plugin Name: Universal Services Plugin
  Description: Create additional options in the theme.
  Version: 2.0
  Author: themerex
  Author URI: http://themerex.net
 */

// Universal Services Plugin


// Plugin's storage
if (!defined('TRX_ADDONS_PLUGIN_DIR'))	define('TRX_ADDONS_PLUGIN_DIR', plugin_dir_path(__FILE__));
if (!defined('TRX_ADDONS_PLUGIN_URL'))	define('TRX_ADDONS_PLUGIN_URL', plugin_dir_url(__FILE__));
if (!defined('TRX_ADDONS_PLUGIN_BASE'))	define('TRX_ADDONS_PLUGIN_BASE',dirname(plugin_basename(__FILE__)));

global $TRX_ADDONS_STORAGE;
$TRX_ADDONS_STORAGE = array(
    // Plugin's location and name
    'plugin_dir' => plugin_dir_path(__FILE__),
    'plugin_url' => plugin_dir_url(__FILE__),
    'plugin_base'=> explode('/', plugin_basename(__FILE__)),
    'plugin_active' => false,
    // Custom post types and taxonomies
    'register_taxonomies' => array(),
    'register_post_types' => array()
);


// Plugin init
if (!function_exists('themerex_universal_services_plugin')) {
    add_action( 'themerex_action_before_init_theme', 'themerex_universal_services_plugin', 10 );
    function themerex_universal_services_plugin() {
        return;
    }
}


/* Theme required types and taxes
------------------------------------------------------------------------------------- */

// Register theme required types and taxes
if (!function_exists('themerex_require_data')) {
    function themerex_require_data( $type, $name, $args) {
        $fn = join('_', array('register', $type));
        if ($type == 'taxonomy')
            @$fn($name, $args['post_type'], $args);
        else
            @$fn($name, $args);
    }
}


// Team
// Theme init
if (!function_exists('themerex_team_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_team_theme_setup' );
	function themerex_team_theme_setup() {

		// Add item in the admin menu
		add_action('admin_menu',							'themerex_team_add_meta_box');

		// Save data from meta box
		add_action('save_post',								'themerex_team_save_data');

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('themerex_filter_get_blog_type',			'themerex_team_get_blog_type', 9, 2);
		add_filter('themerex_filter_get_blog_title',		'themerex_team_get_blog_title', 9, 2);
		add_filter('themerex_filter_get_current_taxonomy',	'themerex_team_get_current_taxonomy', 9, 2);
		add_filter('themerex_filter_is_taxonomy',			'themerex_team_is_taxonomy', 9, 2);
		add_filter('themerex_filter_get_stream_page_title',	'themerex_team_get_stream_page_title', 9, 2);
		add_filter('themerex_filter_get_stream_page_link',	'themerex_team_get_stream_page_link', 9, 2);
		add_filter('themerex_filter_get_stream_page_id',	'themerex_team_get_stream_page_id', 9, 2);
		add_filter('themerex_filter_query_add_filters',		'themerex_team_query_add_filters', 9, 2);
		add_filter('themerex_filter_detect_inheritance_key','themerex_team_detect_inheritance_key', 9, 1);

		// Extra column for team members lists
		if (themerex_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-team_columns',			'themerex_post_add_options_column', 9);
			add_filter('manage_team_posts_custom_column',	'themerex_post_fill_options_column', 9, 2);
		}

		// Meta box fields
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS['team_meta_box'] = array(
			'id' => 'team-meta-box',
			'title' => __('Team Member Details', 'trx_addons'),
			'page' => 'team',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"team_member_position" => array(
					"title" => __('Position',  'trx_addons'),
					"desc" => __("Position of the team member", 'trx_addons'),
					"class" => "team_member_position",
					"std" => "",
					"type" => "text"),
				"team_member_email" => array(
					"title" => __("E-mail",  'trx_addons'),
					"desc" => __("E-mail of the team member - need to take Gravatar (if registered)", 'trx_addons'),
					"class" => "team_member_email",
					"std" => "",
					"type" => "text"),
				"team_member_link" => array(
					"title" => __('Link to profile',  'trx_addons'),
					"desc" => __("URL of the team member profile page (if not this page)", 'trx_addons'),
					"class" => "team_member_link",
					"std" => "",
					"type" => "text"),
				"team_member_socials" => array(
					"title" => __("Social links",  'trx_addons'),
					"desc" => __("Links to the social profiles of the team member", 'trx_addons'),
					"class" => "team_member_email",
					"std" => "",
					"type" => "social")
			)
		);

		// Prepare type "Team"
		themerex_require_data( 'post_type', 'team', array(
				'label'               => __( 'Team member', 'trx_addons' ),
				'description'         => __( 'Team Description', 'trx_addons' ),
				'labels'              => array(
					'name'                => _x( 'Team', 'Post Type General Name', 'trx_addons' ),
					'singular_name'       => _x( 'Team member', 'Post Type Singular Name', 'trx_addons' ),
					'menu_name'           => __( 'Team', 'trx_addons' ),
					'parent_item_colon'   => __( 'Parent Item:', 'trx_addons' ),
					'all_items'           => __( 'All Team', 'trx_addons' ),
					'view_item'           => __( 'View Item', 'trx_addons' ),
					'add_new_item'        => __( 'Add New Team member', 'trx_addons' ),
					'add_new'             => __( 'Add New', 'trx_addons' ),
					'edit_item'           => __( 'Edit Item', 'trx_addons' ),
					'update_item'         => __( 'Update Item', 'trx_addons' ),
					'search_items'        => __( 'Search Item', 'trx_addons' ),
					'not_found'           => __( 'Not found', 'trx_addons' ),
					'not_found_in_trash'  => __( 'Not found in Trash', 'trx_addons' ),
				),
				'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments'),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-admin-users',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 25,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'query_var'           => true,
				'capability_type'     => 'page',
				'rewrite'             => true
			)
		);

		// Prepare taxonomy for team
		themerex_require_data( 'taxonomy', 'team_group', array(
				'post_type'			=> array( 'team' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Team Group', 'taxonomy general name', 'trx_addons' ),
					'singular_name'     => _x( 'Group', 'taxonomy singular name', 'trx_addons' ),
					'search_items'      => __( 'Search Groups', 'trx_addons' ),
					'all_items'         => __( 'All Groups', 'trx_addons' ),
					'parent_item'       => __( 'Parent Group', 'trx_addons' ),
					'parent_item_colon' => __( 'Parent Group:', 'trx_addons' ),
					'edit_item'         => __( 'Edit Group', 'trx_addons' ),
					'update_item'       => __( 'Update Group', 'trx_addons' ),
					'add_new_item'      => __( 'Add New Group', 'trx_addons' ),
					'new_item_name'     => __( 'New Group Name', 'trx_addons' ),
					'menu_name'         => __( 'Team Group', 'trx_addons' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'team_group' ),
			)
		);
	}
}

if ( !function_exists( 'themerex_team_settings_theme_setup2' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_team_settings_theme_setup2', 3 );
	function themerex_team_settings_theme_setup2() {
		// Add post type 'team' and taxonomy 'team_group' into theme inheritance list
		themerex_add_theme_inheritance( array('team' => array(
				'stream_template' => 'team',
				'single_template' => 'single-team',
				'taxonomy' => array('team_group'),
				'taxonomy_tags' => array(),
				'post_type' => array('team'),
				'override' => 'post'
			) )
		);
	}
}


// Add meta box
if (!function_exists('themerex_team_add_meta_box')) {
	//add_action('admin_menu', 'themerex_team_add_meta_box');
	function themerex_team_add_meta_box() {
		global $THEMEREX_GLOBALS;
		$mb = $THEMEREX_GLOBALS['team_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'themerex_team_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('themerex_team_show_meta_box')) {
	function themerex_team_show_meta_box() {
		global $post, $THEMEREX_GLOBALS;

		// Use nonce for verification
		$data = get_post_meta($post->ID, 'team_data', true);
		$fields = $THEMEREX_GLOBALS['team_meta_box']['fields'];
		?>
		<input type="hidden" name="meta_box_team_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
		<table class="team_area">
			<?php
			foreach ($fields as $id=>$field) {
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="team_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td>
						<?php
						if ($id == 'team_member_socials') {
							$upload_info = wp_upload_dir();
							$upload_url = $upload_info['baseurl'];
							$social_list = themerex_get_theme_option('social_icons');
							foreach ($social_list as $soc) {
								$sn = basename($soc['icon']);
								$sn = themerex_substr( $sn, themerex_strpos( $sn, '-' ) + 1 );
								if (($pos=themerex_strrpos($sn, '_'))!==false)
									$sn = themerex_substr($sn, 0, $pos);
								$link = isset($meta[$sn]) ? $meta[$sn] : '';
								?>
								<label for="<?php echo esc_attr(($id).'_'.($sn)); ?>"><?php echo esc_attr(themerex_strtoproper($sn)); ?></label><br>
								<input type="text" name="<?php echo esc_attr($id); ?>[<?php echo esc_attr($sn); ?>]" id="<?php echo esc_attr(($id).'_'.($sn)); ?>" value="<?php echo esc_attr($link); ?>" size="30" /><br>
							<?php
							}
						} else {
							?>
							<input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<?php
						}
						?>
						<br><small><?php echo esc_attr($field['desc']); ?></small>
					</td>
				</tr>
			<?php
			}
			?>
		</table>
	<?php
	}
}


// Save data from meta box
if (!function_exists('themerex_team_save_data')) {
	//add_action('save_post', 'themerex_team_save_data');
	function themerex_team_save_data($post_id) {
		// verify nonce
		if (!isset($_POST['meta_box_team_nonce']) || !wp_verify_nonce($_POST['meta_box_team_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='team' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		global $THEMEREX_GLOBALS;

		$data = array();

		$fields = $THEMEREX_GLOBALS['team_meta_box']['fields'];

		// Post type specific data handling
		foreach ($fields as $id=>$field) {
			if (isset($_POST[$id])) {
				if (is_array($_POST[$id])) {
					foreach ($_POST[$id] as $sn=>$link) {
						$_POST[$id][$sn] = stripslashes($link);
					}
					$data[$id] = $_POST[$id];
				} else {
					$data[$id] = stripslashes($_POST[$id]);
				}
			}
		}

		update_post_meta($post_id, 'team_data', $data);
	}
}



// Return true, if current page is team member page
if ( !function_exists( 'themerex_is_team_page' ) ) {
	function themerex_is_team_page() {
		return get_query_var('post_type')=='team' || is_tax('team_group');
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'themerex_team_detect_inheritance_key' ) ) {
	//add_filter('themerex_filter_detect_inheritance_key',	'themerex_team_detect_inheritance_key', 9, 1);
	function themerex_team_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return themerex_is_team_page() ? 'team' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'themerex_team_get_blog_type' ) ) {
	//add_filter('themerex_filter_get_blog_type',	'themerex_team_get_blog_type', 9, 2);
	function themerex_team_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('team_group') || is_tax('team_group'))
			$page = 'team_category';
		else if ($query && $query->get('post_type')=='team' || get_query_var('post_type')=='team')
			$page = $query && $query->is_single() || is_single() ? 'team_item' : 'team';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'themerex_team_get_blog_title' ) ) {
	//add_filter('themerex_filter_get_blog_title',	'themerex_team_get_blog_title', 9, 2);
	function themerex_team_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( themerex_strpos($page, 'team')!==false ) {
			if ( $page == 'team_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'team_group' ), 'team_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'team_item' ) {
				$title = themerex_get_post_title();
			} else {
				$title = __('All team', 'trx_addons');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'themerex_team_get_stream_page_title' ) ) {
	//add_filter('themerex_filter_get_stream_page_title',	'themerex_team_get_stream_page_title', 9, 2);
	function themerex_team_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (themerex_strpos($page, 'team')!==false) {
			if (($page_id = themerex_team_get_stream_page_id(0, $page)) > 0)
				$title = themerex_get_post_title($page_id);
			else
				$title = __('All team', 'trx_addons');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'themerex_team_get_stream_page_id' ) ) {
	//add_filter('themerex_filter_get_stream_page_id',	'themerex_team_get_stream_page_id', 9, 2);
	function themerex_team_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (themerex_strpos($page, 'team')!==false) $id = themerex_get_template_page_id('team');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'themerex_team_get_stream_page_link' ) ) {
	//add_filter('themerex_filter_get_stream_page_link',	'themerex_team_get_stream_page_link', 9, 2);
	function themerex_team_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (themerex_strpos($page, 'team')!==false) {
			$id = themerex_get_template_page_id('team');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'themerex_team_get_current_taxonomy' ) ) {
	//add_filter('themerex_filter_get_current_taxonomy',	'themerex_team_get_current_taxonomy', 9, 2);
	function themerex_team_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( themerex_strpos($page, 'team')!==false ) {
			$tax = 'team_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'themerex_team_is_taxonomy' ) ) {
	//add_filter('themerex_filter_is_taxonomy',	'themerex_team_is_taxonomy', 9, 2);
	function themerex_team_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else
			return $query && $query->get('team_group')!='' || is_tax('team_group') ? 'team_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'themerex_team_query_add_filters' ) ) {
	//add_filter('themerex_filter_query_add_filters',	'themerex_team_query_add_filters', 9, 2);
	function themerex_team_query_add_filters($args, $filter) {
		if ($filter == 'team') {
			$args['post_type'] = 'team';
		}
		return $args;
	}
}


/*********************************************************************************************************************/















// Testimonial
// Theme init
if (!function_exists('themerex_testimonial_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_testimonial_theme_setup' );
	function themerex_testimonial_theme_setup() {

		// Add item in the admin menu
		add_action('admin_menu',			'themerex_testimonial_add_meta_box');

		// Save data from meta box
		add_action('save_post',				'themerex_testimonial_save_data');

		// Meta box fields
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS['testimonial_meta_box'] = array(
			'id' => 'testimonial-meta-box',
			'title' => __('Testimonial Details', 'trx_addons'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => __('Testimonial author',  'trx_addons'),
					"desc" => __("Name of the testimonial's author", 'trx_addons'),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => __("Author's e-mail",  'trx_addons'),
					"desc" => __("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'trx_addons'),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => __('Testimonial link',  'trx_addons'),
					"desc" => __("URL of the testimonial source or author profile page", 'trx_addons'),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text"),
				"additional_field" => array(
					"title" => __('Additional field',  'trx_addons'),
					"desc" => __("Additional field of the testimonial's", 'trx_addons'),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
			)
		);

		// Prepare type "Testimonial"
		themerex_require_data( 'post_type', 'testimonial', array(
				'label'               => __( 'Testimonial', 'trx_addons' ),
				'description'         => __( 'Testimonial Description', 'trx_addons' ),
				'labels'              => array(
					'name'                => _x( 'Testimonials', 'Post Type General Name', 'trx_addons' ),
					'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'trx_addons' ),
					'menu_name'           => __( 'Testimonials', 'trx_addons' ),
					'parent_item_colon'   => __( 'Parent Item:', 'trx_addons' ),
					'all_items'           => __( 'All Testimonials', 'trx_addons' ),
					'view_item'           => __( 'View Item', 'trx_addons' ),
					'add_new_item'        => __( 'Add New Testimonial', 'trx_addons' ),
					'add_new'             => __( 'Add New', 'trx_addons' ),
					'edit_item'           => __( 'Edit Item', 'trx_addons' ),
					'update_item'         => __( 'Update Item', 'trx_addons' ),
					'search_items'        => __( 'Search Item', 'trx_addons' ),
					'not_found'           => __( 'Not found', 'trx_addons' ),
					'not_found_in_trash'  => __( 'Not found in Trash', 'trx_addons' ),
				),
				'supports'            => array( 'title', 'editor', 'author', 'thumbnail'),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-cloud',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 25,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
			)
		);

		// Prepare taxonomy for testimonial
		themerex_require_data( 'taxonomy', 'testimonial_group', array(
				'post_type'			=> array( 'testimonial' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => _x( 'Testimonials Group', 'taxonomy general name', 'trx_addons' ),
					'singular_name'     => _x( 'Group', 'taxonomy singular name', 'trx_addons' ),
					'search_items'      => __( 'Search Groups', 'trx_addons' ),
					'all_items'         => __( 'All Groups', 'trx_addons' ),
					'parent_item'       => __( 'Parent Group', 'trx_addons' ),
					'parent_item_colon' => __( 'Parent Group:', 'trx_addons' ),
					'edit_item'         => __( 'Edit Group', 'trx_addons' ),
					'update_item'       => __( 'Update Group', 'trx_addons' ),
					'add_new_item'      => __( 'Add New Group', 'trx_addons' ),
					'new_item_name'     => __( 'New Group Name', 'trx_addons' ),
					'menu_name'         => __( 'Testimonial Group', 'trx_addons' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'testimonial_group' ),
			)
		);
	}
}


// Add meta box
if (!function_exists('themerex_testimonial_add_meta_box')) {
	//add_action('admin_menu', 'themerex_testimonial_add_meta_box');
	function themerex_testimonial_add_meta_box() {
		global $THEMEREX_GLOBALS;
		$mb = $THEMEREX_GLOBALS['testimonial_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'themerex_testimonial_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('themerex_testimonial_show_meta_box')) {
	function themerex_testimonial_show_meta_box() {
		global $post, $THEMEREX_GLOBALS;

		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_testimonial_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

		$data = get_post_meta($post->ID, 'testimonial_data', true);

		$fields = $THEMEREX_GLOBALS['testimonial_meta_box']['fields'];
		?>
		<table class="testimonial_area">
			<?php
			foreach ($fields as $id=>$field) {
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_attr($field['desc']); ?></small></td>
				</tr>
			<?php
			}
			?>
		</table>
	<?php
	}
}


// Save data from meta box
if (!function_exists('themerex_testimonial_save_data')) {
	//add_action('save_post', 'themerex_testimonial_save_data');
	function themerex_testimonial_save_data($post_id) {
		// verify nonce
		if (!isset($_POST['meta_box_testimonial_nonce']) || !wp_verify_nonce($_POST['meta_box_testimonial_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		global $THEMEREX_GLOBALS;

		$data = array();

		$fields = $THEMEREX_GLOBALS['testimonial_meta_box']['fields'];

		// Post type specific data handling
		foreach ($fields as $id=>$field) {
			if (isset($_POST[$id]))
				$data[$id] = stripslashes($_POST[$id]);
		}

		update_post_meta($post_id, 'testimonial_data', $data);
	}
}


/*********************************************************************************************************************/












// attachment manipulations
// Theme init
if ( !function_exists( 'themerex_attachment_settings_theme_setup2' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_attachment_settings_theme_setup2', 3 );
	function themerex_attachment_settings_theme_setup2() {
		themerex_add_theme_inheritance( array('attachment' => array(
				'stream_template' => '',
				'single_template' => 'attachment',
				'taxonomy' => array(),
				'taxonomy_tags' => array(),
				'post_type' => array('attachment'),
				'override' => 'post'
			) )
		);
	}
}

if (!function_exists('themerex_attachment_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_attachment_theme_setup');
	function themerex_attachment_theme_setup() {

		// Add folders in ajax query
		add_filter('ajax_query_attachments_args',				'themerex_attachment_ajax_query_args');

		// Add folders in filters for js view
		add_filter('media_view_settings',						'themerex_attachment_view_filters');

		// Add folders list in js view compat area
		add_filter('attachment_fields_to_edit',					'themerex_attachment_view_compat');

		// Prepare media folders for save
		add_filter( 'attachment_fields_to_save',				'themerex_attachment_save_compat');

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('themerex_filter_detect_inheritance_key',	'themerex_attachmnent_detect_inheritance_key', 9, 1);

		// Prepare taxonomy for attachment
		themerex_require_data( 'taxonomy', 'media_folder', array(
				'post_type'			=> array( 'attachment' ),
				'hierarchical' 		=> true,
				'labels' 			=> array(
					'name'              => __('Media Folders', 'trx_addons'),
					'singular_name'     => __('Media Folder', 'trx_addons'),
					'search_items'      => __('Search Media Folders', 'trx_addons'),
					'all_items'         => __('All Media Folders', 'trx_addons'),
					'parent_item'       => __('Parent Media Folder', 'trx_addons'),
					'parent_item_colon' => __('Parent Media Folder:', 'trx_addons'),
					'edit_item'         => __('Edit Media Folder', 'trx_addons'),
					'update_item'       => __('Update Media Folder', 'trx_addons'),
					'add_new_item'      => __('Add New Media Folder', 'trx_addons'),
					'new_item_name'     => __('New Media Folder Name', 'trx_addons'),
					'menu_name'         => __('Media Folders', 'trx_addons'),
				),
				'query_var'			=> true,
				'rewrite' 			=> true,
				'show_admin_column'	=> true
			)
		);
	}
}


// Add folders in ajax query
if (!function_exists('themerex_attachment_ajax_query_args')) {
	//add_filter('ajax_query_attachments_args', 'themerex_attachment_ajax_query_args');
	function themerex_attachment_ajax_query_args($query) {
		if (isset($query['post_mime_type'])) {
			$v = $query['post_mime_type'];
			if (themerex_substr($v, 0, 13)=='media_folder.') {
				unset($query['post_mime_type']);
				if (themerex_strlen($v) > 13)
					$query['media_folder'] = themerex_substr($v, 13);
				else {
					$list_ids = array();
					$terms = themerex_get_terms_by_taxonomy('media_folder');
					if (count($terms) > 0) {
						foreach ($terms as $term) {
							$list_ids[] = $term->term_id;
						}
					}
					if (count($list_ids) > 0) {
						$query['tax_query'] = array(
							array(
								'taxonomy' => 'media_folder',
								'field' => 'id',
								'terms' => $list_ids,
								'operator' => 'NOT IN'
							)
						);
					}
				}
			}
		}
		return $query;
	}
}

// Add folders in filters for js view
if (!function_exists('themerex_attachment_view_filters')) {
	//add_filter('media_view_settings', 'themerex_attachment_view_filters');
	function themerex_attachment_view_filters($settings, $post=null) {
		$taxes = array('media_folder');
		foreach ($taxes as $tax) {
			$terms = themerex_get_terms_by_taxonomy($tax);
			if (count($terms) > 0) {
				$settings['mimeTypes'][$tax.'.'] = __('Media without folders', 'trx_addons');
				$settings['mimeTypes'] = array_merge($settings['mimeTypes'], themerex_get_terms_hierarchical_list($terms, array(
						'prefix_key' => 'media_folder.',
						'prefix_level' => '-'
					)
				));
			}
		}
		return $settings;
	}
}

// Add folders list in js view compat area
if (!function_exists('themerex_attachment_view_compat')) {
	//add_filter('attachment_fields_to_edit', 'themerex_attachment_view_compat');
	function themerex_attachment_view_compat($form_fields, $post=null) {
		static $terms = null, $id = 0;
		if (isset($form_fields['media_folder'])) {
			$field = $form_fields['media_folder'];
			if (!$terms) {
				$terms = themerex_get_terms_by_taxonomy('media_folder', array(
					'hide_empty' => false
				));
				$terms = themerex_get_terms_hierarchical_list($terms, array(
					'prefix_key' => 'media_folder.',
					'prefix_level' => '-'
				));
			}
			$values = array_map('trim', explode(',', $field['value']));
			$readonly = ''; //! $user_can_edit && ! empty( $field['taxonomy'] ) ? " readonly='readonly' " : '';
			$required = !empty($field['required']) ? '<span class="alignright"><abbr title="required" class="required">*</abbr></span>' : '';
			$aria_required = !empty($field['required']) ? " aria-required='true' " : '';
			$html = '';
			if (count($terms) > 0) {
				foreach ($terms as $slug=>$name) {
					$id++;
					$slug = themerex_substr($slug, 13);
					$html .= ($html ? '<br />' : '') . '<input type="checkbox" class="text" id="media_folder_'.esc_attr($id).'" name="media_folder_' . esc_attr($slug) . '" value="' . esc_attr( $slug ) . '"' . (in_array($slug, $values) ? ' checked="checked"' : '' ) . ' ' . ($readonly) . ' ' . ($aria_required) . ' /><label for="media_folder_'.esc_attr($id).'"> ' . ($name) . '</label>';
				}
			}
			$form_fields['media_folder']['input'] = 'media_folder_input';
			$form_fields['media_folder']['media_folder_input'] = '<div class="media_folder_selector">' . ($html) . '</div>';
		}
		return $form_fields;
	}
}

// Prepare media folders for save
if (!function_exists('themerex_attachment_save_compat')) {
	//add_filter( 'attachment_fields_to_save', 'themerex_attachment_save_compat');
	function themerex_attachment_save_compat($post=null, $attachment_data=null) {
		if (!empty($post['ID']) && ($id = intval($post['ID'])) > 0) {
			$folders = array();
			$from_media_library = !empty($_REQUEST['tax_input']['media_folder']) && is_array($_REQUEST['tax_input']['media_folder']);
			// From AJAX query
			if (!$from_media_library) {
				foreach ($_REQUEST as $k => $v) {
					if (themerex_substr($k, 0, 12)=='media_folder')
						$folders[] = $v;
				}
			} else {
				if (count($folders)==0) {
					if (!empty($_REQUEST['tax_input']['media_folder']) && is_array($_REQUEST['tax_input']['media_folder'])) {
						foreach ($_REQUEST['tax_input']['media_folder'] as $k => $v) {
							if ((int)$v > 0)
								$folders[] = $v;
						}
					}
				}
			}
			if (count($folders) > 0) {
				foreach ($folders as $k=>$v) {
					if ((int) $v > 0) {
						$term = get_term_by('id', $v, 'media_folder');
						$folders[$k] = $term->slug;
					}
				}
			} else
				$folders = null;
			// Save folders list only from AJAX
			if (!$from_media_library)
				wp_set_object_terms( $id, $folders, 'media_folder', false );
		}
		return $post;
	}
}


// Filter to detect current page inheritance key
if ( !function_exists( 'themerex_attachmnent_detect_inheritance_key' ) ) {
	//add_filter('themerex_filter_detect_inheritance_key',	'themerex_attachmnent_detect_inheritance_key', 9, 1);
	function themerex_attachmnent_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return is_attachment() ? 'attachment' : '';
	}
}

/*********************************************************************************************************************/
// Add theme required shortcode
if (!function_exists('themerex_addons_require_shortcode')) {
    function themerex_addons_require_shortcode($name, $cb) {
        add_shortcode($name, $cb);
    }
}
if (!function_exists('themerex_addons_get_twitter_data')) {
    function themerex_addons_get_twitter_data($cfg) {
        $data = get_transient("twitter_data_".($cfg['mode']));
        if (!$data) {
            require_once(  TRX_ADDONS_PLUGIN_DIR .'lib/tmhOAuth/tmhOAuth.php' );
            $tmhOAuth = new tmhOAuth(array(
                'consumer_key'    => $cfg['consumer_key'],
                'consumer_secret' => $cfg['consumer_secret'],
                'token'           => $cfg['token'],
                'secret'          => $cfg['secret']
            ));
            $code = $tmhOAuth->user_request(array(
                'url' => $tmhOAuth->url(themerex_get_twitter_mode_url($cfg['mode']))
            ));
            if ($code == 200) {
                $data = json_decode($tmhOAuth->response['response'], true);
                if (isset($data['status'])) {
                    $code = $tmhOAuth->user_request(array(
                        'url' => $tmhOAuth->url(themerex_get_twitter_mode_url($cfg['oembed'])),
                        'params' => array(
                            'id' => $data['status']['id_str']
                        )
                    ));
                    if ($code == 200)
                        $data = json_decode($tmhOAuth->response['response'], true);
                }
                set_transient("twitter_data_".($cfg['mode']), $data, 60*60);
            }
        } else if (!is_array($data) && themerex_substr($data, 0, 2)=='a:') {
            $data = unserialize($data);
        }
        return $data;
    }
}
// Prepare required styles and scripts for admin mode
if ( !function_exists( 'universal_themerex_admin_prepare_scripts' ) ) {
    add_action("admin_head", 'universal_themerex_admin_prepare_scripts');
    function universal_themerex_admin_prepare_scripts() {
        ?>
        <script>
            if (typeof TRX_ADDONS_GLOBALS == 'undefined') var TRX_ADDONS_GLOBALS = {};
            jQuery(document).ready(function() {
                TRX_ADDONS_GLOBALS['admin_mode']	= true;
                TRX_ADDONS_GLOBALS['ajax_nonce'] = "<?php echo wp_create_nonce('ajax_nonce'); ?>";
                TRX_ADDONS_GLOBALS['ajax_url']	= "<?php echo admin_url('admin-ajax.php'); ?>";
                TRX_ADDONS_GLOBALS['user_logged_in'] = true;
                TRX_ADDONS_GLOBALS['msg_importer_full_alert'] = "<?php
                    echo esc_html__("ATTENTION!\\n\\nIn this case ALL THE OLD DATA WILL BE ERASED\\nand YOU WILL GET A NEW SET OF POSTS, pages and menu items.", 'jacqueline')
                        . "\\n\\n"
                        . esc_html__("It is strongly recommended only for new installations of WordPress\\n(without posts, pages and any other data)!", 'jacqueline')
                        . "\\n\\n"
                        . esc_html__("Press OK to continue or Cancel to return to a partial installation", 'jacqueline');
                    ?>";
            });
        </script>
        <?php
    }
}

// Return text for the Privacy Policy checkbox
if (!function_exists('trx_addons_get_privacy_text')) {
    function trx_addons_get_privacy_text() {
        $page = get_option('wp_page_for_privacy_policy');
        return apply_filters( 'trx_addons_filter_privacy_text', wp_kses_post(
                __( 'I agree that my submitted data is being collected and stored.', 'trx_addons' )
                . ( '' != $page
                    // Translators: Add url to the Privacy Policy page
                    ? ' ' . sprintf(__('For further details on handling user data, see our %s', 'trx_addons'),
                        '<a href="' . esc_url(get_permalink($page)) . '" target="_blank">'
                        . __('Privacy Policy', 'trx_addons')
                        . '</a>')
                    : ''
                )
            )
        );
    }
}

// File functions
if (file_exists(TRX_ADDONS_PLUGIN_DIR . 'includes/plugin.files.php')) {
    require_once TRX_ADDONS_PLUGIN_DIR . 'includes/plugin.files.php';
}
// Third-party plugins support
if (file_exists(TRX_ADDONS_PLUGIN_DIR . 'api/api.php')) {
    require_once TRX_ADDONS_PLUGIN_DIR . 'api/api.php';
}
// Demo data import/export
if (file_exists(TRX_ADDONS_PLUGIN_DIR . 'importer/importer.php')) {
    require_once TRX_ADDONS_PLUGIN_DIR . 'importer/importer.php';
}

require_once trx_addons_get_file_dir('includes/core.socials.php');

if (is_admin()) {
    require_once trx_addons_get_file_dir('tools/emailer/emailer.php');
    require_once trx_addons_get_file_dir('tools/po_composer/po_composer.php');
}


// Shortcodes init
if (!function_exists('trx_addons_sc_init')) {
    add_action( 'after_setup_theme', 'trx_addons_sc_init' );
    function trx_addons_sc_init() {
        global $TRX_ADDONS_STORAGE;
        if ( !($TRX_ADDONS_STORAGE['plugin_active'] = apply_filters('trx_addons_active', $TRX_ADDONS_STORAGE['plugin_active'])) ) return;

        // Include shortcodes
        require_once trx_addons_get_file_dir('/shortcodes/shortcodes.php');
        require_once trx_addons_get_file_dir('/shortcodes/core.shortcodes.php');

        require_once( trx_addons_get_file_dir('/shortcodes/shortcodes_settings.php') );

        if ( class_exists('WPBakeryShortCode')
            && (
                is_admin()
                || (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
                || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
            )
        ) {
            require_once( trx_addons_get_file_dir('/shortcodes/shortcodes_vc_classes.php') );
            require_once( trx_addons_get_file_dir('/shortcodes/shortcodes_vc.php') );
        }

    }
}


// Widgets init
if (!function_exists('trx_addons_setup_widgets')) {
    add_action( 'widgets_init', 'trx_addons_setup_widgets', 9 );
    function trx_addons_setup_widgets() {
        global $TRX_ADDONS_STORAGE;
        if ( !($TRX_ADDONS_STORAGE['plugin_active'] = apply_filters('trx_addons_active', $TRX_ADDONS_STORAGE['plugin_active'])) ) return;

        // Include widgets
        require_once trx_addons_get_file_dir('widgets/advert.php');
        require_once trx_addons_get_file_dir('widgets/calendar.php');
        require_once trx_addons_get_file_dir('widgets/categories.php');
        require_once trx_addons_get_file_dir('widgets/flickr.php');
        require_once trx_addons_get_file_dir('widgets/popular_posts.php');
        require_once trx_addons_get_file_dir('widgets/recent_posts.php');
        require_once trx_addons_get_file_dir('widgets/recent_reviews.php');
        require_once trx_addons_get_file_dir('widgets/socials.php');
        require_once trx_addons_get_file_dir('widgets/top10.php');
        require_once trx_addons_get_file_dir('widgets/twitter.php');
        require_once trx_addons_get_file_dir('widgets/qrcode/qrcode.php');
    }
}

/* Support for meta boxes
--------------------------------------------------- */
if (!function_exists('trx_utils_meta_box_add')) {
    add_action('add_meta_boxes', 'trx_utils_meta_box_add');
    function trx_utils_meta_box_add() {
        // Custom theme-specific meta-boxes
        $boxes = apply_filters('trx_utils_filter_override_options', array());
        if (is_array($boxes)) {
            foreach ($boxes as $box) {
                $box = array_merge(array('id' => '',
                    'title' => '',
                    'callback' => '',
                    'page' => null,        // screen
                    'context' => 'advanced',
                    'priority' => 'default',
                    'callbacks' => null
                ),
                    $box);
                add_meta_box($box['id'], $box['title'], $box['callback'], $box['page'], $box['context'], $box['priority'], $box['callbacks']);
            }
        }
    }
}

?>
