// to serialize form data as Json object
$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
$.fn.setTypeForHook = function () {
    this.each(function () {
        this.type = 'source';
    });
    return this;
};

$(document).ready(function () {
    if ($(".maskissn").length) {
        $(".maskissn").inputmask({
            mask: "####-###M",
            definitions: {'#': {validator: "[0-9]", cardinality: 1}, 'M': {validator: "[0-9X]", cardinality: 1}}
        });
    }
    $(".contrastColor").each(function () {
        $(".contrastColor").css("color", (parseInt($(this).css("backgroundColor"), 16) > 0xffffff / 2) ? 'black' : 'white');
    });
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
        }
    });
    $('.select2-element').select2();

    $(document).on('pjax:send', function () {
        $('#loading').show();
    });
    $(document).on('pjax:complete', function () {
        $('#loading').hide();
    });

    $('a[title]').tooltip();
    $(".panel-heading.toggle-body").click(function () {
        $(this).next(".panel-body").slideToggle();
    });

    /* float placeholders to labels */
    $('.float-label').jvFloat();

    //Loads the correct sidebar on window load,
    //collapses the sidebar on window resize.

    $(window).bind("load resize", function () {
        //console.log($(this).width());
        if ($(this).width() < 768) {
            $('div.sidebar-collapse').addClass('collapse');
        } else {
            $('div.sidebar-collapse').removeClass('collapse');
        }
    });
    if ($('#issuetree').length > 0 && typeof alternateData != "undefined") {
        $('#issuetree').treeview({
            showTags: true,
            data: alternateData,
            onNodeSelected: function (event, node) {
                /* @todo we should get issue content by ajax */
                // $.pjax({url: node.href, container: '#issue-container'})

                console.log(node.text + ':' + node.href);
            }
        });
    }

    var $btnSets = $('#responsive'),
        $btnLinks = $btnSets.find('a');

    $btnLinks.click(function (e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.user-menu>div.user-menu-content").removeClass("active");
        $("div.user-menu>div.user-menu-content").eq(index).addClass("active");
    });

    $("[rel='tooltip']").tooltip();

    $('.view').hover(
        function () {
            $(this).find('.caption').slideDown(250); //.fadeIn(250)
        },
        function () {
            $(this).find('.caption').slideUp(250); //.fadeOut(205)
        }
    );
    window.alert = function (message) {
        var n = noty({
            text: message,
            type: "alert",
            dismissQueue: true,
            layout: 'center',
            theme: "relax",
            closeWith: ['button', 'click'],
            maxVisible: 20,
            modal: true
        });
    };
    window.notify = function (message, type) {
        var n = noty({
            text: message,
            type: type,
            dismissQueue: true,
            layout: 'topCenter',
            theme: "relax",
            closeWith: ['button', 'click'],
            maxVisible: 20,
            modal: false
        });
    };
});


/*
 * SIDEBAR MENU
 * ------------
 * This is a custom plugin for the sidebar menu. It provides a tree view.
 * 
 * Usage:
 * $(".sidebar).tree();
 * 
 * Note: This plugin does not accept any options. Instead, it only requires a class
 *       added to the element that contains a sub-menu.
 *       
 * When used with the sidebar, for example, it would look something like this:
 * <ul class='sidebar-menu'>
 *      <li class="treeview active">
 *          <a href="#>Menu</a>
 *          <ul class='treeview-menu'>
 *              <li class='active'><a href=#>Level 1</a></li>
 *          </ul>
 *      </li>
 * </ul>
 * 
 * Add .active class to <li> elements if you want the menu to be open automatically
 * on page load. See above for an example.
 */
(function ($) {
    "use strict";

    $.fn.tree = function () {

        return this.each(function () {
            var btn = $(this).children("a").first();
            var menu = $(this).children(".treeview-menu").first();
            var isActive = $(this).hasClass('active');

            //initialize already active menus
            if (isActive) {
                menu.show();
                btn.children(".fa-angle-left").first().removeClass("fa-angle-left").addClass("fa-angle-down");
            }
            //Slide open or close the menu on link click
            btn.click(function (e) {
                e.preventDefault();
                if (isActive) {
                    //Slide up to close menu
                    menu.slideUp();
                    isActive = false;
                    btn.children(".fa-angle-down").first().removeClass("fa-angle-down").addClass("fa-angle-left");
                    btn.parent("li").removeClass("active");
                } else {
                    //Slide down to open menu
                    menu.slideDown();
                    isActive = true;
                    btn.children(".fa-angle-left").first().removeClass("fa-angle-left").addClass("fa-angle-down");
                    btn.parent("li").addClass("active");
                }
            });

            /* Add margins to submenu elements to give it a tree look */
            menu.find("li > a").each(function () {
                var pad = parseInt($(this).css("margin-left")) + 10;

                $(this).css({"margin-left": pad + "px"});
            });

        });

    };
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

    $('.submission-subform-panel').each(function() {
        var container = $(this).find('.submission-subform-container'),
            template = $(this).find('.submission-subform-template').val();
        $(this).find('.submission-subform-add-panel').on('click', function() {
            var newSrc = $(template.replace(/__name__/g, container.find('.submission-subform').length))
                , uploader;
            newSrc.appendTo(container);
            uploader = newSrc.find('.jb_fileupload');
            if(uploader.length) {
                uploader.jbFileUpload();
            }
        });
        $(this).on('click', '.submission-subform .submission-subform-remove-panel', function() {
            $(this).parent().remove();
        });
    });

}(jQuery));
