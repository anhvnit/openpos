(function($) {
    function realTime()
    {
        $.ajax({
            url: openpos_admin.ajax_url,
            type: 'post',
            dataType: 'json',
            data:{action:'admin_openpos_data'},
            success:function(){
                setTimeout(
                    function()
                    {
                        realTime();
                    }, 5000);
            },
            error: function(){
                setTimeout(
                    function()
                    {
                        realTime();
                    }, 5000);
            }
        })
    }
    $(document).ready(function(){
        if($('body').hasClass('toplevel_page_openpos-dasboard'))
        {
            realTime();
        }


    });


}(jQuery));