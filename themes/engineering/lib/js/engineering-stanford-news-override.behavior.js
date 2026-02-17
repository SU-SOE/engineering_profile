(function ($, Drupal) {

  Drupal.behaviors.engineeringNewsSocialMedia = {
      attach(context, settings) {
        $('.news-social-media', context).empty();
        $('.news-social-media', context).prepend('<div class="widget-wrapper-print"><a href="/' + settings.path.currentPath + '/printable/print" class="share-print su-news-header__social-print"><i class="fas fa-printer" aria-hidden="true"></i><span>' + Drupal.t('Print Article') + '</span></a></div>');
        $('.news-social-media', context).prepend('<div class="widget-wrapper-copylink"><a href="" class="share-forward su-news-header__copylink"><img src="dist/assets/copy-link.png" alt="Copy Link"/>' + Drupal.t('Forward Email') + '</span></a></div>');

        // Get the current URL.
        var pathname = window.location;

        // Data.
        var shareTitle = $('div[property="dc:title"] h1', context).text();
        var shareSubtitle = $('.share-sub', context).text();

        // URL's
        var twurl = 'https://twitter.com/intent/tweet?url=' + encodeURI(pathname) + '&text=' + shareTitle + ' ' + shareSubtitle;
        var fburl = 'http://www.facebook.com/sharer.php?u=' + pathname + '&display=popup';
        var liurl = 'https://www.linkedin.com/shareArticle?mini=true&url=' + pathname + '&title=' + shareTitle + '&summary=' + shareSubtitle;

        // Going native rather than using forward module.
        var forurl = "mailto:?subject=" + document.title + "&body=" + encodeURI(document.location);

        // Going native rather than using print_pdf module.
        var prurl = 'window.print();return false;';

        // Add the URL's to anchors.
        $('.share-fb', context).attr({
          href: fburl
        });

        $('.share-twitter', context).attr({
          href: twurl
        });

        $('.share-linkedin', context).attr({
          href: liurl
        });

        $('.share-forward', context).attr({
          href: forurl
        });
      }
    };
})(jQuery, Drupal);



