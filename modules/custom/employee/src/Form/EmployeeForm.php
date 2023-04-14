<?php

  namespace Drupal\employee\Form;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Code\Database;

  class EmployeeForm extends FormBase{
    /**
     * {@inheritdoc}
     */

    //  return form id
     public function getFormId(){
        return 'create_employee';
     }

    /**
     * {@inheritdoc}
     */
    //create form
    public function buildForm(array $form, FormStateInterface $form_state){

        $genderOptions = array(
            'Male' => 'Male',
            'Female' => 'Female',
            'Other' => 'Other'
        );

        $form['name'] = array( 
            '#type' => 'textfield',
            '#title' => t('Name'),
            '#default_value' => '',
            '#required' => true,
            '#attributes' => array(
                'placeholder' => 'Name'
            )
        );

        /* $form['email'] = array(
            '#type' => 'email',
            '#title' => t('Email'),
            '#placeholder' => 'Enter your email',
            '#required' => true
        ); */

        $form['gender'] = array(
            '#type' => 'select',
            '#title' => 'Gender',
            '#options' => $genderOptions,
            '#required' => true
        );

        $form['about_employee'] = array(
            '#type' => 'textarea',
            '#title' => 'About Employee',
            '#default_value' => ''
        );

        $form['save'] = array(
            // server side verification
            '#type' => 'submit',
            '#value' => 'Save Employee',
            '#button_type' => 'primary',
            // for html verification
            '#required' => true
        );

        return $form;
    }


        /**
         * {@inheritdoc}
         */
       public function validateForm(array &$form, FormStateInterface $form_state){
        //    getValue() for accessing single value
        //    getValues() for accessing multiple or all values
            $name = $form_state->getValue('name');

            if(trim($name) == ''){
                 $form_state->setErrorByName('name',$this->t('Name field is required'));
            }
            // else if(trim($form_state->getValue('email')) == ''){
            //     $form_state->setErrorByName('email',$this->t('email field is required'));
            // }
            else if($form_state->getValue('gender') == '0'){
                $form_state->setErrorByName('gender',$this->t('Gender field is required'));
            }
            else if($form_state->getValue('about_employee') == ''){
                $form_state->setErrorByName('about_employee',$this->t('About Employee is empty'));
            }
       } 


         /**
         * {@inheritdoc}
         */

        //  ---------CREATE-----------
         public function submitForm(array &$form, FormStateInterface $form_state){
            $postData = $form_state->getValues();
       //------------read------ 
            // $readData = $form_state->getValue(['form_build_id']);
            // print_r($readData);
            // die;

            // echo "<pre>";
            // print_r($postData);
            // echo "</pre>";
        

            // remove unwanted data fromt postdata
            unset($postData['save'],$postData['form_build_id'],$postData['form_token'],$postData['form_id'],$postData['op']);

            // ----insert into db-------
            $query = \Drupal::database();
            //get the fields from post data of form
            $query->insert('employee')->fields($postData)->execute();

            // ----show message after query is exec-----
            //Status, error,warning, info options for msg
            drupal_set_message(t('Employee data save successfully!'),'status', TRUE);
            /* drupal_set_message(t('Employee data save successfully!'),'warning', TRUE);
            drupal_set_message(t('Employee data save successfully!'),'error', TRUE);
            drupal_set_message(t('Employee data save successfully!'),'info', TRUE); */
            
         }
  }