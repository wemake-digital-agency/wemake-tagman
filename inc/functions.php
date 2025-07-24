<?php

if(!defined('WMTM_ABSPATH')) exit;

function wmtm_reg_esc($c){
    $patterns = array('/\//', '/\^/', '/\./', '/\$/', '/\|/',
        '/\(/', '/\)/', '/\[/', '/\]/', '/\*/', '/\+/',
        '/\?/', '/\{/', '/\}/', '/\,/');
    $replace = array('\/', '\^', '\.', '\$', '\|', '\(', '\)',
        '\[', '\]', '\*', '\+', '\?', '\{', '\}', '\,');
    return preg_replace($patterns,$replace, $c);
}

function wmtm_strip_tags($str){
    return htmlspecialchars(strip_tags($str));
}

function wmtm_admin_perm(){
    if(current_user_can('administrator')){
        return true;
    }
    return false;
}

function wmtm_ajax_return($data){
    echo json_encode($data);
    exit;
}

function wmtm_get_ajax_action_url($action, $parameters = array(), $echo = true){

    $action_url = admin_url('/admin-ajax.php?action='.$action.'&_wpnonce='.wp_create_nonce($action));

    if(count($parameters)){
        foreach($parameters as $par_k=>$par){
            $action_url .= '&'.$par_k.'='.$par;
        }
    }

    if($echo){
        echo $action_url;
    }else{
        return $action_url;
    }

}

function wmtm_recursive_files_search($dir_path, $filter = '*.*', $data = array()){

    // Find folders

    if(count($folders = glob($dir_path.'/*', GLOB_ONLYDIR))){
        foreach($folders as $folder){
            $data = wmtm_recursive_files_search($folder, $filter, $data);
        }
    }

    // Find files

    if(count($files = glob($dir_path.'/'.$filter))){
        foreach($files as $file){
            $data[] = $file;
        }
    }

    return $data;

}

function wmtm_get_maintenance_status(){
    return get_option('wmtm_maintenance_on');
}

function wmtm_set_maintenance_status($status){
    update_option('wmtm_maintenance_on', $status ? 1 : 0);
}

function wmtm_basename($file_path){
    return preg_replace('/(.*)\//', '', $file_path);
}

function wmtm_get_settings(){
    return array(
        'tag_manager_enabled' => get_option('wmtm_tag_manager_enabled'),
        'tag_manager_id' => get_option('wmtm_tag_manager_id'),
        'analytics_enabled' => get_option('wmtm_analytics_enabled'),
        'analytics_id' => get_option('wmtm_analytics_id'),
        'adwords_enabled' => get_option('wmtm_adwords_enabled'),
        'adwords_id' => get_option('wmtm_adwords_id'),
        'facebook_enabled' => get_option('wmtm_facebook_enabled'),
        'facebook_id' => get_option('wmtm_facebook_id'),
    );
}

function wmtm_get_plugin_row_path(){
    return WMTM_PLUGIN_SLUG.'/'.preg_replace('/\-/', '_', WMTM_PLUGIN_SLUG).'.php';
}

?>