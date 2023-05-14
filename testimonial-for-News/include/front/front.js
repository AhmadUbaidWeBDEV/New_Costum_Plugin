jQuery(document).ready(function($) {
    // Show paragraph button click event
    $('.show-para-btn').click(function() {
        var para_id = $(this).data('para-id');
        
        setTimeout(function() {
            $.ajax({
                type: 'POST',
                url: my_ajax_obj.ajax_url,
                data: {
                    action: 'my_ajax_callback',
                    para_id: para_id
                },
                success: function(response) {

                    $('.showpara[data-para-id='+para_id+']').hide();
                    $('.show-para-btn[data-para-id='+para_id+']').hide();
                    $('.hide-para-btn[data-para-id='+para_id+']').show();
                    $('.para[data-para-id='+para_id+']').show();
                }
            });
        }, 1); // Add a delay of 100 milliseconds (adjust as needed)
    });

    // Hide paragraph button click event
    $('.hide-para-btn').click(function() {
        var para_id = $(this).data('para-id');
        
        setTimeout(function() {
            $.ajax({
                type: 'POST',
                url: my_ajax_obj.ajax_url,
                data: {
                    action: 'my_ajax_callback',
                    para_id: para_id
                },
                success: function(response) {

                    $('.showpara[data-para-id='+para_id+']').show();
                    $('.show-para-btn[data-para-id='+para_id+']').show();
                    $('.hide-para-btn[data-para-id='+para_id+']').hide();
                    $('.para[data-para-id='+para_id+']').hide();
                  
                }
            });
        }, 1); // Add a delay of 100 milliseconds (adjust as needed)
    });
    
    $('.filter').click(function() {
        
        $('.resenttestimonial').css('display', 'none');
        $('.rcnt').css('display', 'none');
        $('.ltest').css('display', 'none');
    });


    $('.show-para-btn1').click(function() {
        var para_id = $(this).data('para-id');
        
        setTimeout(function() {
            $.ajax({
                type: 'POST',
                url: my_ajax_obj.ajax_url,
                data: {
                    action: 'my_ajax_callback',
                    para_id: para_id
                },
                success: function(response) {

                    $('.showpara1[data-para-id='+para_id+']').hide();
                    $('.show-para-btn1[data-para-id='+para_id+']').hide();
                    $('.hide-para-btn1[data-para-id='+para_id+']').show();
                    $('.para1[data-para-id='+para_id+']').show();
                }
            });
        }, 1); // Add a delay of 100 milliseconds (adjust as needed)
    });

    // Hide paragraph button click event
    $('.hide-para-btn1').click(function() {
        var para_id = $(this).data('para-id');
        
        setTimeout(function() {
            $.ajax({
                type: 'POST',
                url: my_ajax_obj.ajax_url,
                data: {
                    action: 'my_ajax_callback',
                    para_id: para_id
                },
                success: function(response) {

                    $('.showpara1[data-para-id='+para_id+']').show();
                    $('.show-para-btn1[data-para-id='+para_id+']').show();
                    $('.hide-para-btn1[data-para-id='+para_id+']').hide();
                    $('.para1[data-para-id='+para_id+']').hide();
                  
                }
            });
        }, 1); // Add a delay of 100 milliseconds (adjust as needed)
    });

});
