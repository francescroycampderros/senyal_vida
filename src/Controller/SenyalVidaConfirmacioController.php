<?php
/**
 * @file
 * Contains \Drupal\senyal_vida\Controller\SenyalVidaConfirmacioController.
 */
namespace Drupal\senyal_vida\Controller;

use Drupal\senyal_vida\Utility\SqlQueries;
use Drupal\Core\Controller\ControllerBase;

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
