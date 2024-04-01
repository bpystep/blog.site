(function($) {
    $.fn.phonemaskedinput = function(options) {
        var that = $.fn.phonemaskedinput, $this = this;

        const EVENT_AFTER_INIT = 'phonemaskedinput.afterInit';

        const AVAILABLE_LANGUAGES = ['ru', 'en'];

        let settings = {
            'selectCss'        : {},
            'selectClass'      : null,
            'selectName'       : null,
            'code'             : 'AC',
            'lang'             : 'en',
            'changePlaceholder': true
        };

        let codes = phoneCodes, startCountry = null, $select;

        that.init = function() {
            //Переопределяет "Девятку"
            $.mask.definitions['9'] = '';
            $.mask.definitions['#'] = '[0-9]';

            that.normalize(); that.createDom(); that.changeSelect();

            if (settings.code) {
                $($this).mask(startCountry.mask);
            }

            $this.trigger(EVENT_AFTER_INIT, {'select': $select});
        };

        that.createDom = function() {
            $select = $('<select>', {
                'class': [settings.selectClass, 'select2', 'select2-custom'].filter((item) => !!item),
                'name' : settings.selectName,
                'data' : {
                    'select2Options': {
                        'html'                   : true,
                        'minimumResultsForSearch': '-1',
                        'templateSelection'      : that.formatFlags,
                        'templateResult'         : that.formatFlags
                    }
                }
            }).css(settings.selectCss);
            codes.sort((a, b) => a.name.localeCompare(b.name));
            $.each(codes, function() {
                const $option = $('<option>', {'value': this.cc, 'data-name': this.name, 'data-mask': this.mask}).html(
                    '<img src="' + commonDomain + '/img/flags/64/' + this.cc + '.png" width="20" height="20" title="' + this.name + '">'
                );
                if (this.cc == settings.code) {
                    startCountry = this;
                    $option.prop('selected', this.cc == settings.code);

                    if (settings.changePlaceholder) {
                        that.changePlaceholder($($this), this.mask);
                    }
                }
                $select.append($option);
            });
            $($this).parents('.input-group').find('.input-group-btn').append($select);
        };

        that.changeSelect = function() {
            $select.change(function() {
                let $selected = $(this).find('option:selected')
                if ($selected.length) {
                    if (settings.changePlaceholder) {
                        that.changePlaceholder($($this), $selected.data('mask'));
                    }

                    setTimeout(function() {
                        $.mask.definitions['9'] = '';
                        $.mask.definitions['#'] = '[0-9]';
                        $($this).val('').mask($selected.data('mask')).focus();
                    });
                }

                return false;
            });
        };

        that.changePlaceholder = function($el, name) {
            if (settings.placeholder) {
                $el.attr('placeholder', settings.placeholder + ' (' + name + ')');
            } else {
                $el.attr('placeholder', name);
            }
        };

        that.findCodeByValue = function(value) {
            let code = null;
            $.each(codes, function() {
                if (this.mask.length == value.length) {
                    let tmp = value;
                    for (let i = 0; i < this.mask.length; i++) {
                        if (this.mask[i] == '#') {
                            tmp = replaceAt(tmp, i, '#');
                        }
                    }

                    if (tmp === this.mask) {
                        code = this; return false;
                    }
                }
            });

            return code;
        };

        that.normalize = function() {
            if ($($this).attr('placeholder')) {
                options.placeholder = $($this).attr('placeholder');
            }
            if (options.lang && !in_array(options.lang, AVAILABLE_LANGUAGES)) {
                delete options.lang;
            }
            if ($($this).val() != '') {
                var code = that.findCodeByValue($($this).val());
                options.code = code ? code.cc : options.code;
            }

            settings = $.extend({}, settings, options);
        };

        that.formatFlags = function(icon) {
            return $('<div>').css({
                height        : '100%',
                display       : 'flex',
                alignItems    : 'center',
                justifyContent: 'center'
            }).html($(icon.element).html());
        }

        function in_array(needle, haystack, strict) {
            var found = false, key, strict = !!strict;
            for (key in haystack) {
                if ((strict && haystack[key] === needle) || (!strict && haystack[key] === needle)) {
                    found = true;
                    break;
                }
            }
            return found;
        }

        function replaceAt(str, index, replacement) {
            return str.substr(0, index) + replacement + str.substr(index + replacement.length);
        }

        return this.each(that.init);
    };
})(jQuery);
