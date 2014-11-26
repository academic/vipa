var CitationEditor = {
    addCitationTpl: function (params) {
        $("#citationContainer").append(Mustache.render($("#step3_tpl").html(), params));
    },
    newCitationField: function (citationItem) {
        if (typeof citationItem !== "undefined") {
            $("#citationInfoFields input[name=raw]").attr("value", citationItem.title);
        }

        $("#citationContainer").append('<div id="' + Math.round(Math.random() * 10000000) + '">' + $("#citationInfoFields").html() + '</div>');
        this.refreshCitationOrders();
        $("#citationPasteField").show("fast");
    },
    parseAndAppend: function (txt) {
        OjsCommon.waitModal();
        $.post(OjsCommon.api.urls.citeParser, {"citations": txt, "apikey": OjsCommon.api.userApikey}, function (res) {
            var citationInfoFields = $('#citationInfoFields');
            if (typeof res === "object") {
                for (i in res) {
                    citationItem = res[i];
                    var tmp_citation_div_id = "citation_" + Math.round(Math.random() * 10000000);

                    $("#citationContainer").append('<div id="' + tmp_citation_div_id + '">' + $("#citationInfoFields").html() + '</div>');
                    var tmp_citation_div = $("#" + tmp_citation_div_id);
                    var $mustFields = $($("option[value=" + citationItem.type + "]", citationInfoFields)).data("must");
                    var $shouldFields = $($("option[value=" + citationItem.type + "]", citationInfoFields)).data("should");
                    var fields = $mustFields.concat($shouldFields);
                    $(".citationDetailsFields", tmp_citation_div).html("");
                    $('.citation_type option[value=' + citationItem.type + ']', tmp_citation_div).prop('selected', true);
                    for (var i in $mustFields) {
                        $(".citationDetailsFields", tmp_citation_div).append(
                                '<input type="text" class="form-control has-warning" placeholder="' +
                                $mustFields[i] + ' *" name="' + $mustFields[i] + '" /> ');
                    }
                    for (var i in $shouldFields) {
                        $(".citationDetailsFields", tmp_citation_div).append(
                                '<input type="text" class="form-control" placeholder="' +
                                $shouldFields[i] + '" name="' + $shouldFields[i] + '" /> ');
                    }
                    $("input[name=raw]", tmp_citation_div).attr("value", citationItem.raw);
                    $.each(citationItem, function (k, v) {
                        if ($.inArray(k, fields) > -1) {
                            $('.citationDetailsFields input[name=' + k + ']', tmp_citation_div).val(v);
                        }
                    });
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
        $(".citationDetailsFields", $el.parent()).html("");
        for (var i in $mustFields) {
            $(".citationDetailsFields", $el.parent()).append(
                    '<input type="text" class="form-control has-warning" placeholder="' +
                    $mustFields[i] + ' *" name="' + $mustFields[i] + '" /> ');
        }
        for (var i in $shouldFields) {
            $(".citationDetailsFields", $el.parent()).append(
                    '<input type="text" class="form-control" placeholder="' +
                    $shouldFields[i] + '" name="' + $shouldFields[i] + '" /> ');
        }
    }
};

$(document).ready(function () {

    $("#citationContainer").on("change", ".citationDetails select", function () {

    });
    $("#citationContainer").on("click", "#addArticleCitationInline", function (e) {
        e.preventDefault();
        CitationEditor.newCitationField();
    });

    $("body").on("click", "a.removeArticleCitationInline", function (e) {
        e.preventDefault();
        $(this).parents().closest(".cite-item ").slideUp();
        $(this).parents().closest(".cite-item").remove();
        CitationEditor.refreshCitationOrders();
    });

    $("body").on("click", ".addCitationDetails", function (e) {
        e.preventDefault();
        $(this).parent().next(".citationDetails").slideToggle("fast");
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