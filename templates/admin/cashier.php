<div class="wrap">
    <h1>POS Products</h1>
    <form id="op-product-list">
        <table id="grid-selection" class="table table-condensed table-hover table-striped op-product-grid">
            <thead>
            <tr>
                <th data-column-id="id" data-type="numeric" data-identifier="true" >ID</th>
                <th data-column-id="user_login" data-sortable="false">Name</th>
                <th data-column-id="user_email" data-sortable="false">Email</th>
                <th data-column-id="allow_pos" data-sortable="false">Allow POS</th>
<!--                <th data-column-id="action"  data-sortable="false">Action</th>-->
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
                    action: "op_cashier"
                };
            },
            url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
            selection: true,
            multiSelect: true,
            templates: {
                header: "<div id=\"{{ctx.id}}\" class=\"{{css.header}}\"><div class=\"row\"><div class=\"col-sm-12 actionBar\"><p class=\"{{css.search}}\"></p><p class=\"{{css.actions}}\"></p><button type=\"button\" class=\"btn vna-action btn-default\" data-action=\"save\"><span class=\" icon glyphicon glyphicon-floppy-save\"></span></button></div></div></div>"
            }
        }).on("selected.rs.jquery.bootgrid", function(e, rows)
        {

            var rowIds = [];
            for (var i = 0; i < rows.length; i++)
            {
                rowIds.push(rows[i].id);

                if($('select[name="_op_allow_pos['+rows[i].id+']"]'))
                {
                    $('select[name="_op_allow_pos['+rows[i].id+']"]').prop('disabled',false);
                }

            }

        }).on("deselected.rs.jquery.bootgrid", function(e, rows)
        {
            var rowIds = [];
            for (var i = 0; i < rows.length; i++)
            {
                rowIds.push(rows[i].id);
                if($('select[name="_op_allow_pos['+rows[i].id+']"]'))
                {
                    $('select[name="_op_allow_pos['+rows[i].id+']"]').prop('disabled',true);
                }
            }

        });


        $('.vna-action').click(function(){
            var selected = $("#grid-selection").bootgrid("getSelectedRows");
            var action = $(this).data('action');
            if(selected.length == 0)
            {
                alert('Please choose row to continue.');
            }else{

                var serialized_rows = $('select._op_allow_pos').serialize();
                $.ajax({
                    url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                    type: 'post',
                    dataType: 'json',
                    data: 'action=save_cashier&'+serialized_rows,
                    success:function(){
                        console.log('saved');
                    }
                });
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