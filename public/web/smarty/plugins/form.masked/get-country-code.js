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

                maskedData.selectCss = {'width': '64px', 'borderWidth': '1px'};
                $el.on('phonemaskedinput.afterInit', function(event, data) {
                    loadScript(plugin_path + 'select2/js/select2.full.min.js', function() {
                        loadScript(plugin_path + 'select2/js/i18n/ru.js', function() {
                            $(data.select).select2($(data.select).data('select2Options'))
                        });
                    });
                }).phonemaskedinput(maskedData);
            }
        });
    };
};
