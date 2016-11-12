<?php

add_filter('acf/validate_value/key=field_56b326315abd5', 'domwiedzy_acf_validate_member_email', 10, 4);
add_filter('acf/validate_value/key=field_56c5e27b004f1', 'domwiedzy_acf_validate_member_fname', 11, 4);
add_filter('acf/validate_value/key=field_56c5e294004f2', 'domwiedzy_acf_validate_member_lname', 12, 4);

function domwiedzy_acf_validate_member_lname( $valid, $value, $field, $input ){

    if( !$valid ) {
        return $valid;
    }

    global $wpdb;
    $message = false;

    $fname = (isset($_POST['acf']['field_56c5e27b004f1']) && !empty($_POST['acf']['field_56c5e27b004f1'])) ? $_POST['acf']['field_56c5e27b004f1'] : "";
    $lname = $value;
    $email = (isset($_POST['acf']['field_56b326315abd5']) && !empty($_POST['acf']['field_56b326315abd5'])) ? $_POST['acf']['field_56b326315abd5'] : "";;

    if($lname !=""){

        $emailHash = md5(strtolower(trim($fname.$lname)).$email);

        $validSql = "SELECT ID, post_title FROM {$wpdb->posts} WHERE `post_type` = 'osobykontaktowe' AND `post_status` = 'publish' AND `post_name` = '{$emailHash}'";

        if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){

            parse_str($_SERVER['HTTP_REFERER'], $vars);
            if(isset($vars['action']) && $vars['action'] == 'edit'){

                $ID = (int)$vars[key($vars)];

                $validSql .= " AND ID != {$ID}";

            }

        }

        $company_exist = $wpdb->get_results($validSql , ARRAY_A );
        if(!empty($company_exist)){
            $message = "Ten użytkownik już istnieje w bazie. ID: ".$company_exist[0]['ID'] . ' / '. $company_exist[0]['post_title'] . ' - ' . get_field('typ', $company_exist[0]['ID']);
        }

    }else{
        $message = "Nazwisko jest obowiązkowe";
    }

    if( $message !== false ) {

        $valid = $message;

    }

    return $valid;
}

function domwiedzy_acf_validate_member_fname( $valid, $value, $field, $input ){

    if( !$valid ) {
        return $valid;
    }

    global $wpdb;
    $message = false;

    $fname = $value;
    $lname = (isset($_POST['acf']['field_56c5e294004f2']) && !empty($_POST['acf']['field_56c5e294004f2'])) ? $_POST['acf']['field_56c5e294004f2'] : "";
    $email = (isset($_POST['acf']['field_56b326315abd5']) && !empty($_POST['acf']['field_56b326315abd5'])) ? $_POST['acf']['field_56b326315abd5'] : "";;

    if($fname !=""){

        $emailHash = md5(strtolower(trim($fname.$lname)).$email);

        $validSql = "SELECT ID, post_title FROM {$wpdb->posts} WHERE `post_type` = 'osobykontaktowe' AND `post_status` = 'publish' AND `post_name` = '{$emailHash}'";

        if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){

            parse_str($_SERVER['HTTP_REFERER'], $vars);
            if(isset($vars['action']) && $vars['action'] == 'edit'){

                $ID = (int)$vars[key($vars)];

                $validSql .= " AND ID != {$ID}";

            }

        }

        $company_exist = $wpdb->get_results($validSql , ARRAY_A );
        if(!empty($company_exist)){
            $message = "Ten użytkownik już istnieje w bazie. ID: ".$company_exist[0]['ID'] . ' / '. $company_exist[0]['post_title'] . ' - ' . get_field('typ', $company_exist[0]['ID']);
        }

    }else{
        $message = "Imię jest obowiązkowe";
    }

    if( $message !== false ) {

        $valid = $message;

    }

    return $valid;
}

function domwiedzy_acf_validate_member_email( $valid, $value, $field, $input ){

    if( !$valid ) {
        return $valid;
    }

    global $wpdb;
    $message = false;

    $fname = (isset($_POST['acf']['field_56c5e27b004f1']) && !empty($_POST['acf']['field_56c5e27b004f1'])) ? $_POST['acf']['field_56c5e27b004f1'] : "";
    $lname = (isset($_POST['acf']['field_56c5e294004f2']) && !empty($_POST['acf']['field_56c5e294004f2'])) ? $_POST['acf']['field_56c5e294004f2'] : "";
    $email = $value;

    if($email !=""){

        if(filter_var($email, FILTER_VALIDATE_EMAIL)){

            $emailHash = md5(strtolower(trim($fname.$lname)).$email);

            $validSql = "SELECT ID, post_title FROM {$wpdb->posts} WHERE `post_type` = 'osobykontaktowe' AND `post_status` = 'publish' AND `post_name` = '{$emailHash}'";

            if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){

                parse_str($_SERVER['HTTP_REFERER'], $vars);
                if(isset($vars['action']) && $vars['action'] == 'edit'){

                    $ID = (int)$vars[key($vars)];

                    $validSql .= " AND ID != {$ID}";

                }

            }

            $company_exist = $wpdb->get_results($validSql , ARRAY_A );
            if(!empty($company_exist)){
                $message = "Ten użytkownik już istnieje w bazie. ID: ".$company_exist[0]['ID'] . ' / '. $company_exist[0]['post_title'] . ' - ' . get_field('typ', $company_exist[0]['ID']);
            }

        }else{
            $message = "Email jest nieprawidłowy";
        }
    }else{
        $message = "Email jest obowiązkowy";
    }

    if( $message !== false ) {

        $valid = $message;

    }

    return $valid;
}

add_action( 'save_post', 'valid_custom_post_type_osobykontaktowe_meta', 99, 2 );

function valid_custom_post_type_osobykontaktowe_meta( $post_id )
{

    $post_type = 'osobykontaktowe';

    if (!(isset($_POST['post_type']) && $_POST['post_type'] == $post_type))
        return;


    if(get_field('typ', $post_id) != ''){

        if(get_field('email', $post_id) !="" && filter_var(get_field('email', $post_id), FILTER_VALIDATE_EMAIL)){

            require_once INC_PLUGIN_DIR . DS . "freshmail" . DS . 'class.rest.php';
            require_once INC_PLUGIN_DIR . DS . "freshmail" . DS . 'config.php';

            $rest = new FmRestAPI();
            $rest->setApiKey( FM_API_KEY );
            $rest->setApiSecret( FM_API_SECRET );

            $listId = "rpj7xhcgrc";
            $comanyRelFieldname = "zgloszenie_osoby";
            if(get_field('typ', $post_id) == 'zglaszajacy'){
                $listId = "7247fena6t";
                $comanyRelFieldname = "osoba_zglaszajaca";
            }

            $title = get_the_title($post_id);
            $comaniesName = "";
            $lName = get_field('nazwisko', $post_id);
            $fName = get_field('imie', $post_id);
            $comapnies = get_field('firma', $post_id);

            if(!empty($comapnies)){

                $comaniesName = join(', ', array_map(function($c){
                    return get_the_title($c);
                },$comapnies));

                foreach($comapnies as $company_id){
                    $currentData = get_field($comanyRelFieldname, $company_id);
                    if($currentData !== "" && !empty($currentData)){
                        $currentData[] = $post_id;
                        $currentData = array_unique($currentData);
                    }else{
                        $currentData = array($post_id);
                    }
                    update_field($comanyRelFieldname, $currentData , $company_id);
                }

            }


            //Add to newsletter

            $mailPoetData = array(
                'email' => get_field('email', $post_id),
            );

            $m_data = array(
                'email' => get_field('email', $post_id),
                'list'  => $listId,
                'state'   => 1, // Aktywny
                'confirm' => 1,
                'custom_fields' => array()
            );

            if($fName !==false && $fName != ""){
                $m_data['custom_fields']['imie'] = $fName;
                $mailPoetData['firstname'] = $fName;
            }
            if($lName !==false && $lName != ""){
                $m_data['custom_fields']['nazwisko'] = $lName;
                $mailPoetData['lastname'] = $lName;
            }
            if(get_field('stanowisko', $post_id) != ""){
                $m_data['custom_fields']['stanowisko'] = get_field('stanowisko', $post_id);
            }
            if(get_field('telefon', $post_id) != ""){
                $m_data['custom_fields']['telefon'] = get_field('telefon', $post_id);
            }

            try {
                $rest->doRequest('subscriber/get/'.$m_data['list'].'/'.$m_data['email']);
            } catch (Exception $e) {
                @$rest->doRequest('subscriber/add', $m_data);
            }

            // add to mailPoet

            addToMailPoetList($mailPoetData, array(WYSIJA_LIST_ALL, WYSIJA_LIST_REGISTR_APPLICATION));

            remove_action('save_post', 'valid_custom_post_type_osobykontaktowe_meta', 99, 2 );

            $args = array(
                'ID' => $post_id,
                'post_name' => md5(strtolower(trim($fName.$lName)).get_field('email', $post_id))
            );

            $t = ucfirst($lName) .' '.ucfirst($fName);
            if($comaniesName != "") $t .= ' - ' . $comaniesName;
            $args['post_title'] = $t;

            wp_update_post($args);

            add_action('save_post', 'valid_custom_post_type_osobykontaktowe_meta', 99, 2 );

        }

    }
}