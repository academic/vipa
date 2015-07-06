var OjsArticleSubmission = {
    submissionId: null,
    step: null,
    languages: [],
    activatedSteps: {"step0": true, "step1": false, "step2": false, "step3": false, "step4": false},
    showResumeNote: function () {

        var html = '<a href="author/article/submit/resume/' + this.submissionId + '#' + this.step + '">' + this.submissionId + '</a>';
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
        this.step = step;
        window.location.hash = step;
    },
    hideStep: function (step) {
        $("#step" + step + "-container").slideUp("fast", 200, "easeInBack");
    },
    removeAllAuthorForms: function () {
        if(confirm("are you sure")){
            $(".author-item").remove();
            OjsArticleSubmission.recalculateAuthorFormCount();
        }
        return;
    },
    authorFormCount: function () {
        return $(".author-item").length;
    },
    recalculateAuthorFormCount: function () {
        $("#submissionAuthorCount").val(OjsArticleSubmission.authorFormCount());
    },
    addAuthorForm: function (params) {
        $("#step3").append(Mustache.render($("#step3_tpl").html(), params));
        OjsArticleSubmission.recalculateAuthorFormCount();
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
                url: Routing.generate('api_get_institutions'), dataType: 'json', type: "GET", delay: 300,
                data: function (params) {
                    return {q: params, verified: true};
                },
                results: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {text: item.name, slug: item.name, id: item.id};
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    },
    addFileForm: function (params) {
        $("#step5").append(Mustache.render($("#step5_tpl").html(), params));
        this.setupUi();
    },
    removeAuthor: function ($el) {
        $el.parents(".author-item").first().remove();
    },
    removeCitation: function(element) {
        $(element).closest('.well').remove();
    },
    addCitationForm: function () {
        $("#step4").append(Mustache.render($("#step4_tpl").html()));
        this.setupUi();
    },
    step1: function (actionUrl) {
        var form = $("#step1-container form").serialize();
        var status = OjsArticleSubmission.submissionChecklist();
        if (status === false) {
            return;
        }
        OjsCommon.waitModal();
        $.post(actionUrl, form, function (response) {
            if (response.success == 1) {
                window.location = response.resumeLink;
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        });
    },
    submissionChecklist: function () {
        var checkboxes = $("#step0-container input[type=checkbox]");
        var status = true;
        checkboxes.each(function () {
            if ($(this).is(':checked') === false) {
                status = false;
                OjsCommon.errorModal("Please check all fields!");
            }
        });
        return status;
    },
    step2: function (actionUrl) {
        form = $("form[name=ojs_article_submission_step2]");
        if (!form.validationEngine('validate')) {
            hasError = true;
            return;
        }
        articleParams = [];
        articleParams.postUrl = actionUrl;
        articleParams.data = form.serializeObject();
        articleParams.data.submissionId = OjsArticleSubmission.submissionId;

        OjsCommon.waitModal();
        $.post(articleParams.postUrl, articleParams.data, function (response) {
            OjsArticleSubmission.hideAllSteps();
            OjsCommon.hideallModals();
            if (response.success == 1) {
                OjsArticleSubmission.prepareStep.step3();
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        }).fail(function () {
            OjsCommon.errorModal("Something went wrong while sumitting. Please try again.");
        });
    },
    step3: function (actionUrl) {
        formPanels = $(".author-item");
        if (formPanels.length === 0) {
            OjsCommon.errorModal("Add at least one author.");
            return false;
        }
        // prepare post params
        authorParams = false;
        var dataArray = [];
        var hasError = false;
        formPanels.each(function () {
            form = $("form", $(this));
            if (!form.validationEngine('validate')) {
                hasError = true;
            }
            dataArray.push(form.serializeObject());
        });
        if (hasError) {
            OjsCommon.errorModal("Please fill required fields.");
            return;
        }
        OjsCommon.waitModal();
        $.post(actionUrl, {
            "authorsData": JSON.stringify(dataArray),
            "submissionId": OjsArticleSubmission.submissionId
        }, function (response) {
            OjsArticleSubmission.hideAllSteps();
            OjsCommon.hideallModals();
            if (response.success == 1) {
                OjsArticleSubmission.prepareStep.step4();
            } else {
                OjsCommon.errorModal("Error occured. Check your data and please <b>try again</b>.");
            }
        }).error(function () {
            OjsCommon.errorModal("Something is wrong. Check your data and try again.");
        });
    },
    step4: function (actionUrl) {
        forms = $("form[name=article_submission_citation]");

        // prepare post params
        var dataArray = [];
        if (forms.length > 0) {
            forms.each(function () {
                dataArray.push($(this).serializeObject());
            });
        }
        OjsCommon.waitModal();
        $.post(actionUrl, {
            "citeData": JSON.stringify(dataArray),
            "submissionId": OjsArticleSubmission.submissionId
        }, function (response) {
            OjsCommon.hideallModals();
            OjsArticleSubmission.hideAllSteps();
            OjsArticleSubmission.prepareStep.step5();
        }).error(function () {
            OjsCommon.errorModal("Something is wrong. Check your data and try again.");
        });
    },
    step5: function (actionUrl) {
        forms = $("form.file-item");
        if (forms.length > 0) {
            $primaryLang = $("select[name=primaryLanguage] option:selected").val();
            // prepare post params
            var dataArray = [];
            forms.each(function () {
                dataArray.push($(this).serializeObject());
            });
            OjsCommon.waitModal();
            $.post(actionUrl, {
                "filesData": JSON.stringify(dataArray),
                "submissionId": OjsArticleSubmission.submissionId
            }, function (response) {
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
        var submissionChecklist = OjsArticleSubmission.submissionChecklist();
        var check = confirm("Are you sure to submit your article?");
        if (check === true && submissionChecklist === true) {
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
            OjsArticleSubmission.configureProgressBar(1);
            OjsArticleSubmission.showStep(1, true);
        },
        step2: function () {
            OjsCommon.scrollTop();
            OjsArticleSubmission.configureProgressBar(2);
            OjsArticleSubmission.hideAllSteps();
            OjsArticleSubmission.showStep(2);

        },
        step3: function () {
            OjsCommon.scrollTop();
            if ($("#step3").html().length > 0) {
                OjsArticleSubmission.configureProgressBar(3);
            }
            OjsArticleSubmission.addAuthorForm();
            OjsArticleSubmission.showStep(3);
            OjsArticleSubmission.setupUi();
        },
        step4: function () {
            OjsCommon.scrollTop();
            if ($("#step4").html().length > 0) {
                OjsArticleSubmission.configureProgressBar(4);
                OjsArticleSubmission.loadStepTemplate(4);
            }
            OjsArticleSubmission.showStep(4);
        },
        step5: function () {
            OjsCommon.scrollTop();
            if ($("#step4").html().length > 0) {
                OjsArticleSubmission.configureProgressBar(5);
                OjsArticleSubmission.loadStepTemplate(5);
            }
            OjsArticleSubmission.showStep(5);
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
            $('.previewLink', $(this).parent().parent()).attr('href', 'uploads/journalfiles/' + $obj.files.path + $obj.files.name).removeClass('hide');
            $('.filename', $(this).parent()).attr('value', $obj.files.name);
            $('input[name="article_file_mime_type"]', $(this).parent()).attr('value', $obj.files.size);
            $('input[name="article_file_size"]', $(this).parent()).attr('value', $obj.files.mimeType);
        });
    },
    setupUi: function () {
        this.bindFileUploader();
        $primaryLang = $("select[name=primaryLanguage] option:selected").val();
        $('input[name=keywords], input[name=subjects]').tagsinput({
            tagClass: 'label label-info',
            trimValue: true,
            confirmKeys: [13, 44, 188, 59]
        });
        $('#changeSelectedJournal').on("select2-selecting", function (e) {
            window.location.href = "" + e.val;
        });
        $('.select2-element').select2({placeholder: '', allowClear: true, closeOnSelect: false});
        if (window.editorsReady !== true) {
            $("textarea.editor").wysihtml5({
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
        }
        window.editorsReady = true;
    },
    step1ScrollToLangPanel: function (lang) {
        $('html, body').animate({
            scrollTop: $("#lang_" + lang).offset().top
        }, 500);
    },
    /**
     * gets orcid author
     * @param $orcid
     */
    getOrcidAuthor: function (event, orcidInput) {
        if (event.keyCode != 13) {
            return;
        }
        $orcidInput = $(orcidInput);
        $orcid = $orcidInput.val();
        $actionUrl = $orcidInput.attr('data-url');
        $parent = $orcidInput.closest('div[class="col-md-12"]');
        $togglePanel = $parent.find('.togglePanel');
        OjsCommon.waitModal();
        $.post($actionUrl, {'orcidAuthorId': $orcid}, function (response) {
            OjsCommon.hideallModals();
            if (typeof response['error-desc'] == 'object') {
                if (response['error-desc'] !== null) {
                    OjsCommon.errorModal(response['error-desc'].value);
                    return;
                }
            }
            /*reset div fields*/
            $togglePanel.hide();
            $parent.find('input[name=firstName]').val('');
            $parent.find('input[name=lastName]').val('');
            $parent.find('input[name=email]').val('');
            $parent.find('textarea[name=summary]').val('');
            $bio = response['orcid-profile']['orcid-bio'];
            $personalDetail = $bio['personal-details'];
            $contactDetail = $bio['contact-details'];
            $biography = $bio['biography'];
            if (typeof $personalDetail !== 'undefined') {
                if (typeof $personalDetail['given-names'] !== 'undefined') {
                    $parent.find('input[name=firstName]').val($personalDetail['given-names'].value);
                }
                if (typeof $personalDetail['family-name'] !== 'undefined') {
                    $parent.find('input[name=lastName]').val($personalDetail['family-name'].value);
                }
            }
            if (typeof $contactDetail !== 'undefined') {
                if (typeof $contactDetail['email'] !== 'undefined'
                    && $contactDetail['email'].length > 0) {
                    $parent.find('input[name=email]').val($contactDetail['email'][0].value);
                }
            }
            if (typeof $biography !== 'undefined') {
                if ($biography.value !== '') {
                    $parent.find('textarea[name=summary]').val($biography.value);
                    $togglePanel.slideToggle('fast');
                }
            }
        }).error(function () {
            OjsCommon.errorModal("Something is wrong. Check your data and try again.");
        });
    },
    authorCount: function(authorCountInput){
        getAuthorCountInput = $(authorCountInput);
        authorCount = getAuthorCountInput.val();
        console.log(authorCount);
        currentAuthorCount = OjsArticleSubmission.authorFormCount();
        if (authorCount > currentAuthorCount) {
            for (i = 0; i < (authorCount - currentAuthorCount); i++) {
                OjsArticleSubmission.addAuthorForm();
            }
        } else if (authorCount < currentAuthorCount) {
            $(this).val(currentAuthorCount);
            window.notify("You should remove author forms by clicking 'Remove' button on left bottom corner of every form.", "warning");
        }

    }
};
window.editorsReady = false;
