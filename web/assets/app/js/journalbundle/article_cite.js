var CitationEditor = {
    newCitationField: function (rawCitation) {
        if (typeof rawCitation !== "undefined") {
            $("#citationInfoFields input[name=raw]").attr("value", rawCitation);
        }
        var html = $("#citationInfoFields").html();
        $("#citationContainer").append(html);
        this.refreshCitationOrders();
        $("#citationInfoFields input[name='raw[]']").val();
        $("#citationPasteField").show("fast");
    },
    parseAndAppend: function (txt) {
        items = txt.split("\n");
        for (i in items) {
            if (items[i].length > 0) {
                this.newCitationField(items[i]);
            }
        }
    },
    refreshCitationOrders: function () {
        $("#citationContainer input[name='orderNum']").each(function (index) {
            $(this).attr("value", index + 1);
        });
    },
    citationTypeSelected: function ($el) {
        var $mustFields = JSON.parse($("option:selected", $el).attr("must"));
        var $shouldFields = JSON.parse($("option:selected", $el).attr("should"));
        $(".citationDetailsFields", $el.parent()).html("");
        for (var i in $mustFields) {
            $(".citationDetailsFields", $el.parent()).append(
                    '<input type="text" class="form-control" placeholder="' +
                    $mustFields[i] + '" name="' + $mustFields[i] + '" /> ');
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