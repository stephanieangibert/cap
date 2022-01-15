<?php
/**
 * @package ma course
 * @version 1.0.0
 */
/*
Plugin Name: ma course
Plugin URI: http://cap.stephange.fr
Description: associer la course à l'utilisateur
Author: stephanie angibert
Version: 1.0.0
Author URI: http://cap.stephange.fr
*/
if(!class_exists("macourse")){
    class macourse{
        function course_install(){      
            global $wpdb; 
            $table_site = $wpdb->prefix.'course';
      if($wpdb->get_var("SHOW TABLES LIKE '$table_site'") != $table_site){
                $sql="CREATE TABLE `$table_site`(
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY , 
                `titre` TEXT NOT NULL,
                `serie` TEXT NOT NULL                              
                )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
                  ;";
        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
          dbDelta($sql);
      }
  }    
   function course_user(){
    global $wpdb; 
    $table_site = $wpdb->prefix.'courseUser';
if($wpdb->get_var("SHOW TABLES LIKE '$table_site'") != $table_site){
        $sql="CREATE TABLE `$table_site`(
        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY , 
        `nom` TEXT NOT NULL,
        `prenom` TEXT NOT NULL,
        `elephant` TEXT NOT NULL,
        `mauves` TEXT NOT NULL,
        `nantes` TEXT NOT NULL               
        )ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
          ;";
require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql);
  }
}
        
        function init(){
            if (function_exists('add_options_page')){ 
                  $mapage=add_options_page("ma course", 'ma course', 'administrator', __FILE__, array($this,'course_admin_page'));
                 
                  add_action('load-'.$mapage, array($this,'course_admin_js_css'));
              
            }
        }
        function course_admin_page(){   
        
            $page= isset($_GET['p']) ? $_GET['p'] : null;
            switch($page){
              case 'map' :         
                require_once('template-map.php'); 
              break;
              default:        
                require_once('template.php');   
              break;              
            } 
              
        if(isset($_GET['action'])){           
            if($_GET['action']=='createmap'){ 
             $_POST['serie']==1;
                if((trim($_POST['titre'])!='')){   
                              
                      $insertmap=$this->insertmap($_POST['titre'],$_POST["serie"]); 
                                           
                      if($insertmap==true)  echo '<script> window.location = "'.get_bloginfo('url').'/wp-admin/options-general.php?page=course.php&map=ok'.'"; </script>';   
                      else echo "Une erreur est survenue";
                                          
                }else{
                  echo '<p style="color:red;">Veuillez remplir tous les champs</p>';
                }
            // }else if($_GET['action']=='updatemap'){
            //     if((trim($_POST['titre'])!='')&&(trim($_POST['id'])!='')){   
                              
            //           $updatemap=$this->updatemap($_POST['id'],$_POST['titre']); 
                                           
            //           if($updatemap==true) echo '<script> window.location = "'.get_bloginfo('url').'/wp-admin/options-general.php?page=course.php&p=map&id='.$_POST['id'].'&map=ok'.'"; </script>';   
            //           else echo "Une erreur est survenue";
                                          
            //     }else{
            //       echo '<p style="color:red;">Veuillez remplir tous les champs</p>';
            //     }          
             }
             else if($_GET['action']=='deletemap'){
                 if(trim($_POST['id'])!=''){
                 
                      $deletemap=$this->deletemap($_POST['id']);
                      
                      if($deletemap==true) echo '<script> window.location = "'.get_bloginfo('url').'/wp-admin/options-general.php?page=course.php&map=deleteok'.'"; </script>';  
                      else echo "Une erreur est survenue";
                 }
              }          
            }
           
             
    }
    
    
      
    function gmap_admin_js_css(){
        wp_register_style('course_css', plugins_url('css/admin-course.css', __FILE__));
        wp_enqueue_style('course_css');
        wp_enqueue_script('course_js', plugins_url('js/admin-course.js', __FILE__), array('jquery'),'1.0',true);
       
      } 
      function getmaplist(){ 
        global $wpdb;  
        $table_map= $wpdb->prefix.'course';        
        $sql = "SELECT * FROM ".$table_map;  
        $maplist = $wpdb->get_results($sql);                        
      return $maplist; 
      } 
      function getmap($id){ 
        global $wpdb;  
        $table_map= $wpdb->prefix.'course';         
        $sql = $wpdb->prepare("SELECT * FROM ".$table_map." WHERE serie=1",$id);  
        $map = $wpdb->get_results($sql);
                             
      return $map; 
      }
      function courseInsert($nom,$prenom,$elephant,$nantes,$mauves){
        global $wpdb;  
         $table_map= $wpdb->prefix.'courseUser'; 
      
        $sql=$wpdb->prepare( 
          "
         INSERT INTO ".$table_map."
         (nom,prenom,elephant,nantes,mauves)
         VALUES (%s,%s,%s,%s,%s)
         ", 
         $nom,
         $prenom,
         $elephant,
         $nantes,
         $mauves
                 
                         
);
  
$wpdb->query($sql);

if (!$sql) $insertUser = false;
else $insertUser = true;        
return $insertUser; 
      } 
      function getcourseuser(){ 
        global $wpdb;  
        $table_map= $wpdb->prefix.'courseUser';        
        $sql = "SELECT * FROM ".$table_map;  
        $courseuser = $wpdb->get_results($sql);                        
      return $courseuser; 
      }   
      function semiNantes(){ 
        global $wpdb;         
        $table_map= $wpdb->prefix.'courseUser';         
        $sql = $wpdb->prepare("SELECT * FROM ".$table_map." WHERE nantes='on'");  
        $nantes = $wpdb->get_results($sql);                             
      return $nantes; 
      }  
      function elephant(){ 
        global $wpdb;         
        $table_map= $wpdb->prefix.'courseUser';         
        $sql = $wpdb->prepare("SELECT * FROM ".$table_map." WHERE elephant='on'");  
        $elephant = $wpdb->get_results($sql);                             
      return $elephant; 
      }
      function trail(){ 
        global $wpdb;         
        $table_map= $wpdb->prefix.'courseUser';         
        $sql = $wpdb->prepare("SELECT * FROM ".$table_map." WHERE mauves='on'");  
        $trail = $wpdb->get_results($sql);                             
      return $trail; 
      }    
      function insertmap($titre,$serie){
        global $wpdb;  
        $table_map= $wpdb->prefix.'course';  
        
      	$sql=$wpdb->prepare( 
             		  "
                  INSERT INTO ".$table_map."
                  (titre,serie)
                  VALUES (%s,1)
              		", 
                  $titre,
                  $serie            	
              	                	
      	);
        $wpdb->query($sql);
        
        
        if (!$sql) $insertmap = false;
        else $insertmap = true;        
        return $insertmap;
      }
      function updatemap($nom,$prenom,$elephant,$nantes,$mauves){
        global $wpdb;  
  $table_map= $wpdb->prefix.'courseUser';  
  
  $sql=$wpdb->prepare( 
             "
            UPDATE ".$table_map." SET  
            prenom=%s,          
            elephant=%s,
            nantes=%s,
            mauves=%s,
            id=%d
            WHERE nom=%s
            ", 
            
            $prenom,
            $elephant,
            $nantes,
            $mauves,
            $id,
            $nom
                                     	
             ); 

        $wpdb->query($sql);
        
        if (!$sql) $updatemap = false;
        else $updatemap = true;
        
        return $updatemap;
}
      function deletemap($id){
              global $wpdb; 
              
            $table_map= $wpdb->prefix.'course';   
            $sql = $wpdb->prepare("DELETE FROM ".$table_map." WHERE id=%d",$id);              
            $mapdelete = $wpdb->query($sql);                                  
            if (!$sql) $mapdelete = false;
            else $mapdelete = true;              
        return $mapdelete;
      }
      function gmap_shortcode($att){      
        $maplist=$this->getmap($att['id']); 
        $updatemap=$this->updatemap($_POST['nom'],$_POST['prenom'],$_POST['elephant'],$_POST['nantes'],$_POST['mauves']);
       if(isset($_POST['submitCourse'])){
        if($_POST['nom']!="" && $_POST['prenom']!=""){
          $insertUser=$this->courseInsert($_POST['nom'],$_POST['prenom'],$_POST['elephant'],$_POST['nantes'],$_POST['mauves']);  
          $message="";  
        }else{
          $message =" ⛔ Veuillez renseigner votre nom et votre prénom !";
        }
       }else{
         $message="";
       }   
      
        
        $courseuser=$this->getcourseuser();
        $nantes=$this->semiNantes();  
        $elephant=$this->elephant(); 
        $trail=$this->trail();       
        $value=0;
        echo'
        <section class="bgCourse">
        <div class="formCourseUser">
        <h3 class="titreHCourse">Quelles courses souhaites-tu faire cette année ! 🏃‍♀️</h3>
        <form action="https://cap.stephange.fr/course/" method="post">
        <label>Nom</label>
        <input type=text name="nom" placeholder="saisis ton nom">
        <label>Prénom</label>
        <input type=text name="prenom" placeholder="saisis ton prénom">
        <h3 class="check">Check les courses que tu désires faire 👟 </h3>
        ';    
         echo 'Course de l/éléphant&nbsp<input type="checkbox" name="elephant"><br />';
         echo 'Semi de Nantes&nbsp<input type="checkbox" name="nantes"><br />';
         echo 'Trail de mauves&nbsp<input type="checkbox" name="mauves"><br />';
       
        echo'
        <input type="submit" name="submitCourse">
        </br>
        <p class="message">'.$message.'</p>
        </br>
        </form>
        </div>';  
        
           
    echo '<div class="souhait">Tu veux voir les souhaits de tes ami.es !&nbspclick&nbsp&nbsp&nbsp<i class="flecheC fas fa-chevron-down"></i></div>
    <div class="displayCourse">
    <div class="titreCourse">Les personnes suivantes se sont inscrites pour le semi de nantes :</div>';
 
    foreach($nantes as $nantesUsers){
      echo '<li>'.$nantesUsers->prenom.'&nbsp'.$nantesUsers->nom.'</li>';
     
    }
    echo '<div class="titreCourse">Les personnes suivantes se sont inscrites pour la course de l éléphant :</div>';
 
    foreach($elephant as $elephantUsers){
      echo '<li>'.$elephantUsers->prenom.'&nbsp'.$elephantUsers->nom.'</li>';
     
    } 
    echo '<div class="titreCourse">Les personnes suivantes se sont inscrites pour le trail de Mauves :</div>';
 
    foreach($trail as $trailUsers){
      echo '<li>'.$trailUsers->prenom.'&nbsp'.$trailUsers->nom.'</li>';
     
    }  
    echo '</div>
    </div>
    </section>';     
      }    
     
}
}



if (class_exists("macourse")){  
    $inst_map = new macourse();  
} 
if (isset($inst_map)){
    register_activation_hook(__FILE__, array($inst_map, 'course_install')); 
    register_activation_hook(__FILE__, array($inst_map, 'course_user'));   
    add_action('admin_menu', array($inst_map, 'init'));      
    if(function_exists('add_shortcode')){  
    add_shortcode('mygmap',array($inst_map, 'gmap_shortcode'));
    } 

 
    } 
 
?>