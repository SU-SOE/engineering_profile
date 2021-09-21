
(function ($, Drupal) {

  Drupal.behaviors.engineeringTheme = {
    // Attach Drupal Behavior.
    attach(context, settings) {
      // Variables
      const firstPath = window.location.pathname.split('/')[1];

      // Color map for highlights
      ///  #00ece9 - teal
      ///  #ff525c - orange
      ///  #ffbd54 - yellow

      function getAccentColor() {
        const accentColors = [
          "#00ece9", "#ff525c", "#ffbd54"
        ];
        //return accentColors[Math.floor(Math.random() * accentColors.length)];
        return accentColors[(Math.random() * accentColors.length) | 0];
      }

      $(".engineering-accent-color__link a").each(function () {
        $(this).css('text-decoration', 'underline');
        $(this).css('text-decoration-color', getAccentColor());
      });

      //Adds different highlight color to spotlight headshot images.
      $(".engineering-accent-color__image img").each(function () {
        $(this).css('border-color', getAccentColor());
      });

      $(".soe-spotlight--cards .su-link").each(function () {
        $(this).removeClass('su-card__link su-link--action');
        $(this).addClass('su-link--external');
      })

      // Adds comma and space to individual spotlight pages where both
      if(firstPath === "spotlight"){
        if(document.getElementsByClassName('su-spotlight-degrees').length > 0 &&
          document.getElementsByClassName('su-spotlight-degrees').length > 0){
          var divSpotlightDegree = document.getElementsByClassName('su-spotlight-degrees');
          divSpotlightDegree[0].innerHTML += ',&nbsp;';
        }
      }



      //$('form-actions').hide();
    },

    // Detach Example.
    detach() {

    }
  };
})(jQuery, Drupal);
