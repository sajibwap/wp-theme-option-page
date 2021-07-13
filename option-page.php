<?php 

/*
Plugin Name: Plugin Option Page
Plugin URI: http://msajib.com
Description: Theme Option page
Version: 1.00
Author: Sajib
Author URI: http://msajib.com
Text Domain: textdomain
Domain Path: /languages/
*/

class Plugin_option_page {

	public function __construct(){
		add_action( 'admin_menu', array($this,'add_menu_page_callback') );
		add_action( 'admin_init', array($this,'ex_setup_section_callback') );
		add_action( 'admin_init', array($this,'ex_setup_fields_callback') );
	}

	/**
	 * Adds a new top-level menu to the bottom of the WordPress administration menu.
	 */
	public function add_menu_page_callback(){
		$page_title = __('Theme Option','textdomain');
		$menu_title = __('Theme Option','textdomain');
		$capability = 'manage_options';
		$menu_slug 	= 'themeoption';
		$callback 	= array($this,'exc_option_page_content');
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $callback );
	}
	/**
	 * Renders the basic display of the menu page for the theme.
	 */
	public function exc_option_page_content(){
		?>
		<div class="wrap">
			<h1>Plugin Page Title</h1>
			<?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php 

				$option_group = 'exc_option_group';
				$page 		  = 'exc_plugin_page';

			settings_fields( $option_group ); // same name used in the register_setting() function
			do_settings_sections( $page );  // replaces the form-field markup in the form itself.
			submit_button();
			?>
			</form>
	    </div>
		<?php
	}

	/**
	 *  Setup section
	 */

	public function ex_setup_section_callback(){
		add_settings_section( 
		 	'exc_theme_option_section_1',	 // unique section id
			__('Profile Data','textdomain'), // Section Name
			array($this,'print_section_info'), // Print the Section text
			'exc_plugin_page' 			 	 // do_setting_section() - id
		);		
	}

	/** 
     * Print the Section text
     */
    public function print_section_info(){
        print 'Enter your data below:';
    }

	/*
	** Setup Fields Register
	**/

	public function ex_setup_fields_callback(){
		add_settings_field( 
			'exc_fname', 
			__('First Name','textdomain'), 
			array($this,'fname_cb'),
			'exc_plugin_page',
			'exc_theme_option_section_1' 
		);
		register_setting( 'exc_option_group', 'exc_fname' ); // nonce_id and field_id and sanitize_callback

		add_settings_field( 
			'exc_lname', 
			__('Last Name','textdomain'), 
			array($this,'lname_cb'),
			'exc_plugin_page',
			'exc_theme_option_section_1' 
		);
		register_setting( 'exc_option_group', 'exc_lname' );

		add_settings_field( 
			'exc_checkbox', 
			__('Checkbox','textdomain'), 
			array($this,'checkbox_cb'),
			'exc_plugin_page',
			'exc_theme_option_section_1' 
		);

		register_setting( 'exc_option_group', 'exc_checkbox' );

		add_settings_field( 
			'exc_gender', 
			__('Gender','textdomain'), 
			array($this,'radio_cb'),
			'exc_plugin_page',
			'exc_theme_option_section_1' 
		);

		register_setting( 'exc_option_group', 'exc_gender' );

		add_settings_field( 
			'exc_select', 
			__('Rule','textdomain'), 
			array($this,'select_cb'),
			'exc_plugin_page',
			'exc_theme_option_section_1' 
		);

		register_setting( 'exc_option_group', 'exc_select' );

		add_settings_field( 
			'exc_checkbox_multi', 
			__('Skills','textdomain'), 
			array($this,'checkbox_multi_cb'),
			'exc_plugin_page',
			'exc_theme_option_section_1' 
		);

		register_setting( 'exc_option_group', 'exc_checkbox_multi' );

		add_settings_field( 
			'exc_select_post', 
			__('All Post','textdomain'), 
			array($this,'select_post_cb'),
			'exc_plugin_page',
			'exc_theme_option_section_1' 
		);

		register_setting( 'exc_option_group', 'exc_select_post' );

	}

	/*
	** Form Fields HTML Callback
	**/

	public function fname_cb(){
		$val = get_option( 'exc_fname' );
		printf('<input class="regular-text" type="text" name="exc_fname" id="fname" value="%s">',$val) ;
	}
	public function lname_cb(){
		$val = get_option( 'exc_lname' );
		printf('<input class="regular-text" type="text" name="exc_lname" id="lname" value="%s">',$val) ;
	}
	public function checkbox_cb(){
		$val   = get_option('exc_checkbox');
		$checked = ( $val == 1 ) ? 'checked' : "";
		printf('<input type="checkbox" name="exc_checkbox" id="exc_checkbox" value="1" %s >',$checked);
	}
	public function radio_cb(){
		$val = get_option( 'exc_gender' );
		printf('<input class="regular-text" type="radio" name="exc_gender" id="exc_gender" value="male" %s>Male ',$val == 'male' ? 'checked':'');
		printf('<input class="regular-text" type="radio" name="exc_gender" id="exc_gender" value="female" %s>Female',$val == 'female' ? 'checked':'');
	}
	public function select_cb(){
		$val = get_option( 'exc_select' );
		echo '<select name="exc_select" id="exc_select"><option disabled>Select an option</option>';
		printf('<option value="admin" %s>Admin</option>',$val == 'admin' ? 'selected' : '');
		printf('<option value="editor" %s>Editor</option>',$val == 'editor' ? 'selected' : '');
		echo '</select>';
	}
	public function checkbox_multi_cb(){
		$val = get_option( 'exc_checkbox_multi' );
		$val = is_array($val) ? $val : array();


		printf('<input class="regular-text" type="checkbox" name="exc_checkbox_multi[]" value="web" %s>Web design',in_array('web', $val) ? 'checked':'');
		printf('<input class="regular-text" type="checkbox" name="exc_checkbox_multi[]" value="seo" %s>SEO ',in_array('seo', $val)  ? 'checked':'');
		printf('<input class="regular-text" type="checkbox" name="exc_checkbox_multi[]" value="graphics" %s>Graphics',in_array('graphics', $val)  ? 'checked':'');
	}
	public function select_post_cb(){
		$val = get_option( 'exc_select_post' );
		$posts = get_posts(array(
    		'post_type'=>'post',
    		'post_per_page'=>-1
		));

		echo '<select name="exc_select_post"><option disabled>Select an option</option>';
		foreach ($posts as $_post) {
			printf('<option value="%s" %s>%s</option>',
				$_post->ID,
				$val == $_post->ID ? "selected" : "",
				$_post->post_title);
		}
		echo '</select>';
	}

}

new Plugin_option_page();