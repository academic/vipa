window.onbeforeunload = function () {
    return "Are you sure you want to navigate away from this page?\n\nYour changes may be lost.";
};

var OjsArticleSubmission = {
    submissionId: null,
    languages: [],
    activatedSteps: {"step1": true, "step2": false, "step3": false, "step4": false},
    showResumeNote: function (submissionId) {
        $rnt = $("#resumeNote");
        $rnt.html("You can resume your submission progress anytime with this url  " +
                "<a href='/author/article/submit/resume/" + submissionId + "'>#" + submissionId + "</a>");
        $rnt.show();
    },
    loadStepTemplate: function (step) {
        if (!this.activatedSteps["step" + step]) {
            $("#step" + step).append(Mustache.render($("#step" + step + "_tpl").html()));
            this.activatedSteps["step" + step] = true;
        }
    },
    backTo: function (step) {
        this.configureProgressBar(step);
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
        history.pushState({}, "Article Submission", "/author/article/submit/resume/" + $("input[name=submissionId]").val());
        window.location.hash = step;
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
    addAuthorForm: function (params) {
        $("#step2").append(Mustache.render($("#step2_tpl").html(), params));
    },
    addFileForm: function (params) {
        $("#step4").append(Mustache.render($("#step4_tpl").html(), params));
        this.bindFileUploader();
        this.setupUi();
    },
    removeAuthor: function ($el) {
        $el.parents(".author-item").first().remove();
    },
    step1AddLanguageForm: function (langcode, langtitle, params) {
        // check if selected language tab already exists
        if (OjsCommon.inArray(langcode, OjsArticleSubmission.languages)) {
            return false;
        }
        $tpl = Mustache.render($('#step1_tpl').html(), params);
        $("#step1").append('<div class="tab-pane step1" id="' + langcode + '">' + $tpl);

        OjsArticleSubmission.languages.push(langcode);
        $("div#" + langcode + " textarea.editor").wysihtml5({
            toolbar: {"font-styles": false, "emphasis": true, "lists": false, "html": false, "link": true, "image": false, "color": false, "blockquote": true}
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
        forms = $("#step1 .tab-pane");
        if (forms.length === 0) {
            OjsCommon.errorModal("Add at least one language to your submission.");
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
            OjsCommon.errorModal("Please select and fill metadata for article's language.");
            return;
        }

        OjsCommon.waitModal();
        if (translationParams) {
            articleParams.data.translations = JSON.stringify(translationParams);
        }
        articleParams.data.submissionId = $("input[name=submissionId]").val();
        $.post(articleParams.postUrl, articleParams.data, function (response) {
            OjsCommon.hideallModals();
            if (response.submissionId) {
                OjsArticleSubmission.submissionId = response.submissionId;
                $("input[name=submissionId]").attr('value', response.submissionId);
                OjsArticleSubmission.hideAllSteps();
                OjsArticleSubmission.prepareStep.step2();
                OjsArticleSubmission.showResumeNote(OjsArticleSubmission.submissionId);
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        });
    },
    step2: function (actionUrl) {
        forms = $(".author-item");
        if (forms.length === 0) {
            OjsCommon.errorModal("Add at least one author.");
            return false;
        }
        $primaryLang = $("select[name=primaryLanguage] option:selected").val();
        // prepare post params
        authorParams = false;
        var dataArray = [];
        forms.each(function () {
            dataArray.push($("form", this).serializeObject());
        });
        OjsCommon.waitModal();
        $.post(actionUrl, {"authorsData": JSON.stringify(dataArray), "submissionId": OjsArticleSubmission.submissionId}, function (response) {
            OjsCommon.hideallModals();
            OjsArticleSubmission.hideAllSteps();
            OjsArticleSubmission.prepareStep.step3();
        }).error(function () {
            OjsCommon.errorModal("Something is wrong. Check your data and try again.");
        });
    },
    step3: function (actionUrl) {
        forms = $("form.cite-item");
        if (forms.length > 0) {
            $primaryLang = $("select[name=primaryLanguage] option:selected").val();
            // prepare post params 
            var dataArray = [];
            forms.each(function () {
                dataArray.push($(this).serializeObject());
            });
            OjsCommon.waitModal();
            $.post(actionUrl, {"citeData": JSON.stringify(dataArray), "submissionId": OjsArticleSubmission.submissionId}, function (response) {
                OjsCommon.hideallModals();
                OjsArticleSubmission.hideAllSteps();
                OjsArticleSubmission.prepareStep.step4();
            }).error(function () {
                OjsCommon.errorModal("Something is wrong. Check your data and try again.");
            });
        }
    },
    step4: function (actionUrl) {
        OjsCommon.waitModal();
        
        this.hideAllSteps();
        window.location.href = actionUrl;
    },
    /**
     * prepare and show steps
     */
    prepareStep: {
        step1: function () {

        },
        step2: function () {
            OjsCommon.scrollTop();
            if ($("#step2").html().length > 0) {
                OjsArticleSubmission.configureProgressBar(2);
                OjsArticleSubmission.loadStepTemplate(2);
            }
            OjsArticleSubmission.showStep(2);
        },
        step3: function () {
            OjsCommon.scrollTop();
            if ($("#step3").html().length > 0) {
                OjsArticleSubmission.configureProgressBar(3);
                OjsArticleSubmission.loadStepTemplate(3);
            }
            OjsArticleSubmission.showStep(3);
        },
        step4: function () {
            OjsCommon.scrollTop();
            if ($("#step4").html().length > 0) {
                OjsArticleSubmission.configureProgressBar(4);
                OjsArticleSubmission.loadStepTemplate(4);
            }
            OjsArticleSubmission.showStep(4);
        }
    },
    bindFileUploader: function () {
        $('.article_file_upload').fileupload({});
        $('.article_file_upload').bind('fileuploadsend', function (e, data) {
            $(this).parent().next('.upload_progress').show();
            $(this).parent().next('.upload_progress').html("Uploading...");
        }).bind('fileuploaddone', function (e, data) {
            $(this).parent().next('.upload_progress').html("Done.");
            $('.filename', $(this).parent()).attr('value', JSON.parse(data.result).files.name);
        });
    },
    setupUi: function () {
        $('.select2-element').select2({placeholder: '', allowClear: true, closeOnSelect: false});
    }
};

$(document).ready(function () {
    OjsArticleSubmission.setupUi();
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
});
