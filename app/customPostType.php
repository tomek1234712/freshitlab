<?php
class AppCustomPostType {
    function __construct() {
        $this->init();
    }

    public function init() {
        add_action('init', array($this, 'registerCustomPostTypes') ) ;
        //add_action('init', array($this, 'registerCustomTaxonomies') ) ;
    }


    public function registerCustomPostTypes() {
        
        // custom post types
        
        $pagePostTypes = array(
            array(
                'name' => 'Inwestycje',
                'slug' => 'inwestycje',
                'typename' => 'inwestycje',
                'fields' => array( 'title', 'editor', 'thumbnail'),
                'icon' => 'dashicons-admin-multisite',
            ),

        );

        foreach($pagePostTypes as $postType){

            register_post_type( $postType['typename'], array(
                'labels'   => array(
                    'name'               => __( $postType['name'], PROJECT_SLUG ),
                    'singular_name'      => __( $postType['name'], PROJECT_SLUG ),
                    'menu_name'          => __( $postType['name'], PROJECT_SLUG ),
                    'name_admin_bar'     => __( $postType['name'], PROJECT_SLUG ),
                    'add_new'            => __( 'Dodaj', PROJECT_SLUG ),
                    'add_new_item'       => __( 'Dodaj', PROJECT_SLUG ),
                    'new_item'           => __( 'Dodaj', PROJECT_SLUG ),
                    'edit_item'          => __( 'Edytuj', PROJECT_SLUG ),
                    'view_item'          => __( 'Pokaż', PROJECT_SLUG ),
                    'all_items'          => __( 'Wszystkie', PROJECT_SLUG ),
                    'search_items'       => __( 'Szukaj', PROJECT_SLUG ),
                    'parent_item_colon'  => __( 'Rodzic:', PROJECT_SLUG ),
                    'not_found'          => __( 'Nie znaleziono.', PROJECT_SLUG ),
                    'not_found_in_trash' => __( 'Nie znaleziono w koszu', PROJECT_SLUG )
                ),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'taxonomies'         => array(),
                'rewrite'            => !$postType['slug'] ? false : array( 'slug' => $postType['slug'] ),
                'capability_type'    => 'post',
                'menu_icon'          => $postType['icon'],
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => $postType['fields']
            ) );
            
        }

    }
    
    public function registerCustomTaxonomies() {

        $pagePostTypesTax = array(
            array(
                'name' => 'Kategorie',
                'slug' => 'kategorie-szkolen',
                'typename' => array( 'szkolenia', 'prelegenci'),
                'taxname' => 'szkolenia_cat',
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
            ),
        );

        foreach($pagePostTypesTax as $taxonomy){

            register_taxonomy( $taxonomy['taxname'], $taxonomy['typename'], array(
                'hierarchical'      => true,
                'labels'            => array(
                    'name'              => __( $taxonomy['name'], PROJECT_SLUG),
                    'singular_name'     => __( $taxonomy['name'], PROJECT_SLUG),
                    'search_items'      => __( $taxonomy['name'], PROJECT_SLUG),
                    'all_items'         => __( $taxonomy['name'], PROJECT_SLUG),
                    'parent_item'       => __( 'Rodzic' , PROJECT_SLUG),
                    'parent_item_colon' => __( 'Rodzic:' , PROJECT_SLUG),
                    'edit_item'         => __( 'Edytuj' , PROJECT_SLUG),
                    'update_item'       => __( 'Zaktualizuj', PROJECT_SLUG ),
                    'add_new_item'      => __( 'Dodaj' , PROJECT_SLUG),
                    'new_item_name'     => __( 'Dodaj', PROJECT_SLUG ),
                    'menu_name'         => __( $taxonomy['name'], PROJECT_SLUG),
                ),
                'show_ui'           => $taxonomy['show_ui'],
                'show_admin_column' => $taxonomy['show_admin_column'],
                'query_var'         => $taxonomy['query_var'],
                'rewrite'           => array( 'slug' => $taxonomy['slug'] ),
            ) );

        }

    }
}