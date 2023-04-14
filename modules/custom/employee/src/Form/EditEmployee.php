<?php
// -----------UPDATE-------
namespace Drupal\employee\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Code\Database;

class EditEmployee extends FormBase{
  /**
   * {@inheritdoc}
   */

  //  return form id
   public function getFormId(){
      return 'edit_employee';
   }

  /**
   * {@inheritdoc}
   */
  //create form
  public function buildForm(array $form, FormStateInterface $form_state){

    //get the id query param
    $id = \Drupal::routeMatch()->getParameter('id');
    $query = \Drupal::database();
    // GET RECORD FROM DATABASE WHERE ID MATCHED WITH OUR SUPPLIED ID
    $data = $query->select('employee','e')
            ->fields('e',['id','name','gender','about_employee'])
            ->condition('e.id', $id, '=') //where e.id = $id 
            ->execute()->fetchAll(\PDO::FETCH_OBJ); 

    // print_r($data);    
    // die;    

      $genderOptions = array(
          'Male' => 'Male',
          'Female' => 'Female',
          'Other' => 'Other'
      );

      $form['name'] = array( 
          '#type' => 'textfield',
          '#title' => t('Name'),
          '#default_value' => $data[0]->name,
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
          '#default_value'=> $data[0]->gender,
          '#type' => 'select',
          '#title' => 'Gender',
          '#options' => $genderOptions,
          '#required' => true,
      );

      $form['about_employee'] = array(
          '#type' => 'textarea',
          '#title' => 'About Employee',
          '#default_value' => $data[0]->about_employee
      );

      $form['Update'] = array(
          // server side verification
          '#type' => 'submit',
          '#value' => 'update',
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

       public function submitForm(array &$form, FormStateInterface $form_state){
        //get id from query params
        $id = \Drupal::routeMatch()->getParameter('id');

        //get all submitted values by the form
        $postData = $form_state->getValues();

        //   echo "<pre>";
        //   print_r($postData);
        //   echo "</pre>";
        //   die;
      

          // remove unwanted data fromt postdata
          unset($postData['Update'],$postData['form_build_id'],$postData['form_token'],$postData['form_id'],$postData['op'],$$postData['gender']);

          // ----insert into db-------
          $query = \Drupal::database();
          //update fields of particular passed id
          $query->update('employee')->fields($postData)
                ->condition('id',$id) //update table employee where id=$id set name=$name ....
                ->execute();


        //redirect to /employee-list     
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse('../employee-list');
        $response->send();

          // ----show message after query is exec-----
          //Status, error,warning, info options for msg
          drupal_set_message(t('Employee data update successfully!'),'status', TRUE);
          /* drupal_set_message(t('Employee data save successfully!'),'warning', TRUE);
          drupal_set_message(t('Employee data save successfully!'),'error', TRUE);
          drupal_set_message(t('Employee data save successfully!'),'info', TRUE); */
          
       }
}