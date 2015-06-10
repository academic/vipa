var CitationEditor = {

    addCitationTpl: function (params) {
        $("#citationContainer").append(Mustache.render($("#step3_tpl").html(), params));
    },
    newCitationField: function (raw) {
        var $tpl = $.parseHTML($("#step3_tpl").html().trim());
        if (typeof raw !== "undefined") {
            $("input[name=raw]", $tpl).attr("value", raw);
        }
        $($tpl).removeClass("hide");
        $("#citationContainer").append($tpl);
        this.refreshCitationOrders();
    },
    parseAndAppend: function (txt, elem) {
        OjsCommon.waitModal();
        $(".citationPasteTextArea").val("");
        $("#citationPasteField").slideUp();
        $.post(OjsCommon.api.urls.citeParser, {"citations": txt, "apikey": OjsCommon.api.userApikey}, function (res) {
            if (typeof res === "object") {
                for (i in res) {
                    citationItem = res[i];
                    var $tpl = $.parseHTML($("#step3_tpl").html().trim());
                    $($tpl).removeClass("hide");
                    var $mustFields = $($("option[value=" + citationItem.type + "]", $tpl)).data("must");
                    var $shouldFields = $($("option[value=" + citationItem.type + "]", $tpl)).data("should");
                    var fields = $mustFields.concat($shouldFields);
                    $(".citationDetailsFields", $tpl).html("");
                    $('.citation_type option[value=' + citationItem.type + ']', $tpl).prop('selected', true);
                    var $citeItemMustTpl = $("#step3_cite_item_must_tpl").html();
                    var $citeItemShouldTpl = $("#step3_cite_item_should_tpl").html();
                    for (var i in $mustFields) {
                        renderedTpl = Mustache.render($citeItemMustTpl, {'field': $mustFields[i], 'value': ''});
                        $(".citationDetailsFields", $tpl).append(renderedTpl);
                    }
                    for (var i in $shouldFields) {
                        renderedTpl = Mustache.render($citeItemShouldTpl, {'field': $shouldFields[i], 'value': ''});
                        $(".citationDetailsFields", $tpl).append(renderedTpl);
                    }
                    $("input[name=raw]", $tpl).attr("value", citationItem.raw);
                    $.each(citationItem, function (k, v) {
                        if ($.inArray(k, fields) > -1) {
                            $('.citationDetailsFields input[name=' + k + ']', $tpl).attr('value', v);
                        }
                    });
                    if (typeof elem === "undefined") {
                        $("#citationContainer").append($tpl);
                    } else {
                        elem.after($tpl);
                        elem.remove();
                    }

                }
            }
        })
            .done(function () {
                CitationEditor.refreshCitationOrders();
                OjsCommon.hideallModals();
            })
            .error(function () {
                OjsCommon.errorModal("We can't parse your citation for now. Please add your citation details below.");
                CitationEditor.parseAndAppendByNewLine(txt);
            });
    },
    parseAndAppendByNewLine: function (txt) {
        items = txt.split("\n");
        for (i in items) {
            if (items[i].length > 0) {
                CitationEditor.newCitationField(items[i]);
            }
        }
    },
    refreshCitationOrders: function () {
        $("#citationContainer input[name='orderNum']").each(function (index) {
            $(this).attr("value", index + 1);
        });
    },
    citationTypeSelected: function ($el) {
        var $mustFields = $($("option:selected", $el)).data("must");
        var $shouldFields = $($("option:selected", $el)).data("should");
        var $targetElm = $('.citationDetailsFields', $el.parent().parent());
        $targetElm.html("");
        for (var i in $mustFields) {
            $targetElm.append(
                '<input type="text" class="form-control input-sm has-warning" placeholder="' +
                $mustFields[i] + ' *" name="' + $mustFields[i] + '" /> ');
        }
        for (var i in $shouldFields) {
            $targetElm.append(
                '<input type="text" class="form-control input-sm " placeholder="' +
                $shouldFields[i] + '" name="' + $shouldFields[i] + '" /> ');
        }
    }
};
$(document).ready(function () {

    $("#citationContainer").on("change", ".citationDetails select", function () {

    });
    $("#addArticleCitationInline").click(function (e) {
        e.preventDefault();
        CitationEditor.newCitationField();
    });

    $("#clearAllCitations").click(function (e) {
        e.preventDefault();
        $(".cite-item").slideUp();
        $(".cite-item").remove();
        CitationEditor.refreshCitationOrders();
    });
    $("body").on("click", "a.removeArticleCitationInline", function (e) {
        e.preventDefault();
        $(this).parents().closest(".cite-item").slideUp();
        $(this).parents().closest(".cite-item").remove();
        CitationEditor.refreshCitationOrders();
    });
    $("body").on("click", "a.parseArticleCitationInline", function (e) {
        e.preventDefault();
        $citeItem = $(this).parents().closest(".cite-item");
        $citeText = $('input[name=raw]', $citeItem).val();
        CitationEditor.parseAndAppend($citeText, $citeItem);
    });
    $("body").on("click", ".addCitationDetails", function (e) {
        e.preventDefault();
        $(this).parent().parent().next(".citationDetails").slideToggle("fast");
    });
    $("body").on("click", "#pasteArticleCitationInline", function (e) {
        e.preventDefault();
        $("#citationPasteField").slideToggle();
    });
    $("body").on("paste", '.citationPasteTextArea', function () {
        var element = this;
        setTimeout(function () {
            var txt = $(element).val();
            CitationEditor.parseAndAppend(txt);
        }, 100);
    });
    $("body").on("click", '.citationPasteActionBtn', function () {
        var element = $('.citationPasteTextArea');
        setTimeout(function () {
            var txt = $(element).val();
            CitationEditor.parseAndAppend(txt);
        }, 100);
    });
    var citeDetails = [];
    $("#saveArticleCitation").on("click", function () {
        $(".cite-item").each(function () {
            if ($("select[name='type']", $(this)).val().length !== 0) {
                var details = {};
                details.type = $("select[name='type']", $(this)).val();
                details.orderNum = $("input[name=orderNum]", $(this)).val();
                details.raw = $("input[name=raw]", $(this)).val();
                details.settings = {};
                $(".citationDetailsFields input", $(this)).each(function () {
                    details['settings'][$(this).attr('name')] = $(this).val();
                });
                citeDetails.push(details);
            }
        });
        $.post(REST_API_BASEURL + "articles/" + articleId + "/bulkcitations", {cites: JSON.stringify(citeDetails)}, function (resp) {
            window.location.href = "/manager/article/" + articleId + "/show";
        });
    });
});
