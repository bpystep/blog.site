$(document).ready(function() {
    blog.init();
});

var blog = new function() {
    var self = this;

    this.isMobile = $(window).width() < 481;

    this.params = {
        '_csrf': $('meta[name="csrf-token"]').attr("content"),
        'theme': {'name': null, 'options': {}}
    };

    this.init = function() {

    };

    this.ajaxCall = function(obj) {
        if (!obj.url) {
            return false;
        }
        obj.data = obj.data ? obj.data : {};
        var callbacks = {
            success: function(data) {
                if (data.success) {
                    alert(data.message ? data.message : 'Выполнено!');
                } else {
                    alert(data.message ? data.message : 'Неудачно!');
                }
            },
            error  : function(data) {
                console.log('error ' + ajax.url, data);
            }
        };

        var ajax = {
            url     : obj.url,
            data    : $.extend(obj.data, {_csrf: self.params._csrf}),
            type    : !obj.type     ? 'POST'            : obj.type,
            dataType: !obj.dataType ? 'JSON'            : obj.dataType,
            success : !obj.success  ? callbacks.success : obj.success,
            error   : !obj.error    ? callbacks.error   : obj.error
        };

        $.ajax(ajax);
    };

    this.locker = {
        lock      : function(el) {
            let lockerContent = $('<div>', {'class': 'js-locker-content'}).html(
                $(el).html().split('<div class="waves-ripple"')[0]
            ).hide();
            $(lockerContent).data('classList', $(el).prop('className'));
            $(el).html(lockerContent);
            $(el).addClass('js-locker-locked');
            $(el).removeClass('btn-reveal');
            if ($(el).data('lockerText')) {
                $(el).append($(el).data('lockerText'));
            }
            $(el).attr('disabled', 1);
            $(el).prop('disabled', true);
        },
        unlock    : function(el) {
            let lockerContent = $(el).find('.js-locker-content');
            $(el).addClass($(lockerContent).data('classList'));
            $(el).html(lockerContent.html());
            $(el).removeClass('js-locker-locked');
            $(el).removeAttr('disabled');
            $(el).prop('disabled', false);
        }
    };
};

function in_array(needle, haystack, strict) {
    var found = false, key, strict = !!strict;
    for (key in haystack) {
        if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
            found = true;
            break;
        }
    }
    return found;
}

function pad(n) {
    return n < 10 ? '0' + n : n;
}

function round(value, decimals) {
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}
