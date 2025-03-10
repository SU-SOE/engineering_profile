<?php

namespace Drupal\engineering_magazine\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'News Navigation' Block.
 *
 * @Block(
 *   id = "news_navigation_block",
 *   admin_label = @Translation("News Navigation"),
 *   category = @Translation("SoE Magazine"),
 * )
 */
class NewsNavigationBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('<h1 class="news-navigation-nav__title"><a href="/news">Latest News</a></h1>'),
      '#cache' => [
        'contexts' => ['url'],
      ],
    ];
  }

}
