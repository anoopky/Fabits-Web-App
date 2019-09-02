$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    $("#female").click(function () {
        $("#female").addClass('cust_border1');
        $("#male").removeClass('cust_border');
        $("#female").removeClass('cust_border');
        $("#male").removeClass('cust_border1');
        $("#Gender-val").val(0);
    });

    $("#male").click(function () {
        $("#male").addClass('cust_border1');
        $("#female").removeClass('cust_border');
        $("#male").removeClass('cust_border');
        $("#female").removeClass('cust_border1');
        $("#Gender-val").val(1);
    });

    $('#myModal').modal({show: false});


    function post_success_manager(data, location, success) {

        switch (success) {
            case 'otp':
                $('#myModal').modal({backdrop: 'static', keyboard: false, show: true,});
                break;

            case 'otp1':
                $('#otpModal').modal({backdrop: 'static', keyboard: false, show: true,});
                break;
            case 'otp2':
                $('#otpModal').modal('hide');
                break;
            case 'load_picture':
                $('#profile_pic_upload').children().first().remove();
                $('#profile_pic_upload').children().first().removeClass("hidden-xs-up");
                $('#profile_pic_upload').children().first().attr("src", data);
                break;

            case 'upload_profile_picture':
                var data = $('#profile_pic_upload').children().first().attr('src');
                $('.myprofile').attr('src', data);
                $('#userinfo').find('img').attr('src', data);
                user['user_picture'] = data;
                $('#profilepicModal').modal('hide');
                break;

            case 'resetInit':
                $('#forgetPasswordModal').modal('hide');
                $('#OtpModal').modal('show');
                break;

            case 'resetOTP':
                $('#OtpModal').modal('hide');
                $('#resetPasswordModal').modal('show');
                break;


            default:
                if (location)
                    window.location.replace(location);
                break;
        }

    }

    function post_Bsuccess_manager(location, success) {

        switch (success) {


            case 'load_picture':
                $('#profile_pic_upload').children().first().addClass("hidden-xs-up");
                $('#profile_pic_upload').prepend('<div class="progress"> ' +
                    '<div id="progressBar" class="progress-bar progressBar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">' +
                    '0% ' +
                    '</div> ' +
                    '</div>');


                break;


        }
    }

    $(document).on('submit', 'form', function (event) {

        event.preventDefault();
        var location = $("input[name='location']", this).val();
        var submit = $("input[type='submit']", this);
        var success = $("input[name='success']", this).val();

        var form_data = new FormData();
        $($(this).prop('elements')).each(function () {
            if (this.type == 'file')
                form_data.append(this.name, this.files[0]);
            else if (this.type == 'checkbox')
                form_data.append(this.name, $(this).prop('checked'));
            else if (this.type == 'radio') {
                if ($(this).prop('checked'))
                    form_data.append(this.name, $(this).attr('value'));
            }
            else {

                form_data.append(this.name, $(this).val());

            }
        });

        $.ajax({
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            url: $(this).attr('action'),
            data: form_data,
            beforeSend: function () {
                $("input[type='submit']").prop('disabled', true);
                $("input[type='file']").prop('disabled', true);
                NProgress.start();
                post_Bsuccess_manager(location, success);

            },
            success: function (data) {
                NProgress.done();
                post_success_manager(data, location, success);
                $("input[type='submit']").prop('disabled', false);
                $("input[type='file']").prop('disabled', false);


            },

            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (success === 'post_upload' || success === 'load_picture' || success === 'load_wall') {
                    if (myXhr.upload) {
                        // For handling the progress of the upload
                        myXhr.upload.addEventListener('progress', function (e) {
                            if (e.lengthComputable) {

                                $(".progressBar").width(((e.loaded / e.total) * 100) + '%'); //update progressbar percent complete
                                if (parseInt((e.loaded / e.total) * 100) == 100) {
                                    $(".progressBar").html('Compressing...'); //update status text

                                } else {
                                    $(".progressBar").html(parseInt((e.loaded / e.total) * 100) + '%'); //update status text

                                }

                                // $('progress').attr({
                                //     value: e.loaded,
                                //     max: e.total,
                                // });
                            }
                        }, false);
                    }
                }
                return myXhr;
            },
            error: function (data) {

                $("input[type='submit']").prop('disabled', false);
                $("input[type='file']").prop('disabled', false);

                $("input[type='file']").prop('disabled', false);

                NProgress.done();
                $.each($.parseJSON(data.responseText), function (idx, obj) {
                    $("#alert_message").html(alertTemplate(obj[0]));
                    $("#alert_message").hide();
                    $("#alert_message").fadeIn();
                    $("#alert_message").css('opacity', '1');
                    $("#alert_message").show();
                    $("#alert_message").fadeOut(5000);


                    return false;
                });
            }
        });

    });

    function get_Bsuccess_manager(success) {

        switch (success) {
            case 'page-menu':
                if (!$('#collapse').hasClass('in'))
                    $('#collapse').addClass('in');
                break;
        }
    }

    function get_success_manager(data, success) {

        switch (success) {


            case 'settings':
                if (data) {
                    $('#settings-content').html(data);
                    $('#settings-list').addClass('z-10');
                    $('#settings-list').removeClass('z-11');
                    $('#settings-content').addClass('z-11');
                    $('#settings-content').removeClass('z-10');
                }
                break;
            case 'settings-back':
                $('#settings-list').addClass('z-11');
                $('#settings-list').removeClass('z-10');
                $('#settings-content').addClass('z-10');
                $('#settings-content').removeClass('z-11');
                break;

            case 'chats-back':

                $('#chats-list').removeClass('hidden-sm-down');
                $('#chats-content').addClass('hidden-sm-down');
                chat_app_conversation_id = null;

                break;
            case 'page':
                $(window).off("scroll");
                $('#page').html('');
                $('#page').html(data);
                $('#profileListModal').modal('hide');
                $('#likeModal').modal('hide');
                $(document).prop('title', $('#ajax-title').attr('data-title'));
                break;

            case 'page-menu':
                $(window).off("scroll");
                $('#page').html('');
                $('#page').html(data);
                $('[data-toggle="tooltip_custom"]').tooltip('hide');
                $(document).prop('title', $('#ajax-title').attr('data-title'));
                break;


            case 'like_list':
                var template = '';
                $.each(data, function (key, val) {
                    template += '<a href="/@' + val.username + '" data-loc="page" class="ba-0 px-1 my-1 py-0 square-corner list-group-item ' +
                        '">' +
                        likelistTemplate(val) +
                        '</a>';
                });
                $("#likedby").html(template);
                $('#likeModal').modal('show');
                break;


        }
    }

    $(document).on('click', 'a', function (event) {
        if ($(this).attr('target') == '_blank')
            return true;
        //
        event.preventDefault();
        var success = $(this).attr('data-loc');

        var url = $(this).attr('href');
        if (success == 'page-full')
            window.location.href = url;

        if (success == 'page-exter')
            window.open(url, '_blank');

        if (!(url.indexOf('#')) || window.location.href === url) {

            get_success_manager(null, success);
        }
        else {
            $.ajax({
                type: "GET",
                url: url,
                beforeSend: function () {
                    $('#search-result').hide();
                    NProgress.start();
                    get_Bsuccess_manager(success);

                },
                success: function (data) {
                    NProgress.done();
                    if (success != "like_list")
                        history.pushState(null, null, url);
                    get_success_manager(data, success);
                },
                error: function (data) {
                    NProgress.done();
                    $.each($.parseJSON(data.responseText), function (idx, obj) {
                        $("#alert_message").html(alertTemplate(obj[0]));
                        $("#alert_message").hide();
                        $("#alert_message").fadeIn();
                        $("#alert_message").css('opacity', '1');
                        $("#alert_message").show();
                        $("#alert_message").fadeOut(5000);

                        return false;
                    });
                }
            });
        }
    });

    function alertTemplate(data) {

        var template = '<div class="alert alert-warning alert-dismissible fade in mb-0 sd-1" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' + data + '</div>';
        return template;
    }

    $('#forget').click(function () {

        $("#forgetPasswordModal").modal("show");
    })

});


;(function (root, factory) {

    if (typeof define === 'function' && define.amd) {
        define(factory);
    } else if (typeof exports === 'object') {
        module.exports = factory();
    } else {
        root.NProgress = factory();
    }

})(this, function () {
    var NProgress = {};

    NProgress.version = '0.2.0';

    var Settings = NProgress.settings = {
        minimum: 0.08,
        easing: 'linear',
        positionUsing: '',
        speed: 350,
        trickle: true,
        trickleSpeed: 250,
        showSpinner: true,
        barSelector: '[role="bar"]',
        spinnerSelector: '[role="spinner"]',
        parent: 'body',
        template: '<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>'
    };

    /**
     * Updates configuration.
     *
     *     NProgress.configure({
   *       minimum: 0.1
   *     });
     */
    NProgress.configure = function (options) {
        var key, value;
        for (key in options) {
            value = options[key];
            if (value !== undefined && options.hasOwnProperty(key)) Settings[key] = value;
        }

        return this;
    };

    /**
     * Last number.
     */

    NProgress.status = null;

    /**
     * Sets the progress bar status, where `n` is a number from `0.0` to `1.0`.
     *
     *     NProgress.set(0.4);
     *     NProgress.set(1.0);
     */

    NProgress.set = function (n) {
        var started = NProgress.isStarted();

        n = clamp(n, Settings.minimum, 1);
        NProgress.status = (n === 1 ? null : n);

        var progress = NProgress.render(!started),
            bar = progress.querySelector(Settings.barSelector),
            speed = Settings.speed,
            ease = Settings.easing;

        progress.offsetWidth;
        /* Repaint */

        queue(function (next) {
            // Set positionUsing if it hasn't already been set
            if (Settings.positionUsing === '') Settings.positionUsing = NProgress.getPositioningCSS();

            // Add transition
            css(bar, barPositionCSS(n, speed, ease));

            if (n === 1) {
                // Fade out
                css(progress, {
                    transition: 'none',
                    opacity: 1
                });
                progress.offsetWidth;
                /* Repaint */

                setTimeout(function () {
                    css(progress, {
                        transition: 'all ' + speed + 'ms linear',
                        opacity: 0
                    });
                    setTimeout(function () {
                        NProgress.remove();
                        next();
                    }, speed);
                }, speed);
            } else {
                setTimeout(next, speed);
            }
        });

        return this;
    };

    NProgress.isStarted = function () {
        return typeof NProgress.status === 'number';
    };

    /**
     * Shows the progress bar.
     * This is the same as setting the status to 0%, except that it doesn't go backwards.
     *
     *     NProgress.start();
     *
     */
    NProgress.start = function () {
        if (!NProgress.status) NProgress.set(0);

        var work = function () {
            setTimeout(function () {
                if (!NProgress.status) return;
                NProgress.trickle();
                work();
            }, Settings.trickleSpeed);
        };

        if (Settings.trickle) work();

        return this;
    };

    /**
     * Hides the progress bar.
     * This is the *sort of* the same as setting the status to 100%, with the
     * difference being `done()` makes some placebo effect of some realistic motion.
     *
     *     NProgress.done();
     *
     * If `true` is passed, it will show the progress bar even if its hidden.
     *
     *     NProgress.done(true);
     */

    NProgress.done = function (force) {
        if (!force && !NProgress.status) return this;

        return NProgress.inc(0.3 + 0.5 * Math.random()).set(1);
    };

    /**
     * Increments by a random amount.
     */

    NProgress.inc = function (amount) {
        var n = NProgress.status;

        if (!n) {
            return NProgress.start();
        } else if (n > 1) {
            return;
        } else {
            if (typeof amount !== 'number') {
                if (n >= 0 && n < 0.25) {
                    // Start out between 3 - 6% increments
                    amount = (Math.random() * (5 - 3 + 1) + 3) / 100;
                } else if (n >= 0.25 && n < 0.65) {
                    // increment between 0 - 3%
                    amount = (Math.random() * 3) / 100;
                } else if (n >= 0.65 && n < 0.9) {
                    // increment between 0 - 2%
                    amount = (Math.random() * 2) / 100;
                } else if (n >= 0.9 && n < 0.99) {
                    // finally, increment it .5 %
                    amount = 0.005;
                } else {
                    // after 99%, don't increment:
                    amount = 0;
                }
            }

            n = clamp(n + amount, 0, 0.994);
            return NProgress.set(n);
        }
    };

    NProgress.trickle = function () {
        return NProgress.inc();
    };

    /**
     * Waits for all supplied jQuery promises and
     * increases the progress as the promises resolve.
     *
     * @param $promise jQUery Promise
     */
    (function () {
        var initial = 0, current = 0;

        NProgress.promise = function ($promise) {
            if (!$promise || $promise.state() === "resolved") {
                return this;
            }

            if (current === 0) {
                NProgress.start();
            }

            initial++;
            current++;

            $promise.always(function () {
                current--;
                if (current === 0) {
                    initial = 0;
                    NProgress.done();
                } else {
                    NProgress.set((initial - current) / initial);
                }
            });

            return this;
        };

    })();

    /**
     * (Internal) renders the progress bar markup based on the `template`
     * setting.
     */

    NProgress.render = function (fromStart) {
        if (NProgress.isRendered()) return document.getElementById('nprogress');

        addClass(document.documentElement, 'nprogress-busy');

        var progress = document.createElement('div');
        progress.id = 'nprogress';
        progress.innerHTML = Settings.template;

        var bar = progress.querySelector(Settings.barSelector),
            perc = fromStart ? '-100' : toBarPerc(NProgress.status || 0),
            parent = document.querySelector(Settings.parent),
            spinner;

        css(bar, {
            transition: 'all 0 linear',
            transform: 'translate3d(' + perc + '%,0,0)'
        });

        if (!Settings.showSpinner) {
            spinner = progress.querySelector(Settings.spinnerSelector);
            spinner && removeElement(spinner);
        }

        if (parent != document.body) {
            addClass(parent, 'nprogress-custom-parent');
        }

        parent.appendChild(progress);
        return progress;
    };

    /**
     * Removes the element. Opposite of render().
     */

    NProgress.remove = function () {
        removeClass(document.documentElement, 'nprogress-busy');
        removeClass(document.querySelector(Settings.parent), 'nprogress-custom-parent');
        var progress = document.getElementById('nprogress');
        progress && removeElement(progress);
    };

    /**
     * Checks if the progress bar is rendered.
     */

    NProgress.isRendered = function () {
        return !!document.getElementById('nprogress');
    };

    /**
     * Determine which positioning CSS rule to use.
     */

    NProgress.getPositioningCSS = function () {
        // Sniff on document.body.style
        var bodyStyle = document.body.style;

        // Sniff prefixes
        var vendorPrefix = ('WebkitTransform' in bodyStyle) ? 'Webkit' :
            ('MozTransform' in bodyStyle) ? 'Moz' :
                ('msTransform' in bodyStyle) ? 'ms' :
                    ('OTransform' in bodyStyle) ? 'O' : '';

        if (vendorPrefix + 'Perspective' in bodyStyle) {
            // Modern browsers with 3D support, e.g. Webkit, IE10
            return 'translate3d';
        } else if (vendorPrefix + 'Transform' in bodyStyle) {
            // Browsers without 3D support, e.g. IE9
            return 'translate';
        } else {
            // Browsers without translate() support, e.g. IE7-8
            return 'margin';
        }
    };

    /**
     * Helpers
     */

    function clamp(n, min, max) {
        if (n < min) return min;
        if (n > max) return max;
        return n;
    }

    /**
     * (Internal) converts a percentage (`0..1`) to a bar translateX
     * percentage (`-100%..0%`).
     */

    function toBarPerc(n) {
        return (-1 + n) * 100;
    }


    /**
     * (Internal) returns the correct CSS for changing the bar's
     * position given an n percentage, and speed and ease from Settings
     */

    function barPositionCSS(n, speed, ease) {
        var barCSS;

        if (Settings.positionUsing === 'translate3d') {
            barCSS = {transform: 'translate3d(' + toBarPerc(n) + '%,0,0)'};
        } else if (Settings.positionUsing === 'translate') {
            barCSS = {transform: 'translate(' + toBarPerc(n) + '%,0)'};
        } else {
            barCSS = {'margin-left': toBarPerc(n) + '%'};
        }

        barCSS.transition = 'all ' + speed + 'ms ' + ease;

        return barCSS;
    }

    /**
     * (Internal) Queues a function to be executed.
     */

    var queue = (function () {
        var pending = [];

        function next() {
            var fn = pending.shift();
            if (fn) {
                fn(next);
            }
        }

        return function (fn) {
            pending.push(fn);
            if (pending.length == 1) next();
        };
    })();

    /**
     * (Internal) Applies css properties to an element, similar to the jQuery
     * css method.
     *
     * While this helper does assist with vendor prefixed property names, it
     * does not perform any manipulation of values prior to setting styles.
     */

    var css = (function () {
        var cssPrefixes = ['Webkit', 'O', 'Moz', 'ms'],
            cssProps = {};

        function camelCase(string) {
            return string.replace(/^-ms-/, 'ms-').replace(/-([\da-z])/gi, function (match, letter) {
                return letter.toUpperCase();
            });
        }

        function getVendorProp(name) {
            var style = document.body.style;
            if (name in style) return name;

            var i = cssPrefixes.length,
                capName = name.charAt(0).toUpperCase() + name.slice(1),
                vendorName;
            while (i--) {
                vendorName = cssPrefixes[i] + capName;
                if (vendorName in style) return vendorName;
            }

            return name;
        }

        function getStyleProp(name) {
            name = camelCase(name);
            return cssProps[name] || (cssProps[name] = getVendorProp(name));
        }

        function applyCss(element, prop, value) {
            prop = getStyleProp(prop);
            element.style[prop] = value;
        }

        return function (element, properties) {
            var args = arguments,
                prop,
                value;

            if (args.length == 2) {
                for (prop in properties) {
                    value = properties[prop];
                    if (value !== undefined && properties.hasOwnProperty(prop)) applyCss(element, prop, value);
                }
            } else {
                applyCss(element, args[1], args[2]);
            }
        }
    })();

    /**
     * (Internal) Determines if an element or space separated list of class names contains a class name.
     */

    function hasClass(element, name) {
        var list = typeof element == 'string' ? element : classList(element);
        return list.indexOf(' ' + name + ' ') >= 0;
    }

    /**
     * (Internal) Adds a class to an element.
     */

    function addClass(element, name) {
        var oldList = classList(element),
            newList = oldList + name;

        if (hasClass(oldList, name)) return;

        // Trim the opening space.
        element.className = newList.substring(1);
    }

    /**
     * (Internal) Removes a class from an element.
     */

    function removeClass(element, name) {
        var oldList = classList(element),
            newList;

        if (!hasClass(element, name)) return;

        // Replace the class name.
        newList = oldList.replace(' ' + name + ' ', ' ');

        // Trim the opening and closing spaces.
        element.className = newList.substring(1, newList.length - 1);
    }

    /**
     * (Internal) Gets a space separated list of the class names on the element.
     * The list is wrapped with a single space on each end to facilitate finding
     * matches within the list.
     */

    function classList(element) {
        return (' ' + (element && element.className || '') + ' ').replace(/\s+/gi, ' ');
    }

    /**
     * (Internal) Removes an element from the DOM.
     */

    function removeElement(element) {
        element && element.parentNode && element.parentNode.removeChild(element);
    }

    return NProgress;
});

/*toggle */
+function (a) {
    "use strict";
    function b(b) {
        return this.each(function () {
            var d = a(this), e = d.data("bs.toggle"), f = "object" == typeof b && b;
            e || d.data("bs.toggle", e = new c(this, f)), "string" == typeof b && e[b] && e[b]()
        })
    }

    var c = function (b, c) {
        this.$element = a(b), this.options = a.extend({}, this.defaults(), c), this.render()
    };
    c.VERSION = "2.2.0", c.DEFAULTS = {
        on: "On",
        off: "Off",
        onstyle: "primary",
        offstyle: "default",
        size: "normal",
        style: "",
        width: null,
        height: null
    }, c.prototype.defaults = function () {
        return {
            on: this.$element.attr("data-on") || c.DEFAULTS.on,
            off: this.$element.attr("data-off") || c.DEFAULTS.off,
            onstyle: this.$element.attr("data-onstyle") || c.DEFAULTS.onstyle,
            offstyle: this.$element.attr("data-offstyle") || c.DEFAULTS.offstyle,
            size: this.$element.attr("data-size") || c.DEFAULTS.size,
            style: this.$element.attr("data-style") || c.DEFAULTS.style,
            width: this.$element.attr("data-width") || c.DEFAULTS.width,
            height: this.$element.attr("data-height") || c.DEFAULTS.height
        }
    }, c.prototype.render = function () {
        this._onstyle = "btn-" + this.options.onstyle, this._offstyle = "btn-" + this.options.offstyle;
        var b = "large" === this.options.size ? "btn-lg" : "small" === this.options.size ? "btn-sm" : "mini" === this.options.size ? "btn-xs" : "", c = a('<label class="btn">').html(this.options.on).addClass(this._onstyle + " " + b), d = a('<label class="btn">').html(this.options.off).addClass(this._offstyle + " " + b + " active"), e = a('<span class="toggle-handle btn btn-default">').addClass(b), f = a('<div class="toggle-group">').append(c, d, e), g = a('<div class="toggle btn" data-toggle="toggle">').addClass(this.$element.prop("checked") ? this._onstyle : this._offstyle + " off").addClass(b).addClass(this.options.style);
        this.$element.wrap(g), a.extend(this, {
            $toggle: this.$element.parent(),
            $toggleOn: c,
            $toggleOff: d,
            $toggleGroup: f
        }), this.$toggle.append(f);
        var h = this.options.width || Math.max(c.outerWidth(), d.outerWidth()) + e.outerWidth() / 2, i = this.options.height || Math.max(c.outerHeight(), d.outerHeight());
        c.addClass("toggle-on"), d.addClass("toggle-off"), this.$toggle.css({
            width: h,
            height: i
        }), this.options.height && (c.css("line-height", c.height() + "px"), d.css("line-height", d.height() + "px")), this.update(!0), this.trigger(!0)
    }, c.prototype.toggle = function () {
        this.$element.prop("checked") ? this.off() : this.on()
    }, c.prototype.on = function (a) {
        return this.$element.prop("disabled") ? !1 : (this.$toggle.removeClass(this._offstyle + " off").addClass(this._onstyle), this.$element.prop("checked", !0), void(a || this.trigger()))
    }, c.prototype.off = function (a) {
        return this.$element.prop("disabled") ? !1 : (this.$toggle.removeClass(this._onstyle).addClass(this._offstyle + " off"), this.$element.prop("checked", !1), void(a || this.trigger()))
    }, c.prototype.enable = function () {
        this.$toggle.removeAttr("disabled"), this.$element.prop("disabled", !1)
    }, c.prototype.disable = function () {
        this.$toggle.attr("disabled", "disabled"), this.$element.prop("disabled", !0)
    }, c.prototype.update = function (a) {
        this.$element.prop("disabled") ? this.disable() : this.enable(), this.$element.prop("checked") ? this.on(a) : this.off(a)
    }, c.prototype.trigger = function (b) {
        this.$element.off("change.bs.toggle"), b || this.$element.change(), this.$element.on("change.bs.toggle", a.proxy(function () {
            this.update()
        }, this))
    }, c.prototype.destroy = function () {
        this.$element.off("change.bs.toggle"), this.$toggleGroup.remove(), this.$element.removeData("bs.toggle"), this.$element.unwrap()
    };
    var d = a.fn.bootstrapToggle;
    a.fn.bootstrapToggle = b, a.fn.bootstrapToggle.Constructor = c, a.fn.toggle.noConflict = function () {
        return a.fn.bootstrapToggle = d, this
    }, a(function () {
        a("input[type=checkbox][data-toggle^=toggle]").bootstrapToggle()
    }), a(document).on("click.bs.toggle", "div[data-toggle^=toggle]", function (b) {
        var c = a(this).find("input[type=checkbox]");
        c.bootstrapToggle("toggle"), b.preventDefault()
    })
}(jQuery);


