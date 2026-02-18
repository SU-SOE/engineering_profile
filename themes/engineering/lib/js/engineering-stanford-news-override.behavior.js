(function ($, Drupal) {

  Drupal.behaviors.engineeringNewsSocialMedia = {
      attach(context, settings) {
        $('.news-social-media', context).empty();
        $('.news-social-media', context).prepend('<div class="widget-wrapper-print"><a href="/' + settings.path.currentPath + '/printable/print" class="share-print su-news-header__social-print"><img src="dist/assets/print-icon.png" alt="Print Article"/><span>' + Drupal.t('Print Article') + '</span></a></div>');
        $('.news-social-media', context).prepend('<div class="widget-wrapper-copylink"><a href="" class="su-news-header__copylink share-copylink"><img src="dist/assets/copy-link-icon.png" alt="Copy Link"/></a></div>');

        // Get the current URL.
        var pathname = window.location;

        // Going native rather than using forward module.
        var forurl = "mailto:?subject=" + document.title + "&body=" + encodeURI(document.location);

        // Going native rather than using print_pdf module.
        var prurl = 'window.print();return false;';

        // Copy url to clipboard.
        $('.share-copylink', context).click(function (e) {
          e.preventDefault();
          navigator.clipboard.writeText(pathname).then(function () {
            alert(Drupal.t('Link copied to clipboard!'));
          }, function (err) {
            alert(Drupal.t('Failed to copy link: ', err));
          });
        });
      }
    };
})(jQuery, Drupal);



