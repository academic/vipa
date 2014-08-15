/* jshint expr: true */
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define('bootstrap.wysihtml5', ['jquery', 'wysihtml5', 'bootstrap.wysihtml5.templates', 'bootstrap.wysihtml5.commands'], factory);
    } else {
        // Browser globals
        factory(jQuery, wysihtml5);
    }
}(function ($, wysihtml5) {

var bsWysihtml5 = function($, wysihtml5) {
  'use strict';

  var templates = function(key, locale, options) {
    if(wysihtml5.tpl[key]) {
      return wysihtml5.tpl[key]({locale: locale, options: options});
    }
  };

  var Wysihtml5 = function(el, options) {
    this.el = el;
    var toolbarOpts = $.extend(true, {}, defaultOptions, options);
    for(var t in toolbarOpts.customTemplates) {
      wysihtml5.tpl[t] = toolbarOpts.customTemplates[t];
    }
    this.toolbar = this.createToolbar(el, toolbarOpts);
    this.editor =  this.createEditor(toolbarOpts);

    window.editor = this.editor;

    $('iframe.wysihtml5-sandbox').each(function(i, el){
      $(el.contentWindow).off('focus.wysihtml5').on({
        'focus.wysihtml5' : function(){
          $('li.dropdown').removeClass('open');
        }
      });
    });
  };

  Wysihtml5.prototype = {

    constructor: Wysihtml5,

    createEditor: function(options) {
      options = options || {};

      // Add the toolbar to a clone of the options object so multiple instances
      // of the WYISYWG don't break because 'toolbar' is already defined
      options = $.extend(true, {}, options);
      options.toolbar = this.toolbar[0];

      var editor = new wysihtml5.Editor(this.el[0], options);

      // #30 - body is in IE 10 not created by default, which leads to nullpointer
      // 2014/02/13 - adapted to wysihtml5-0.4, does not work in IE
      if(editor.composer.editableArea.contentDocument) {
        this.addMoreShortcuts(editor, editor.composer.editableArea.contentDocument.body || editor.composer.editableArea.contentDocument, options.shortcuts);
      } else {
        this.addMoreShortcuts(editor, editor.composer.editableArea, options.shortcuts);    
      }
      
      if(options && options.events) {
        for(var eventName in options.events) {
          editor.on(eventName, options.events[eventName]);
        }
      }
      return editor;
    },

    createToolbar: function(el, options) {
      var self = this;
      var toolbar = $('<ul/>', {
        'class' : 'wysihtml5-toolbar',
        'style': 'display:none'
      });
      var culture = options.locale || defaultOptions.locale || 'en';
      if(!locale.hasOwnProperty(culture)) {
        console.debug('Locale \'' + culture + '\' not found. Available locales are: ' + Object.keys(locale) + '. Falling back to \'en\'.');
        culture = 'en';
      }
      var localeObject = $.extend(true, {}, locale.en, locale[culture]);
      for(var key in options.toolbar) {
        if(options.toolbar[key]) {
          toolbar.append(templates(key, localeObject, options));

          if(key === 'html') {
            this.initHtml(toolbar);
          }

          if(key === 'link') {
            this.initInsertLink(toolbar);
          }

          if(key === 'image') {
            this.initInsertImage(toolbar);
          }
        }
      }

      toolbar.find('a[data-wysihtml5-command="formatBlock"]').click(function(e) {
        var target = e.delegateTarget || e.target || e.srcElement,
            el = $(target),
            showformat = el.data('wysihtml5-display-format-name'),
            formatname = el.data('wysihtml5-format-name') || el.html();
        if(showformat === undefined || showformat === 'true') {
          self.toolbar.find('.current-font').text(formatname);
        }
      });

      toolbar.find('a[data-wysihtml5-command="foreColor"]').click(function(e) {
        var target = e.target || e.srcElement;
        var el = $(target);
        self.toolbar.find('.current-color').text(el.html());
      });

      this.el.before(toolbar);

      return toolbar;
    },

    initHtml: function(toolbar) {
      var changeViewSelector = 'a[data-wysihtml5-action="change_view"]';
      toolbar.find(changeViewSelector).click(function(e) {
        toolbar.find('a.btn').not(changeViewSelector).toggleClass('disabled');
      });
    },

    initInsertImage: function(toolbar) {
      var self = this;
      var insertImageModal = toolbar.find('.bootstrap-wysihtml5-insert-image-modal');
      var urlInput = insertImageModal.find('.bootstrap-wysihtml5-insert-image-url');
      var insertButton = insertImageModal.find('a.btn-primary');
      var initialValue = urlInput.val();
      var caretBookmark;

      var insertImage = function() {
        var url = urlInput.val();
        urlInput.val(initialValue);
        self.editor.currentView.element.focus();
        if (caretBookmark) {
          self.editor.composer.selection.setBookmark(caretBookmark);
          caretBookmark = null;
        }
        self.editor.composer.commands.exec('insertImage', url);
      };

      urlInput.keypress(function(e) {
        if(e.which == 13) {
          insertImage();
          insertImageModal.modal('hide');
        }
      });

      insertButton.click(insertImage);

      insertImageModal.on('shown', function() {
        urlInput.focus();
      });

      insertImageModal.on('hide', function() {
        self.editor.currentView.element.focus();
      });

      toolbar.find('a[data-wysihtml5-command=insertImage]').click(function() {
        var activeButton = $(this).hasClass('wysihtml5-command-active');

        if (!activeButton) {
          self.editor.currentView.element.focus(false);
          caretBookmark = self.editor.composer.selection.getBookmark();
          insertImageModal.appendTo('body').modal('show');
          insertImageModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function(e) {
            e.stopPropagation();
          });
          return false;
        }
        else {
          return true;
        }
      });
    },

    initInsertLink: function(toolbar) {
      var self = this;
      var insertLinkModal = toolbar.find('.bootstrap-wysihtml5-insert-link-modal');
      var urlInput = insertLinkModal.find('.bootstrap-wysihtml5-insert-link-url');
      var targetInput = insertLinkModal.find('.bootstrap-wysihtml5-insert-link-target');
      var insertButton = insertLinkModal.find('a.btn-primary');
      var initialValue = urlInput.val();
      var caretBookmark;

      var insertLink = function() {
        var url = urlInput.val();
        urlInput.val(initialValue);
        self.editor.currentView.element.focus();
        if (caretBookmark) {
          self.editor.composer.selection.setBookmark(caretBookmark);
          caretBookmark = null;
        }

        var newWindow = targetInput.prop('checked');
        self.editor.composer.commands.exec('createLink', {
          'href' : url,
          'target' : (newWindow ? '_blank' : '_self'),
          'rel' : (newWindow ? 'nofollow' : '')
        });
      };
      var pressedEnter = false;

      urlInput.keypress(function(e) {
        if(e.which == 13) {
          insertLink();
          insertLinkModal.modal('hide');
        }
      });

      insertButton.click(insertLink);

      insertLinkModal.on('shown', function() {
        urlInput.focus();
      });

      insertLinkModal.on('hide', function() {
        self.editor.currentView.element.focus();
      });

      toolbar.find('a[data-wysihtml5-command=createLink]').click(function() {
        var activeButton = $(this).hasClass('wysihtml5-command-active');

        if (!activeButton) {
          self.editor.currentView.element.focus(false);
          caretBookmark = self.editor.composer.selection.getBookmark();
          insertLinkModal.appendTo('body').modal('show');
          insertLinkModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function(e) {
            e.stopPropagation();
          });
          return false;
        }
        else {
          return true;
        }
      });
    },

    addMoreShortcuts: function(editor, el, shortcuts) {
      /* some additional shortcuts */
      wysihtml5.dom.observe(el, 'keydown', function(event) {
        var keyCode  = event.keyCode,
            command  = shortcuts[keyCode];
        if ((event.ctrlKey || event.metaKey || event.altKey) && command && wysihtml5.commands[command]) {
          wysihtml5.commands[command].exec(editor.composer, command);
          event.preventDefault();
        }
      });
    }
  };

  // these define our public api
  var methods = {
    resetDefaults: function() {
      $.fn.wysihtml5.defaultOptions = $.extend(true, {}, $.fn.wysihtml5.defaultOptionsCache);
    },
    bypassDefaults: function(options) {
      return this.each(function () {
        var $this = $(this);
        $this.data('wysihtml5', new Wysihtml5($this, options));
      });
    },
    shallowExtend: function (options) {
      var settings = $.extend({}, $.fn.wysihtml5.defaultOptions, options || {}, $(this).data());
      var that = this;
      return methods.bypassDefaults.apply(that, [settings]);
    },
    deepExtend: function(options) {
      var settings = $.extend(true, {}, $.fn.wysihtml5.defaultOptions, options || {});
      var that = this;
      return methods.bypassDefaults.apply(that, [settings]);
    },
    init: function(options) {
      var that = this;
      return methods.shallowExtend.apply(that, [options]);
    }
  };

  $.fn.wysihtml5 = function ( method ) {
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.wysihtml5' );
    }    
  };

  $.fn.wysihtml5.Constructor = Wysihtml5;

  var defaultOptions = $.fn.wysihtml5.defaultOptions = {
    toolbar: {
      'font-styles': true,
      'color': false,
      'emphasis': {
        'small': true
      },
      'blockquote': true,
      'lists': true,
      'html': false,
      'link': true,
      'image': true,
      'smallmodals': false
    },
    events: {},
    parserRules: {
      classes: {
        'wysiwyg-color-silver' : 1,
        'wysiwyg-color-gray' : 1,
        'wysiwyg-color-white' : 1,
        'wysiwyg-color-maroon' : 1,
        'wysiwyg-color-red' : 1,
        'wysiwyg-color-purple' : 1,
        'wysiwyg-color-fuchsia' : 1,
        'wysiwyg-color-green' : 1,
        'wysiwyg-color-lime' : 1,
        'wysiwyg-color-olive' : 1,
        'wysiwyg-color-yellow' : 1,
        'wysiwyg-color-navy' : 1,
        'wysiwyg-color-blue' : 1,
        'wysiwyg-color-teal' : 1,
        'wysiwyg-color-aqua' : 1,
        'wysiwyg-color-orange' : 1
      },
      tags: {
        'b':  {},
        'i':  {},
        'strong': {},
        'em': {},
        'p': {},
        'br': {},
        'ol': {},
        'ul': {},
        'li': {},
        'h1': {},
        'h2': {},
        'h3': {},
        'h4': {},
        'h5': {},
        'h6': {},
        'blockquote': {},
        'u': 1,
        'img': {
          'check_attributes': {
            'width': 'numbers',
            'alt': 'alt',
            'src': 'url',
            'height': 'numbers'
          }
        },
        'a':  {
          check_attributes: {
            'href': 'url' // important to avoid XSS
          },
          'set_attributes': {
            'target': '_blank',
            'rel': 'nofollow'
          }
        },
        'span': 1,
        'div': 1,
        'small': 1,
        // to allow save and edit files with code tag hacks
        'code': 1,
        'pre': 1
      }
    },
    locale: 'en',
    shortcuts: {
      '83': 'small'     // S
    }
    
  };

  if (typeof $.fn.wysihtml5.defaultOptionsCache === 'undefined') {
    $.fn.wysihtml5.defaultOptionsCache = $.extend(true, {}, $.fn.wysihtml5.defaultOptions);
  }

  var locale = $.fn.wysihtml5.locale = {};
};
bsWysihtml5($, wysihtml5);
}));
