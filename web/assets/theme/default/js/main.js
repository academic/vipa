/**
 * Default Theme's javascript file
 */

$(function(){
    $("[rel='ajax']").on('click', function(){
        var href = $(this).prop('href');
        var text = $(this).text();
        var that = $(this);
        $.ajax({
            beforeSend: function(){
                that.text("...")
            },
            type: 'POST',
            data: '',
            url: href,
            dataType: 'json',
            success: function(data){
                if(data.status){
                    window[data.callback](data)
                }else{
                    alert(data.message)
                }
                that.text(text);
            }
        });
        return false;
    })

});

var regenerateAPI = function(response){
    $("#user_api_key").text(response.apikey);
}
