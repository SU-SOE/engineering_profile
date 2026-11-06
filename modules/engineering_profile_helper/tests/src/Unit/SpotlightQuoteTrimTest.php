<?php

declare(strict_types=1);

namespace Drupal\Tests\engineering_profile_helper\Unit;

use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests the Spotlight quote trimming helpers.
 *
 * Covers _engineering_profile_helper_shorten_spotlight_quote(),
 * _engineering_profile_helper_trim_spotlight_quote() and
 * _engineering_profile_helper_trim_spotlight_quote_markup().
 */
#[Group('engineering_profile_helper')]
class SpotlightQuoteTrimTest extends UnitTestCase {

  /**
   * A 179 character quote made of 30 five-letter "words".
   */
  protected const LONG_QUOTE = 'alpha bravo charl delta echoo foxtr golfo hotel india julie kilos limao mikes novem oscar papas quebe romeo sierr tango unifo victo whisk xrays yanke zulus alpha bravo charl delta';

  /**
   * The first 100 characters of LONG_QUOTE cut at a word boundary.
   */
  protected const LONG_QUOTE_SHORT = 'alpha bravo charl delta echoo foxtr golfo hotel india julie kilos limao mikes novem oscar papas';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    require_once dirname(__DIR__, 3) . '/engineering_profile_helper.module';
  }

  /**
   * Shortening cuts at 100 characters on a word boundary, without ellipsis.
   */
  #[DataProvider('providerShorten')]
  public function testShortenSpotlightQuote(string $quote, string $expected): void {
    $result = _engineering_profile_helper_shorten_spotlight_quote($quote);
    $this->assertSame($expected, $result);
    $this->assertLessThanOrEqual(100, mb_strlen($result));
  }

  /**
   * Data provider for testShortenSpotlightQuote().
   */
  public static function providerShorten(): array {
    return [
      'empty' => ['', ''],
      'under limit is unchanged' => ['A short quote', 'A short quote'],
      'surrounding whitespace is trimmed' => ["  \n A short quote \n", 'A short quote'],
      'trailing punctuation is trimmed' => ['Is it short?!.', 'Is it short'],
      'over limit is cut on a word boundary' => [self::LONG_QUOTE, self::LONG_QUOTE_SHORT],
    ];
  }

  /**
   * The plain text trim always appends an ellipsis entity.
   */
  public function testTrimSpotlightQuote(): void {
    $this->assertSame('A short quote &hellip;', _engineering_profile_helper_trim_spotlight_quote('A short quote.'));
    $this->assertSame(self::LONG_QUOTE_SHORT . ' &hellip;', _engineering_profile_helper_trim_spotlight_quote(self::LONG_QUOTE));
  }

  /**
   * Markup trimming for the cases the spotlights view renders.
   */
  #[DataProvider('providerTrimMarkup')]
  public function testTrimSpotlightQuoteMarkup(string $markup, string $expected): void {
    $this->assertSame($expected, _engineering_profile_helper_trim_spotlight_quote_markup($markup));
  }

  /**
   * Data provider for testTrimSpotlightQuoteMarkup().
   */
  public static function providerTrimMarkup(): array {
    return [
      'empty string is returned unchanged' => ['', ''],
      'whitespace only is returned unchanged' => ["  \n ", "  \n "],
      'plain text under the limit' => [
        'A short quote.',
        'A short quote &hellip;',
      ],
      'plain text over the limit' => [
        self::LONG_QUOTE,
        self::LONG_QUOTE_SHORT . ' &hellip;',
      ],
      'no-div markup over the limit is reduced to well-formed text' => [
        '<p>' . self::LONG_QUOTE . '</p>',
        self::LONG_QUOTE_SHORT . ' &hellip;',
      ],
      'no-div markup under the limit drops its tags' => [
        '<p>A <strong>short</strong> quote.</p>',
        'A short quote &hellip;',
      ],
      'no-div markup is escaped' => [
        '<p>Tom &amp; Jerry &lt;b&gt;</p><script>x</script>',
        'Tom &amp; Jerry &lt;b&gt;x &hellip;',
      ],
      'block_1 wrapper under the limit keeps its tags' => [
        '<div class="soe-quote">A short quote.</div>',
        '<div class="soe-quote">A short quote &hellip;</div>',
      ],
      'block_1 wrapper over the limit keeps its tags' => [
        '<div class="soe-quote">' . self::LONG_QUOTE . '</div>',
        '<div class="soe-quote">' . self::LONG_QUOTE_SHORT . ' &hellip;</div>',
      ],
      'block_2 formatter wrapper keeps its tags and flattens children' => [
        "<div class=\"spotlight__quote\">\n  <p>" . self::LONG_QUOTE . "</p>\n</div>",
        '<div class="spotlight__quote">' . self::LONG_QUOTE_SHORT . ' &hellip;</div>',
      ],
      'only the first div is trimmed' => [
        '<div class="outer"><div class="inner">Inner text</div></div>',
        '<div class="outer">Inner text &hellip;</div>',
      ],
      'ampersand entity is not double encoded or decoded' => [
        '<div class="soe-quote">Tom &amp; Jerry</div>',
        '<div class="soe-quote">Tom &amp; Jerry &hellip;</div>',
      ],
      'escaped markup in the quote stays escaped' => [
        '<div class="soe-quote">Use &lt;script&gt;alert(1)&lt;/script&gt; here</div>',
        '<div class="soe-quote">Use &lt;script&gt;alert(1)&lt;/script&gt; here &hellip;</div>',
      ],
      'multibyte text is kept as UTF-8' => [
        '<div class="soe-quote">“Café” – naïve</div>',
        '<div class="soe-quote">“Café” – naïve &hellip;</div>',
      ],
    ];
  }

  /**
   * An ampersand near the cut point is counted once and stays one entity.
   */
  public function testAmpersandAtLimit(): void {
    // 94 characters of text, then " & end": the decoded text is exactly 100
    // characters, so nothing may be cut off. Counting the raw "&amp;" would
    // make it 104 characters and drop " end".
    $prefix = str_repeat('x', 94);
    $result = _engineering_profile_helper_trim_spotlight_quote_markup('<div>' . $prefix . ' &amp; end</div>');
    $this->assertSame('<div>' . $prefix . ' &amp; end &hellip;</div>', $result);
    $this->assertStringNotContainsString('&amp;amp;', $result);
  }

}
