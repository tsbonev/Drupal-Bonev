<?php

namespace Drupal\music\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Core\Url;

class MusicController extends ControllerBase
{
  public function listAlbums() {
    $header_table = ['id'=>t('ID'),
      'name'=>t('Artist Name'),
      'isBand'=>t('Is Band'),
      'opt'=>[t('Options')]];

    $row=[];

    $conn = Database::getConnection();

    $query = $conn->select('artist', 'a');
    $query->fields('a', ['artist_id', 'name', 'isBand']);
    $result = $query->execute()->fetchAll();

    foreach ($result as $value) {
      $edit = Url::fromUserInput('/music/form/artist?id=' . $value->artist_id);

      $row[]=['id'=>$value->artist_id,
        'name'=>$value->name,
        'isBand'=> ($value->isBand == 0) ? 'False' : 'True',
        'opt'=>Link::fromTextAndUrl('Edit', $edit)
      ];
    }

    $add = Url::fromUserInput('/music/form/artist');

    $text = 'Add artist';

    $data['table'] = ['#type' =>'table',
      '#header'=>$header_table,
      '#rows'=>$row,
      '#empty'=>$this->t('No Artists Found'),
      '#caption'=>Link::fromTextAndUrl($text, $add)->toString()];

    $this->messenger()->addMessage('Artists listed');

    return $data;
  }
}
