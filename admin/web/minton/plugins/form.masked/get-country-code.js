$(document).ready(function() {
    geoCountryCode.init();
});

var geoCountryCode = new function() {
    const MASKED_CLASS    = 'js-masked-phone';
    const MASKED_SELECTOR = '.' + MASKED_CLASS;

    let that = this;

    this.init = function() {
        if ($(MASKED_SELECTOR).length) {
            $(MASKED_SELECTOR).each(function() {
                $(this).data('alreadyDisabled', $(this).prop('disabled'));
                $(this).prop('disabled', true);
            });
            that.request();
        }
    };

    this.request = function() {
        $.ajax({
            url : '/geo/country-code',
            data: {_csrf: $('meta[name="csrf-token"]').attr("content")}
        }).done(that.callback);
    };

    this.callback = function(data) {
        $(MASKED_SELECTOR).each(function() {
            let $el = $(this);

            if (!$el.data('alreadyDisabled')) {
                $el.prop('disabled', false);
            }

            if (data.success) {
                let maskedData = $.extend(data, $el.data());

                $el.on('phonemaskedinput.afterInit', function(event, data) {
                    $(data.select).select2($(data.select).data())
                }).phonemaskedinput(maskedData);
            }
        });
    };
};
