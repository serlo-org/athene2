/*global define*/
define([], function () {
    "use strict";
    var LayoutBuilderConfiguration = function () {
        this.layouts = [];
    };

    LayoutBuilderConfiguration.prototype.addLayout = function (layout) {
        this.layouts.push(layout);
        return this;
    };

    return LayoutBuilderConfiguration;
});