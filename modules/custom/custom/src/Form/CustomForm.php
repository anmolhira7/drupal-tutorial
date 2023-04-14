<?php

namespace Drupal\custom\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Code\Database;

class CustomForm extends FormBase{
    /**
     * {@inheritdoc}
     */

     //return form id
    public function getFormId(){
        return 'custom_form_id';
     }

     //build form
     /**
       * {@inheritdoc}
       */
    public function buildForm(array $form, FormStateInterface $form_state){
        $form['email'] = array(
            '#title' => t('Email Address'),
            '#type' => 'textfield',
            '#size' => 25,
            '#required' => TRUE,
            '#description' => t('my first form'),
          );

          $form['phone'] = array(
            '#title' => 'Phone Number',
            '#type' => 'textfield',
            '#size' => 25,
            '#attributes' => array(
                'placeholder' => 'Enter 10 digit Mobile Number'
            )
          );

          $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('submit')
          );
          return $form;
     }

    public function validateForm(array &$form, FormStateInterface $form_state){
        if(strlen($form_state->getValue('phone')) < 10 || strlen($form_state->getValue('phone')) > 10){
            $form_state->setErrorByName('phone', $this->t('The phone number is less than or greater than 10. Please enter a 10 digit phone number'));
        } 
     }



    public function submitForm(array &$form, FormStateInterface $form_state){
       drupal_set_message(t('Form testing Completed!'), 'status', TRUE);
    } 

}