var REST_API_BASEURL = "/api/";

var OjstrApp;
OjstrApp = OjstrApp || (function() {

})();



var OjstrCommon = {
    scrollTop: function() {
        $('html, body').animate({
            scrollTop: 0
        }, {
            duration: 200,
            specialEasing: {
                width: "linear",
                height: "easeOutBounce"
            }}
        );
    },
    modalTypes: {
        default: BootstrapDialog.TYPE_DEFAULT,
        info: BootstrapDialog.TYPE_INFO,
        primary: BootstrapDialog.TYPE_PRIMARY,
        success: BootstrapDialog.TYPE_SUCCESS,
        warning: BootstrapDialog.TYPE_WARNING,
        danger: BootstrapDialog.TYPE_DANGER
    },
    errorModal: function(message, title) {
        BootstrapDialog.show({
            title: title ? title : 'Warning',
            message: message,
            type: OjstrCommon.modalTypes.danger
        });
    },
    hideallModals: function() {
        BootstrapDialog.closeAll();
    },
    waitModal: function(customWaitingMessage) {
        BootstrapDialog.show({
            title: 'Wait',
            message: customWaitingMessage ? customWaitingMessage : 'Please wait...',
            type: OjstrCommon.modalTypes.info,
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