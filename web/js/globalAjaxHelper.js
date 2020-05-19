/**
 * gelobal js helper
 * dependencies: jquery  ,toaster , mask
 */

jQuery(function ($) {


    // /**
    //  * global ajax preloader handler
    //  * @author mojtaba akbarzadeh
    //  */
    // $( document ).ajaxSend(function( event, jqxhr, settings ) {
    //     $actionName =settings.url.substr(settings.url.lastIndexOf("/")+1);
    //     if ($actionName != "croneJobsAdmin" ) {
    //         $('#ajaxContainerLoader').fadeIn()
    //     }
    // });
    //
    // $(document).ajaxStart(function () {
    //     // $('#ajaxContainerLoader').fadeIn()
    // });
    // $(document).ajaxComplete(function () {
    //     $('#ajaxContainerLoader').fadeOut()
    // });

    // $(document).ajaxError(function () {
    //     toastr.clear();
    //     toastr.error('خطا در ارتباط با سروریس دهنده, لطفا دوباره تلاش کنید .');
    // });

    function ajaxShowPreloader() {
        $('#ajaxContainerLoader').fadeIn()
    }

    function ajaxHidePreloader() {
        $('#ajaxContainerLoader').fadeOut()
    }

    /**
     * Create form by div
     * @author mojtaba akbarzadeh

     * @example
     * <div
     *     class="ajaxSendForm"
     *     data-action="<?= current_full_url(); ?>"
     *     data-method="post"
     *     data-success="doOnSuccess(data)"
     *     data-error="doOnError(data)"
     *     data-target="#resutlContainer"
     *     data-replace-target="#resutlContainer"
     *     >
     *
     *     <input type="hidden" name="data" value="value" />
     *
     *     <!--send button must have "sendForm" class -->
     *      <input type="button" class="sendForm" />
     *
     *
     * </div>
     */
    $(document).on('click', 'div.ajaxSendForm .sendForm', function (e) {

        $randId = "tmpfrom" + (Math.random().toString().substr(2));
        $('<form id="' + $randId + '" style="display: none;" ></form>').clone().appendTo('body');

        $form = $("#" + $randId);

        $div = $(this).closest("div.ajaxSendForm");


        $($div).each(function () {
            $.each(this.attributes, function () {
                // this.attributes is not a plain object, but an array
                // of attribute nodes, which contain both the name and value
                if (this.specified) {
                    //console.log(this.name, this.value);

                    $prop = this.name;
                    $prpValue = this.value;
                    if ($prop == "class") {
                        return true;
                    }
                    var formAttrs = ["action", "autocomplete", "enctype", "method", "name"];
                    if ($prop.indexOf('data-') === 0) {
                        $noDataProp = $prop.substr(5);
                        if (formAttrs.indexOf($noDataProp) >= 0) {
                            $($form).attr($noDataProp, $prpValue);
                        } else {
                            $($form).attr($prop, $prpValue);
                        }
                    } else {
                        $($form).attr($prop, $prpValue);
                    }
                }
            });
        });

        $($div).find(":input").each(function () {
            if (this.name == "" || typeof (this.name) == undefined) {
                return true;
            }
            $name = $(this).attr("name");
            $val = $(this).val();
            // if ($(this).attr("type") == "file") {
            //     file_data = $(this).prop('files')[0];
            //     form_data.append(this.name, file_data);
            // } else
            if ($(this).attr("type") == "checkbox" || $(this).attr("type") == "radio") {
                if ($(this).is(':checked')) {
                    $($form).append('<input type="hidden" name="' + $name + '" value="' + $val + '" />')
                }
            } else {
                $($form).append('<input type="hidden" name="' + $name + '" value="' + $val + '" />')
            }
        });

        $($form).addClass("ajaxSendForm");
        $($form).submit();

    });

    /**
     * global ajax form sender
     * @author mojtaba akbarzadeh
     *
     * @example
     * <form
     *     class="ajaxSendForm"
     *     action="<?= current_full_url(); ?>"
     *     method="post"
     *     data-success="doOnSuccess(data)"
     *     data-error="doOnError(data)"
     *     data-target="#resutlContainer"
     *     data-replace-target="#resutlContainer"
     *     >
     *        <input type="hidden" name="data" value="value" />
     *        <input type="submit" />
     * </form>
     */
    $(document).on('submit', 'form.ajaxSendForm', function (e) {
        e.preventDefault();
        $form = $(this);
        $method = $(this).attr("method");
        if (typeof $method === typeof undefined || $method === false) {
            $method = "GET";
        } else {
            $method.toUpperCase();
        }

        $formData = $($form).data();

        $ajaxConf = {
            type: $method,
            cache: false,
            url: $(this).attr('action'),
            beforeSend: function () {
                ajaxShowPreloader();
            },
            complete: function () {
                ajaxHidePreloader();
            },
            success: function (data) {

                /**
                 * custom action
                 */
                if (typeof($formData.success) !== "undefined") {
                    var callback = $formData.success
                    var x = eval(callback)
                    if (typeof x == 'function') {
                        x();
                    }
                    return;
                }

                /**
                 * custom action
                 */
                if (typeof($formData.error) !== "undefined") {
                    var callback = $formData.error
                    var x = eval(callback)
                    if (typeof x == 'function') {
                        x();
                    }
                    return;
                }

                if (typeof(data.code) !== "undefined") {
                    if (data.code == 1) {
                        toastr.clear();
                        toastr.success(data.msg);
                    } else {
                        toastr.clear();
                        toastr.error(data.msg);
                    }
                }




                $redirect = false;

                if(typeof data.indexOf === 'function' && data.indexOf('redirect:')===0){
                    $redirect= data.substr(9);
                }

                if(typeof data.indexOf === 'function' && data.indexOf(' redirect:')===0){
                    $redirect = data.substr(10);
                }

                if (typeof(data.redirect) !== "undefined") {
                    $redirect = data.redirect;
                }

                if ($redirect) {
                    /**
                     * fix url
                     */
                    $loc = data.redirect;
                    if ($loc.indexOf('#') >= 0)
                        $loc = data.redirect.substr(data.redirect.indexOf('#') + 1);

                    if ($loc.indexOf('?') <= 0) {
                        $loc += "?" + Date.now();
                    } else {
                        $loc += "&" + Date.now();
                    }

                    /**
                     * if target is set ? load redirect content in Target
                     */

                    if (typeof($formData.target) !== "undefined") {
                        $.get($loc).done(function (data) {
                            $($formData.target).html(data);
                        });
                        return;
                    }
                    if (typeof($formData.replaceTarget) !== "undefined") {
                        $.get($loc).done(function (data) {
                            $($formData.replaceTarget).replaceWith(data);
                        });
                        return;
                    }

                    /**
                     * redirect if need
                     */
                    //todo fix redirect $.address.value($loc);
//                         console.log("loc="+$loc);
                    return;
                }




                /**
                 * data-replace-target="#ajaxContainer"
                 * Custom onSuccess : replace respond content with selected container
                 */
                if (typeof($formData.replaceTarget) !== "undefined") {
                    $ajaxConf.dataType = "html";
                    if (typeof(data.msg) !== "undefined") {
                        data = data.msg;
                    }
                    $($formData.replaceTarget).replaceWith(data);
                    return;
                }

                /**
                 * data-target="#ajaxContainer"
                 * Custom onSuccess : set respond content into selected container
                 */
                if (typeof($formData.target) !== "undefined") {
                    $ajaxConf.dataType = "html";
                    if (typeof(data.msg) !== "undefined") {
                        data = data.msg;
                    }
                    $($formData.target).html(data);
                    return;
                }

                if (typeof(data.code) !== "undefined") {
                    if (data.code == 1) {
                        toastr.clear();
                        toastr.success(data.msg);
                    } else {
                        toastr.clear();
                        toastr.error(data.msg);
                    }
                }

            },
            error: function () {
                toastr.clear();
                toastr.error("پاسخ درستی از سرویس دهنده دریافت نشد. لطفا بعدا تلاش کنید.");
            },
            failure: function () {
                toastr.clear();
                toastr.error("پاسخ درستی از سرویس دهنده دریافت نشد. لطفا بعدا تلاش کنید.");
            }
        };
        if ($($form).find('[type="file"]').length) {
            var form_data = new FormData();
            $($form).find(':input').each(function () {
                if (this.name == "" || typeof (this.name) == undefined) {
                    return true;
                }
                if ($(this).attr("type") == "file") {
                    file_data = $(this).prop('files')[0];
                    form_data.append(this.name, file_data);
                } else if ($(this).attr("type") == "checkbox" || $(this).attr("type") == "radio") {
                    if ($(this).is(':checked')) {
                        form_data.append(this.name, $(this).val());
                    }
                } else {
                    form_data.append(this.name, $(this).val());
                }
            });
            //console.log(form_data);
            $ajaxConf.data = form_data;
            $ajaxConf.cache = false;
            $ajaxConf.contentType = false;
            $ajaxConf.processData = false;
        } else {
            $ajaxConf.data = $(this).serialize();
        }
        /**
         *  data-respond-type="json"
         */
        if (typeof($formData.respondType) !== "undefined") {
            $ajaxConf.dataType = $formData.respondType;
        } else {
            $ajaxConf.dataType = "json";
        }

        //console.log($ajaxConf);
        $.ajax($ajaxConf);

    });

    /**
     * global ajax get inner html by post
     * @author mojtaba akbarzadeh
     */
    $(document).on('click', '.ajaxLoadPostContent', function (e) {
        e.preventDefault();

        $url = $(this).attr('href');
        $params = $(this).data('params');
        $target = $(this).data('target');
        // console.log($url);
        // console.log($params);
        // console.log($target);
        $.ajax({
            type: "POST",
            data: $params,
            url: $url,
            beforeSend: function () {
                ajaxShowPreloader();
            },
            complete: function () {
                ajaxHidePreloader();
            },
            // processData: false,
            success: function (responseText) {
                data = responseText;
                $redirect = false;

                if(typeof data.indexOf === 'function' && data.indexOf('redirect:')===0){
                    $redirect= data.substr(9);
                }

                if(typeof data.indexOf === 'function' && data.indexOf(' redirect:')===0){
                    $redirect = data.substr(10);
                }

                if (typeof(data.redirect) !== "undefined") {
                    $redirect = data.redirect;
                }
                if ($redirect) {

                    /**
                     * fix url
                     */
                    $loc = $redirect
                    if ($loc.indexOf('#') >= 0)
                        $loc = $redirect.substr($redirect.indexOf('#') + 1);

                    if ($loc.indexOf('?') <= 0) {
                        $loc += "?" + Date.now();
                    } else {
                        $loc += "&" + Date.now();
                    }

                    $.get($loc).done(function (data) {
                        $($target).html(data);
                    });
                    return;
                }
                $($target).html(responseText);
            },
            failure: function (response) {
                toastr.clear();
                toastr.error('خطا اتصال  دوباره تلاش کنید .');
            },
            error: function (xhr, status, error) {
                toastr.clear();
                toastr.error('خطا اتصال  دوباره تلاش کنید .');
            }
        });
    });


    /**
     * global ajax get inner html
     * @author mojtaba akbarzadeh
     */
    $(document).on('click', '.ajaxLoadContent', function (e) {
        e.preventDefault();

        $url = $(this).data('url');
        $params = $(this).data('params');
        $target = $(this).data('target');
        $method = $(this).data('method');
        $formData = $(this).data();

        if (typeof($formData.method) !== "undefined") {
            $type = $formData.method;
        } else {
            $type = "GET";
        }
        // console.log($url);
        // console.log($params);
        // console.log($target);
        $('#ajaxContainerLoader').fadeIn('fast')
        $.ajax({
            type: $type,
            data: $params,
            url: $url,
            beforeSend: function () {
                ajaxShowPreloader();
            },
            complete: function () {
                ajaxHidePreloader();
            },
            // processData: false,
            success: function (responseText) {
                data = responseText;
                $redirect = false;

                if(typeof data.indexOf === 'function' && data.indexOf('redirect:')===0){
                    $redirect= data.substr(9);
                }

                if(typeof data.indexOf === 'function' && data.indexOf(' redirect:')===0){
                    $redirect = data.substr(10);
                }

                if (typeof(data.redirect) !== "undefined") {
                    $redirect = data.redirect;
                }
                if ($redirect) {

                    /**
                     * fix url
                     */
                    $loc = $redirect
                    if ($loc.indexOf('#') >= 0)
                        $loc = $redirect.substr($redirect.indexOf('#') + 1);

                    if ($loc.indexOf('?') <= 0) {
                        $loc += "?" + Date.now();
                    } else {
                        $loc += "&" + Date.now();
                    }

                    $.get($loc).done(function (data) {
                        $($target).html(data);
                    });
                    return;
                }

                $($target).html(responseText);
                $('#ajaxContainerLoader').fadeOut()
            },
            failure: function (response) {
                toastr.clear();
                toastr.error('خطا اتصال  دوباره تلاش کنید .');
                $('#ajaxContainerLoader').fadeOut()

            },
            error: function (xhr, status, error) {
                toastr.clear();
                toastr.error('خطا اتصال  دوباره تلاش کنید .');
                $('#ajaxContainerLoader').fadeOut()
            }
        });
    });


    /**
     * global ajax a sender
     * @author mojtaba akbarzadeh
     *
     * @example
     *
     * <div class="ajaxLinkBox" data-method="get|POST" data-replace-target="" data-target="" data-respond-type="json|html|text|raw" >
     *    <a href="link"></a>
     * </div>
     */
    $(document).on('click', '.ajaxLinkBox a', function (e) {
        e.preventDefault();
        $box = $(this).parents(".ajaxLinkBox");
        $method = $($box).attr("data-method");
        if (typeof $method === typeof undefined || $method === false) {
            $method = "GET";
        } else {
            $method.toUpperCase();
        }

        $boxData = $($box).data();

        $ajaxConf = {
            type: $method,
            cache: false,
            url: $(this).attr('href'),
            beforeSend: function () {
                ajaxShowPreloader();
            },
            complete: function () {
                ajaxHidePreloader();
            },
            success: function (data) {
                /**
                 * data-replace-target="#ajaxContainer"
                 * Custom onSuccess : replace respond content with selected container
                 */
                if (typeof($boxData.replaceTarget) !== "undefined") {
                    $ajaxConf.dataType = "html";
                    $($boxData.replaceTarget).replaceWith(data);
                    return;
                }

                /**
                 * data-target="#ajaxContainer"
                 * Custom onSuccess : set respond content into selected container
                 */
                if (typeof($boxData.target) !== "undefined") {
                    $ajaxConf.dataType = "html";
                    $($boxData.target).html(data);
                    return;
                }

                if (typeof(data.code) !== "undefined") {
                    if (data.code == 1) {
                        toastr.clear();
                        toastr.success(data.msg);
                    } else {
                        toastr.clear();
                        toastr.error(data.msg);
                    }
                }
                if (typeof(data.redirect) !== "undefined") {
                    /**
                     * fix url
                     */
                    $loc = data.redirect;
                    if ($loc.indexOf('#') >= 0)
                        $loc = data.redirect.substr(data.redirect.indexOf('#') + 1);

                    if ($loc.indexOf('?') <= 0) {
                        $loc += "?" + Date.now();
                    } else {
                        $loc += "&" + Date.now();
                    }

                    /**
                     * if target is set ? load redirect content in Target
                     */

                    if (typeof($boxData.target) !== "undefined") {
                        $.get($loc).done(function (data) {
                            $($boxData.target).html(data);
                        });
                        return;
                    }
                    if (typeof($boxData.replaceTarget) !== "undefined") {
                        $.get($loc).done(function (data) {
                            $($boxData.replaceTarget).replaceWith(data);
                        });
                        return;
                    }

                    /**
                     * redirect if need
                     */
                    //todo fix redirect  $.address.value($loc);
//                         console.log("loc="+$loc);
                    return;
                }
            },
            error: function () {
                toastr.clear();
                toastr.error("پاسخ درستی از سرویس دهنده دریافت نشد. لطفا بعدا تلاش کنید.");
            },
            failure: function () {
                toastr.clear();
                toastr.error("پاسخ درستی از سرویس دهنده دریافت نشد. لطفا بعدا تلاش کنید.");
            }
        };

        /**
         *  data-respond-type="json"
         */
        if (typeof($boxData.respondType) !== "undefined") {
            $ajaxConf.dataType = $boxData.respondType;
        } else {
            $ajaxConf.dataType = "html";
        }

        // console.log($ajaxConf);
        $.ajax($ajaxConf);
    });

    /**
     *
     * @param $url
     * @param $params
     * @param $method
     * @param $config
     *
     * @example
     *
     *  simpleAjaxRequest(
     *      'http://gl.com',
     *      {
     *         name:'ali',
     *         age:26'
     *      },
     *      'GET',
     *      {
     *          success: function(){}
     *          error: function(){}
     *          replaceTarget: "#targetId"
     *          target: "#targetId"
     *      }
     *  );
     *
     *
     */
    jQuery.simpleAjaxRequest=function simpleAjaxRequest($url, $params, $method, $config) {

        $params = $params || {};
        $method = $method || "GET";
        $formData = $config || {};

        $ajaxConf = {
            type: $method,
            cache: false,
            data: $params,
            url: $url,
            beforeSend: function () {
                ajaxShowPreloader();
            },
            complete: function () {
                ajaxHidePreloader();
            },
            success: function (data, textStatus, jqXHR) {

                /**
                 * custom action
                 */
                if (typeof($formData.success) !== "undefined") {
                    $formData.success(data, textStatus, jqXHR)
                    // var x = eval(callback)
                    // if (typeof x == 'function') {
                    //     x();
                    // }
                    return;
                }

                if (typeof(data.redirect) !== "undefined") {
                    /**
                     * fix url
                     */
                    $loc = data.redirect;
                    if ($loc.indexOf('#') >= 0)
                        $loc = data.redirect.substr(data.redirect.indexOf('#') + 1);

                    if ($loc.indexOf('?') <= 0) {
                        $loc += "?" + Date.now();
                    } else {
                        $loc += "&" + Date.now();
                    }

                    /**
                     * if target is set ? load redirect content in Target
                     */

                    if (typeof($formData.target) !== "undefined") {
                        $.get($loc).done(function (data) {
                            $($formData.target).html(data);
                        });
                        return;
                    }
                    if (typeof($formData.replaceTarget) !== "undefined") {
                        $.get($loc).done(function (data) {
                            $($formData.replaceTarget).replaceWith(data);
                        });
                        return;
                    }

                    /**
                     * redirect if need
                     */
                    //todo fix redirect $.address.value($loc);
//                         console.log("loc="+$loc);
                    return;
                }


                if (typeof(data.code) !== "undefined") {
                    if (data.code == 1) {
                        toastr.clear();
                        toastr.success(data.msg);
                    } else {
                        toastr.clear();
                        toastr.error(data.msg);
                    }
                    return;
                }

                /**
                 * data-replace-target="#ajaxContainer"
                 * Custom onSuccess : replace respond content with selected container
                 */
                if (typeof($formData.replaceTarget) !== "undefined") {
                    $ajaxConf.dataType = "html";
                    if (typeof(data.msg) !== "undefined") {
                        data = data.msg;
                    }
                    $($formData.replaceTarget).replaceWith(data);
                    return;
                }

                /**
                 * data-target="#ajaxContainer"
                 * Custom onSuccess : set respond content into selected container
                 */
                if (typeof($formData.target) !== "undefined") {
                    $ajaxConf.dataType = "html";
                    if (typeof(data.msg) !== "undefined") {
                        data = data.msg;
                    }
                    $($formData.target).html(data);
                    return;
                }

                if (typeof(data.code) !== "undefined") {
                    if (data.code == 1) {
                        toastr.clear();
                        toastr.success(data.msg);
                    } else {
                        toastr.clear();
                        toastr.error(data.msg);
                    }
                }

            },
            error: function (jqXHR,textStatus, errorThrown) {

                /**
                 * custom action
                 */
                if (typeof($formData.error) !== "undefined") {
                    $formData.error(jqXHR,textStatus, errorThrown);
                    // var x = eval(callback)
                    // if (typeof x == 'function') {
                    //     x();
                    // }
                    return;
                }
                toastr.clear();
                toastr.error("پاسخ درستی از سرویس دهنده دریافت نشد. لطفا بعدا تلاش کنید.");
            },
            failure: function () {
                toastr.clear();
                toastr.error("پاسخ درستی از سرویس دهنده دریافت نشد. لطفا بعدا تلاش کنید.");
            }
        };

        /**
         *  data-respond-type="json"
         */
        if (typeof($formData.respondType) !== "undefined") {
            $ajaxConf.dataType = $formData.respondType;
        } else {
            $ajaxConf.dataType = "json";
        }

        //console.log($ajaxConf);
        $.ajax($ajaxConf);
    }

    /**
     * GLOBAL FORMATTERS:
     */
    if (!$.fn.mask) {
        console.log('globalHelper: Mask plugin not included in this page');
    } else {
        /**
         * mask example:
         *  $('.time').mask('00:00:00');
         *  $('.date_time').mask('00/00/0000 00:00:00');
         *  $('.cep').mask('00000-000');
         *  $('.phone').mask('0000-0000');
         *  $('.phone_with_ddd').mask('(00) 0000-0000');
         *  $('.phone_us').mask('(000) 000-0000');
         *  $('.mixed').mask('AAA 000-S0S');
         *  $('.cpf').mask('000.000.000-00', {reverse: true});
         *  $('.money').mask('000.000.000.000.000,00', {reverse: true});
         */

        /**
         * shaba formatter
         *
         * usage :  add  data-formatter="shaba"  to input
         *
         */
        var shabaFormat = "00-0000-0000-0000-0000-0000-00";
        $('[data-formatter="shaba"]').mask(shabaFormat);
        $(document).ajaxComplete(function () {
            $('[data-formatter="shaba"]').mask(shabaFormat);
        });

        /**
         * number formatter
         */


        /**
         * money formatter
         *
         * usage :  add  data-formatter="money"  to input
         *
         */
        var moneyFormat = '000.000.000.000.000.000';
        $('[data-formatter="money"]').mask(moneyFormat, {reverse: true});
        $(document).ajaxComplete(function () {
            $('[data-formatter="money"]').mask(moneyFormat, {reverse: true});
        });
    }

});

