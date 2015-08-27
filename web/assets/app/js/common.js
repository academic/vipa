$(document).ready(function () {
    if ($(".maskissn").length) {
        $(".maskissn").inputmask({
            mask: "####-###M",
            definitions: {'#': {validator: "[0-9]", cardinality: 1}, 'M': {validator: "[0-9X]", cardinality: 1}}
        });
    }
    $(".validate-form").validationEngine({
        promptPosition: 'inline', validateNonVisibleFields: true,
        updatePromptsPosition: true
    });
    $(".wysihtml5").wysihtml5({
        toolbar: {
            "font-styles": false,
            "emphasis": true,
            "lists": false,
            "html": false,
            "link": true,
            "image": false,
            "color": false,
            "blockquote": true
        }
    });

    var tagAutocompleteInput = $('select[data-role=tagsinputautocomplete]');
    tagAutocompleteInput.select2({
        ajax: {
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        templateResult: function (user) {
            return user.text;
        },
        templateSelection: function (user) {
            return user.text;
        },
        width: 'resolve'
    });
    $('.select2-element').select2({width: 'resolve'});


    $('a[title],[rel="tooltip"]').tooltip();
    $(".panel-heading.toggle-body").click(function () {
        $(this).next(".panel-body").slideToggle();
    });


    if ($('#issuetree').length > 0 && typeof alternateData != "undefined") {
        $('#issuetree').treeview({
            showTags: true,
            data: alternateData,
            onNodeSelected: function (event, node) {
                /* @todo we should get issue content by ajax */

            }
        });
    }


    function deleteSubmit(link) {
        var form = $(document.createElement('form')),
            object = $(link);
        object.after(
            form.attr({
                method: 'post',
                action: object.attr('href')
            })
                .append('<input type="hidden" name="_method" value="delete" />')
                .append('<input type="hidden" name="_token" value="' + object.data('token') + '" />')
        );
        form.submit();
    }

    $('a[data-method=delete]').click(function () {
        if ($(this).data('confirm')) {
            if (confirm($(this).data('confirm'))) {
                deleteSubmit(this);
            }
            return false;
        }
        deleteSubmit(this);
        return false;

    });
    $('.select-search-type').click(function () {
        var searchType = $(this).attr('data-type');
        var searchTypeText = $(this).text();
        $('#search-type-text').html(searchTypeText);
        $('#search-type').val(searchType);
    });

    $('.tri').each(function () {
        var elm = $(this);
        var pattern = Trianglify({
            height: 200,
            width: 150,
            cell_size: 3 + Math.random() * 100
        });
        elm.prop('src', pattern.png());
    });

    $('.submission-subform-panel').each(function () {
        var container = $(this).find('.submission-subform-container'),
            template = $(this).find('.submission-subform-template').val();
        $(this).find('.submission-subform-add-panel').on('click', function () {
            var newSrc = $(template.replace(/__name__/g, container.find('.submission-subform').length))
                , uploader;
            newSrc.appendTo(container);
            uploader = newSrc.find('.jb_fileupload');
            if (uploader.length) {
                uploader.jbFileUpload();
            }
        });
        $(this).on('click', '.submission-subform .submission-subform-remove-panel', function () {
            $(this).parent().remove();
        });
    });
});
