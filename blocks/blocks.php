<?php

if ( !class_exists( 'Meow_MBL_Blocks' ) ) {

  class Meow_MBL_Blocks {

    public $core;

    public function __construct( $core ) {
      $this->core = $core;
      add_action( 'enqueue_block_editor_assets', array( $this, 'backend_editor' ) );
    }

    function backend_editor() {
      wp_enqueue_script(
        'mbl-blocks-layout-js', plugins_url( 'blocks/blocks-layout/block.js', dirname( __FILE__ ) ), 
        array( 'wp-blocks', 'wp-i18n', 'wp-element' ), filemtime( plugin_dir_path( __FILE__ ) . 'blocks-layout/block.js' )
      );
      wp_enqueue_style(
        'mbl-blocks-layout-css', plugins_url( 'blocks/blocks-layout/editor.min.css', dirname( __FILE__ ) ), 
        array( 'wp-edit-blocks' ), filemtime( plugin_dir_path( __FILE__ ) . 'blocks-layout/editor.min.css' )
      );
      $layouts = $this->core->get_layouts();
      wp_localize_script( 'mbl-blocks-layout-js', 'mbl_block_params', array(
        'logo' => trailingslashit( plugin_dir_url( __FILE__ ) ) . '../img/meowapps.png',
        'layouts' => $layouts
      ) );

    }

  }

}

?>