<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="robots" content="noindex">
    <meta name="author" content="anhvnit@gmail.com">

    <title>OpenPos Panel</title>
    <?php do_action( 'openpos_head' ); ?>

</head>

<body ng-app="openPos" ng-controller="uiBuilder">
<?php /*
<!--<div class="sk-wave">-->
<!--    <div class="sk-rect sk-rect1"></div>-->
<!--    <div class="sk-rect sk-rect2"></div>-->
<!--    <div class="sk-rect sk-rect3"></div>-->
<!--    <div class="sk-rect sk-rect4"></div>-->
<!--    <div class="sk-rect sk-rect5"></div>-->
<!--</div>-->



 <script type="text/ng-template" id="login.html">
    <div class="container login-container">
        <form class="form-signin">
            <h2 class="form-signin-heading">Please sign in</h2>
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" ng-model="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>

    </div>
</script>
 */?>
<div class="body-container">
    <div class=" top-date ">
        <div class="col-md-3 col-sm-12">
            <ul class="top-left-btn-fn col-sm-12">
                <li class="col-md-3">
                    <a href="javascript:void(0);" ng-click="fullScreen()" id="full-screen" class="closed" data-toggle="tooltip" data-placement="bottom"  title="Full screen">
                        <span class="glyphicon glyphicon-resize-full" aria-hidden="true"></span>
                        <span class="glyphicon glyphicon-resize-small" aria-hidden="true"></span>
                    </a>
                </li>
                <li class="col-md-3">
                    <a href="javascript:void(0);" data-toggle="tooltip" data-placement="bottom"  title="Refresh - Sync status"><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span></a>
                </li>
                <li class="col-md-3">
                    <a href="#" data-toggle="tooltip" data-placement="bottom"  title="Logoff"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a>
                </li>
                <li class="col-md-3">
                    <a href="#" data-toggle="tooltip" data-placement="bottom"  title="Open Cash drawer"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span></a>
                </li>
    
            </ul>
    
        </div>
        <div class="text-center col-md-6 col-sm-12"> <span id="clock" >{{posClock}}</span></div>
        <div class="col-md-3 col-sm-12">
            <div class="col-md-2">
<!--                <a href="#"  class="pull-right"><span class="glyphicon glyphicon-bell" aria-hidden="true"></span></a>-->
            </div>
            <div class="col-md-10">
                <div class="pull-right">
    
                    <img alt="140x140" data-src="holder.js/140x140" class="img-circle" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgdmlld0JveD0iMCAwIDE0MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzE0MHgxNDAKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNWNhNDdlMjQzMSB0ZXh0IHsgZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE1Y2E0N2UyNDMxIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjQ0LjA1NDY4NzUiIHk9Ijc0LjUiPjE0MHgxNDA8L3RleHQ+PC9nPjwvZz48L3N2Zz4=" data-holder-rendered="true" style="width: 30px; height: 30px;">
    
                    <label>Cashier Name </label>
                    <a href="javascript:void(0);" class="btn-exit"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a>
                </div>
            </div>
    
        </div>
    </div>
    <div class="main-container">
        <div class="row">
            <div class="left-content products col-md-8">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="col-md-2"><a href="#pos-menu" aria-controls="menu" role="tab" data-toggle="tab">DashBoard</a></li>
                    <li role="presentation" class="col-md-2"><a href="#pos-transactions" aria-controls="transactions" role="tab" data-toggle="tab">Transactions</a></li>
                    <li role="presentation" class="col-md-2"><a href="#pos-orders" aria-controls="ordres" role="tab" data-toggle="tab">Orders</a></li>
                    <li role="presentation" class="active col-md-2"><a href="#pos-products" aria-controls="products" role="tab" data-toggle="tab">Products</a></li>
                    <li class="pull-right col-md-4 barcode-frm">
                        <form>
                            <input type="email" class="form-control" id="barcode" placeholder="Barcode">
                        </form>
                    </li>
                </ul>
    
                <!-- Tab panes -->
                <div class="tab-content">
    
                    <div role="tabpanel" class="tab-pane" id="pos-menu">...</div>
                    <div role="tabpanel" class="tab-pane" id="pos-tranactions">...</div>
                    <div role="tabpanel" class="tab-pane" id="pos-orders">
                        <div class="order-container">
                            <div class="row">
    
                            </div>
                            <div class="row"></div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane active" id="pos-products" ng-controller="productsUi">

                        <div class="products-container" data-page_products="{{page_products}}" data-min="{{ min }}" data-page="{{pos_current_product_page}}" data-max="{{ max }}">
                            <div class="col-md-2 col-sm-2 product-cell"  ng-repeat="product in products">
                                <div class="product-details" ng-click="addToCart(product.id)" style="background-image: url('{{ product.image }}');">
                                    <div class="product-name">{{ product.name }}</div>
                                    <div class="product-price">{{ product.price }}</div>
                                </div>
                            </div>

                            <div class="product-loader-container">
                                <div class="product-loader">
                                </div>
                            </div>
                        </div>
                        <div class="row products-nav">
                            <div class="col-md-6">
                                <button ng-click="backProducts(pos_current_product_page)"  class="btn btn-default col-md-12 col-sm-12 col-lg-12">Back</button>
                            </div>
                            <div class="col-md-6">
                                <button ng-click="nextProducts(pos_current_product_page)"  class="btn btn-default col-md-12 col-sm-12 col-lg-12">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-content cart col-md-4" ng-controller="cartUi">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs cart-tab" role="tablist" >
                    <li role="presentation"  ng-repeat="cart in carts" class=" {{ (cart.created_at == current_cart) ? 'active':''}}"><a href="#cart-{{cart.created_at}}" ng-click="updateCurrentCart(cart.created_at)"  aria-controls="menu" role="tab" data-toggle="tab">{{cart.label}}<span class="cart-qty">{{cart.qty}}</span></a></li>
                    <li class="li-new-cart" ><a ng-click="newCart()" href="javascript:void(0);" id="new-cart">+</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content cart-tab-content">
                    <div ng-repeat="cart in carts " role="tabpanel" class="tab-pane {{ (cart.created_at == current_cart) ? 'active':''}}" id="cart-{{cart.created_at}}">
                        <div class="cart-customer row">
                            <div class="col-md-12 text-center">

                                <img alt="140x140" data-src="holder.js/140x140" class="img-circle" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgdmlld0JveD0iMCAwIDE0MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzE0MHgxNDAKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNWNhNDdlMjQzMSB0ZXh0IHsgZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE1Y2E0N2UyNDMxIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjQ0LjA1NDY4NzUiIHk9Ijc0LjUiPjE0MHgxNDA8L3RleHQ+PC9nPjwvZz48L3N2Zz4=" data-holder-rendered="true" style="width: 20px; height: 20px;">
                                Guest
                            </div>

                        </div>
                        <div class="cart-products">
                            <div class="container-fluid">
                                <div class="row cart-header">
                                    <div class="col-md-1 text-center">#</div>
                                    <div class="col-md-6"><label>Product</label></div>
                                    <div class="col-md-4"><label>Price</label></div>
                                    <div class="col-md-1"></div>
                                </div>
                            </div>
                            <div class="container-fluid cart-items">
                                <div class="row cart-item" ng-repeat="item in cart.products">
                                    <div class=" item" >
                                        <div class="col-md-1 text-center">1</div>
                                        <div class="col-md-6">{{item.product_name}}</div>
                                        <div class="col-md-4">{{item.product_price}}</div>
                                        <div class="col-md-1"><a href="javascript:void(0);" class="remove-cart-item" ng-click="removeItem(item.added_at)"><span>x</span></a></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="container-fluid row-total-block">
                            <div class="row cart-total">
                                <div class="col-md-6 text-right"><label>Sub-Total</label></div>
                                <div class="col-md-6 text-right">100.00</div>
                            </div>
                            <div class="row cart-total">
                                <div class="col-md-6 text-right"><label>Discount</label></div>
                                <div class="col-md-6 text-right">100.00</div>
                            </div>
                            <div class="row cart-total">
                                <div class="col-md-6 text-right"><label>Grand Total</label></div>
                                <div class="col-md-6 text-right">100.00</div>
                            </div>
                        </div>
                        <div class="cart-btn-container" id="cart-btn-container">
                            <div class="col-md-4 col-sm-4 col-lg-4">
                                <button ng-click="closeCart(cart.created_at)" class="btn btn-default col-md-12 col-sm-12 col-lg-12" type="button">Close</button>
                            </div>
                            <div class="col-md-8 col-sm-8 col-lg-8">
                                <button class="btn btn-default col-md-12 col-sm-12 col-lg-12" type="button">Checkout</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php do_action( 'openpos_footer' ); ?>
</body>

<script id="cart-tab-content-template" type="text/template">
    <div role="tabpanel" class="tab-pane" id="<%- id %>">
        <div class="cart-customer row">
            <div class="col-md-12 text-center">
                <img alt="140x140" data-src="holder.js/140x140" class="img-circle" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgdmlld0JveD0iMCAwIDE0MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzE0MHgxNDAKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNWNhNDdlMjQzMSB0ZXh0IHsgZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE1Y2E0N2UyNDMxIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjQ0LjA1NDY4NzUiIHk9Ijc0LjUiPjE0MHgxNDA8L3RleHQ+PC9nPjwvZz48L3N2Zz4=" data-holder-rendered="true" style="width: 20px; height: 20px;">
                Guest
            </div>
        </div>
        <div class="cart-products">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Sub-Total</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="cart-btn-container" id="cart-btn-container">
            <div class="col-md-4 col-sm-4 col-lg-4">
                <button class="btn btn-default col-md-12 col-sm-12 col-lg-12 close-tab" type="button">Close</button>
            </div>
            <div class="col-md-8 col-sm-8 col-lg-8">
                <button disabled class="btn btn-default col-md-12 col-sm-12 col-lg-12" type="button">Checkout</button>
            </div>
        </div>
    </div>
</script>

</html>
