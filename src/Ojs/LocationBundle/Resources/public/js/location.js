$(function() {
    $('.country_selector').on('change', function(){
        var province_selector = $('select.province_selector'),
            country = $(this).val(),
            ajaxUrl = $(this).data('province-source');
        $(this).select2('close');
        province_selector.select2('close');
        province_selector.select2('destroy');

        province_selector.prop('disabled', true);
        province_selector.html('');
        if(ajaxUrl) {
            $.ajax({
                url: ajaxUrl + country,
                dataType: 'json',
                method: 'GET',
                success: function (cities) {
                    var html = '', i;
                    for (i = 0; i < cities.length; i++) {
                        html += '<option value="' + cities[i].id + '">' + cities[i].name + '</option>';
                    }
                    province_selector.html(html);

                    province_selector.prop('disabled', false);
                    province_selector.select2();
                }
            });
        }
    });
});
