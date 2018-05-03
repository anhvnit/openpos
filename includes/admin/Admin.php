<?php
/**
 * Created by PhpStorm.
 * User: anhvnit
 * Date: 7/26/16
 * Time: 23:32
 */
class Op_Admin{
    private $settings_api;
    public $core;
    public function __construct()
    {
        global $_OP_SETTING;
        global $_OP_CORE;
        $this->settings_api = $_OP_SETTING;
        $this->core = $_OP_CORE;
    }

    public function init()
    {
        add_action( 'admin_init', array($this, 'admin_init') );
        add_action('admin_enqueue_scripts', array($this,'admin_style'));
        add_action( 'init', array($this,'create_store_taxonomies'), 0 );

        add_filter( "manage_edit-store_columns", array($this,'store_setting_column_header'), 10);
        add_action( "manage_store_custom_column",array($this,'store_setting_column_content'), 10, 3);
        add_action( 'admin_menu', array($this,'pos_admin_menu'),1 );

        //ajax

        add_action( 'wp_ajax_op_products', array($this,'products') );

        // Admin bar menus
        if ( apply_filters( 'woocommerce_show_admin_bar_visit_store', true ) ) {
            add_action( 'admin_bar_menu', array( $this, 'admin_bar_menus' ), 31 );
        }

        add_action( 'wp_ajax_op_cashier', array($this,'getUsers') );
        add_action( 'wp_ajax_save_cashier', array($this,'save_cashier') );

        add_action( 'wp_ajax_print_barcode', array($this,'print_bacode') );

    }
    function admin_init() {
        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );
        //initialize settings
        $this->settings_api->admin_init();
    }
    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'openpos_general',
                'title' => __( 'General Settings', 'openpos' )
            ),
            array(
                'id'    => 'openpos_label',
                'title' => __( 'Barcode Label Sheet Settings', 'openpos' )
            ),
            array(
                'id'    => 'openpos_receipt',
                'title' => __( 'Print Receipt Settings', 'openpos' )
            ),
//            array(
//                'id'    => 'wedevs_advanced',
//                'title' => __( 'Advanced Settings', 'openpos' )
//            )
        );
        return $sections;
    }
    private function _getPages()
    {
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        $result = array(0=> __('--Choose--','openpos'));

        foreach($pages as $p)
        {
            $id = $p->ID;
            $title = $p->post_title;
            $result[$id] = $title;
        }
        return $result;
    }
    function get_settings_fields() {
        $settings_fields = array(
            'openpos_general' => array(
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'Pos Tax Class', 'wedevs' ),
                    'desc'    => __( 'Tax Class assign for POS system', 'wedevs' ),
                    'type'    => 'select',
                    'default' => 'op_notax',
                    'options' => array_merge( array(
                        'op_productax' => 'Use Product Tax Class',
                        'op_notax'  => 'No Tax'
                    ),wc_get_product_tax_class_options())
                ),
            ),
            'openpos_label' => array(
                array(
                    'name'              => 'unit',
                    'label'             => __( 'Unit', 'openpos' ),
                    'type'              => 'select',
                    'default' => 'in',
                    'options' => array(
                        'in' => 'Inch',
                        'mm' => 'Minimeter'
                    )
                ),
                array(
                    'name'              => 'heading-s',
                    'desc'              => __( '<h2>Sheet Setting</h2>', 'openpos' ),
                    'type'              => 'html'
                ),

                array(
                    'name'              => 'sheet_width',
                    'label'             => __( 'Sheet Width', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '8.5',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'sheet_height',
                    'label'             => __( 'Sheet Height', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '11',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'sheet_vertical_space',
                    'label'             => __( 'Vertical Space', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'sheet_horizontal_space',
                    'label'             => __( 'Horizontal Space', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0.125',
                    'sanitize_callback' => 'sanitize_text_field'
                ),

                array(
                    'name'              => 'sheet_margin_top',
                    'label'             => __( 'Margin Top', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0.5',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'sheet_margin_right',
                    'label'             => __( 'Margin Right', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0.188',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'sheet_margin_bottom',
                    'label'             => __( 'Margin Bottom', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0.5',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'sheet_margin_left',
                    'label'             => __( 'Margin Left', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0.188',
                    'sanitize_callback' => 'sanitize_text_field'
                ),


                array(
                    'name'              => 'barcode_label_width',
                    'label'             => __( 'Label Width', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '2.625',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'barcode_label_height',
                    'label'             => __( 'Label Height', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '1',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'barcode_label_padding_top',
                    'label'             => __( 'Padding Top', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0.1',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'barcode_label_padding_right',
                    'label'             => __( 'Padding Right', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0.1',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'barcode_label_padding_bottom',
                    'label'             => __( 'Padding Bottom', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0.1',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'barcode_label_padding_left',
                    'label'             => __( 'Padding Left', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0.1',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                /*
                array(
                    'name'              => 'barcode_label_template',
                    'label'             => __( 'Label Template', 'openpos' ),
                    'desc'              => __( 'use [barcode with="" height=""] to adjust barcode image, accept html string', 'openpos' ),
                    'default'           => '[barcode]',
                    'type'              => 'textarea'
                ),
                */
                array(
                    'name'              => 'heading',
                    'desc'              => __( '<h2>Barcode Setting</h2>', 'openpos' ),
                    'type'              => 'html'
                ),


                array(
                    'name'              => 'barcode_mode',
                    'label'             => __( 'Mode', 'openpos' ),
                    'type'              => 'select',
                    'default' => 'code_128_reader',
                    'options' => array(
                        'code_128_reader' => 'Code 128',
                        'ean_reader' => 'EAN-13',
                        'ean_8_reader' => 'EAN-8',
                        'code_39_reader' => 'Code-39',
//                        'code_39_vin_reader' => 'Code-39 Full ASCII',
                        //'codabar_reader' => 'Code 128',
                        'upc_reader' => 'UPC-A',
                        'upc_e_reader' => 'UPC-E',
                        //'i2of5_reader' => 'Code 128',
                        //'2of5_reader' => 'Code 128',
                        //'code_93_reader' => 'Code 128'
                    )
                ),
                array(
                    'name'              => 'barcode_width',
                    'label'             => __( 'Width', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '2.625',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'barcode_height',
                    'label'             => __( 'Height', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '1',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
            ),

            'openpos_receipt' => array(

                array(
                    'name'              => 'receipt_width',
                    'label'             => __( 'Receipt Width', 'openpos' ),
                    'desc'              => __( 'mm', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'receipt_padding_top',
                    'label'             => __( 'Padding Top', 'openpos' ),
                    'desc'              => __( 'mm', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'receipt_padding_right',
                    'label'             => __( 'Padding Right', 'openpos' ),
                    'desc'              => __( 'mm', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'receipt_padding_bottom',
                    'label'             => __( 'Padding Bottom', 'openpos' ),
                    'desc'              => __( 'mm', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'receipt_padding_left',
                    'label'             => __( 'Padding Left', 'openpos' ),
                    'desc'              => __( 'mm', 'openpos' ),
                    'type'              => 'number',
                    'default'           => '0',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'receipt_template',
                    'label'             => __( 'Label Template', 'openpos' ),
                    'desc'              => __( 'use [barcode with="" height=""] to adjust barcode image, accept html string', 'openpos' ),
                    'type'              => 'textarea'
                ),
            ),

            'openpos_basics' => array(


                array(
                    'name'              => 'number_input',
                    'label'             => __( 'Number Input', 'wedevs' ),
                    'desc'              => __( 'Number field with validation callback `floatval`', 'wedevs' ),
                    'placeholder'       => __( '1.99', 'wedevs' ),
                    'min'               => 0,
                    'max'               => 100,
                    'step'              => '0.01',
                    'type'              => 'number',
                    'default'           => 'Title',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'        => 'textarea',
                    'label'       => __( 'Textarea Input', 'wedevs' ),
                    'desc'        => __( 'Textarea description', 'wedevs' ),
                    'placeholder' => __( 'Textarea placeholder', 'wedevs' ),
                    'type'        => 'textarea'
                ),
                array(
                    'name'        => 'html',
                    'desc'        => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'wedevs' ),
                    'type'        => 'html'
                ),
                array(
                    'name'  => 'checkbox',
                    'label' => __( 'Checkbox', 'wedevs' ),
                    'desc'  => __( 'Checkbox Label', 'wedevs' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'radio',
                    'label'   => __( 'Radio Button', 'wedevs' ),
                    'desc'    => __( 'A radio button', 'wedevs' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'A Dropdown', 'wedevs' ),
                    'desc'    => __( 'Dropdown description', 'wedevs' ),
                    'type'    => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'wedevs' ),
                    'desc'    => __( 'Password description', 'wedevs' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', 'wedevs' ),
                    'desc'    => __( 'File description', 'wedevs' ),
                    'type'    => 'file',
                    'default' => '',
                    'options' => array(
                        'button_label' => 'Choose Image'
                    )
                )
            ),
            'wedevs_advanced' => array(
                array(
                    'name'    => 'color',
                    'label'   => __( 'Color', 'wedevs' ),
                    'desc'    => __( 'Color description', 'wedevs' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'wedevs' ),
                    'desc'    => __( 'Password description', 'wedevs' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'wysiwyg',
                    'label'   => __( 'Advanced Editor', 'wedevs' ),
                    'desc'    => __( 'WP_Editor description', 'wedevs' ),
                    'type'    => 'wysiwyg',
                    'default' => ''
                ),
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', 'wedevs' ),
                    'desc'    => __( 'Multi checkbox description', 'wedevs' ),
                    'type'    => 'multicheck',
                    'default' => array('one' => 'one', 'four' => 'four'),
                    'options' => array(
                        'one'   => 'One',
                        'two'   => 'Two',
                        'three' => 'Three',
                        'four'  => 'Four'
                    )
                ),
            )
        );
        return $settings_fields;
    }

    public function products()
    {
        $rows = array();
        $current = isset($_REQUEST['current']) ? $_REQUEST['current'] : 1;
        $sort  = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : false;
        $searchPhrase  = $_REQUEST['searchPhrase'] ? $_REQUEST['searchPhrase'] : false;
        $sortBy = 'date';
        $order = 'DESC';
        if($sort && is_array($sort))
        {
            $key = array_keys($sort);

            $sortBy = end($key);
            if($sortBy == 'id')
            {
                $sortBy = 'ID';
            }
            $order = end($sort);
        }


        $rowCount = $_REQUEST['rowCount'] ? $_REQUEST['rowCount'] : get_option( 'posts_per_page' );
        $offet = ($current -1) * $rowCount;

        $variable_arg = array(
            'posts_per_page'   => -1,
            'post_type'        => array('product'),
            'product_type' => 'variable'
        );

        $variable_array = get_posts($variable_arg);
        $ignores = array();
        foreach($variable_array as $a)
        {
            $ignores[] = $a->ID;
        }

        $args = array(
            'posts_per_page'   => $rowCount,
            'offset'           => $offet,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => $sortBy,
            'order'            => $order,
            'exclude'          => $ignores,
            'post_type'        => array('product','product_variation'),
            'post_status'      => 'publish',
            'suppress_filters' => false
        );
        if($searchPhrase)
        {
            $args['s'] = $searchPhrase;
        }

        $posts = $this->core->getProducts($args);
        $posts_array = $posts['posts'];
        $total = $posts['total'];
        $fields = array('post_title');

        foreach($posts_array as $post)
        {
            $product_id = $post->ID;
            $_product = wc_get_product($product_id);
            $type = $_product->get_type();
            $allow_types = array('simple','variation');
            if(in_array($type,$allow_types))
            {
                $tmp = array();
                $thumb = '';
                if( wc_placeholder_img_src() ) {
                    $thumb = wc_placeholder_img();
                }
                $parent_product = false;
                foreach($fields as $field)
                {
                    $tmp[$field] = $post->$field;
                }


                if($tid = get_post_thumbnail_id($post->ID))
                {
                    $props = wc_get_product_attachment_props( get_post_thumbnail_id($product_id), $post );
                    $thumb = get_the_post_thumbnail( $post->ID, 'shop_thumbnail', array(
                        'title'  => $props['title'],
                        'alt'    => $props['alt'],
                    ) );
                }
                $tmp['action'] = '<a href="'.get_edit_post_link($product_id).'">'.__('edit','openpos').'</a>';

                if($type == 'variation')
                {
                   $parent_id = $post->post_parent;
                    $parent_product = wc_get_product($parent_id);
                    if($tid = get_post_thumbnail_id($parent_id))
                    {
                        $props = wc_get_product_attachment_props( get_post_thumbnail_id($parent_id), $parent_product );
                        $thumb = get_the_post_thumbnail( $parent_id, 'shop_thumbnail', array(
                            'title'  => $props['title'],
                            'alt'    => $props['alt'],
                        ) );
                    }
                    $tmp['action'] = '<a href="'.get_edit_post_link($parent_id).'">'.__('edit','openpos').'</a>';

                }
                $tmp['action'] .= '<a href="'.admin_url( 'admin-ajax.php?action=print_barcode&id='.$product_id ).'" target="_blank" class="print-barcode-product-btn">Print Barcode</a>';
                $tmp['action'] = '<div class="action-row">'.$tmp['action'].'</div>';
                $tmp['regular_price'] = $_product->get_regular_price();
                $tmp['sale_price'] = $_product->get_sale_price();
                $price = $_product->get_price();
                $tmp['price'] = $price;
                $barcode = $this->core->getBarcode($product_id);
                $tmp['barcode'] = '<input type="text" name="barcode['.$product_id.']" class="form-control" disabled value="'.$barcode.'">';

                if(!$price)
                {
                    $price = 0;
                }
                $tmp['formatted_price'] = wc_price($price);
                $qty = $_product->get_stock_quantity();
                $manage_stock = $_product->get_manage_stock();
                if($manage_stock)
                {
                    $tmp['qty'] = '<div class="col-xs-6 pull-left"><input class="form-control"  disabled name="qty['.$product_id.']" type="number" value="'.$qty.'" /></div>';

                }else{
                    $tmp['qty'] = 'Unlimited';
                }
                $tmp['id'] = $product_id;

                $tmp['product_thumb'] = $thumb;

                $rows[] = $tmp;


            }


        }


        $result = array(
            'current' => $current,
            'rowCount' => $rowCount,
            'rows' => $rows,
            'total' => $total

        );
        echo json_encode($result);
        exit;
    }
    public function admin_style() {
        $allow_bootstrap = array('op-products','op-cashiers','op-transactions');
        $current_page = isset( $_REQUEST['page'])  ?  $_REQUEST['page']: false;
        if(in_array($current_page,$allow_bootstrap))
        {
            wp_enqueue_style('openpos.bootstrap', OPENPOS_URL.'/assets/css/bootstrap.min.css');
            wp_enqueue_script('openpos.bootstrap', OPENPOS_URL.'/assets/js/bootstrap.min.js','jquery');

        }

        wp_enqueue_style('openpos.admin-jquery.bootgrid', OPENPOS_URL.'/assets/css/jquery.bootgrid.min.css');
        wp_enqueue_script('openpos.admin-jquery.bootgrid', OPENPOS_URL.'/assets/js/jquery.bootgrid.min.js','jquery');

        wp_enqueue_style('openpos.admin', OPENPOS_URL.'/assets/css/admin.css');
        wp_enqueue_script('openpos.admin.js', OPENPOS_URL.'/assets/js/admin.js','jquery');
        $vars['ajax_url'] = admin_url('admin-ajax.php');
        wp_localize_script('openpos.admin.js', 'openpos_admin', $vars);
    }
    public function create_store_taxonomies()
    {
        $labels = array(
            'name'              => _x( 'Warehouses', 'taxonomy general name' ),
            'singular_name'     => _x( 'Warehouses', 'taxonomy singular name' ),
            'search_items'      => __( 'Search Warehouses' ),
            'all_items'         => __( 'All Warehouses' ),
            'parent_item'       => __( 'Parent Warehouses' ),
            'parent_item_colon' => __( 'Parent Warehouses:' ),
            'edit_item'         => __( 'Edit Warehouses' ),
            'update_item'       => __( 'Update Warehouses' ),
            'add_new_item'      => __( 'Add New Warehouses' ),
            'new_item_name'     => __( 'New Warehouses Name' ),
            'menu_name'         => __( 'POS - Warehouses' ),
        );

        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => false,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'pos_warehouse' ),
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => false
        );

        register_taxonomy( 'pos_warehouse', array( 'product' ), $args );
    }

    function add_store_setting_column($content,$column_name,$term_id){
//        $term= get_term($term_id, 'pos_warehouse');
//        switch ($column_name) {
//            case 'slug':
//                //do your stuff here with $term or $term_id
//                $content = 'test';
//                break;
//            default:
//                break;
//        }
        return $content;
    }

    function store_setting_column_header( $columns ){
        $columns['header_name'] = __( 'Action','openpos' );
        return $columns;
    }

    function store_setting_column_content( $value, $column_name, $tax_id ){

        $href = '';
        return '<a href="'.esc_url($href).'">'.__('Setting','openpos').'</a>';

    }

    function register_post_types()
    {
        register_post_type( 'op_transaction',
                array(
                    'labels'              => array(
                        'name'                  => __( 'Transactions', 'openpos' ),
                        'singular_name'         => __( 'Transaction', 'openpos' ),
                        'menu_name'             => _x( 'POS - Transactions', 'Admin menu name', 'openpos' ),
                        'add_new'               => __( 'Add Transaction', 'openpos' ),
                        'add_new_item'          => __( 'Add New Transaction', 'openpos' ),
                        'edit'                  => __( 'Edit', 'openpos' ),
                        'edit_item'             => __( 'Edit Transaction', 'openpos' ),
                        'new_item'              => __( 'New Transaction', 'openpos' ),
                        'view'                  => __( 'View Transactions', 'openpos' ),
                        'view_item'             => __( 'View Transaction', 'openpos' ),
                        'search_items'          => __( 'Search Transactions', 'openpos' ),
                        'not_found'             => __( 'No Transactions found', 'openpos' ),
                        'not_found_in_trash'    => __( 'No Transactions found in trash', 'openpos' ),
                        'parent'                => __( 'Parent Transactions', 'openpos' ),
                        'filter_items_list'     => __( 'Filter Transactions', 'openpos' ),
                        'items_list_navigation' => __( 'Transactions navigation', 'openpos' ),
                        'items_list'            => __( 'Transactions list', 'openpos' ),
                    ),
                    'description'         => __( 'This is where you can add new transaction that customers can use in your store.', 'openpos' ),
                    'public'              => false,
                    'show_ui'             => true,
                    'capability_type'     => 'op_transaction',
                    'map_meta_cap'        => true,
                    'publicly_queryable'  => false,
                    'exclude_from_search' => true,
                    'show_in_menu'        => current_user_can( 'manage_woocommerce' ) ? 'woocommerce' : true,
                    'hierarchical'        => false,
                    'rewrite'             => false,
                    'query_var'           => false,
                    'supports'            => array( 'title' ),
                    'show_in_nav_menus'   => false,
                    'show_in_admin_bar'   => true
                )

        );
    }

    public function admin_bar_menus( $wp_admin_bar ) {
        if ( ! is_admin() || ! is_user_logged_in() ) {
            return;
        }

        // Show only when the user is a member of this site, or they're a super admin.
        if ( ! is_user_member_of_blog() && ! is_super_admin() ) {
            return;
        }

        // Don't display when shop page is the same of the page on front.
        if ( !$this->settings_api->get_option('pos_page_id','openpos_basics') ) {
            return;
        }

        // Add an option to visit the store.
        $wp_admin_bar->add_node( array(
            'parent' => 'site-name',
            'id'     => 'view-pos',
            'target'     => '_blank',
            'title'  => __( 'Visit POS', 'woocommerce' ),
            'href'   => get_permalink( $this->settings_api->get_option('pos_page_id','openpos_basics') ),
        ) );
    }

    function pos_admin_menu() {
        add_menu_page( __( 'Open POS', 'openpos' ), __( 'POS', 'openpos' ),'manage_options','openpos-dasboard',array($this,'dashboard'),plugins_url('openpos/assets/images/pos.png'),58 );
        $page = add_submenu_page( 'openpos-dasboard', __( 'POS - Products', 'openpos' ),  __( 'Products', 'openpos' ) , 'manage_woocommerce', 'op-products', array( $this, 'products_page' ) );
        add_action( 'admin_print_styles-'. $page, array( &$this, 'admin_enqueue' ) );
        $page = add_submenu_page( 'openpos-dasboard', __( 'POS - Cashiers', 'openpos' ),  __( 'Cashiers', 'openpos' ) , 'manage_woocommerce', 'op-cashiers', array( $this, 'cashier_page' ) );
        add_action( 'admin_print_styles-'. $page, array( &$this, 'admin_enqueue' ) );

        $page = add_submenu_page( 'openpos-dasboard', __( 'POS - Transactions', 'openpos' ),  __( 'Transactions', 'openpos' ) , 'manage_woocommerce', 'op-transactions', array( $this, 'transactions_page' ) );
        add_action( 'admin_print_styles-'. $page, array( &$this, 'admin_enqueue' ) );
        //$page = add_submenu_page( 'openpos-dasboard', __( 'POS - Warehouses', 'openpos' ),  __( 'Warehouses', 'openpos' ) , 'manage_woocommerce', 'op-warehouses', array( $this, 'products_page' ) );
        $page = add_submenu_page( 'openpos-dasboard', __( 'POS - Setting', 'openpos' ),  __( 'Setting', 'openpos' ) , 'manage_woocommerce', 'op-setting', array( $this, 'setting_page' ) );

    }
    function products_page() {
        require(OPENPOS_DIR.'templates/admin/products.php');
    }
    public function dashboard()
    {
        require(OPENPOS_DIR.'templates/admin/dashboard.php');
    }
    public function cashier_page()
    {
        require(OPENPOS_DIR.'templates/admin/cashier.php');
    }
    public function transactions_page()
    {
        require(OPENPOS_DIR.'templates/admin/transactions.php');
    }
    public function setting_page()
    {
        echo '<div class="wrap">';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        echo '</div>';
    }
    public function admin_enqueue() {
        
    }

    public function getUsers(){



        $rows = array();
        $current = isset($_REQUEST['current']) ? $_REQUEST['current'] : 1;
        $sort  = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : false;
        $searchPhrase  = $_REQUEST['searchPhrase'] ? $_REQUEST['searchPhrase'] : false;
        $sortBy = 'date';
        $order = 'DESC';
        if($sort)
        {
            $sortBy = end(array_keys($sort));
            if($sortBy == 'id')
            {
                $sortBy = 'ID';
            }
            $order = end($sort);
        }


        $rowCount = $_REQUEST['rowCount'] ? $_REQUEST['rowCount'] : get_option( 'posts_per_page' );
        $offet = ($current -1) * $rowCount;


        $args = array(
            'count_total' => true,
            'number'   => $rowCount,
            'offset'           => $offet,
            'orderby'          => $sortBy,
            'order'            => $order,
            'fields' => array('ID', 'display_name','user_email','user_login','user_status' )
        );
        if($searchPhrase)
        {
            $args['search'] = $searchPhrase;
        }

        $user_query = new WP_User_Query( $args );


        $users = get_users( $args);
        $total = $user_query->total_users;

        foreach($users as $user)
        {
            $tmp = (array)$user;
            $allow_pos = get_user_meta($tmp['ID'],'_op_allow_pos',true);
            if(!$allow_pos)
            {
                $allow_pos = 0;
            }else{
                $allow_pos = 1;
            }
            //$tmp['allow_post'] = $allow_pos;
            $tmp['id'] = (int)$tmp['ID'];
            unset($tmp['ID']);
            if($allow_pos)
            {
                $tmp['allow_pos'] = '<select type="text" name="_op_allow_pos['.$tmp['id'].']" class="form-control _op_allow_pos" disabled><option value="0">No</option><option value="1" selected>Yes</option></select>';
            }else{
                $tmp['allow_pos'] = '<select  type="text" name="_op_allow_pos['.$tmp['id'].']" class="form-control _op_allow_pos" disabled><option value="0" selected>No</option><option value="1">Yes</option></select>';
            }

            $rows[] = $tmp;
        }


        $result = array(
            'current' => $current,
            'rowCount' => $rowCount,
            'rows' => $rows,
            'total' => $total

        );
        echo json_encode($result);
        exit;
    }

    public function save_cashier(){
        $data = $_REQUEST['_op_allow_pos'];
        foreach($data as $user_id => $value)
        {
            update_user_meta($user_id,'_op_allow_pos',$value);
        }
        exit;
    }

    public function print_bacode(){
        if(!isset($_POST['product_id']))
        {
            require(OPENPOS_DIR.'templates/admin/print_barcode.php');
        }else{
            require(OPENPOS_DIR.'templates/admin/print_barcode_paper.php');
        }
        
        exit;
    }
}