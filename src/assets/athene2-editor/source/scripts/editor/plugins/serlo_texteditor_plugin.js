/*global define*/
define(['jquery', 'events', 'translator', 'text!./editor/templates/plugins/default.html', 'jquery.ui.resizable'], function ($, eventScope, t, plugin_template) {
    var EditorPlugin,
        defaults = {};

    EditorPlugin = function (settings) {
        eventScope(this);
        this.settings = $.extend(settings, defaults);
        this.state = this.settings.state;

        this.data = {
            name: 'Plugin',
            content: 'Default Plugin'
        };

        this.template = _.template(plugin_template);
    };

    EditorPlugin.prototype.setData = function (key, value) {
        this.data[key] = value;
        this.updateContentString();
        this.trigger('update', this);
    };

    EditorPlugin.prototype.updateContentString = function () {
        // rebuild markdown query
        this.data.content = '**' + this.name + '**';
    };

    EditorPlugin.prototype.save = function () {
        this.trigger('save');
        return this.data;
    };

    EditorPlugin.prototype.close = function () {
        this.trigger('close');
    };

    EditorPlugin.prototype.render = function () {
        // should be called, after a Plugins $el has been added to the dom
    };

    EditorPlugin.prototype.activate = function () {

        this.$el = $(this.template(this.data));

        this.makeRezisable();

        return this.$el;
    };

    EditorPlugin.prototype.makeRezisable = function () {
        var that = this,
            $iframe = $('iframe', that.$el);

        if (!$('.ui-resizable-se', that.$el).length) {
            $('<div class="ui-resizable-handle ui-resizable-se fa fa-arrows">').appendTo($('.panel-body', that.$el));
        }

        $('.panel-body', that.$el).resizable({
            handles: {
                'se': $('.ui-resizable-se', that.$el)
            },
            resize: function (event, ui) {
                var newWidth = ui.originalSize.width+((ui.size.width - ui.originalSize.width)*2);

                // ui.size.height -= 40;

                $(this).width(newWidth).position({
                    of: that.$el,
                    my: "center center",
                    at: "center center"
                });

                if ($iframe.length) {
                    $iframe.width(newWidth).height(ui.size.height - 140);
                }
            }
        });

    }

    EditorPlugin.prototype.deactivate = function () {
        this.$el.detach();
    };

    EditorPlugin.prototype.getActivateLink = function () {
        return this.widget || (this.widget = $('<a class="editor-widget" href="#">').text(t('Edit %s', this.data.name)));
    };

    return EditorPlugin;
});