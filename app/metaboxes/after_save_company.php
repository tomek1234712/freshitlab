<?php

add_filter('acf/validate_value/key=field_56b322e3d6ed0', 'domwiedzy_acf_validate_nip', 10, 4);

function domwiedzy_acf_validate_nip( $valid, $value, $field, $input ){

    if( !$valid ) {
        return $valid;
    }

    global $wpdb;
    $message = false;

    if(CheckNIP($value)){

        $niphash = md5(trim(str_replace('-','',$value)));

        $validSql = "SELECT ID, post_title FROM {$wpdb->posts} WHERE `post_type` = 'firmy' AND `post_status` = 'publish' AND `post_name` = '{$niphash}'";

        if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){

            parse_str($_SERVER['HTTP_REFERER'], $vars);
            if(isset($vars['action']) && $vars['action'] == 'edit'){

                $ID = (int)$vars[key($vars)];

                $validSql .= " AND ID != {$ID}";

            }

        }

        $company_exist = $wpdb->get_results($validSql , ARRAY_A );
        if(!empty($company_exist)){
            $message = "Już istnienie firma o takim numerze NIP. ID: ".$company_exist[0]['ID'] . ' Nazwa: '. $company_exist[0]['post_title'];
        }
    }else{
        $message = "Błędny format NIP - Popraw go";
    }


    if( $message !== false ) {

        $valid = $message;

    }

    return $valid;
}

add_filter('acf/validate_value/key=field_56b3233bd6ed2', 'domwiedzy_acf_validate_zip', 10, 4);

function domwiedzy_acf_validate_zip( $valid, $value, $field, $input ){

    if( !$valid ) {
        return $valid;
    }

    $message = false;

    if(!validatePL($value)){
        $message = "Podaj poprawny kod pocztowy w formacie 00-000";
    }

    if( $message !== false ) {
        $valid = $message;
    }

    return $valid;
}

add_action( 'save_post', 'valid_custom_post_type_firmy_meta' , 101, 3 );

function valid_custom_post_type_firmy_meta( $post_id)
{

    $post_type = 'firmy';

    if (!(isset($_POST['post_type']) && $_POST['post_type'] == $post_type))
        return;


    if(get_field('zgloszony_udzial_jako', $post_id) == 'company'){

        $slug = md5(trim(str_replace('-', '', get_field('nip', $post_id))));

        remove_action('save_post', 'valid_custom_post_type_firmy_meta' , 101, 3 );

        wp_update_post(array(
            'ID' => $post_id,
            'post_name' => $slug
        ));

        add_action('save_post', 'valid_custom_post_type_firmy_meta' , 101, 3 );

    }else{

        $os = get_field('osoba_zglaszajaca', $post_id);

        if(isset($os[0]) && !empty($os[0]) && filter_var($os[0], FILTER_VALIDATE_EMAIL)){

            remove_action('save_post', 'valid_custom_post_type_firmy_meta' , 101, 3 );

            wp_update_post(array(
                'ID' => $post_id,
                'post_name' => md5($os[0])
            ));

            add_action('save_post', 'valid_custom_post_type_firmy_meta' , 101, 3 );

        }

    }
}