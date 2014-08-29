var REST_API_BASEURL = "/api/";

var OjstrApp;
OjstrApp = OjstrApp || (function() {
    
})();

var modalTypes = [];
modalTypes.default = BootstrapDialog.TYPE_DEFAULT;
modalTypes.info = BootstrapDialog.TYPE_INFO;
modalTypes.primary = BootstrapDialog.TYPE_PRIMARY;
modalTypes.success = BootstrapDialog.TYPE_SUCCESS;
modalTypes.warning = BootstrapDialog.TYPE_WARNING;
modalTypes.danger = BootstrapDialog.TYPE_DANGER;



var OjstrCommon = {
    errorModal: function(message, title) {
        BootstrapDialog.show({
            title: title ? title : 'Error',
            message: message,
            type: modalTypes.error
        });
    },
    hideallModals: function() {
        BootstrapDialog.closeAll();
    },
    waitModal: function(customWaitingMessage) {
        BootstrapDialog.show({
            title: 'Wait',
            message: customWaitingMessage ? customWaitingMessage : 'Please wait...',
            type: modalTypes.info,
            closable: false
        });
    },
    inArray: function(needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (haystack[i] === needle)
                return true;
        }
        return false;
    }
};