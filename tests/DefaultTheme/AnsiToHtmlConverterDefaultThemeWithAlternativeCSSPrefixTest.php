<?php

use voku\AnsiConverter\AnsiToHtmlConverter;

class AnsiToHtmlConverterDefaultThemeWithAlternativeCSSPrefixTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @dataProvider getConvertData
   */
  public function testConvert($expectedOutput, $expectedCss, $input)
  {
    $converter = new AnsiToHtmlConverter(null, false, 'UTF-8', 'alternative_prefix');
    $this->assertEquals($expectedOutput, $converter->convert($input));
    $this->assertEquals($expectedCss, $converter->getTheme()->asCss());
  }

  public function getConvertData()
  {
    $css = <<< 'END_CSS'
.alternative_prefix_fg_black { color: black }
.alternative_prefix_bg_black { background-color: black }
.alternative_prefix_fg_red { color: darkred }
.alternative_prefix_bg_red { background-color: darkred }
.alternative_prefix_fg_green { color: green }
.alternative_prefix_bg_green { background-color: green }
.alternative_prefix_fg_yellow { color: yellow }
.alternative_prefix_bg_yellow { background-color: yellow }
.alternative_prefix_fg_blue { color: blue }
.alternative_prefix_bg_blue { background-color: blue }
.alternative_prefix_fg_magenta { color: darkmagenta }
.alternative_prefix_bg_magenta { background-color: darkmagenta }
.alternative_prefix_fg_cyan { color: cyan }
.alternative_prefix_bg_cyan { background-color: cyan }
.alternative_prefix_fg_white { color: white }
.alternative_prefix_bg_white { background-color: white }
.alternative_prefix_fg_brblack { color: black }
.alternative_prefix_bg_brblack { background-color: black }
.alternative_prefix_fg_brred { color: red }
.alternative_prefix_bg_brred { background-color: red }
.alternative_prefix_fg_brgreen { color: lightgreen }
.alternative_prefix_bg_brgreen { background-color: lightgreen }
.alternative_prefix_fg_bryellow { color: lightyellow }
.alternative_prefix_bg_bryellow { background-color: lightyellow }
.alternative_prefix_fg_brblue { color: lightblue }
.alternative_prefix_bg_brblue { background-color: lightblue }
.alternative_prefix_fg_brmagenta { color: magenta }
.alternative_prefix_bg_brmagenta { background-color: magenta }
.alternative_prefix_fg_brcyan { color: lightcyan }
.alternative_prefix_bg_brcyan { background-color: lightcyan }
.alternative_prefix_fg_brwhite { color: white }
.alternative_prefix_bg_brwhite { background-color: white }
.alternative_prefix_underlined { text-decoration: underlined }
END_CSS;

    return [
      // text is escaped
      [
          '<span class="alternative_prefix_bg_black alternative_prefix_fg_white">foo &lt;br /&gt;</span>',
          $css,
          'foo <br />',
      ],

      // newlines are preserved
      ["<span class=\"alternative_prefix_bg_black alternative_prefix_fg_white\">foo\nbar</span>", $css, "foo\nbar"],

      // backspaces
      [
          '<span class="alternative_prefix_bg_black alternative_prefix_fg_white">foo   </span>',
          $css,
          "foobar\x08\x08\x08   ",
      ],
      [
          '<span class="alternative_prefix_bg_black alternative_prefix_fg_white">foo</span><span class="alternative_prefix_bg_black alternative_prefix_fg_white">   </span>',
          $css,
          "foob\e[31;41ma\e[0mr\x08\x08\x08   ",
      ],

      // color
      ['<span class="alternative_prefix_bg_red alternative_prefix_fg_red">foo</span>', $css, "\e[31;41mfoo\e[0m"],

      // color with [m as a termination (equivalent to [0m])
      ['<span class="alternative_prefix_bg_red alternative_prefix_fg_red">foo</span>', $css, "\e[31;41mfoo\e[m"],

      // bright color
      ['<span class="alternative_prefix_bg_brred alternative_prefix_fg_brred">foo</span>', $css, "\e[31;41;1mfoo\e[0m"],

      // carriage returns
      ['<span class="alternative_prefix_bg_black alternative_prefix_fg_white">foobar</span>', $css, "foo\rbar\rfoobar"],

      // underline
      [
          '<span class="alternative_prefix_bg_black alternative_prefix_fg_white alternative_prefix_underlined">foo</span>',
          $css,
          "\e[4mfoo\e[0m",
      ],

      // non valid unicode codepoints substitution (only available with PHP >= 5.4)
      [
          '<span class="alternative_prefix_bg_black alternative_prefix_fg_white">foo ' . "\xEF\xBF\xBD" . '</span>',
          $css,
          "foo \xF4\xFF\xFF\xFF",
      ],

      // Yellow on green.
      ['<span class="alternative_prefix_bg_green alternative_prefix_fg_yellow">foo</span>', $css, "\e[33;42mfoo\e[0m"],

      // Yellow on green - reversed.
      [
          '<span class="alternative_prefix_bg_yellow alternative_prefix_fg_green">foo</span>',
          $css,
          "\e[33;42;7mfoo\e[0m",
      ],
    ];
  }
}
