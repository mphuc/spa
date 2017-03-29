$(document).ready(function() {

    var funDaskboard = {
      
        ajaxAnalytics : function(element, callback) {
            $.ajax({
                url : element.data('link'),
                type : 'GET',
                data : {
                    'id' : element.data('id'),
                    'level' : element.data('level')
                },
                async : true,
                success : function(result) {
                    result = $.parseJSON(result);
                    callback(result);
                }
            });
        },
      
    }

 
    _.each([0, 1, 2, 3, 4, 5, 6], function(value) {
        funDaskboard.ajaxAnalytics($('div.analytics-tree[data-level=' + value + ']'), function(result) {
            _.has(result, 'success') && $('div.analytics-tree[data-level=' + value + ']').html(_.values(result)[0] + '<i class="fa fa-user" style="margin-left: 10px;"></i>');
            $('div.analytics-tree[data-level=' + value + ']').css({
                'background-image' : 'none'
            });
        });
    });

 

});