$(document).ready(function() {
    
    $("#addArticleCitationInline").click(function(e) {
        e.preventDefault();
        $("#citationContainer").append($("#citationInfoFields").html());
    });

    $("#citationContainer").on("click", "a.removeArticleCitationInline", function() {
        $(this).parent().remove();
    });
});