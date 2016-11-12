<?php

function domwiedzy_metabox_companies_rel() {

    add_meta_box(
        'domwiedzy_metabox_companies_rel',
        __( 'Szkolenia w których firma brała udział', PROJECT_SLUG ),
        'domwiedzy_metabox_companies_rel_body',
        'firmy',
        'normal',
        'low'
    );
}
add_action( 'add_meta_boxes', 'domwiedzy_metabox_companies_rel' );


function domwiedzy_metabox_companies_rel_body($post){
    global $wpdb;

    $rowHeaderLine = '<div class="dwrr-training-items"><div class="dwrr-training-item legend"><div class="t"><div class="tr"><div class="td t-name">Nazwisko i imię</div><div class="td t-formp">Osoba zgłaszająca</div><div class="td t-promo">Promocja</div><div class="td t-created">Data dodania</div><div class="td t-actions">Akcje</div></div></div></div></div>';

    $dbData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}trainees WHERE 1 = 1 AND company_id = {$post->ID} GROUP BY training_id ORDER BY createdAt DESC", ARRAY_A );

    if(!empty($dbData)){

        $currentTrainingId = null;
        $currentTrainingDate = null;
        $currentTrainingMembers = null;
        $currentCompany = null;
        $currentFormPerson = null;
        $dates = array();
        echo '<div class="dwrr">';

        foreach($dbData as $data){

            echo '<div id="dwrr_rep_'.$data['training_id'].'">';

            if($currentTrainingId != $data['training_id']){
                echo '<div class="dwrr-training">'.get_the_title($data['training_id']).'</div>';

                $dates = get_post_meta($data['training_id'], 'training_date_place_trainer_relation', true);
                $currentTrainingId = $data['training_id'];
            }


            $dbTrainigsData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}trainees WHERE 1 = 1  AND company_id = {$post->ID} AND training_id = {$data['training_id']} AND training_time = {$data['training_time']} ORDER BY createdAt DESC", ARRAY_A );

            foreach($dbTrainigsData as $tdata){

                if($currentTrainingDate != $tdata['training_id']. "_" . $tdata['training_time']){
                    echo '<div class="dwrr-training-time">';
                    echo '<button class="button button-primary b-print" data-rel="'.$tdata['training_id'].'_'.$tdata['training_time'].'_'.$tdata['company_id'].'">Drukuj</button>';
                    echo '<button class="button button-primary b-genpdf" data-action="open" data-rel="'.$tdata['training_id'].'_'.$tdata['training_time'].'_'.$tdata['company_id'].'">Otwórz PDF</button>';
                    echo '<button class="button button-primary b-genpdf" data-action="download" data-rel="'.$tdata['training_id'].'_'.$tdata['training_time'].'_'.$tdata['company_id'].'">Pobierz PDF</button>';
                    if(isset($dates[$tdata['training_time']]['price'])) echo '<i>' . $dates[$tdata['training_time']]['price'] . '</i>';
                    if(isset($dates[$tdata['training_time']]['date'])) echo '<b>' . $dates[$tdata['training_time']]['date'] . '</b>';
                    if(isset($dates[$tdata['training_time']]['places'])) echo '<span>' . join(', ', array_map(function($it){
                            $t = get_term_by('id', $it, 'szkolenia_place');
                            return $t->name;
                        }, $dates[$tdata['training_time']]['places'])) . '</span>';

                    echo '</div>';
                    echo $rowHeaderLine;
                    $currentTrainingDate = $data['training_id']. "_" . $data['training_time'];


                    // sort members
                    $memoryTp = array();
                    if(!empty($dbTrainigsData)){
                        foreach($dbTrainigsData as $dbDataMembersSet){
                            $memoryTp = $memoryTp + json_decode($dbDataMembersSet['team_persons'], true);
                        }
                    }

                    $personSort = array_keys($memoryTp);

                    if(is_array($personSort) && !empty($personSort)){

                        add_filter('posts_orderby', 'forse_posts_orderby_title', 999, 2);
                        $items = new WP_Query(array(
                            'post_type'         => 'osobykontaktowe',
                            'post_status'       => 'publish',
                            'posts_per_page'    => -1,
                            'orderby'           => 'title',
                            'order'             => 'ASC',
                            'post__in'          => $personSort,
                        ));
                        remove_filter('posts_orderby', 'forse_posts_orderby_title');


                        // print sort members

                        if($items->have_posts()):

                            $loop = 0;

                            echo '<div class="dwrr-training-items">';

                            while($items->have_posts()): $items->the_post();

                                $loop++;
                                echo '<div class="dwrr-training-item '.( ($loop % 2 == 0) ? 'odd' : 'even' ).'">';
                                echo '<div class="t">';
                                echo '<div class="tr">';
                                echo '<div class="td t-name">'.get_the_title(get_the_ID()).'</div>';
                                echo '<div class="td t-formp">'.( (isset($data['reporting_person']) && $data['reporting_person'] != "0") ? (get_field('imie', $data['reporting_person']). ' ' .get_field('nazwisko', $data['reporting_person'])) : '').'</div>';
                                //echo '<div class="td t-company">'.get_the_title($tdata['company_id']).'</div>';
                                echo '<div class="td t-promo">';

                                if(isset($dates[$tdata['training_time']]['date_promo']) && !empty($dates[$tdata['training_time']]['date_promo'])){
                                    if(strtotime($dates[$data['training_time']]['date_promo']) > $memoryTp[get_the_ID()]){
                                        echo '<div class="ch"';
                                        if(isset($dates[$tdata['training_time']]['price_promo_text']) && !empty($dates[$tdata['training_time']]['price_promo_text'])){
                                            echo ' data-text="'.$dates[$tdata['training_time']]['price_promo_text'].'"';
                                        }
                                        echo'></div>';
                                    }
                                }

                                echo '</div>';
                                echo '<div class="td t-created">'.date('Y-m-d H:i', $memoryTp[get_the_ID()]).'</div>';
                                echo '<div class="td t-actions">';
                                echo '<a href="/wp-admin/post.php?post='.get_the_ID().'&action=edit" style="margin: 2px auto;display: table;" class="button button-primary b-edit" data-rel="'.$tdata['id'].'_team_persons_'.get_the_ID().'">Edytuj</a>';
                                //echo '<button class="button button-primary b-delete" data-rel="'.$tdata['id'].'_'.get_the_ID().'">Usuń</button>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';

                            endwhile;

                            echo '</div>';

                        endif;

                    }

                }


            }

            echo '</div>';
        }

        echo '</div>';
    }else{
        _e('Brak danych', PROJECT_SLUG);
    }
    ?>
    <script type="text/javascript">
        var $ = jQuery;
        jQuery(function($){
            $(document).ready(function(){
                jQuery('button.b-print').on('click',function(e){
                    e.preventDefault();
                    var key = jQuery(this).attr('data-rel');
                    jQuery.ajax({
                        type: "POST",
                        async: false,
                        dataType: 'json',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            'action': 'dom_wiedzy_training_list_print',
                            'key': key
                        },
                        success: function(data)
                        {
                            if(data.outputhtml != ""){
                                var myWindow = window.open("Lista obecności", "MsgWindow", "width=794, height=1122");
                                myWindow.document.write(data.outputhtml);
                                myWindow.print();
                            }
                        }
                    });

                });

            });
        });
    </script>
<?php }