/**
 * Created by anhvnit on 6/24/17.
 */
var app = angular.module('openPos', []);
app.value('op',op);
app.controller('uiBuilder', function($scope,$rootScope,$interval) {
    var version = parseInt(op.schema_version);
    var posResize = function(){
        //left
        var w_height =$(window).height();
        var left_tab_offset = $('.left-content .nav-tabs').offset();
        var left_tab_height = $('.left-content .nav-tabs').height();
        $('.left-content .tab-content').css('height',(w_height - left_tab_height - left_tab_offset.top -2)+'px');

        //right
        var right_tab_offset = $('.right-content .nav-tabs').offset();
        var right_tab_height = $('.right-content .nav-tabs').height();
        $('.right-content .tab-content').css('height',(w_height - right_tab_height - right_tab_offset.top -2)+'px');
    }
    posResize();

    $scope.fullScreen = function(){
        alert('full screent');
    };
    $scope.posClock = '--:--:--:--';
    var Clock = function(){
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = (parseInt(m )< 10) ? '0'+m : m;
        s = (parseInt(s )< 10) ? '0'+s : s;

        var str =
            h + ":" + m + ":" + s;
        $scope.posClock = str;
    }
    $interval(Clock, 500);


    var loadSchema = function(){
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
                        name: 'product_id', // optional
                    },
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
                ]
            },{
                    name: 'category',
                    keyPath: 'category_id', // optional,
                    autoIncrement: true, // optional.
                    indexes: [
                        {
                            name: 'name'
                        }
                    ]
            },{
                name: 'cart',
                keyPath: 'created_at',
                autoIncrement: true, // optional.
                indexes: [
                    {
                        name: 'created_at'
                    },{
                        name: 'customer_name'
                    },{
                        name: 'qty'
                    }
                ]
            }
            ]
        };
        var db = new ydn.db.Storage('woocommerce.openpos', schema);
        $scope.db = db;
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
                    //$( document ).trigger( "op_after_db_added", [  db,table] );
                    $scope.$broadcast('op_after_db_added', [  db,table]);
                }
            }
        });
        //end push data
    }
    loadSchema();

});

app.controller('productsUi',function($scope,$rootScope){
    //console.log($scope.db);
    var db = $scope.db;
    var product_per_page = 6;
    var product_table = 'product';
    var cart_table = 'cart';
    var setting_table = 'setting';
    $scope.products = [];
    $scope.page_products = [];
    $scope.pages = [];
    $scope.min = 0;
    $scope.max = 0;
    $scope.pos_current_product_page = 0;

    var listProduct = function(next){
        var table = 'product';
        var start = 0;


        if(next)
        {
            max = $scope.max;
            var key_range = ydn.db.KeyRange.lowerBound(max,true);
        }else{
            if($scope.pos_current_product_page == 0)
            {
                min =  $scope.page_products[$scope.pos_current_product_page].min;
                max =  $scope.page_products[$scope.pos_current_product_page].max;
            }else{
                min =  $scope.page_products[$scope.pos_current_product_page - 1].min;
                max =  $scope.page_products[$scope.pos_current_product_page - 1].max;
            }

            var key_range = ydn.db.KeyRange.bound(min,max);
        }

        db.values(table, key_range,product_per_page ).done(function(values) {
            if(values.length > 0 && $scope.pos_current_product_page >= 0 )
            {
                $scope.page_products[$scope.pos_current_product_page] = new Array();
                $scope.products = [];
                $scope.min = 0;
                $scope.max = 0;
                $.each(values,function(){
                    var value = $(this);
                    var product = value[0];
                    if($scope.min == 0)
                    {
                        $scope.min = product.id;
                    }
                    if($scope.min > product.id)
                    {
                        $scope.min = product.id;
                    }

                    if($scope.max < product.id)
                    {
                        $scope.max = product.id;
                    }
                    $scope.products.push(product);

                });

                if(next)
                {
                    $scope.page_products[$scope.pos_current_product_page] = {min: $scope.min, max: $scope.max};
                    if(values.length == product_per_page)
                    {
                        $scope.pos_current_product_page ++;
                    }

                }

            }else{
                $('.left-content').removeClass('loading');
            }
            if(!next)
            {
                if($scope.pos_current_product_page > 0)
                {
                    $scope.pos_current_product_page --;
                }
            }


        }, function(e) {
            throw e;
        });

    };


    $scope.$on('op_after_db_added', function(event, args) {
        $('.left-content').addClass('loading');
        listProduct(true);
    });
    $scope.nextProducts = function(current_page){
        $('.left-content').addClass('loading');
        listProduct(true);
    };
    $scope.backProducts = function(current_page){
        $('.left-content').addClass('loading');
        listProduct(false);
    }

    $scope.$watch("products", function (value) {//I change here
        $('.left-content').removeClass('loading');
    });

    $scope.addToCart = function(productId){

        db.get(product_table,productId).done(function(product){
            var hasOption = false;
            var hasVariation = false;
            if(product.type == 'simple' && !hasOption)
            {
                var created_at = new Date().getTime();
                var data = {
                    'qty' : 1,
                    'added_at' : created_at,
                    'product_name': product.name,
                    'product_id': product.id,
                    'product_sku': product.sku,
                    'product_price': product.price,
                    'discount_amount': 0,
                    'sub_total': product.price,
                    'options': [],
                    'variations': []
                };
                if($rootScope.current_cart == 0)
                {
                    $rootScope.$emit("createNewCart", {});
                }
                db.get(cart_table, $rootScope.current_cart).done(function(cart){
                    if(cart.qty)
                    {
                        cart.qty = parseInt(cart.qty) + parseInt(data.qty);
                    }else{
                        cart.qty = parseInt(data.qty);
                    }

                   if(cart.products)
                   {
                       cart.products.push(data);
                   }else{
                       cart.products = [data];
                   }

                    db.put(cart_table, cart).done(function(){
                        $rootScope.$emit("RefreshCurrentCart", {});
                    }).fail(function(e) {
                        console.error(e);
                    });
                }).fail(function(e) {
                    console.error(e);
                });
            }

        });
    };

});

app.controller('cartUi',function($scope,$rootScope){

    var db = $scope.db;
    var table = 'cart';
    $scope.carts = [];
    $scope.current_cart = 0;
    $rootScope.current_cart = $scope.current_cart;
    var listCart = function(){
        db.values(table).done(function (items) {
            $scope.carts = items;
            if($scope.current_cart == 0 && items.length > 0)
            {
                $scope.current_cart = items[items.length - 1].created_at;
                $rootScope.current_cart = $scope.current_cart;
            }
        });
    };
    listCart();
    $scope.newCart = function(){
        var created_at = new Date().getTime();
        var data = {
            "label": 'New Cart',
            "created_at": created_at,
            'qty' : 0,
            'sub_total': 0,
            'discount_total': 0,
            'grand_total': 0,
            'customer_name': '',
            'customer_email': ''
        };
        $scope.current_cart = created_at;
        $rootScope.current_cart = $scope.current_cart;
        db.put(table, data).done(function(){
            listCart();
        }).fail(function(e) {
            console.error(e);
        });
    };
    $scope.updateCurrentCart = function(created_at)
    {
        $scope.current_cart = created_at;
        $rootScope.current_cart = $scope.current_cart;
    }
    $scope.closeCart = function(id){
        db.remove(table, id).done(function () {
            $scope.current_cart = 0;
            $rootScope.current_cart = $scope.current_cart;
        }).fail(function(e) {
            console.error(e);
        });
        listCart();
    };
    $scope.removeItem = function(added_at){
        var current_cart = $rootScope.current_cart;
        db.get(table,current_cart).done(function(cart){
            console.log(cart);
            for (i = 0; i < cart.products.length; i++) {
               var item = cart.products[i];
                if(item.added_at == added_at)
                {
                    cart.products.splice(i, 1);
                    cart.qty -= item.qty;
                }
            }

            db.put(table, cart).done(function(){
                listCart();
            }).fail(function(e) {
                console.error(e);
            });

        });
    };
    $rootScope.$on("createNewCart", function(){
        $scope.newCart();
    });
    $rootScope.$on("RefreshCurrentCart", function(){

        listCart();
    });

});