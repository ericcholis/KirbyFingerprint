<?php

/**
 * Fingerprint css and js assets
 *
 * @version 1.4
 * @author Iksi <info@iksi.cc>
 */

namespace Iksi\Component;

use c;
use f;
use HTML;

if ( ! c::get('fingerprint')) {
    return;
}

class CSS extends \Kirby\Component\CSS {

  /**
   * Builds the html link tag for the given css file
   * 
   * @param string $url
   * @param null|string $media
   * @return string
   */
  public function tag($url, $media = null) {

    if(is_array($url)) {
      $css = array();
      foreach($url as $u) $css[] = $this->tag($u, $media);
      return implode(PHP_EOL, $css) . PHP_EOL;
    }

    // auto template css files
    if($url == '@auto') {

      $file = $this->kirby->site()->page()->template() . '.css';
      $root = $this->kirby->roots()->autocss() . DS . $file;
      $url  = $this->kirby->urls()->autocss() . '/' . $file;

      if(!file_exists($root)) return false;

      $url = preg_replace('#^' . $this->kirby->urls()->index() . '/#', null, $url);

    }

    if (file_exists($url)) {
      $modifier = md5_file($url);
      $filename = f::name($url) . '.' . $modifier . '.' . f::extension($url);
      $dirname  = f::dirname($url);

      $url = ($dirname === '.') ? $filename : $dirname . '/' . $filename;
    }

    return html::tag('link', null, array(
      'rel'   => 'stylesheet',
      'href'  => url($url),
      'media' => $media
    ));

  }

}

class JS extends \Kirby\Component\JS {

  /**
   * Builds the html script tag for the given javascript file
   * 
   * @param string $src
   * @param boolean async
   * @return string
   */
  public function tag($src, $async = false) {

    if(is_array($src)) {
      $js = array();
      foreach($src as $s) $js[] = $this->tag($s, $async);
      return implode(PHP_EOL, $js) . PHP_EOL;
    }

    // auto template css files
    if($src == '@auto') {

      $file = $this->kirby->site()->page()->template() . '.js';
      $root = $this->kirby->roots()->autojs() . DS . $file;
      $src  = $this->kirby->urls()->autojs() . '/' . $file;

      if(!file_exists($root)) return false;

      $src = preg_replace('#^' . $this->kirby->urls()->index() . '/#', null, $src);

    }

    if (file_exists($src)) {
      $modifier = md5_file($src);
      $filename = f::name($src) . '.' . $modifier . '.' . f::extension($src);
      $dirname  = f::dirname($src);

      $src = ($dirname === '.') ? $filename : $dirname . '/' . $filename;
    }

    return html::tag('script', '', array(
      'src'   => url($src),
      'async' => $async
    ));

  }

}

$kirby->set('component', 'css', 'Iksi\Component\CSS');
$kirby->set('component', 'js', 'Iksi\Component\JS');
