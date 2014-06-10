$(document).ready(function() {
    $("#addArticleCitationInline").click(function(e) {
        e.preventDefault();
        $("#citationContainer").append($("#citationInfoFields").html());
        refreshCitationOrders();
    });

    $("#citationContainer").on("click", "a.removeArticleCitationInline", function(e) {
        e.preventDefault();
        $(this).parents().closest(".form-row ").remove();
        refreshCitationOrders();
    });

    $("#citationContainer").on("click", "a.addCitationDetails", function(e) {
        e.preventDefault();
        $(this).next().slideToggle('fast');
    });


    function refreshCitationOrders() {
        $("#citationContainer input[name='orderNum[]']").each(function(index) {
            console.log(index + 1);
            $(this).attr("value", index + 1);
        });
    }
});