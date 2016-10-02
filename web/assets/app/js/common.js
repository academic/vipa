$(document).ready(function () {
    moment.locale(current_language);


    window_width = $(window).width();

    if (window_width >= 992){
        big_image = $('.wrapper > .header');

        $(window).on('scroll', materialKitDemo.checkScrollForParallax);
    }
    /**
     * Usage of abbr ago
     *<abbr title="{{ post.created|date('Y-m-d H:i:s') }}"  class="ago">{{ post.created|date('Y-m-d H:i:s') }}</abbr>
     **/

    $('abbr.ago').each(function () {
        if (moment($(this).attr('title')).isValid()) {
            $(this).livestamp($(this).attr('title'));
        }
    });

    $('.download-link').on('click', function (e) {
        var elm = $(this);
        download(elm.data('raw'), elm.data('filename'), elm.data('mime'));
        e.preventDefault();

    });

    $('[data-toggle="tooltip"]').tooltip();

    $(".js-scroll-to").click(function (e) {
        var destination = $(this).data('href');
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $(destination).offset().top
        }, 500);
    });

    $('#fab .dropdown-menu>li').each(function () {
        $(this).attr('data-original-title', $.trim($(this).find('a').text()));
        $(this).find('a').html($(this).find('a i')[0].outerHTML);
    });

    $(".maskissn").each(function () {
        $(this).inputmask({
            mask: "####-###M",
            definitions: {'#': {validator: "[0-9]", cardinality: 1}, 'M': {validator: "[0-9X]", cardinality: 1}}
        });
    });
    $(".validate-form").validationEngine({
        promptPosition: 'inline', validateNonVisibleFields: true,
        updatePromptsPosition: true
    });

    $('.wysihtml5').each(function () {
        var wysihtml5 = $(this);
        wysihtml5.summernote({
            height: 100,                 // set editor height

            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor

            focus: false,                 // set focus to editable area after initializing summernote
            toolbar: [
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['codeview']]
            ]
        });

        $('form').on('submit', function () {
            if (wysihtml5.summernote('isEmpty')) {
                wysihtml5.val('');
            } else if (wysihtml5.val() == '<p><br></p>') {
                wysihtml5.val('');
            }
        });

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
        }
    });
    $('.select2-element').select2({
        language: current_language
    });

    $('a[data-toggle="tab"]').on('hidden.bs.tab', function (e) {
        $("select[data-role=tagsinputautocomplete]").select2({
            language: current_language,
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
            }
        });
    });


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
            var newSrc = $(template.replace(/__name__/g, getRandomIntInclusive(100, 1000)))
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

    $('.confirm_action').on('click', function (e) {
        var elm = $(this);
        swal({
            title: elm.data('title'),
            text: elm.data('text'),
            type: elm.data('type'),
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: elm.data('confirmButtonText'),
            cancelButtonText: elm.data('cancelButtonText'),
            closeOnConfirm: false,
            html: true
        }, callback);
        e.preventDefault();
    });

    $('.grid-search-reset').parent().remove();
});
// Returns a random integer between min (included) and max (included)
// Using Math.round() will give you a non-uniform distribution!
function getRandomIntInclusive(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

//Get caret position
function getCaret(el) {
    if (el.prop("selectionStart")) {
        return el.prop("selectionStart");
    } else if (document.selection) {
        el.focus();

        var r = document.selection.createRange();
        if (r == null) {
            return 0;
        }

        var re = el.createTextRange(),
            rc = re.duplicate();
        re.moveToBookmark(r.getBookmark());
        rc.setEndPoint('EndToStart', re);

        return rc.text.length;
    }
    return 0;
}

//Append text at caret position
function appendAtCaret($target, caret, $value) {
    var value = $target.val();
    if (caret != value.length) {
        var startPos = $target.prop("selectionStart");
        var scrollTop = $target.scrollTop;
        $target.val(value.substring(0, caret) + ' ' + $value + ' ' + value.substring(caret, value.length));
        $target.prop("selectionStart", startPos + $value.length);
        $target.prop("selectionEnd", startPos + $value.length);
        $target.scrollTop = scrollTop;
    } else if (caret == 0)
    {
        $target.val($value + ' ' + value);
    } else {
        $target.val(value + ' ' + $value);
    }
}