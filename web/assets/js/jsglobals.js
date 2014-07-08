var REST_API_BASEURL = "/api/";

var OjstrApp;
OjstrApp = OjstrApp || (function() {
    var pleaseWaitDiv = $('<div class="modal" aria-hidden="true" \n\
id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">\n\
<div class="modal-dialog">\n\
<div class="modal-content">\n\
<div class="modal-header"><strong>Processing...</strong></div>\n\
<div class="progress">\n\
<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="90" \n\
aria-valuemin="0" aria-valuemax="100" style="width: 90%"><span class="sr-only">--</span></div></div>\n\
</div></div>');
    return {
        showPleaseWait: function() {
            pleaseWaitDiv.modal();
        },
        hidePleaseWait: function() {
            pleaseWaitDiv.modal('hide');
        }
    };
})();


var OjstrCommon = {
    inArray: function(needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (haystack[i] === needle)
                return true;
        }
        return false;
    }
};