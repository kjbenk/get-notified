<?php

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class GNT_Hooks_Table extends WP_List_Table {

    public $hook_settings = array();

    /**
     * Create all the columns
     *
     * @return null
     */
    function get_columns(){
        $columns = array(
            'cb'    => '',
            'name'  => __( 'Name', 'gnt' ),
            'desc'  => __( 'Description', 'gnt' ),
        );
        return $columns;
    }

    /**
     * The default column output
     *
     * @param  array $item        The row
     * @param  string $column_name The column name
     * @return null
     */
    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'name':
            case 'desc':
                return $item[ $column_name ];
            default:
                return print_r( $item, true );
        }
    }

    /**
     * Output the checkboxes
     *
     * @param  array $item The row item
     * @return string   The input field
     */
    function column_cb( $item ) {
        $input = '<input type="checkbox" name="' . $item['slug'] . '-enable"';
        if ( isset( $this->hook_settings[ $item['slug'] . '-enable' ] ) && $this->hook_settings[ $item['slug'] . '-enable' ] ) {
            $input .= ' checked="checked" ';
        }
        $input .= '/>';

        return sprintf( $input );
    }

    /**
     * Prepare the items for output
     *
     * @return null
     */
    function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->hook_settings = gnt_get_hook_settings();
        $this->items = gnt_get_hooks();
    }

    /**
     * Get all the classes for the table
     *
     * @return null
     */
    protected function get_table_classes() {
        return array( 'widefat', 'striped', $this->_args['plural'] );
    }

}
