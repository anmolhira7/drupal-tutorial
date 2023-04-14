<?php
  
   namespace Drupal\employee\Controller;
   use Drupal\Core\Controller\ControllerBase;

   class EmployeeController extends ControllerBase{

    //render form on twig template
     
      public function createEmployee(){
         $form = \Drupal::formBuilder()->getForm('Drupal\employee\Form\EmployeeForm');
        //for rendering twig template

        return [
            // Name of created page ie employee same as twig file, we can also use data instead of items
                '#theme' => 'employee',
                '#items' => $form,
                'title' => 'Employee Form'
             ];
      }

      //---- for normal form rendering without twig template
    //   public function createEmployee2(){
    //     $form = \Drupal::formBuilder()->getForm('Drupal\employee\Form\EmployeeForm');
    //     //render form without twig though drupal
    //     $renderForm = \Drupal::service('renderer')->render($form);


    //     return [
    //        '#type' => 'markup',
    //        '#markup' => $renderForm,
    //        'title' => 'Employee Form'
    //     ];

    //  }

      //  ---------Fetch data from db AND Print data in table---------
       public function getEmployeeList(){
         $limit = 2;
         //initialize query var with database obj/ref
          $query = \Drupal::database();
         //  e is alias for employee table
          $result= $query->select('employee','e')
                   ->fields('e',['id','name','gender','about_employee']) //getting req fields from table
                   ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit($limit)
                   ->execute()->fetchAll(\PDO::FETCH_OBJ); //data is in object format

         
           $data = [];
           $count = 0;

           $params = \Drupal::request()->query->all();
          //  print_r($params);
          //  exit;
            
           if(empty($params) || $params['page'] == 0){
            $count = 1;
           }else if($params['page'] == 1){
            $count = $params['page'] + $limit;
           }else{
            $count = $params['page'] * $limit;
            $count++;
           }

             //  convert it to array

           foreach($result as $row){
             $data []= [
               'serial_no' => $count.".",
               'name'=> $row->name,
               'gender'=> $row->gender,
               'about_employee'=> $row->about_employee,
               'edit'=>t("<a href='edit-employees/$row->id'>Edit</a>"),
               'Delete'=>t("<a href='delete-employees/$row->id'>Delete</a>")
             ];
             $count++;
           }

          //  var_dump($data);
          //  die;
        
          //--------create table to show fetched data
         // table header matlab table ke lie th
           $header = array('Serial', 'NAME','GENDER','ABOUT EMPLOYEE','Edit','Delete');

           $build['table'] = [
            '#type' => 'table',
            '#header' => $header,
            '#rows'=> $data    
           ];

           $build['pager'] = [
            '#type'=>'pager'
           ];

           /*table data ayega array pass kia hai */
           return[
            $build,
            '#title' =>'Employee list'
           ];

       }

      //  ----delete----
       public function deleteEmployee($id){
         $query = \Drupal::database();
         $query->delete('employee')
               ->condition('id',$id,'=')
               ->execute(); 
       

       //redirect to /employee-list     
       $response = new \Symfony\Component\HttpFoundation\RedirectResponse('../employee-list');
       $response->send();

       drupal_set_message(t('Employee deleted successfully!'), 'error', TRUE);

       }

   }