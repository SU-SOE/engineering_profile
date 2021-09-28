
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
        $(this).css('text-decoration-color', getAccentColor())
          .on('mouseover', function () {
            console.log('chained');
            $(this).css('text-decoration-color', '#000');
          });
      });

      //Adds different highlight color to spotlight headshot images.
      $(".engineering-accent-color__image img").each(function () {
        $(this).css('border-color', getAccentColor());
      });

      $(".soe-spotlight--cards .su-link").each(function () {
        $(this).removeClass('su-card__link su-link--action');
        $(this).addClass('su-link--external');

        $(this).hover(function (element = this) {
          console.log('got in.');
          $(element).css('text-decoration-color', 'black');
        });
      })

      $(".engineering-accent-color__background").each(function () {
        $(this).css('background-color', getAccentColor());
      })
      // Adds comma and space to individual spotlight pages where both
      if (firstPath === "spotlight") {
        if (document.getElementsByClassName('node-spotlight-su-spotlight-degrees').length > 0 &&
          document.getElementsByClassName('node-spotlight-su-soe-department').length > 0) {
          var divSpotlightDegree = document.getElementsByClassName('su-spotlight-degrees');
          divSpotlightDegree[0].innerHTML += ',&nbsp;';
        }
      }

      // This is a less than ideal solution for removing ajax from Spotlight filter button.
      // Thankfully a solution is in the works: https://www.drupal.org/project/drupal/issues/2904754
      // After this moves into Core, this can be removed.
      $("#edit-submit-spotlights").attr("hidden", true);
      $('#edit-submit-spotlights').clone().appendTo("#views-exposed-form-spotlights-block-1 .form-actions").attr("hidden", false).addClass('show-spotlight-apply__button');
    },

    // Detach Example.
    detach() {

    }
  };
})(jQuery, Drupal);
