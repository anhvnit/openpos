<?php
    global $_OP_SETTING;
    global $_OP_CORE;
    global $barcode_generator;
    //sheet
    $sheet_width = $_REQUEST['sheet_width'];
    $sheet_height = $_REQUEST['sheet_height'];
    $sheet_padding_top = $_REQUEST['sheet_margin_top'];
    $sheet_padding_right = $_REQUEST['sheet_margin_right'];
    $sheet_padding_bottom = $_REQUEST['sheet_margin_bottom'];
    $sheet_padding_left = $_REQUEST['sheet_margin_left'];
    $vertical_space = $_REQUEST['sheet_vertical_space'];
    $horizontal_space = $_REQUEST['sheet_horisontal_space'];

    //label
    $label_width = $_REQUEST['label_width'];
    $label_height = $_REQUEST['label_height'];
    $label_margin_top = $_REQUEST['label_margin_top'];
    $label_margin_right = $_REQUEST['label_margin_right'];
    $label_margin_bottom = $_REQUEST['label_margin_bottom'];
    $label_margin_left = $_REQUEST['label_margin_left'];

    $barcode_width = $_REQUEST['barcode_width'];
    $barcode_height = $_REQUEST['barcode_height'];
    //other
    $unit = $_REQUEST['unit'];
    $total = $_REQUEST['total'];
    //calc

    $sheet_space_width = $sheet_width - $sheet_padding_left - $sheet_padding_right + $horizontal_space ;
    $sheet_space_height = $sheet_height - $sheet_padding_top - $sheet_padding_bottom + $vertical_space ;
    $columns = ceil($sheet_space_width / ($label_width + $horizontal_space));
    $rows = ceil($sheet_space_height / ($label_height + $vertical_space));

    $truth_label_width = ($sheet_space_width  / $columns )  ;
    $truth_label_height = ($sheet_space_height  / $rows );

    $label_per_sheet = $rows * $columns;
    $page = ceil($total / $label_per_sheet);
    $count = 0;
    $barcode_mode = $_OP_SETTING->get_option('barcode_mode','openpos_label');
    $mode = $barcode_generator::TYPE_CODE_128;
    switch ($barcode_mode)
    {
        case 'ean_reader':
            $mode = $barcode_generator::TYPE_CODE_128;
            break;
        case 'ean_8_reader':
            $mode = $barcode_generator::TYPE_EAN_8;
            break;
        case 'code_39_reader':
            $mode = $barcode_generator::TYPE_CODE_39;
            break;
        case 'upc_reader':
            $mode = $barcode_generator::TYPE_UPC_A;
            break;
        case 'upc_e_reader':
            $mode = $barcode_generator::TYPE_UPC_E;
            break;
        default:
            $mode = $barcode_generator::TYPE_CODE_128;
    }
    $barcode = $_OP_CORE->getBarcode((int)$_REQUEST['product_id']);
?>
<?php ob_start(); ?>
<body style="background-color: transparent;padding:0;margin:0;">
    <?php for($k = 1;$k <= $page;$k++): ?>
    <div style="width: <?php echo $sheet_width.$unit;?>;height:<?php echo $sheet_height.$unit; ?>; display: block; overflow: hidden; background-color: transparent;" class="sheet">
        <div style="display: block; overflow: hidden;background-color: transparent;">
        <?php for($i = 0; $i < $rows; $i++): ?>
            <div class="label-row" style="margin-bottom: <?php echo ($i != ($rows - 1)) ? $vertical_space.$unit:0;?>; display: block;width: 100%;">
                <?php for($j = 0; $j < $columns; $j++): $count++; ?>
                    <div class="label"  style=" text-align: center; width: <?php echo $truth_label_width.$unit; ?>;height: <?php echo $truth_label_height.$unit; ?>; display: inline-block;overflow: hidden; <?php echo ($j != ($columns - 1))? 'margin-right:'.$horizontal_space.$unit:'';?> " >
                        <img src="data:image/png;base64, <?php echo base64_encode($barcode_generator->getBarcode($barcode, $mode)) ; ?>" style="max-width:<?php echo ($truth_label_width - $label_margin_right - $label_margin_left).$unit; ?>;max-height:<?php echo ($truth_label_height - $label_margin_top - $label_margin_bottom).$unit; ?>;width: <?php echo $barcode_width.$unit; ?>;height:<?php echo $barcode_height.$unit; ?>">
                    </div>
                <?php if($count == $total){ break; }  endfor; ?>
            </div>
        <?php if($count == $total){ break; }  endfor; ?>
        </div>
    </div>
    <?php if($k != $page): ?>
            <div class="pagebreak"> </div>
    <?php endif; ?>
    <?php if($count == $total){ break; }  endfor; ?>
</body>
<?php
$out2 = ob_get_contents();

ob_end_clean();
$buffer = preg_replace('/\s+/', ' ', $out2);


$search = array(
    '/\>[^\S ]+/s',
    '/[^\S ]+\</s',
    '/(\s)+/s'
);
$replace = array(
    '>',
    '<',
    '\\1'
);
if (preg_match("/\<html/i",$buffer) == 1 && preg_match("/\<\/html\>/i",$buffer) == 1) {
    $buffer = preg_replace($search, $replace, $buffer);
}
$buffer = str_replace('> <', '><', $buffer);
?>
<html>
<head>
    <title>barcode</title>
    <script type="application/javascript">
        window.print();
    </script>
    <style media="print">
        @page {
            size: <?php echo $sheet_width.$unit;?> <?php echo $sheet_height.$unit; ?>;
            padding:0;
            padding:  <?php echo $sheet_padding_top.$unit.' '.$sheet_padding_right.$unit.' '.$sheet_padding_bottom.$unit.' '.$sheet_padding_left.$unit; ?>;

        }
        .sheet{
            width: 100%;
        }
        .pagebreak { page-break-before: always; }
    </style>
</head>
<?php echo $buffer; ?>
</html>
