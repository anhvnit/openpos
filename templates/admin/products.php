<div class="wrap">
    <h1>POS Products</h1>
    <form id="op-product-list">
        <table id="grid-selection" class="table table-condensed table-hover table-striped op-product-grid">
            <thead>
            <tr>
                <th data-column-id="id" data-identifier="true" data-type="numeric">ID</th>
                <th data-column-id="barcode" data-identifier="true" data-type="numeric">Barcode</th>
                <th data-column-id="product_thumb" data-sortable="false">Thumbnail</th>
                <th data-column-id="post_title" data-sortable="false">Product Name</th>
                <th data-column-id="price" data-sortable="false">Price</th>
                <th data-column-id="qty" data-type="numeric" data-sortable="false" data-order="desc">Qty</th>
                <th data-column-id="action"  data-sortable="false">Action</th>
            </tr>
            </thead>
        </table>
    </form>
    <br class="clear">
</div>


<script type="text/javascript">
    (function($) {
        "use strict";
       var grid = $("#grid-selection").bootgrid({
            ajax: true,
            post: function ()
            {
                /* To accumulate custom parameter with the request object */
                return {
                    action: "op_products"
                };
            },
            url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
            selection: true,
            multiSelect: true,
            formatters: {
                "link": function(column, row)
                {
                    return "<a href=\"#\">" + column.id + ": " + row.id + "</a>";
                },
                "price": function(column,row){

                    return row.formatted_price;
                }
            },
            templates: {
                header: "<div id=\"{{ctx.id}}\" class=\"{{css.header}}\"><div class=\"row\"><div class=\"col-sm-12 actionBar\"><p class=\"{{css.search}}\"></p><p class=\"{{css.actions}}\"></p><button type=\"button\" class=\"btn vna-action btn-default\" data-action=\"save\"><span class=\" icon glyphicon glyphicon-floppy-save\"></span></button><button type=\"button\" class=\"btn vna-action btn-default\" data-action=\"delete\"><span class=\" icon glyphicon glyphicon-trash\"></span></button></div></div></div>"
            }
        }).on("initialized.rs.jquery.bootgrid",function(){
            console.log('xx');
        }).on("selected.rs.jquery.bootgrid", function(e, rows)
        {
            var rowIds = [];
            for (var i = 0; i < rows.length; i++)
            {
                rowIds.push(rows[i].id);
                if($('input[name="barcode['+rows[i].id+']"]'))
                {
                    $('input[name="barcode['+rows[i].id+']"]').prop('disabled',false);
                }
                if($('input[name="qty['+rows[i].id+']"]'))
                {
                    $('input[name="qty['+rows[i].id+']"]').prop('disabled',false);
                }
            }
        
           // alert("xxSelect: " + rowIds.join(","));
        }).on("deselected.rs.jquery.bootgrid", function(e, rows)
        {
            var rowIds = [];
            for (var i = 0; i < rows.length; i++)
            {
                rowIds.push(rows[i].id);
                if($('input[name="barcode['+rows[i].id+']"]'))
                {
                    $('input[name="barcode['+rows[i].id+']"]').prop('disabled',true);
                }
                if($('input[name="qty['+rows[i].id+']"]'))
                {
                    $('input[name="qty['+rows[i].id+']"]').prop('disabled',true);
                }
            }
            //alert("Deselect: " + rowIds.join(","));
        });
        $('.vna-action').click(function(){
            var selected = $("#grid-selection").find('input[type="checkbox"]:checked');
            var action = $(this).data('action');
            if(selected.length == 0)
            {
                alert('Please choose row to continue.');
            }else{
                alert(action);
            }

        });
    })( jQuery );
</script>

<style>
    .action-row a{
        display: block;
        padding: 3px 4px;
        text-decoration: none;
        border: solid 1px #ccc;
        text-align: center;
        margin: 5px;
    }
    .op-product-grid td{
        vertical-align: middle!important;
    }
</style>