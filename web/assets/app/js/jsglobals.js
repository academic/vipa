var REST_API_BASEURL = "/api/";

var OjstrApp;
OjstrApp = OjstrApp || (function () {

})();



var OjsCommon = {
    api: {
        userApikey: null,
        urls: {
            citeParser: "/api/citation/parse",
            userSearch: "",
            journalSearc: ""
        },
        call: function (method, callUrl) {
            params = {"apikey": OjsCommon.api.userApikey};
            console.log(params);
            $.ajax(
                    {
                        url: callUrl, data: params,
                        type: method
                    });
        }
    },
    scrollTop: function () {
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
    errorModal: function (message, title) {
        this.hideallModals();
        BootstrapDialog.show({
            title: title ? title : 'Warning',
            message: message,
            type: OjsCommon.modalTypes.danger
        });
    },
    hideallModals: function () {
        BootstrapDialog.closeAll();
    },
    waitModal: function (customWaitingMessage) {
        BootstrapDialog.show({
            title: 'Wait',
            message: customWaitingMessage ? customWaitingMessage : 'Please wait...',
            type: OjsCommon.modalTypes.info,
            closable: false
        });
    },
    inArray: function (needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (haystack[i] === needle)
                return true;
        }
        return false;
    }
};