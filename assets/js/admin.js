/*jslint nomen: true*/
/*global jQuery, document, window, wp, _wpMediaViewsL10n, file_frame, roboblog_vars*/
/*jslint nomen: false*/
jQuery(document).ready(function ($) {
    'use strict';

    // Setup color picker
    if ($('.roboblog-color-picker').length) {
        $('.roboblog-color-picker').wpColorPicker();
    }

    // Setup uploaders
    if ($('.roboblog_settings_upload_button').length) {
        $('body').on('click', '.roboblog_settings_upload_button', function (e) {
            e.preventDefault();

            var button = $(this);

            window.formfield = $(this).parent().prev();

            // If the media frame already exists, reopen it
            if (file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame
            file_frame = wp.media.frames.file_frame = wp.media({
                frame: 'post',
                state: 'insert',
                title: button.data('uploader_title'),
                button: {
                    text: button.data('uploader_button_text')
                },
                multiple: false
            });

            file_frame.on('menu:render:default', function (view) {
                // Store our views in an object
                var views = {};

                // Unset default menu items
                view.unset('library-separator');
                view.unset('gallery');
                view.unset('featured-image');
                view.unset('embed');

                // Initialize the views in our object
                view.set(views);
            });

            // Run a callback on select
            file_frame.on('insert', function () {
                var selection = file_frame.state().get('selection');

                selection.each(function (attachment, index) {
                    attachment = attachment.toJSON();
                    window.formfield.val(attachment.url);
                });
            });

            // Open the modal
            file_frame.open();
        });

        var file_frame;
        window.formfield = '';
    }
});
