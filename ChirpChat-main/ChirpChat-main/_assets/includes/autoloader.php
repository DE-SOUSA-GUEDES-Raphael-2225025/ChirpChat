<?php
function my_autoload(string $class): void {
    $class_path = str_replace( '\\', '/', $class);
    $class_path = explode('/',$class_path);
    $newClass_path = "";
    $className = "";

    /* On récupère le dernier morceau avec le nom de la classe */
    foreach ($class_path as $section){
        if($section != $class_path[count($class_path) - 1]){
            $newClass_path .= strtolower($section) . '/';
        }else{
            $className = $section;
        }
    }

    /* Le fichier de la classe commence par une majuscule */
    if(file_exists('modules/' . $newClass_path . $className . '.php')){
        require_once  'modules/' . $newClass_path . $className . '.php';
    }else{ /* Il ne commence pas par une majuscule */
        require_once  'modules/' . $newClass_path . lcfirst($className) . '.php';
    }

}

spl_autoload_register('my_autoload');