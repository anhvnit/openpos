/**
 * Created by anhvnit on 6/18/17.
 */
(function($) {
    function renderProduct(item)
    {
        var html_template = $('#product-list-content-template').html();
        var template = _.template(html_template);
        var compiled = template({product: item});
        $('#pos-products .products-container').append(compiled);

    }
    $(document).on('op_after_db_added',function(event,db,table){

        var q = db.from(table);
        q.list(24).done(function(products){
            $.each(products,function(){
                var product = $(this);
                renderProduct(product[0]);
            });

        });

    })
}(jQuery));