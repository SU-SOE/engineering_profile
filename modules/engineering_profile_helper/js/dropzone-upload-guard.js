/**
 * @file
 * Prevents the "Upload and Continue" button from being clicked before the
 * dropzone async upload has finished, fixing a race condition where the form
 * submits with an empty uploaded_files value.
 */

(function ($, Drupal, drupalSettings, once) {
  'use strict';

  Drupal.behaviors.dropzoneUploadGuard = {
    attach: function (context) {
      if (typeof drupalSettings.dropzonejs === 'undefined' ||
          typeof drupalSettings.dropzonejs.instances === 'undefined') {
        return;
      }

      Object.values(drupalSettings.dropzonejs.instances).forEach(function (item) {
        if (typeof item.instance === 'undefined') {
          return;
        }

        var $form = $(item.instance.element).parents('form');
        var $button = $form.find('[data-drupal-selector="edit-continue"]');

        if ($button.length === 0) {
          return;
        }

        // Only attach once per button element.
        if ($(once('dropzone-upload-guard', $button)).length === 0) {
          return;
        }

        // Disable the button when a file starts uploading.
        item.instance.on('sending', function () {
          $button.prop('disabled', true);
        });

        // Re-enable the button when all uploads have completed.
        item.instance.on('queuecomplete', function () {
          if (item.instance.getUploadingFiles().length === 0) {
            $button.prop('disabled', false);
          }
        });

        // Re-enable the button if an upload errors out, so the user isn't
        // stuck with a permanently disabled button.
        item.instance.on('error', function () {
          if (item.instance.getUploadingFiles().length === 0 &&
              item.instance.getQueuedFiles().length === 0) {
            $button.prop('disabled', false);
          }
        });
      });
    }
  };

}(jQuery, Drupal, drupalSettings, once));
