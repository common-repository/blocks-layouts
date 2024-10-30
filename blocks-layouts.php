<?php
/*
Plugin Name: Reusable Blocks Layouts
Description: Create your own reusable layouts of blocks, and insert them anywhere, as a block (Gutenberg).
Version: 1.0.0
Author: Jordy Meow
Author URI: https://meowapps.com
Text Domain: blocks-layouts

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
*/

if ( !class_exists( 'Meow_MBL_Core' ) ) {

  class Meow_MBL_Core {

  	public function __construct() {
  		add_action( 'init', array( $this, 'init' ) );
  	}

    public function init() {
      $this->create_post_type();
      include( 'blocks/blocks.php' );
      new Meow_MBL_Blocks( $this );
      add_filter( 'gutenberg_can_edit_post_type', array( $this, 'gutenberg_can_edit_post_type' ), 10, 2 );
      add_shortcode( 'blocks-layout', array( $this, 'display_blocks_layout' ) );
    }

    function gutenberg_can_edit_post_type( $can_edit, $post_type ) {
      if ( $post_type == 'blocks-layouts' )
        return;
      return $can_edit;
    }

    function get_layouts() {
      $posts = get_posts( array(
        'numberposts' => 1000,
        'post_type' => 'blocks_layout'
      ) );
      return $posts;
    }

    function display_blocks_layout( $atts, $content ) {
      if ( !empty( $atts ) && isset( $atts['layout_id'] ) ) {
        return get_post_field( 'post_content', $atts['layout_id'] );
      }
      return $content;
    }

    private function create_post_type() {
    	$labels = array(
    		'name'               => _x( 'Layouts', 'Name', 'blocks-layouts' ),
    		'singular_name'      => _x( 'Layout', 'Singular Name', 'blocks-layouts' ),
    		'menu_name'          => _x( 'Layouts', 'Menu Name', 'blocks-layouts' ),
    		'name_admin_bar'     => _x( 'Layout', 'Admin Bar Name', 'blocks-layouts' ),
    		'add_new'            => _x( 'Add New', 'Add New', 'blocks-layouts' ),
    		'add_new_item'       => __( 'Add New Layout', 'blocks-layouts' ),
    		'new_item'           => __( 'New Layout', 'blocks-layouts' ),
    		'edit_item'          => __( 'Edit Layout', 'blocks-layouts' ),
    		'view_item'          => __( 'View Layout', 'blocks-layouts' ),
    		'all_items'          => __( 'All Layouts', 'blocks-layouts' ),
    		'search_items'       => __( 'Search Layouts', 'blocks-layouts' ),
    		'parent_item_colon'  => __( 'Parent Layouts:', 'blocks-layouts' ),
    		'not_found'          => __( 'No Layouts found.', 'blocks-layouts' ),
    		'not_found_in_trash' => __( 'No Layouts found in Trash.', 'blocks-layouts' )
    	);

    	$args = array(
    		'labels'             => $labels,
        'description'        => __( 'Description.', 'blocks-layouts' ),
        'show_in_rest'       => true,
    		'public'             => true,
    		'publicly_queryable' => true,
    		'show_ui'            => true,
    		'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-editor-kitchensink',
    		'query_var'          => true,
    		'rewrite'            => array( 'slug' => 'collection' ),
    		'capability_type'    => 'post',
    		'has_archive'        => true,
    		'hierarchical'       => false,
    		'menu_position'      => 20,
    		'supports'           => array( 'title', 'editor', 'author' )
    	);

    	register_post_type( 'blocks_layout', $args );
    }

    private function create_taxonomy() {

      // Create Categories
      $labels = array(
    		'name'              => _x( 'Categories', 'Name', 'blocks-layouts' ),
    		'singular_name'     => _x( 'Category', 'Singular Name', 'blocks-layouts' ),
    		'search_items'      => __( 'Search Categories', 'blocks-layouts' ),
    		'all_items'         => __( 'All Categories', 'blocks-layouts' ),
    		'parent_item'       => __( 'Parent Category', 'blocks-layouts' ),
    		'parent_item_colon' => __( 'Parent Category:', 'blocks-layouts' ),
    		'edit_item'         => __( 'Edit Category', 'blocks-layouts' ),
    		'update_item'       => __( 'Update Category', 'blocks-layouts' ),
    		'add_new_item'      => __( 'Add New Category', 'blocks-layouts' ),
    		'new_item_name'     => __( 'New Category Name', 'blocks-layouts' ),
    		'menu_name'         => __( 'Categories', 'blocks-layouts' ),
    	);
    	$args = array(
    		'hierarchical'          => true,
    		'labels'                => $labels,
    		'show_ui'               => true,
    		'show_admin_column'     => true,
    		'query_var'             => true,
        'update_count_callback' => '_update_generic_term_count',
    		'rewrite'               => array( 'slug' => 'layout_cat' ),
    	);
    	register_taxonomy( 'blocks_layout_category', array( 'blocks_layout' ), $args );
    }

  }

}

new Meow_MBL_Core();

?>
