jQuery(document).ready(function($) {
    $('[data-toggle="tooltip"]').tooltip();
});
// jQuery(function () {
//     $hash = window.location.hash;
//     ele = $($hash);
//     if (ele.length > 0) {
//         $('html, body').animate({
//             scrollTop: $($hash).offset().top - 50
//         }, 2000);
//     }
// });
/* =================================================================== */
jQuery(function() {
    $('body').on('submit', '.staticNonAjaxForm', function(e) {
        $(this).find('[type="submit"]').attr('disabled', 'disabled');
    });
});
/* =================================================================== */
jQuery(function() {
    $('body').on('click', function(e) {
        $('.popover.closeByBody').each(function(i) {
            /* popoverX ===================================================== */

            $target = $(e.target).attr('data-target');
            if (undefined == $target) {
                $target = $(e.target).parents('[data-toggle=popover-x]').attr('data-target');
            }
            $myId = $(this).attr('id');
            if (
                $target != '#' + $myId &&
                !$(this).is(e.target) &&
                $(this).has(e.target).length === 0 &&
                $('.popover').has(e.target).length === 0
            ) {
                $(this).find('[data-dismiss="popover-x"]').click();
            }
            /* popover ====================================================== */

            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
    /* ################################################################### */
    $('body').on('click', 'a.disabled', function(event) {
        event.preventDefault();
    });
});
/* =================================================================== */
/* =================================================================== */


function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function addParamToUrl(url, params) {
    if (url.indexOf('?') > 0) {
        return url + '&' + params;
    } else {
        return url + '?' + params;
    }
}
/* =================================================================== */

function numberFormat(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

/* =================================================================== */

function enNumber(ret) {
    $farsi_array = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "."];
    $farsi_array = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫"];

    $english_array = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "."];

    ret += "";
    for ($i = 0; $i < 11; $i++)
        for ($j = 0; $j < ret.length; $j++)
            ret = ret.replace($farsi_array[$i], $english_array[$i]);
    return ret;
}

/**
 * @deprecated
 * @param $str
 * @return mixed
 */
function faNumber(ret) {
    $farsi_array = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "."];
    $farsi_array = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹", "٫"];

    $english_array = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "."];

    ret += "";
    for ($i = 0; $i < 11; $i++)
        for ($j = 0; $j < ret.length; $j++)
            ret = ret.replace($english_array[$i], $farsi_array[$i]);
    return ret;
}

/* =================================================================== */

jQuery(document).ready(function($) {
    $firstSlug = $('.slugGenerateDestination').val();
    if ($firstSlug == '') {
        $('.slugGenerateSource').change(function() {
            $source = $(this).val();
            var $slug = '';
            $slug = $.trim($source);
            //            $slug = $slug.replace($special_chars, '');
            $slug = $slug.replace(/ /g, '-');
            $slug = $slug.toLowerCase();
            $slug = $slug.replace('%20', '-');
            $slug = $slug.replace('+', '-');
            $slug = $slug.replace(/[\r\n\t -]+/, '-');
            $slug = $slug.replace(' ', '-');
            $slug = $slug.replace('"', '-');
            $slug = $slug.replace("'", '-');
            $slug = $slug.replace("\n", '-');
            $slug = $slug.replace("\t", '-');
            $special_chars = ["\\?", "\\؟", "\\[", "\\]", "\\/", "\\\\", "=", "<", ">", ":", ";", ",", "'", "\"", "\\&", "\\$", "\\#", "\\*", "\\(", "\\)", "\\|", "~", "`", "\\!", "\\{", "\\}"];
            for ($i = 0; $i < $special_chars.length; $i++) {
                re = new RegExp($special_chars[$i], "g");
                $slug = $slug.replace(re, '');
            }
            //                    .replace('/#\x{00a0}#siu/g', '-')
            $('.slugGenerateDestination').val($slug);
        });
    }
});

function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0)
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

/* =================================================================== */

function copyToClipboard($selctor, $prompt) {
    var copyTextarea = document.querySelector($selctor);
    copyTextarea.select();
    try {
        document.execCommand('copy');
    } catch (err) {
        if ($prompt = '') {
            $prompt = "لطفا کلید CTRL+C را فشار دهید. سپس کلید اینتر را بزنید";
        }
        text = $($selctor).html();
        window.prompt($prompt, text);
    }
}


function selectText(containerid) {

    var node = document.getElementById(containerid);

    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(node);
        range.select();
    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNodeContents(node);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
    }
}

$.fn.size = function() {
    return this.lenght;
};


$(document).ready(function() {
    $('table thead th').each(function(i){
        $label=$(this).text();
        $('tr td:nth-child('+(i+1)+')').attr('data-label',$label);
    });
});


$(document).on('keyup','.select2-container input',function (e) {
    var type=$(e.target).val();
    type=type.replace(/ي/g, "ی");
    type=type.replace(/ك/g, 'ک');
    type=type.replace(/ة/g, "ه");
    type=type.replace(/٤/g, "۴");
    type=type.replace(/٥/g, "۵");
    type=type.replace(/٦/g, "۶");
    $(e.target).val(type);
    //$('.select2-search__field').trigger("input").trigger("change");
    $(e.target).find('.select2-search__field').trigger("input").trigger("change");
});