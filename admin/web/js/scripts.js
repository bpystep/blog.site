$(document).ready(function() {
    admin.select2.init();
    admin.bootbox.init();
    admin.tooltip();
    admin.popover();
});

var admin = new function() {
    var self = this;

    this.params = {
        'bodyContainer': $('body')
    };

    this.select2 = {
        init       : function() {
            self.select2.initSelect2();
            $(document).on('pjax:success', function() {
                self.tooltip();
                self.popover();
                self.select2.initSelect2();
            });
        },
        initSelect2: function() {
            $('select:not(.custom-init)').each(function() {
                $(this).select2($(this).data('s2') ? $(this).data('s2') : {})
            });
        }
    };

    this.bootbox = {
        init    : function() {
            $('body').on('click', '.js-sweet-alert', function() {
                var el = this, data = $(el).data();

                Swal.fire({
                    title             : data['bbTitle'],
                    html              : data['bbMessage'],
                    icon              : data['bbIcon'],
                    showCancelButton  : true,
                    confirmButtonColor: '#1abc9c',
                    cancelButtonColor : '#f1556c',
                    confirmButtonText : data['bbConfirm'] != undefined ? ('<span class="btn-label"><i class="fas fa-check"></i></span>' + data['bbConfirm']) : '<i class="fas fa-check"></i>',
                    cancelButtonText  : data['bbCancel'] != undefined ? (data['bbCancel'] + '<span class="btn-label-right"><i class="fas fa-times"></i></span>') : '<i class="fas fa-times"></i>',
                }).then(function(result) {
                    if (result.value) {
                        if (data['bbAjax']) {
                            var obj = {
                                'url' : data['bbUrl'],
                                'type': data['bbMethod'] ? data['bbMethod'] : 'GET'
                            };
                            if (data['bbData']) {
                                obj.data = data['bbData'];
                            }
                            if (data['bbSuccess'] && bbCallbacks[data['bbSuccess']]) {
                                obj.success = function(response) {
                                    bbCallbacks[data['bbSuccess']](response, el);
                                };
                            }
                            if (data['bbError'] && bbCallbacks[data['bbError']]) {
                                obj.error = function(response) {
                                    bbCallbacks[data['bbError']](response, el);
                                };
                            }
                            reserve.ajaxCall(obj);
                        } else if (data['bbMethod'] && data['bbMethod'].toUpperCase() == 'POST') {
                            var confirmButton = $('<a>', {
                                'href'       : data['bbUrl'],
                                'data-method': data['bbMethod']
                            }).hide();
                            $(admin.params.bodyContainer).append(confirmButton);
                            $(confirmButton).click();
                        } else {
                            window.location = data['bbUrl'];
                        }
                    }
                });
            });
        },
        callback: []
    };

    this.tooltip = function() {
        $('[data-toggle="tooltip"], .add-tooltip').tooltip({'html': true});
        $('[data-bs-toggle="tooltip"]').tooltip();
    };

    this.popover = function() {
        $('[data-toggle="popover"], .add-popover').popover({'html': true});
        $('[data-bs-toggle="popover"]').popover();
    };
};
