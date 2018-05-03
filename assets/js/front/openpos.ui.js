/**
 * Created by anhvnit on 6/11/17.
 */
(function($) {
    document.cancelFullScreen = document.webkitExitFullscreen || document.mozCancelFullScreen || document.exitFullscreen;

    var client = new ClientJS(); // Create A New Client Object
    var screenPrint = client.getScreenPrint(); // Get Screen Print
    //alert(screenPrint);
    var elem = document.querySelector(document.webkitExitFullscreen ? "#fs" : "#fs");
    function toggleFS(el) {
        if (el.webkitEnterFullScreen) {
            el.webkitEnterFullScreen();
        } else {
            if (el.mozRequestFullScreen) {
                el.mozRequestFullScreen();
            } else {
                el.requestFullscreen();
            }
        }

        el.ondblclick = exitFullscreen;
    }

    function onFullScreenEnter() {

        $(window).trigger('resize');
        elem.onwebkitfullscreenchange = onFullScreenExit;
        elem.onmozfullscreenchange = onFullScreenExit;
    };

    // Called whenever the browser exits fullscreen.
    function onFullScreenExit() {
        $(window).trigger('resize');
    };

    // Note: FF nightly needs about:config full-screen-api.enabled set to true.
    function enterFullscreen() {

        elem.onwebkitfullscreenchange = onFullScreenEnter;
        elem.onmozfullscreenchange = onFullScreenEnter;
        elem.onfullscreenchange = onFullScreenEnter;
        if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        } else {
            if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else {
                elem.requestFullscreen();
            }
        }


    }

    function exitFullscreen() {

        document.cancelFullScreen();

    }
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('clock').innerHTML =
            h + ":" + m + ":" + s;
        var t = setTimeout(startTime, 500);
    }
    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }
    function resizeUi()
    {
        var window_height = $(window).height();
        var current_height = $('body').data('height');
        if(window_height != current_height)
        {
            $('body').data('height',window_height);
            var offset = $('.left-content .tab-content').offset();
            var leftTabHeight = window_height - offset.top - 5;
            $('.left-content .tab-content').css('height',leftTabHeight);

            var offset = $('.right-content .tab-content').offset();
            var rightTabHeight = window_height - offset.top - 5;
            $('.right-content .tab-content').css('height',rightTabHeight);
        }

    }
    function resizeCartUi()
    {
        var window_height = $(window).height();
        var current_height = $('body').data('height');
        //if(window_height != current_height)
        //{
            $('body').data('height',window_height);

            var offset = $('.right-content .tab-content').offset();
            var rightTabHeight = window_height - offset.top - 5;
            $('.right-content .tab-content').css('height',rightTabHeight);
        //}

    }
    //$(document).ready(function(){
        startTime();
        $('[data-toggle="tooltip"]').tooltip();
        $(document).on('click','#full-screen',function(){
            if($(this).hasClass('open'))
            {
                exitFullscreen();

                $(this).removeClass('open');
                $(this).addClass('closed');

            }else{

                enterFullscreen();
                $(this).removeClass('closed');
                $(this).addClass('open');
            }

        });

        resizeUi();


        $('#barcode').focus();
        $(window).on('resize',function(){
            doit = setTimeout(resizeUi, 100);
        });

        $(window).on('resize_cart_panel',function(){

            doit = setTimeout(resizeCartUi, 100);
        });

        $('#new-cart').click(function (e) {
            var length = $('.cart-tab li').length;
            if(length == 11)
            {
                alert('Maximum is 10 cart per session');
            }else{
                var nextTab = length+ 1;
                var nexTabId = 'tab'+nextTab;
                // create the tab
                $('<li><a id="a-'+nexTabId+'" href="#'+nexTabId+'" data-toggle="tab"><label>Cart</label> (<span>0</span>)</a></li>').insertBefore('.li-new-cart');

                // create the tab content
                var html_template = $('#cart-tab-content-template').html();
                var template = _.template(html_template);
                var compiled = template({id: nexTabId});
                //$(compiled).appendTo('.cart-tab-content');
                $('.cart-tab-content').append(compiled);
                //$('<div role="tabpanel" class="tab-pane" id="'+nexTabId+'">tab' +nextTab+' content</div>').appendTo('.cart-tab-content');

                // make the new tab active
                $('.cart-tab a').eq(-2).tab('show');
                $(window).trigger('resize_cart_panel');
            }

        });
        $(document).on('click','.close-tab',function(){

            $('.cart .tab-pane.active').remove();
            $('.cart-tab li.active').remove();
            $('.cart-tab a').eq(-2).tab('show');
            $(window).trigger('resize_cart_panel');
            if($('.cart-tab li').length == 1)
            {
                $('#new-cart').click();
            }
        });
        $('#new-cart').click();

    //});


}(jQuery));

