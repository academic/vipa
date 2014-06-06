$(document).ready(function() {
    $("#addArticleCitationInline").click(function(e) {
        e.preventDefault();
        $("#citationContainer").append($("#citationInfoFields").html());
        refreshCitationOrders();
    });

    $("#citationContainer").on("click", "a.removeArticleCitationInline", function(e) {
        e.preventDefault();
        $(this).parent().remove();
        refreshCitationOrders();
    });

    function refreshCitationOrders() {
        $("#citationContainer input[name=orderNum]").each(function(index) {
            console.log(index + 1);
            $(this).attr("value", index + 1);
        });
    }
});