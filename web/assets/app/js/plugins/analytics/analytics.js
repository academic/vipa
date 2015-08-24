var analytics = {
    view: function (entity, id, token) {
        var path = 'api_' + entity + '_view';
        var url = Routing.generate(path, {id: id, _format: 'json'}, true);
        var data = { token: token };
        $.ajax({ url: url, data: data, method: 'POST' });
    }
};