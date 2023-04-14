<?php
namespace Drupal\custom\Controller;
use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase{
     public function content(){
        return [
          '#title' => 'Basic page information',
          '#markup' => '<h2>This is our basic page</h2>'
        ];
     }


     public function information(){
          // items is used to pass data

          $data = array(
               'name' => 'Mudit Kumar',
               'email' => 'muditdexter@gmail.com'
          );
          
          return [
               '#title' => 'Information page',
               '#theme' => 'information_page',
               '#items' => $data
          ];
     }
}


?>