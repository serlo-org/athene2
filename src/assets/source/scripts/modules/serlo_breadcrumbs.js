/**
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author  Jonas Keinholz (jonas.keinholz@serlo.org)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link    https://github.com/serlo-org/athene2 for the canonical source repository
 *
 * Breadcrumb
 *
 */

/*global define*/
define('breadcrumbs', ['jquery'], function ($) {
    'use strict';
    var Breadcrumbs,
        instance,
        defaults;

    defaults = {
        // main wrapper selector
        wrapperId: '#subject-nav-wrapper',

        // breadcrumb selector
        breadcrumbId: '#breadcrumbs',

        // desired height
        height: '45',

        // separator icon
        icon: $('<i>', { class: 'fa fa-angle-left' })
    };

    /**
     * @class Breadcrumbs
     * @param {Object} options See defaults
     *
     * Main constructor
     **/
    Breadcrumbs = function (options) {
        if (!(this instanceof Breadcrumbs)) {
            return new Breadcrumbs(options);
        }

        var self = this,
            elements, len;

        self.options = options ? $.extend({}, defaults, options) : $.extend({}, defaults);

        self.$wrapper = $(this.options.wrapperId);
        self.$breadcrumbs = $(this.options.breadcrumbId);

        self.$breadcrumbs.children().each(function () {
            $(this).find('a').append(self.options.icon.clone());
        });

        elements = this.$breadcrumbs.children().slice(0);
        len = elements.length;

        // queue of shown elements
        self.shownElements = [];
        // stack of hidden elements
        self.hiddenElements = [];

        if (len > 0) {
            elements.each(function (i, el) {
                //dont add subject, topic name and entity name yet
                if (i === 0 || i === len - 2 || i === len - 1) {
                    return true;
                }

                self.shownElements.push($(el));
            });

            self.shownElements.push($(elements[len - 1])); //entity name
            self.shownElements.push($(elements[0])); //subject name
            //topic name (= elements[len-2]) is not added, because it should never be hidden

            self.initDots();
        } else {
            self.$wrapper.addClass('has-no-backlink');
        }

        // adapt height; repeat on resize
        this.adaptHeight();
        $(window).bind('resizeDelay', function () {
            self.adaptHeight();
        });
    };

    /**
     * @method initDots
     */
    Breadcrumbs.prototype.initDots = function () {
        this.$dots = $('<li>', { class: 'hidden' });
        this.$dotsLink = $('<a>', { html: '…'}).append(this.options.icon.clone());
        this.$dots.append(this.$dotsLink);

        this.$breadcrumbs.children().first().after(this.$dots);
    };

    /**
     * @method hasShownElements
     * @return {boolean} true iff there are shown elements
     */
    Breadcrumbs.prototype.hasShownElements = function () {
        return this.shownElements.length > 0;
    };

    /**
     * @method hasHiddenElements
     * @return {boolean} true iff there are hidden elements
     */
    Breadcrumbs.prototype.hasHiddenElements = function () {
        return this.hiddenElements.length > 0;
    };

    /**
     * @method isTooHigh
     * @return {boolean} true iff the wrapper is too high
     */
    Breadcrumbs.prototype.isTooHigh = function () {
        return this.$wrapper.height() > this.options.height;
    };

    /**
     * @method showElement
     *
     * Shows the first hidden element
     */
    Breadcrumbs.prototype.showNextElement = function () {
        var el = this.hiddenElements.pop();
        el.removeClass('hidden');
        this.shownElements.unshift(el);

        // hide suspension points if there are no hidden elements left
        if (!this.hasHiddenElements()) {
            this.$dots.addClass('hidden');
        }
    };

    /**
     * @method hideNextElement
     *
     * Hides the last shown element
     */
    Breadcrumbs.prototype.hideNextElement = function () {
        var el = this.shownElements.shift();
        el.addClass('hidden');
        this.hiddenElements.push(el);

        // show suspension points and update its href
        this.$dots.removeClass('hidden');
        this.$dotsLink.attr('href', el.children().first().attr('href'));
    };

    /**
     * @method adaptHeight
     *
     * Shows as much elements as possible without breaking the wrappers height. Hides exceeding elements.
     */
    Breadcrumbs.prototype.adaptHeight = function () {
        var self = this;

        // try to show more elements
        self.$wrapper.removeClass('backlink-only');

        while (self.hasHiddenElements() && !self.isTooHigh()) {
            self.showNextElement();
        }

        while (self.hasShownElements() && self.isTooHigh()) {
            self.hideNextElement();
        }

        // show backlink only if still to high
        if (self.isTooHigh()) {
            self.$wrapper.addClass('backlink-only');
        }
    };

    /**
     * Breadcrumb constructor wrapper
     * for creating a singleton
     */
    return function (options) {
        // singleton
        return instance || (function () {
                instance = new Breadcrumbs(options);
                return instance;
            }());
    };
});
