<?php

namespace Drupal\music\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\messenger;
use Drupal\Core\Link;


class ArtistForm extends FormBase
{

  /**
   * @inheritDoc
   */
  public function getFormId()
  {
    return "music_artist_form";
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $conn = Database::getConnection();

    $record = [];

    if(isset($_GET['id'])) {
      $query = $conn->select('artist', 'a')->condition('artist_id', $_GET['id'])
        ->fields('a');
      $record = $query->execute()->fetchAssoc();
    }

    $form['name']=['#type'=>'textfield','#title'=>t('Artist Name'), '#required'=>TRUE,
    '#default_value'=>(isset($record['name']) && $_GET['id']) ? $record['name'] : '',];

    $form['isBand']=['#type'=>'checkbox','#title'=>t('Is Band'),
      '#default_value'=>(isset($record['isBand']) && $_GET['id']) ? $record['isBand'] : 1,];

    $form['action'] = ['#type' => 'action'];
    $form['action']['submit'] = ['#type' => 'submit', '#value' => t('Save')];

    $link = Url::fromUserInput('/music');

    $form['action']['cancel'] = ['#markup' => Link::fromTextAndUrl($this->t('Back to artists'), $link)->toString()];

    return $form;
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $name = $form_state->getValue('name');
    $isBand = $form_state->getValue('isBand');

    if(isset($_GET['id'])) {
      $field = ['name' => $name, 'isBand' => $isBand];

      $query = \Drupal::database();
      $query->update('artist')->fields($field)->condition('artist_id', $_GET['id'])->execute();

      $this->messenger()->addMessage('Successfully updated');
    } else {
      $field = ['name' => $name, 'isBand' => $isBand];

      $query = \Drupal::database();
      $query->insert('artist')->fields($field)->execute();

      $this->messenger()->addMessage('Successfully saved');
    }

    $form_state->setRedirect('music.music_controller_listing');

  }
}
