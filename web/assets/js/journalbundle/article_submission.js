var OjsArticleSubmision = {
    languages: ['en'],
    step1: function(actionUrl, next) {
        forms = $(".tab-pane");
        if (forms.length === 0) {
            return false;
        }
        forms.each(function() {
            data = $("form", this).serializeObject();
            locale = $(this).attr('id');
            postUrl = actionUrl.replace('locale', locale); 
            $.post(postUrl, data, function(response) {
                console.log(response);
            });
        });
    }
};