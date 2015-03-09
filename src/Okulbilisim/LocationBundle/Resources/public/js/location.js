var okulbilisimlocation = {
    bridge: function (el) {
        $(el).on('change', function () {
            var that = '#' + $(this).attr('id');
            var to = $(this).data('to');
            var country = $(this).val();
            $(to).prop('disabled', true);
            $(that).select2('close');
            //@todo select box not close before finish request
            var cities = okulbilisimlocation.getCities(country);
            $(to).select2('destroy');
            $(to).html('');
            for (var i = 0; i < cities.length; i++) {
                $(to).append('<option value="' + cities[i].id + '">' + cities[i].name + '</option>')
            }
            $(to).select2();
            $(to).prop('disabled', false);
        })
    },
    getCities: function (country) {
        var cities='';
        $.ajax({
            url: '/location/cities/'+country,
            dataType: 'json',
            method: 'GET',
            success: function(d){
                cities = d;
            },
            async: false
        });
        return cities;
    }
};