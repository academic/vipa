var OjsArticleSubmission = {
    submissionId: null,
    step: null,
    languages: [],
    activatedSteps: {"step0": true, "step1": false, "step2": false, "step3": false, "step4": false},
    showResumeNote: function () {

        var html = '<a href="/author/article/submit/resume/' + this.submissionId + '#' + this.step + '">' + this.submissionId + '</a>';
        $("#resumeNote").html(html);
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
        this.step = step;
        this.showResumeNote();
    },
    hideStep: function (step) {
        $("#step" + step + "-container").slideUp("fast", 200, "easeInBack");
    },
    addAuthorForm: function (params) {
        $("#step2").append(Mustache.render($("#step2_tpl").html(), params));
        OjsArticleSubmission.setupUi();
        function formatResult(item) {
            return item.name;
        }
        function formatSelection(item) {
            return '<b>' + item.name + '</b>';
        }

        $('.float-label').jvFloat();

        $(".select2-institution-search-element").select2({
            multiple: false,
            //Allow manually entered text in drop down.
            createSearchChoice: function (term, data) {
                if ($(data).filter(function () {
                    return this.text.localeCompare(term) === 0;
                }).length === 0) {
                    return {id: term, text: term};
                }
            },
            ajax: {
                url: '/api/public/search/institution',
                dataType: 'json',
                type: "GET",
                delay: 300,
                data: function (params) {
                    return {
                        q: '(.*)'+params+'(.*)',
                        verified: true
                    };
                },
                results: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                slug: item.name,
                                id: item.id
                            };
                        })
                    };
                },
                cache: true
            },
//            escapeMarkup: function (markup) {
//                return markup;
//            }, // let our custom formatter work
            minimumInputLength: 1
//            templateResult: formatResult, 
//            templateSelection: formatSelection

        });
    },
    addFileForm: function (params) {
        $("#step4").append(Mustache.render($("#step4_tpl").html(), params));
        this.setupUi();
    },
    removeAuthor: function ($el) {
        $el.parents(".author-item").first().remove();
    },
    step0: function (actionUrl) {
        var form = $("#step0-container form").serialize();
        var status = OjsArticleSubmission.licenceCheck();
        if (status === false) {
            return;
        }
        OjsCommon.waitModal();
        $.post(actionUrl, form, function (resp) {
            if (resp.submissionId) {
                OjsArticleSubmission.submissionId = resp.submissionId;
                $("input[name=submissionId]").attr('value', resp.submissionId);
                OjsArticleSubmission.hideAllSteps();
                OjsArticleSubmission.prepareStep.step1();
                OjsCommon.hideallModals();
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        });
    },
    licenceCheck: function () {
        var checkboxes = $("#step0-container input[type=checkbox]");
        var status = true;
        checkboxes.each(function () {
            if ($(this).is(':checked') === false) {
                status = false;
                OjsCommon.errorModal("Please check all licence field!");
            }
        });
        return status;
    },
    step1: function (actionUrl) {
        forms = $("#step1 form");
        $primaryLang = $("select[name=primaryLanguage] option:selected").val();
        // prepare post params
        articleParams = false;
        translationParams = [];
        var hasError =false;
        MathJax.Hub.Config({
            extensions: ['tex2jax.js', "TeX/AMSmath.js", "TeX/AMSsymbols.js"],
            tex2jax: {inlineMath: [["$", "$"], ["\\(", "\\)"]]},
            jax: ["input/TeX", "output/HTML-CSS"],
            displayAlign: "center",
            displayIndent: "0.1em",
            showProcessingMessages: false
        });

        forms.each(function () {
            if(!$(this).validationEngine('validate')){
                hasError = true;
            }
            data = $(this).serializeObject();
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

        if(hasError){
            OjsCommon.errorModal("Please select and fill metadata for article's language!");
            return;
        }
        if (!articleParams) {
            OjsCommon.errorModal("Please select and fill metadata for article's language.");
            return;
        }

        OjsCommon.waitModal();
        if (translationParams) {
            articleParams.data.translations = JSON.stringify(translationParams);
        }
        articleParams.data.submissionId = $("input[name=submissionId]").val();
        articleParams.data.section = $("select[name=section]").val();

        if(!$("#section").val()){
            OjsCommon.errorModal("Please select a section for article.");
            return;
        }
        articleParams.data.section = $("#section").val();
        $.post(articleParams.postUrl, articleParams.data, function (response) {
            OjsCommon.hideallModals();
            if (response.submissionId) {
                OjsArticleSubmission.submissionId = response.submissionId;
                $("input[name=submissionId]").attr('value', response.submissionId);
                OjsArticleSubmission.hideAllSteps();
                OjsArticleSubmission.prepareStep.step2();
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        }).fail(function(){
            OjsCommon.errorModal("Something went wrong while sumitting. Please try again.");
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
        var hasError = false;
        forms.each(function () {
            if(!$(this).validationEngine('validate')){
                hasError = true;
            }
            dataArray.push($("form", this).serializeObject());
        });

        if (hasError) {
            OjsCommon.errorModal("Please fill required fields.");
            return;
        }

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
        forms = $("form", $(".cite-item"));
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
        } else {
            OjsCommon.hideallModals();
            OjsArticleSubmission.hideAllSteps();
            OjsArticleSubmission.prepareStep.step4();
        }
    },
    step4: function (actionUrl) {
        forms = $("form.file-item");
        if (forms.length > 0) {
            $primaryLang = $("select[name=primaryLanguage] option:selected").val();
            // prepare post params 
            var dataArray = [];
            forms.each(function () {
                dataArray.push($(this).serializeObject());
            });
            OjsCommon.waitModal();
            $.post(actionUrl, {"filesData": JSON.stringify(dataArray), "submissionId": OjsArticleSubmission.submissionId}, function (response) {
                OjsArticleSubmission.hideAllSteps();
                if (response.redirect) {
                    window.onbeforeunload = null;
                    window.location.href = response.redirect;
                } else {
                    OjsCommon.errorModal("Something is wrong. Check your data and try again.");
                }
            }).error(function () {
                OjsCommon.errorModal("Something is wrong. Check your data and try again.");
            });
        }
    },
    submit: function () {
        var licenceCheck = OjsArticleSubmission.licenceCheck();
        var check = confirm("Are you sure to submit your article?");
        if (check === true && licenceCheck === true) {
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
        step0: function () {
            OjsArticleSubmission.configureProgressBar(0);
            OjsArticleSubmission.showStep(0, true);
        },
        step1: function () {
            OjsCommon.scrollTop();
            if ($("#step1").html().length > 0) {
                OjsArticleSubmission.configureProgressBar(1);
                OjsArticleSubmission.loadStepTemplate(1);
            }
            OjsArticleSubmission.showStep(1);

        },
        step2: function () {
            OjsCommon.scrollTop();
            if ($("#step2").html().length > 0) {
                OjsArticleSubmission.configureProgressBar(2);
            }
            OjsArticleSubmission.addAuthorForm();
            OjsArticleSubmission.showStep(2);
            OjsArticleSubmission.setupUi();
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
            OjsArticleSubmission.setupUi();
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
        
        $("textarea.editor").wysihtml5({
            toolbar: {
                "font-styles": false,
                "emphasis": true,
                "lists": false,
                "html": false,
                "link": true,
                "image": false,
                "color": false,
                "blockquote": true}
        });
    },
    step1ScrollToLangPanel: function (lang) {
        $('html, body').animate({
            scrollTop: $("#lang_" + lang).offset().top
        }, 500);
    }
};

$(document).ready(function () {
    OjsArticleSubmission.setupUi();

});
