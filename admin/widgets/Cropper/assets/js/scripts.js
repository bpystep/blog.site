(function(factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        factory(require('jquery'));
    } else {
        factory(jQuery);
    }
})(function($) {
    'use strict';

    function Cropimage($element) {
        this.$container = $element;
        this.$imageView = this.$container.find('.js-image-view');
        this.$image = this.$imageView.find('img');
        this.$imageModal = this.$container.find('.js-image-modal');
        this.$imageData = this.$imageModal.find('.js-image-data');
        this.$imageInput = this.$imageModal.find('.js-image-input');
        this.$imageSave = this.$imageModal.find('.js-image-save');
        this.$imageWrapper = this.$imageModal.find('.js-image-wrapper');
        this.$imagePreview = this.$imageModal.find('.js-image-preview');
        this.init();
    }

    Cropimage.prototype = {
        constructor: Cropimage,
        support    : {
            fileList: !!$('<input type="file">').prop('files'),
            blobURLs: !!window.URL && URL.createObjectURL
        },
        init       : function() {
            this.support.datauri = this.support.fileList && this.support.blobURLs;
            this.$imageView.on('click', $.proxy(this.preview, this));
            this.$imageInput.on('change', $.proxy(this.change, this));
            this.$imageSave.on('click', $.proxy(this.done, this));
        },
        preview    : function() {
            var src = this.$image.attr('src');
            this.$imagePreview.html('<img src="' + src + '">');
        },
        click      : function() {
            this.initPreview();
        },
        change     : function() {
            if (this.support.datauri) {
                var files = this.$imageInput.prop('files');
                if (files.length > 0) {
                    this.file = files[0];
                    if (this.isImageFile(this.file)) {
                        if (this.url) {
                            URL.revokeObjectURL(this.url);
                        }
                        this.url = URL.createObjectURL(this.file);
                        this.start();
                    }
                }
            }
        },
        isImageFile: function(file) {
            if (file.type) {
                return /^image\/\w+$/.test(file.type);
            } else {
                return /\.(jpg|jpeg|png|gif)$/.test(file);
            }
        },
        start      : function() {
            var _this = this;
            if (this.active) {
                this.$img.cropper('replace', this.url);
            } else {
                this.$img = $('<img src="' + this.url + '">');
                this.$imageWrapper.empty().html(this.$img);
                this.$img.cropper({
                    aspectRatio : parseFloat(this.$container.data('ratio')),
                    viewMode    : 2,
                    autoCropArea: 1,
                    preview     : '#' + this.$imageModal.attr('id') + ' .js-image-preview'
                });
                this.active = true;
            }
            this.$imageModal.one('hidden.bs.modal', function() {
                _this.$imagePreview.empty();
                _this.stop();
            });
        },
        stop       : function() {
            if (this.active) {
                this.$img.cropper('destroy');
                this.$img.remove();
                this.active = false;
            }
        },
        done       : function() {
            var croppedData = this.$img.cropper('getData');
            var data, width, height, ratio;
            ratio = croppedData.width / croppedData.height;
            if (croppedData.width > 1920) {
                width = 1920;
                height = width / ratio;
            } else if (croppedData.height > 1920) {
                height = 1920;
                width = height * ratio;
            } else {
                width = croppedData.width;
                height = croppedData.height;
            }
            data = this.$img.cropper('getCroppedCanvas', {
                width : width,
                height: height
            }).toDataURL(this.file.type ? this.file.type : "image/jpeg", 1.0);
            this.$image.attr('src', data);
            this.$imageData.val(data);
            this.stop();
            this.$imageModal.modal('hide');
        }
    };
    $(function() {
        var instances = [];
        $('.js-image-crop').each(function() {
            instances.push(new Cropimage($(this)));
        });
    });
});
