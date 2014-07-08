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
        $primaryLang = $("select[name=primaryLanguage] option:selected").val();

        // prepare post params
        articleParams = false;
        otherParams = [];
        forms.each(function() {
            data = $("form", this).serializeObject();
            locale = $(this).attr('id');
            postUrl = actionUrl.replace('locale', locale);
            tmpParam = {"data": data, "postUrl": postUrl};
            if ($primaryLang === locale) {
                // article main data
                tmpParam.data.journalId = $("input[name=journalId]").val();
                tmpParam.data.primaryLanguage = $("select[name=primaryLanguage] option:selected").val();
                articleParams = tmpParam;
            } else {
                otherParams.push(tmpParam);
            }
        });
        if (!articleParams) {
            /**
             * @todo use a pretty modal
             */
            alert("Please select and fill metadata for article's language.");
            return;
        }
        /**
         * 1. post primaryLanguage's meta data 
         * 2. get articleId from response 
         * 3. post other meta datas for other languages
         */
        OjstrApp.showPleaseWait();
        $.post(articleParams.postUrl, articleParams.data, function(response) {
            if (response.id) {
                if (otherParams) {
                    for (i in otherParams) {
                        otherParams[i].data.articleId = response.id;
                        OjstrApp.showPleaseWait();
                        $.post(otherParams[i].postUrl, otherParams[i].data, function(response2) {
                            console.log(response2);
                            OjstrApp.hidePleaseWait();
                        });
                    }
                }
            } else {
                /**
                 * @todo use a pretty modal
                 */
                OjstrApp.hidePleaseWait();
                alert("Error occured. Try again.");
            }

        });
    }
};

$(document).ready(function() {
    $('select').select2({placeholder: '', allowClear: true, closeOnSelect: false});
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
        $("div#" + langcode + " textarea").wysihtml5({
            toolbar: {
                "font-styles": false,
                "emphasis": true,
                "lists": false,
                "html": false,
                "link": true,
                "image": false,
                "color": false,
                "blockquote": false
            }
        });
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