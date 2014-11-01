window.onbeforeunload = function () {
    var message = "Are you sure you want to navigate away from this page?\n\nYour changes may be lost.";
    return message;
};

var OjsArticleSubmission = {
    articleId: null,
    languages: [],
    loadStepTemplate: function (step) {
        var tpl = $('#step' + step + '_tpl').html();
        var tpl_rendered = Mustache.render(tpl);
        $("#step" + step).append(tpl_rendered);
    },
    backTo: function (step) {
        this.hideAllSteps();
        this.configureProgressBar(step);
        this.showStep(step);
    },
    configureProgressBar: function (step) {
        $("ul.submission-progress li").removeClass("active");
        $("ul.submission-progress li#submission-progress-step" + step).addClass("active");
    },
    hideAllSteps: function () {
        $(".step").addClass("hide", 200, "easeInBack");
    },
    showStep: function (step) {
        $("#step" + step + "-container").removeClass("hide", 200, "easeInBack");
    },
    hideStep: function (step) {
        $("#step" + step + "-container").slideUp("fast", 200, "easeInBack");
    },
    activateFirstLanguageTab: function () {
        firsttab = $("#step1 .tab-pane").first().attr('id');
        if (firsttab) {
            $("li.lang a[href=#" + firsttab + "]").tab("show");
        }
    },
    step1AddLanguageForm: function (langcode, langtitle) {
        // check if selected language tab already exists
        if (OjstrCommon.inArray(langcode, OjsArticleSubmission.languages)) {
            return false;
        }

        var step1_template = $('#step1_tpl').html();
        var step1_rendered = Mustache.render(step1_template);

        $("#step1").append('<div class="tab-pane step1" id="' + langcode + '">' + step1_rendered);

        OjsArticleSubmission.languages.push(langcode);
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
        OjsArticleSubmission.activateFirstLanguageTab();
    },
    step1RemoveLanguageForm: function (langcode, $tab) {
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
    step1: function (actionUrl) {
        forms = $(".tab-pane");
        if (forms.length === 0) {
            OjstrCommon.errorModal("Add at least one language to your submission.");
            return false;
        }
        $primaryLang = $("select[name=primaryLanguage] option:selected").val();

        // prepare post params
        articleParams = false;
        translationParams = [];
        forms.each(function () {
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
        $.post(articleParams.postUrl, articleParams.data, function (response) {
            OjstrCommon.hideallModals();
            if (response.id) {
                OjsArticleSubmission.articleId = response.id;
                OjsArticleSubmission.hideAllSteps();
                OjsArticleSubmission.step2Prepare();
            } else {
                OjstrCommon.errorModal("Error occured. Try again.");
            }

        });
    },
    step2: function (actionUrl) {
        this.hideAllSteps();
        this.step3Prepare();
    },
    step3: function (actionUrl) {
        this.hideAllSteps();
        this.step4Prepare();
    },
    /**
     * Prepare and show step2
     * @returns void
     */
    step2Prepare: function () {
        OjstrCommon.scrollTop();
        if ($("#step2").html().length > 0) {
            this.configureProgressBar(2);
            this.loadStepTemplate(2);
        }
        this.showStep(2);
    },
    /**
     * prepare and show step3
     * @returns {undefined}
     */
    step3Prepare: function () {
        OjstrCommon.scrollTop();
        if ($("#step3").html().length > 0) {
            this.configureProgressBar(3);
            this.loadStepTemplate(3);
        }
        this.showStep(3);
    },
    step4Prepare: function () {
        OjstrCommon.scrollTop();
        if ($("#step4").html().length > 0) {
            this.configureProgressBar(4);
            this.loadStepTemplate(4);
        }
        this.showStep(4);
    }
};

$(document).ready(function () {
    $('select').select2({placeholder: '', allowClear: true, closeOnSelect: false});
    $("ul#mainTabs li a").click(function (e) {
        e.preventDefault();
    });
    $("ul#languageDropList li a").click(function (e) {
        e.preventDefault();
        langcode = $(this).attr('code');
        langtitle = $(this).attr('lang');
        OjsArticleSubmission.step1AddLanguageForm(langcode, langtitle);
    });
    $("body").on("click", "span.removelang", function (e) {
        e.preventDefault();
        langcode = $(this).parent().attr("href").replace("#", "");
        $tab = $("#" + langcode);
        OjsArticleSubmission.step1RemoveLanguageForm(langcode, $tab);
    });
    // article file uploader
    $('#article_file_upload').fileupload({});
    $('#article_file_upload').bind('fileuploadsend', function (e, data) {
        $(this).parent().next('.upload_progress').show();
        $(this).parent().next('.upload_progress').html("Uploading...");
    }).bind('fileuploaddone', function (e, data) {
        $(this).parent().next('.upload_progress').html("Done.");
        $('.filename', $(this).parent()).attr('value', JSON.parse(data.result).files.name);
    });
});