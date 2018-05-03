<?php
global $_OP_SETTING;

$sheet_width = $_OP_SETTING->get_option('sheet_width','openpos_label');
$sheet_height = $_OP_SETTING->get_option('sheet_height','openpos_label');
$sheet_padding_top = $_OP_SETTING->get_option('sheet_margin_top','openpos_label');
$sheet_padding_right = $_OP_SETTING->get_option('sheet_margin_right','openpos_label');
$sheet_padding_bottom = $_OP_SETTING->get_option('sheet_margin_bottom','openpos_label');
$sheet_padding_left = $_OP_SETTING->get_option('sheet_margin_left','openpos_label');
$vertical_space = $_OP_SETTING->get_option('sheet_vertical_space','openpos_label');
$horizontal_space = $_OP_SETTING->get_option('sheet_horizontal_space','openpos_label');
$label_width = $_OP_SETTING->get_option('barcode_label_width','openpos_label');
$label_height = $_OP_SETTING->get_option('barcode_label_height','openpos_label');

$label_padding_top = $_OP_SETTING->get_option('barcode_label_padding_top','openpos_label');
$label_padding_right = $_OP_SETTING->get_option('barcode_label_padding_right','openpos_label');
$label_padding_bottom = $_OP_SETTING->get_option('barcode_label_padding_bottom','openpos_label');
$label_padding_left = $_OP_SETTING->get_option('barcode_label_padding_left','openpos_label');

$barcode_width = $_OP_SETTING->get_option('barcode_width','openpos_label');
$barcode_height = $_OP_SETTING->get_option('barcode_height','openpos_label');



$unit = $_OP_SETTING->get_option('sheet_width','unit');

?>
<head>
    <title>Sheet Print Information</title>
</head>
<body>
<div class="">
    <form method="post">
        <input type="hidden" name="product_id" value="<?php echo $_GET['id']; ?>">
        <div class="form-row" style="width: 800px;margin: 0 auto;">
            <table>
                <tr>
                    <th>Unit:</th>
                    <td>
                        <select name="unit">
                            <option value="in" <?php echo ($unit == 'in')? 'selected':''; ?>>Inch</option>
                            <option value="mm" <?php echo ($unit == 'mm')? 'selected':''; ?>>Minimeter</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Sheet Width:</th>
                    <td><input type="text" value="<?php echo $sheet_width; ?>" name="sheet_width"> x <input name="sheet_height" type="number" value="<?php echo $sheet_height; ?>"></td>
                </tr>
                <tr>
                    <th>Vertical Spacing:</th>
                    <td><input type="text" name="sheet_vertical_space" value="<?php echo $vertical_space; ?>"></td>
                </tr>
                <tr>
                    <th>Horizontal Spacing:</th>
                    <td><input type="text" name="sheet_horisontal_space"  value="<?php echo $horizontal_space; ?>"></td>
                </tr>


                <tr>
                    <th>Sheet Margin (top x right x bottom x left):</th>
                    <td>
                        <input type="text" name="sheet_margin_top"   value="<?php echo $sheet_padding_top; ?>"> x
                        <input type="text" name="sheet_margin_right" value="<?php echo $sheet_padding_right; ?>"> x
                        <input type="text" name="sheet_margin_bottom" value="<?php echo $sheet_padding_bottom; ?>"> x
                        <input type="text" name="sheet_margin_left" value="<?php echo $sheet_padding_left; ?>">
                    </td>
                </tr>



                <tr>
                    <th>Label Size:</th>
                    <td>
                        <input type="text" name="label_width" value="<?php echo $label_width; ?>"> x <input name="label_height" type="text" value="<?php echo $label_height; ?>">
                    </td>
                </tr>
                <tr>
                    <th>Label Padding (top x right x bottom x left):</th>
                    <td>
                        <input type="text" name="label_margin_top"   value="<?php echo $label_padding_top; ?>"> x
                        <input type="text" name="label_margin_right" value="<?php echo $label_padding_right; ?>"> x
                        <input type="text" name="label_margin_bottom" value="<?php echo $label_padding_bottom; ?>"> x
                        <input type="text" name="label_margin_left" value="<?php echo $label_padding_left; ?>">
                    </td>
                </tr>

                <tr>
                    <th>Barcode Image Size:</th>
                    <td>
                        <input type="text" name="barcode_width" value="<?php echo $barcode_width; ?>"> x <input name="barcode_height" type="text" value="<?php echo $barcode_height; ?>">
                    </td>
                </tr>

                <tr>
                    <th>Number Of Label:</th>
                    <td><input type="number" name="total" value="30"></td>
                </tr>
                <tr>
                    <th></th>
                    <td><button type="submit" name="print" style="
    border: solid 1px #000;
    padding:  5px 7px;
    text-transform: uppercase;
    margin-top: 15px;
    background:  #000;
    color: #fff;">Print</button></td>
                </tr>
            </table>

        </div>
    </form>
</div>
<style>
    form{
        margin-top: 100px;
    }
    form input{
        width: 100px;
        text-align: right;
        padding: 5px 2px;
    }
    .form-row th{
        text-align: left;
    }
</style>
</body>