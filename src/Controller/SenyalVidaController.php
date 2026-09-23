<?php
/**
 * @file
 * Contains \Drupal\senyal_vida\Controller\SenyalVidaController.
 */
namespace Drupal\senyal_vida\Controller;

use Drupal\senyal_vida\Utility\SqlQueries;
use Drupal\Core\Controller\ControllerBase;

class SenyalVidaController extends ControllerBase{

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
    $found = false;

    foreach ($contacts as $contact) {
      $found = true;
      $address .= $contact['address.street_address'];
      $postal_code .= $contact['address.postal_code'];
      $city .= $contact['address.city'];
      $country .= $contact['country.name'];
    }

    return [
      '#theme' => 'my_template',
      '#address' => $address,
      '#city' => $city,
      '#postal_code' => $postal_code,
      '#country' => $country,
      '#found' => $found,
      '#cid' => $cid,
      '#hash' => $hash,
    ];
  }
}
