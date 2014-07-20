var OjsArticleSubmision = {
    languages: [],
    activateFirstLanguageTab: function() {
        firsttab = $("#langtabs .tab-pane").first().attr('id');
        if (firsttab) {
            $("li.lang a[href=#" + firsttab + "]").tab("show");
        }
    },
    step1AddLanguageForm: function(langcode, langtitle) {
        // check if selected language tab already exists
        if (OjstrCommon.inArray(langcode, OjsArticleSubmision.languages)) {
            return false;
        }
        $("#langtabs").append('<div class="tab-pane" id="' + langcode + '">' + $("#step1_tpl").html() + '</div>');
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
    },
    step1RemoveLanguageForm: function(langcode, $tab) {
        check = confirm("Are you sure to remove this language tab?");
        if (!check) {
            return false;
        }
        for (var i in this.languages) {
            if (this.languages[i] === langcode) {
                this.languages.splice(i, 1);
            }
        }
        $tab.remove();
        $('#t_' + langcode).remove();
        this.activateFirstLanguageTab();
    },
    step1: function(actionUrl, next) {
        forms = $(".tab-pane");
        if (forms.length === 0) {
            alert("Add at least one language to your submission.");
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
        OjsArticleSubmision.step1AddLanguageForm(langcode, langtitle);
    });
    $("#langtabs").on("click", "a.removelang", function(e) {
        e.preventDefault();
        $tab = $(this).parent();
        langcode = $(this).parent().attr('id');
        OjsArticleSubmision.step1RemoveLanguageForm(langcode, $tab);
    });
});