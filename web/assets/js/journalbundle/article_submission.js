var OjsArticleSubmision = {
    articleId: null,
    languages: [],
    step1Show: function() {
        $("#step1-container").removeClass("hide", 200, "easeInBack");
    },
    step1Hide: function() {
        $("#step1-container").addClass("hide", 200, "easeInBack");
    },
    step2Show: function() {
        $("#step2-container").removeClass("hide", 200, "easeInBack");
    },
    step2Hide: function() {
        $("#step1-container").slideUp("fast", 200, "easeInBack");
    },
    activateFirstLanguageTab: function() {
        firsttab = $("#step1 .tab-pane").first().attr('id');
        if (firsttab) {
            $("li.lang a[href=#" + firsttab + "]").tab("show");
        }
    },
    step1AddLanguageForm: function(langcode, langtitle) {
        // check if selected language tab already exists
        if (OjstrCommon.inArray(langcode, OjsArticleSubmision.languages)) {
            return false;
        }
        $("#step1").append('<div class="tab-pane step1" id="' + langcode + '">' +
                '<div class="tab_step1">' + $("#step1_tpl").html() + '</div>' +
                '<div class="tab_step2 hide">' + $("#step2_tpl").html() + '</div>' +
                '</div>');
        OjsArticleSubmision.languages.push(langcode);
        $("div#" + langcode + " textarea.editor").wysihtml5({
            toolbar: {
                "font-styles": false,
                "emphasis": true,
                "lists": false,
                "html": false,
                "link": true,
                "image": false,
                "color": false,
                "blockquote": true
            }
        });
        tabhtml = '<li class="lang" id="t_' + langcode + '"><a href="#' +
                langcode + '" role="tab" class="lang" data-toggle="tab">' +
                langtitle + '<span class="removelang btn btn-sm btn-default"><i class="fa fa-trash-o"></i>' +
                '</span></a></li>';
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
    step1: function(actionUrl) {
        forms = $(".tab-pane");
        if (forms.length === 0) {
            OjstrCommon.errorModal("Add at least one language to your submission.");
            return false;
        }
        $primaryLang = $("select[name=primaryLanguage] option:selected").val();

        // prepare post params
        articleParams = false;
        translationParams = [];
        forms.each(function() {
            data = $("form", this).serializeObject();
            locale = $(this).attr('id');
            data.locale = locale;
            postUrl = actionUrl.replace('locale', locale);
            tmpParam = {"data": data, "postUrl": postUrl};
            if ($primaryLang === locale) {
                // article main data
                tmpParam.data.journalId = $("input[name=journalId]").val();
                tmpParam.data.primaryLanguage = $("select[name=primaryLanguage] option:selected").val();
                articleParams = tmpParam;
            } else {
                translationParams.push(tmpParam);
            }
        });
        if (!articleParams) {
            OjstrCommon.errorModal("Please select and fill metadata for article's language.");
            return;
        }
        /**
         * 1. post primaryLanguage's meta data 
         * 2. get articleId from response 
         * 3. post other meta datas for other languages
         */
        OjstrCommon.waitModal();
        if (translationParams) {
            articleParams.data.translations = JSON.stringify(translationParams);
        }
        $.post(articleParams.postUrl, articleParams.data, function(response) {
            OjstrCommon.hideallModals();
            if (response.id) {
                OjsArticleSubmision.articleId = response.id;
                OjsArticleSubmision.step2ShowCitationForm();
            } else {
                OjstrCommon.errorModal("Error occured. Try again.");
            }

        });
    },
    step2ShowCitationForm: function() {
        OjstrCommon.scrollTop();
        $("ul.submission-progress li").removeClass("active");
        $("ul.submission-progress li#submission-progress-step2").addClass("active");
        OjsArticleSubmision.step1Hide();
        OjsArticleSubmision.step2Show();
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
    $("body").on("click", "span.removelang", function(e) {
        e.preventDefault();
        langcode = $(this).parent().attr("href").replace("#", "");
        $tab = $("#" + langcode);
        OjsArticleSubmision.step1RemoveLanguageForm(langcode, $tab);
    });
});