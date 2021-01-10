<?php

namespace Drupal\music\Controller;

class MusicController
{
 public function hello() {
  return array(
    '#title' => 'Hello world!',
    '#markup' => 'This is some content.'
   );
 }
}
