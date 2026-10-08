<?php
/**
 * @file
 * Contains \Drupal\senyal_vida\Form\SenyalVidaForm.
 */
namespace Drupal\senyal_vida\Form;

use Drupal\senyal_vida\Utility\SqlQueries;
use Civi\Api4\Activity;
use Civi\Api4\ActivityContact;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

const SIGN_OF_LIFE_ACTIVITY_TYPE = 35;
const ACTIVITY_SUBJECT = "Senyal de Vida";
const DUPLICATE_WINDOW = '-5 minutes';

/**
 * Confirmation form: the activity is only created on POST, so link
 * previewers and mail scanners (which only do GET) can't trigger it.
 */
class SenyalVidaForm extends FormBase{

  public function getFormId() {
    return 'senyal_vida_confirm_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL, $hash = NULL) {

    // 'value' elements are kept server side, never sent to the browser.
    $form['cid'] = [
      '#type' => 'value',
      '#value' => $cid,
    ];
    $form['hash'] = [
      '#type' => 'value',
      '#value' => $hash,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Click here if you want to continue receiving booklets at this address!'),
      '#attributes' => ['class' => ['my-link', 'submit-button']],
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    \Drupal::service('civicrm')->initialize();

    $contacts = SqlQueries::getContactIfExist($form_state->getValue('cid'), $form_state->getValue('hash'));
    if(sizeof($contacts) == 0){
      $form_state->setErrorByName('', $this->t('Something went wrong. Send an email to') . ' quaderns@fespinal.com');
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::service('civicrm')->initialize();

    $cid = intval($form_state->getValue('cid'));
    $hash = $form_state->getValue('hash');

    // Avoid creating a duplicate if one has already been created recently.
    $recent = ActivityContact::get(FALSE)
      ->selectRowCount()
      ->addWhere('contact_id', '=', $cid)
      ->addWhere('record_type_id:name', '=', 'Activity Source')
      ->addWhere('activity_id.activity_type_id', '=', SIGN_OF_LIFE_ACTIVITY_TYPE)
      ->addWhere('activity_id.activity_date_time', '>=', date('Y-m-d H:i:s', strtotime(DUPLICATE_WINDOW)))
      ->execute()
      ->count();

    if($recent == 0){
      Activity::create(FALSE)
      ->addValue('activity_type_id', SIGN_OF_LIFE_ACTIVITY_TYPE)
      ->addValue('source_contact_id', $cid)
      ->addValue('assignee_contact_id', $cid)
      ->addValue('subject', ACTIVITY_SUBJECT)
      ->execute();
    }

    $form_state->setRedirect('senyal.confcontent', [], ['query' => ['cid' => $cid, 'hash' => $hash]]);
  }
}
