$(document).ready(function() {
    $('.js_socials_cont').on('afterInsert', function() {
        admin.select2.initSelect2();
    });

    $(admin.params.bodyContainer).on('click', '.js-delete-social', function() {
        $(this).parents('.js-social-item').remove();
    });
});
