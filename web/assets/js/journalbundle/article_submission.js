var OjsArticleSubmision = {
    languages: [],
    activateFirstLanguageTab: function() {
        firsttab = $("#langtabs .tab-pane").first().attr('id');
        if (firsttab) {
            $("li.lang a[href=#" + firsttab + "]").tab("show");
        }
    },
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

$(document).ready(function() {

    $("ul#mainTabs li a").click(function(e) {
        e.preventDefault();
    });
    $("ul#languageDropList li a").click(function(e) {
        e.preventDefault();
        langcode = $(this).attr('code');
        langtitle = $(this).attr('lang');
        // check if selected language tab already exists
        if (OjstrCommon.inArray(langcode, OjsArticleSubmision.languages)) {
            return false;
        }
        $("#langtabs").append('<div class="tab-pane" id="' + langcode + '">' + $("#formtpl").html() + '</div>');
        OjsArticleSubmision.languages.push(langcode);
        tabhtml = '<li class="lang" id="t_' + langcode + '"><a href="#' + langcode + '" role="tab" class="lang" data-toggle="tab">' + langtitle + '</a></li>';
        $("ul#mainTabs li.lang").last().before(tabhtml);
        OjsArticleSubmision.activateFirstLanguageTab();
    });
    $("#langtabs").on("click", "a.removelang", function(e) {
        check = confirm("Are you sure to remove this language tab?");
        if (!check) {
            return false;
        }
        e.preventDefault();
        langcode = $(this).parent().attr('id');
        for (var i in OjsArticleSubmision.languages) {
            console.log(OjsArticleSubmision.languages[i]);
            if (OjsArticleSubmision.languages[i] === langcode) {
                OjsArticleSubmision.languages.splice(i, 1);
            }
        }
        $(this).parent().remove();
        $('#t_' + langcode).remove();
        OjsArticleSubmision.activateFirstLanguageTab();
    });
});