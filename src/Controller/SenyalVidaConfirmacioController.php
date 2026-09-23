<?php
/**
 * @file
 * Contains \Drupal\senyal_vida\Controller\SenyalVidaConfirmacioController.
 */
namespace Drupal\senyal_vida\Controller;

use Drupal\senyal_vida\Utility\SqlQueries;
use Civi\Api4\Activity;
use Drupal\Core\Controller\ControllerBase;

const SIGN_OF_LIFE_ACTIVITY_TYPE = 3;
const ACTIVITY_SUBJECT = "Senyal de Vida";

class SenyalVidaConfirmacioController extends ControllerBase{

  public function __construct(){
    \Drupal::service('civicrm')->initialize();
  }

  public function content() {

    $request = \Drupal::request();

    $cid = $request->query->get('cid');
    $hash = $request->query->get('hash');
    //\Drupal::logger('my_module')->info('The cid is "'.$cid. '" and the hash "'.$hash.'"');

    $contacts = SqlQueries::getContactIfExist($cid, $hash);

    $address = "";
    $postal_code = "";
    $city = "";
    $country = "";
    $created = false;

    if(sizeof($contacts) != 0){

      $address .= $contacts[0]['address.street_address'];
      $postal_code .= $contacts[0]['address.postal_code'];
      $city .= $contacts[0]['address.city'];
      $country .= $contacts[0]['country.name'];
      $created = true;

      // TODO: Avoid creating an activity if one has already been created within the last 5 minutes.
      $results = Activity::create(FALSE)
      ->addValue('activity_type_id', SIGN_OF_LIFE_ACTIVITY_TYPE)
      ->addValue('source_contact_id', intval($cid))
      ->addValue('assignee_contact_id', intval($cid))
      ->addValue('subject', ACTIVITY_SUBJECT)
      ->execute();
    }

    return [
      '#theme' => 'my_template-2',
      '#address' => $address,
      '#city' => $city,
      '#postal_code' => $postal_code,
      '#country' => $country,
      '#created' => $created,
    ];
  }
}
