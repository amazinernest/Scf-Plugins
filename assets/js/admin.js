jQuery(document).ready(function ($) {

    // Image Uploader
    $(document).on('click', '.scf-upload-image', function (e) {
        e.preventDefault();
        var button = $(this);
        var uploader = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = uploader.state().get('selection').first().toJSON();
            button.siblings('.scf-image-id').val(attachment.id);
            button.siblings('.scf-image-preview').html('<img src="' + attachment.url + '" style="max-width:150px; height:auto;">');
            button.siblings('.scf-remove-image').show();
        }).open();
    });

    $(document).on('click', '.scf-remove-image', function (e) {
        e.preventDefault();
        var button = $(this);
        button.siblings('.scf-image-id').val('');
        button.siblings('.scf-image-preview').html('');
        button.hide();
    });

    // File Uploader
    $(document).on('click', '.scf-upload-file', function (e) {
        e.preventDefault();
        var button = $(this);
        var uploader = wp.media({
            title: 'Select File',
            button: {
                text: 'Use this file'
            },
            multiple: false
        }).on('select', function () {
            var attachment = uploader.state().get('selection').first().toJSON();
            button.siblings('.scf-file-id').val(attachment.id);
            button.siblings('.scf-file-preview').html('<a href="' + attachment.url + '" target="_blank">' + attachment.filename + '</a>');
            button.siblings('.scf-remove-file').show();
        }).open();
    });

    $(document).on('click', '.scf-remove-file', function (e) {
        e.preventDefault();
        var button = $(this);
        button.siblings('.scf-file-id').val('');
        button.siblings('.scf-file-preview').html('');
        button.hide();
    });

});
