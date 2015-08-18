var REST_API_BASEURL = "api/";

var OjsApp;
OjsApp = OjsApp || (function () {

})();



var OjsCommon = {
    api: {
        userApikey: null,
        urls: {
            citeParser: "api/citation/parse",
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
    inArray: function (needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (haystack[i] === needle)
                return true;
        }
        return false;
    }
};
