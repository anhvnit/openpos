<?php
/**
 * Created by PhpStorm.
 * User: anhvnit
 * Date: 6/10/17
 * Time: 22:11
 */
class Op_Front{
    private $settings_api;
    private $_core;
    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        global $_OP_SETTING;
        global $_OP_CORE;
        $this->settings_api = $_OP_SETTING;
        $this->_core = $_OP_CORE;
        add_action( 'wp_ajax_nopriv_op_api', array($this,'getApi') );
        add_action( 'wp_ajax_op_api', array($this,'getApi') );
        //
    }

    public function getApi(){
        //secure implement
        ob_start();
        header('Content-Type: application/json');
        $result = array('status' => 0, 'message' => '','data' => array());
        $api_action = isset($_REQUEST['api_action']) ? $_REQUEST['api_action'] : '';
        switch ($api_action)
        {
            case 'login':
                if($login = $this->login())
                {
                    $result = $login;
                }
                break;
            case 'products':
                $result = $this->getProducts();
                break;
            case 'orders':
                break;
            case 'add_order':
                break;
            case 'update_order':
                break;
            case 'search_customer':
                break;
            case 'add_customer':
                break;
            case 'add_transaction':
                break;
            case 'transactions':
                break;
        }
        echo json_encode($result);
        exit;
    }

    public function getProducts()
    {
        $result = array('status' => 1, 'message' => '','data' => array());
        $page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;

        $rowCount = 100;
        $current = $page;
        $offet = ($current -1) * $rowCount;
        $sortBy = 'date';
        $order = 'DESC';

        $args = array(
            'posts_per_page'   => $rowCount,
            'offset'           => $offet,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => $sortBy,
            'order'            => $order,
            'post_type'        => array('product','product_variation'),
            'post_status'      => 'publish',
            'suppress_filters' => false
        );
        $products = $this->_core->getProducts($args);
        $data = array('total' => $products['total'],'page' => $current);

        foreach($products['posts'] as $_product)
        {

            $product = wc_get_product($_product->ID);
            if(!$product)
            {

                continue;
            }
            $image =  wc_placeholder_img_src() ;
            if ( has_post_thumbnail( $product->get_id() ) ) {
                $attachment_id =  get_post_thumbnail_id( $product->get_id() );
                $size = 'shop_thumbnail';
                $image_attr = wp_get_attachment_image_src($attachment_id, $size);

                if(is_array($image_attr))
                {
                    $image = $image_attr[0];
                }
            }

            $type = $product->get_type();
            $options = array();
            $group = array();
            switch ($type)
            {

                case 'grouped':
                    $group = $product->get_children();
                    break;
            }
            $tmp = array(
                'name' => $product->get_name(),
                'id' => $product->get_id(),
                'sku' => $product->get_sku(),
                'qty' => $product->get_stock_quantity(),
                'stock_status' => $product->get_stock_status(),
                'barcode' => $product->get_id(),
                'image' => $image,
                'price' => $product->get_price(),
                'special_price' => $product->get_sale_price(),
                'regular_price' => $product->get_regular_price(),
                'sale_from' => $product->get_date_on_sale_from(),
                'sale_to' => $product->get_date_on_sale_to(),
                'status' => $product->get_status(),
                'categories' => '',//$product->get_category_ids(),
                'tax' => $product->get_tax_class(),
                'group_items' => $group,
                'options' => $options
            );
            if(!$tmp['price'])
            {
                continue;
            }
            $data['product'][] = $tmp;

        }

        $result['data'] = $data;
        return $result;
    }
    
    public function getSetting(){
        
        $sections = $this->settings_api->get_fields();
        $setting = array();
        foreach($sections as $section => $fields)
        {
            foreach($fields as $field)
            {
                $option = $field['name'];
                $setting[$option] = $this->settings_api->get_option($option,$section);
            }
        }
        return $setting;
    }

    public function login(){
        $user_name =  isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : '';
        $password =  isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
        if(!$user_name || !$password)
        {
            return false;
        }
        $result = array('status' => 0, 'message' => '','data' => array());
        $creds = array(
            'user_login'    => $user_name,
            'user_password' => $password,
            'remember'      => false
        );
        $user = wp_signon( $creds, false );

        if ( is_wp_error( $user ) ) {
            $result['message'] =  $user->get_error_message();
        }else{
            $id = $user->ID;
            $user->pos_setting = $this->getSetting();
            $allow_pos = get_user_meta($id,'_op_allow_pos',true);
            if($allow_pos)
            {
                $result['status'] = 1;
            }
            $result['data'] = $user;
        }
        return $result;
    }

}