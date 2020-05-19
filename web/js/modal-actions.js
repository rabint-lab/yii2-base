/*!
 * Codebase - v3.0.0
 * @author pixelcave - https://pixelcave.com
 * Copyright (c) 2018
 */

$(document).ready(function () {

    $('[data-toggle="block-option"][data-action="fullscreen_toggle"]').click(function () {
        var t = $(this);
        var $block = $(t).closest(".block");
        if ($block.hasClass("block-mode-fullscreen")) {
            $block.removeClass('block-mode-fullscreen');
            $block.find('canvas').hide().delay(100).fadeIn();
            t.html('<i class="si si-size-fullscreen"></i>');
        } else {
            $block.addClass('block-mode-fullscreen');
            t.html('<i class="si si-size-actual"></i>');
        }
    });

    $('[data-toggle="block-option"][data-action="content_toggle"]').click(function () {
        var t = $(this);
        var $block = $(t).closest(".block");
        if ($block.hasClass("block-mode-hidden")) {
            $block.removeClass('block-mode-hidden');
            t.html('<i class="si si-arrow-up"></i>');
        } else {
            $block.addClass('block-mode-hidden');
            t.html('<i class="si si-arrow-down"></i>');
        }
    });

//      r._lPage.off("click.cb.blocks"), r._lPage.on("click.cb.blocks", '[data-toggle="block-option"]', function (e) {
//         n._uiApiBlocks($(e.currentTarget).closest(".block"), $(e.currentTarget).data("action"))
//     })
// }, fullscreen_toggle
// :
//
// function () {
//     e.removeClass("block-mode-pinned").toggleClass("block-mode-fullscreen"), e.hasClass("block-mode-fullscreen") ? $(e).scrollLock("enable") : $(e).scrollLock("disable"), a.length && (e.hasClass("block-mode-fullscreen") ? $("i", a).removeClass(i).addClass("si si-size-actual") : $("i", a).removeClass("si si-size-actual").addClass(i))
// }
//
// ,
// fullscreen_on:function () {
//     e.removeClass("block-mode-pinned").addClass("block-mode-fullscreen"), $(e).scrollLock("enable"), a.length && $("i", a).removeClass(i).addClass("si si-size-actual")
// }
// ,
// fullscreen_off:function () {
//     e.removeClass("block-mode-fullscreen"), $(e).scrollLock("disable"), a.length && $("i", a).removeClass("si si-size-actual").addClass(i)
// }
// ,
// content_toggle:function () {
//     e.toggleClass("block-mode-hidden"), t.length && (e.hasClass("block-mode-hidden") ? $("i", t).removeClass("si si-arrow-up").addClass("si si-arrow-down") : $("i", t).removeClass("si si-arrow-down").addClass("si si-arrow-up"))
// }
// ,
// content_hide:function () {
//     e.addClass("block-mode-hidden"), t.length && $("i", t).removeClass("si si-arrow-up").addClass("si si-arrow-down")
// }
// ,
// content_show:function () {
//     e.removeClass("block-mode-hidden"), t.length && $("i", t).removeClass("si si-arrow-down").addClass("si si-arrow-up")
// }
// ,
// state_toggle:function () {
//     e.toggleClass("block-mode-loading"), $('[data-toggle="block-option"][data-action="state_toggle"][data-action-mode="demo"]', e).length && setTimeout(function () {
//         e.removeClass("block-mode-loading")
//     }, 2e3)
// }
// ,
// state_loading:function () {
//     e.addClass("block-mode-loading")
// }
// ,
// state_normal:function () {
//     e.removeClass("block-mode-loading")
// }
// ,
// pinned_toggle:function () {
//     e.removeClass("block-mode-fullscreen").toggleClass("block-mode-pinned")
// }
// ,
// pinned_on:function () {
//     e.removeClass("block-mode-fullscreen").addClass("block-mode-pinned")
// }
// ,
// pinned_off:function () {
//     e.removeClass("block-mode-pinned")
// }
// ,
// close:function () {
//     e.addClass("d-none")
// }
// ,
// open:function () {
//     e.removeClass("d-none")
// }
// }
// ;"init" === l ? s[l]() : (e = o instanceof jQuery ? o : $(o)).length && (a = $('[data-toggle="block-option"][data-action="fullscreen_toggle"]', e), t = $('[data-toggle="block-option"][data-action="content_toggle"]', e), s[l] && s[l]())
// }
// },
// {
//     key:"init", value
// :
//
//     function () {
//         this._uiInit()
//     }
// }
// ,
// {
//     key:"layout", value
// :
//
//     function (e) {
//         this._uiApiLayout(e)
//     }
// }
// ,
// {
//     key:"blocks", value
// :
//
//     function (e, a) {
//         this._uiApiBlocks(e, a)
//     }
// }
// ,
// {
//     key:"loader", value
// :
//
//     function (e, a) {
//         this._uiHandlePageLoader(e, a)
//     }
// }
// ,
// {
//     key:"helpers", value
// :
//
//     function (e) {
//         var a = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
//         r.run(e, a)
//     }
// }
// ,
// {
//     key:"helper", value
// :
//
//     function (e) {
//         var a = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
//         r.run(e, a)
//     }
// }
// ])&&
// i(a.prototype, t), n && i(a, n), e
// }
// ();


});