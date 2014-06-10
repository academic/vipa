var CitationEditor = {
    newCitationField: function(rawCitation) {
        if (typeof rawCitation !== "undefined") {
            $("#citationInfoFields input[name='raw[]']").attr("value", rawCitation);
        }
        var html = $("#citationInfoFields").html();
        $("#citationContainer").append(html);
        this.refreshCitationOrders();
        $("#citationInfoFields input[name='raw[]']").val();
        $("#citationPasteField").slideUp("fast");
    },
    parseAndAppend: function(txt) {
        items = txt.split("\n");
        for (i in items) {
            if (items[i].length > 0) {
                this.newCitationField(items[i]);
            }
        }
    },
    refreshCitationOrders: function() {
        $("#citationContainer input[name='orderNum']").each(function(index) {
            $(this).attr("value", index + 1);
        });
    }
};

$(document).ready(function() {

    $("#citationContainer").on("change", ".citationDetails select", function() {
        var $mustFields = JSON.parse($("option:selected", $(this)).attr("must"));
        var $shouldFields = JSON.parse($("option:selected", $(this)).attr("should"));
        $(".citationDetailsFields", $(this).parent()).html("");
        for (var i in $mustFields) {
            $(".citationDetailsFields", $(this).parent()).append('<input type="text" class="form-control" placeholder="' + $mustFields[i] + '" name="' + $mustFields[i] + '" /> ');
        }
    });
    $("#addArticleCitationInline").click(function(e) {
        e.preventDefault();
        CitationEditor.newCitationField();
    });

    $("#citationContainer").on("click", "a.removeArticleCitationInline", function(e) {
        e.preventDefault();
        $(this).parents().closest(".form-row ").remove();
        CitationEditor.refreshCitationOrders();
    });

    $("#citationContainer").on("click", "a.addCitationDetails", function(e) {
        e.preventDefault();
        $(this).next().toggle();
    });

    $("#pasteArticleCitationInline").on("click", function(e) {
        e.preventDefault();
        $("#citationPasteField").slideToggle();
    });

    $('textarea.citationPasteTextArea').on('paste', function() {
        var element = this;
        setTimeout(function() {
            var txt = $(element).val();
            CitationEditor.parseAndAppend(txt);
        }, 100);
    });


    var citeDetails = [];
    $("#saveArticleCitation").on("click", function() {
        $(".cite-item").each(function() {
            if ($("select[name='type']", $(this)).val().length !== 0) {
                var details = {};
                details.type = $("select[name='type']", $(this)).val();
                details.orderNum = $("input[name=orderNum]", $(this)).val();
                details.raw = $("input[name=raw]", $(this)).val();
                details.settings = {};
                $(".citationDetailsFields input", $(this)).each(function() {
                    details['settings'][$(this).attr('name')] = $(this).val();
                });
                citeDetails.push(details);

            }
        });
        $.post("", {cites: JSON.stringify(citeDetails)}, function(resp) {
            resp = JSON.parse(resp);
            if (resp.redirect){
                window.location.href = resp.redirect;
            }else{
                
            }
        });
    });
});