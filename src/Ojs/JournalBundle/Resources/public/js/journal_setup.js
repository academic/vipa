var OjsJournalSetup = {
    submissionId: null,
    journalSetupUrl: $('#journalSetupUrl').val(),
    step: null,
    languages: [],
    activatedSteps: {"step1": true, "step2": false, "step3": false, "step4": false, "step5": false, "step6": false},
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
        history.pushState({}, "Article Submission", OjsJournalSetup.journalSetupUrl + $("input[name=submissionId]").val());
        window.location.hash = step;
        this.step = step;
        this.showResumeNote();
    },
    hideStep: function (step) {
        $("#step" + step + "-container").slideUp("fast", 200, "easeInBack");
    },
    addAuthorForm: function (params) {
        $("#step2").append(Mustache.render($("#step2_tpl").html(), params));
    },
    addFileForm: function (params) {
        $("#step4").append(Mustache.render($("#step4_tpl").html(), params));
        this.setupUi();
    },
    removeAuthor: function ($el) {
        $el.parents(".author-item").first().remove();
    },
    step1: function (actionUrl) {
        form = $("#step1 form");
        journalParams = [];
        journalParams.postUrl = actionUrl;
        journalParams.data = form.serializeObject();
        journalParams.data.journalId = actionUrl;

        OjsCommon.waitModal();
        $.post(journalParams.postUrl, journalParams.data, function (response) {
            OjsCommon.hideallModals();
            if (response.success) {
                OjsJournalSetup.hideAllSteps();
                OjsJournalSetup.prepareStep.step2();
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        });
    },
    step2: function (actionUrl) {
        form = $("#step2 form");
        journalParams = [];
        journalParams.postUrl = actionUrl;
        journalParams.data = form.serializeObject();
        journalParams.data.journalId = actionUrl;

        OjsCommon.waitModal();
        $.post(journalParams.postUrl, journalParams.data, function (response) {
            OjsCommon.hideallModals();
            if (response.success) {
                OjsJournalSetup.hideAllSteps();
                OjsJournalSetup.prepareStep.step3();
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        });
    },
    step3: function (actionUrl) {
        form = $("#step3 form");
        journalParams = [];
        journalParams.postUrl = actionUrl;
        journalParams.data = form.serializeObject();
        journalParams.data.journalId = actionUrl;

        OjsCommon.waitModal();
        $.post(journalParams.postUrl, journalParams.data, function (response) {
            OjsCommon.hideallModals();
            if (response.success) {
                OjsJournalSetup.hideAllSteps();
                OjsJournalSetup.prepareStep.step4();
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        });
    },
    step4: function (actionUrl) {
        form = $("#step4 form");
        journalParams = [];
        journalParams.postUrl = actionUrl;
        journalParams.data = form.serializeObject();
        journalParams.data.journalId = actionUrl;

        OjsCommon.waitModal();
        $.post(journalParams.postUrl, journalParams.data, function (response) {
            OjsCommon.hideallModals();
            if (response.success) {
                OjsJournalSetup.hideAllSteps();
                OjsJournalSetup.prepareStep.step5();
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        });
    },
    step5: function (actionUrl) {
        form = $("#step5 form");
        journalParams = [];
        journalParams.postUrl = actionUrl;
        journalParams.data = form.serializeObject();
        journalParams.data.journalId = actionUrl;

        OjsCommon.waitModal();
        $.post(journalParams.postUrl, journalParams.data, function (response) {
            OjsCommon.hideallModals();
            if (response.success) {
                OjsJournalSetup.hideAllSteps();
                OjsJournalSetup.prepareStep.step6();
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        });
    },
    step6: function (actionUrl) {
        form = $("#step6 form");
        journalParams = [];
        journalParams.postUrl = actionUrl;
        journalParams.data = form.serializeObject();
        journalParams.data.journalId = actionUrl;

        OjsCommon.waitModal();
        $.post(journalParams.postUrl, journalParams.data, function (response) {
            OjsCommon.hideallModals();
            if (response.success) {
                OjsJournalSetup.hideAllSteps();
                /*Show a demo with finish*/
                OjsJournalSetup.prepareStep.preview();
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        });
    },
    submit: function () {
        var check = confirm("Are you sure to submit your article?");
        if (check === true) {
            OjsCommon.waitModal();
            window.location.href = "/";
        } else {
            return false;
        }
    },
    /**
     * prepare and show steps
     */
    prepareStep: {
        step1: function () {
            OjsJournalSetup.configureProgressBar(1);
            OjsJournalSetup.showStep(1);
        },
        step2: function () {
            OjsCommon.scrollTop();
            if ($("#step2").html().length > 0) {
                OjsJournalSetup.configureProgressBar(2);
                OjsJournalSetup.loadStepTemplate(2);
            }
            OjsJournalSetup.showStep(2);
        },
        step3: function () {
            OjsCommon.scrollTop();
            if ($("#step3").html().length > 0) {
                OjsJournalSetup.configureProgressBar(3);
                OjsJournalSetup.loadStepTemplate(3);
            }
            OjsJournalSetup.showStep(3);
        },
        step4: function () {
            OjsCommon.scrollTop();
            if ($("#step4").html().length > 0) {
                OjsJournalSetup.configureProgressBar(4);
                OjsJournalSetup.loadStepTemplate(4);
            }
            OjsJournalSetup.showStep(4);
            OjsJournalSetup.setupUi();
        },
        step5: function () {
            OjsCommon.scrollTop();
            if ($("#step5").html().length > 0) {
                OjsJournalSetup.configureProgressBar(5);
                OjsJournalSetup.loadStepTemplate(5);
            }
            OjsJournalSetup.showStep(5);
            OjsJournalSetup.setupUi();
        },
        step6: function () {
            OjsCommon.scrollTop();
            if ($("#step4").html().length > 0) {
                OjsJournalSetup.configureProgressBar(6);
                OjsJournalSetup.loadStepTemplate(6);
            }
            OjsJournalSetup.showStep(6);
            OjsJournalSetup.setupUi();
        }
    },
    bindFileUploader: function () {
        $('.article_file_upload').fileupload({});
        $('.article_file_upload').bind('fileuploadsend', function (e, data) {
            $uploadIndicator = $('.upload_progress', $(this).parent().parent());
            $uploadIndicator.show();
            $uploadIndicator.html("Uploading...");
        }).bind('fileuploaddone', function (e, data) {
            $('.upload_progress', $(this).parent().parent()).html("Done.");
            $obj = JSON.parse(data.result);
            $('.previewLink', $(this).parent().parent()).attr('href', '/uploads/journalfiles/' + $obj.files.path + $obj.files.name).removeClass('hide');
            $('.filename', $(this).parent()).attr('value', $obj.files.name);
            $('input[name="article_file_mime_type"]', $(this).parent()).attr('value', $obj.files.size);
            $('input[name="article_file_size"]', $(this).parent()).attr('value', $obj.files.mimeType);
        });
    },
    setupUi: function () {
        this.bindFileUploader();
        $primaryLang = $("select[name=primaryLanguage] option:selected").val();
        $('input[name=keywords], input[name=subjects]').tagsinput({
            tagClass: 'label label-info'
        });
        $('#changeSelectedJournal').on("select2-selecting", function (e) {
            window.location.href = "" + e.val;
        });
        $('.select2-element').select2({placeholder: '', allowClear: true, closeOnSelect: false});
        $("div.editor").wysihtml5({
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
    },
    step1ScrollToLangPanel: function (lang) {
        $('html, body').animate({
            scrollTop: $("#lang_" + lang).offset().top
        }, 500);
    }
};

$(document).ready(function () {
    OjsJournalSetup.setupUi();
});
