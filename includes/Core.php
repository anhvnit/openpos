<?php

/**
 * Created by PhpStorm.
 * User: anhvnit
 * Date: 3/12/18
 * Time: 10:10
 */
class Op_Core
{
    public function __construct()
    {
        // check license
        //check requirement ....

    }
    public function getProducts($args = array())
    {
        $ignores = $this->getAllVariableProducts();
        $args['post_type'] = array('product','product_variation');
        $args['exclude'] = $ignores;
        $args['post_status'] = 'publish';
        $args['suppress_filters'] = false;

        $defaults = array(
            'numberposts' => 5,
            'category' => 0, 'orderby' => 'date',
            'order' => 'DESC', 'include' => array(),
            'exclude' => array(), 'meta_key' => '',
            'meta_value' =>'', 'post_type' => 'product',
            'suppress_filters' => true
        );
        $r = wp_parse_args( $args, $defaults );
        if ( empty( $r['post_status'] ) )
            $r['post_status'] = ( 'attachment' == $r['post_type'] ) ? 'inherit' : 'publish';
        if ( ! empty($r['numberposts']) && empty($r['posts_per_page']) )
            $r['posts_per_page'] = $r['numberposts'];
        if ( ! empty($r['category']) )
            $r['cat'] = $r['category'];
        if ( ! empty($r['include']) ) {
            $incposts = wp_parse_id_list( $r['include'] );
            $r['posts_per_page'] = count($incposts);  // only the number of posts included
            $r['post__in'] = $incposts;
        } elseif ( ! empty($r['exclude']) )
            $r['post__not_in'] = wp_parse_id_list( $r['exclude'] );

        $r['ignore_sticky_posts'] = false;
        $get_posts = new WP_Query($r);
        return array('total'=>$get_posts->found_posts,'posts' => $get_posts->get_posts());
    }
    public function getAllVariableProducts()
    {
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => array('product_variation'),
            'post_status'      => 'publish'
        );
        $posts_array = get_posts($args);
        $result = array();
        foreach($posts_array as $post)
        {
            $parent_id =  $post->post_parent;
            if($parent_id)
            {
                $result[] = $parent_id;
            }
        }
        $arr = array_unique($result);
        $result = array_values($arr);
        return $result;
    }

    public function getBarcode($productId)
    {
        $barcode = get_post_meta($productId,'_op_barcode',true);
        if($barcode)
        {
            return $barcode;
        }
        $format = '00000000000';
        $id_leng = strlen($productId);
        if($id_leng < 11)
        {
            $format = substr($format,0,(11 - $id_leng));
            $barcode = $format.$productId;
        }else{
           return $productId;
        }
        return $barcode;
    }

}