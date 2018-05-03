<?php
/*
Plugin Name: OpenPos
Plugin URI: http://openswatch.com
Description: Quick POS system for woocommerce.
Author: anhvnit@gmail.com
Author URI: http://openswatch.com/
Version: 1.0
Text Domain: openpos
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
define('OPENPOS_DIR',plugin_dir_path(__FILE__));
define('OPENPOS_URL',plugins_url('openpos'));
define('DS','/');

global $_OP_SETTING;
global $_OP_CORE;

//global $_OP_ADMIN;

require(OPENPOS_DIR.'vendor/autoload.php');

global $barcode_generator;
$barcode_generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

if(!class_exists('Op_Settings'))
{
    require_once( OPENPOS_DIR.'includes/admin/Settings.php' );
    $_OP_SETTING = new Op_Settings();
}
if(!class_exists('Op_Core'))
{
    require_once( OPENPOS_DIR.'includes/Core.php' );
    $_OP_CORE = new Op_Core();
   // print_r($_OP_CORE->getProducts());die;
}
if(!class_exists('Op_Admin'))
{
    require_once( OPENPOS_DIR.'includes/admin/Admin.php' );
}
$tmp = new Op_Admin();
$tmp->init();

if(!class_exists('Op_Front'))
{
    require_once( OPENPOS_DIR.'includes/front/Front.php' );
}
$tmp = new Op_Front();