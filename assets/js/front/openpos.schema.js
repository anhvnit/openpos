/**
 * Created by anhvnit on 6/14/17.
 */
(function($) {
    console.log(op.schema_version);
    var version = parseInt(op.schema_version);
    var schema = {
        version: version,
        autoSchema: false, // must be false when version is defined
        stores: [{
            name: 'setting',
            keyPath: 'setting_key', // optional,
            autoIncrement: true, // optional.
            indexes: [
                {
                    name: 'setting_key'
                }
            ]
        },{
            name: 'product',
            keyPath: 'id', // optional,
            autoIncrement: true, // optional.
            indexes: [
                {
                    name: 'barcode', // optional
                }, {
                    name: 'name'
                }, {
                    name: 'category_id',
                    multiEntry: true
                }, {
                    name: 'sku'
                }, {
                    name: 'status'
                }
            ], // optional, list of index schema as array.
            /*Sync: {
                format: 'gcs',
                Options: {
                    bucket: 'ydn-data1',
                    prefix: 'author/'
                }
            }*/
        },
        {
            name: 'category',
            keyPath: 'category_id', // optional,
            autoIncrement: true, // optional.
            indexes: [
                {
                    name: 'name'
                }
            ]
        }
        ] /*,
        fullTextCatalogs: {
            name: 'author-name',
            sources: [{
                storeName: 'author',
                keyPath: 'first',
                weight: 1.0
            }, {
                storeName: 'author',
                keyPath: 'last',
                weight: 0.8
            }]
        } */
    };
    var db = new ydn.db.Storage('woocommerce.openpos', schema);

    //start push data
    var page = 1;
    $.ajax({
        url: op.ajax_url,
        type: 'post',
        dataType: 'json',
        data:{action:'op_data',page:page},
        success: function(response){

            if(response.status)
            {
                var table = 'product';
                db.addAll(table,response.data);
                $( document ).trigger( "op_after_db_added", [  db,table] );
            }


        }
    });

    //end push data

}(jQuery));