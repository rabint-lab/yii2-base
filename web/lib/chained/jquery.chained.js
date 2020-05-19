/*
 * Chained - jQuery / Zepto chained selects plugin
 *
 * Copyright (c) 2010-2017 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/chained
 *
 * Version: 2.0.0-beta.3
 *
 */

;(function($, window, document, undefined) {

    $.fn.chained = function(parentSelector) {
        return this.each(function() {

            /* Save this to child because this changes when scope changes. */
            var child   = this;
            /* Handles maximum two parents now. */
            $(parentSelector).each(function() {
                $(this).bind("change", function() {
                    updateChildren();
                });

                /* Force IE to see something selected on first page load, */
                /* unless something is already selected */
                if (!$("option:selected", this).length) {
                    $("option", this).first().attr("selected", "selected");
                }

                /* Force updating the children. */
                updateChildren();
            });

            function updateChildren() {
                var triggerChange = true;
                var currentlySelectedValue = $("option:selected", child).val();

                $(parentSelector).each(function() {
                    var selectedValue = $(this).val();
                    if (selectedValue) {
                        // if (selected.length > 0) {
                        //     selected += "+";
                        // }
                        // selected += selectedValue;
                        $(child).find('option').hide();
                        $(child).find('option.'+selectedValue).show();
                        $(child).find('option[value=""]').show();
                        $(child).val('');
                        $(child).change();
                    }else {
                        $(child).find('option').hide();
                        $(child).find('option[value=""]').show();
                        $(child).val('');
                        $(child).change();
                    }
                });
            }
        });
    };

    /* Alias for those who like to use more English like syntax. */
    $.fn.chainedTo = $.fn.chained;

    /* Default settings for plugin. */
    $.fn.chained.defaults = {};

})(window.jQuery || window.Zepto, window, document);
