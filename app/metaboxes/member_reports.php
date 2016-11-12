<?php

function domwiedzy_metabox_mambers_rel() {

    add_meta_box(
        'domwiedzy_metabox_mambers_rel',
        __( 'Uczestnictwo w szkoleniach', PROJECT_SLUG ),
        'domwiedzy_metabox_mambers_rel_body',
        'osobykontaktowe',
        'normal',
        'low'
    );
}
add_action( 'add_meta_boxes', 'domwiedzy_metabox_mambers_rel' );


function domwiedzy_metabox_mambers_rel_body($post){
    global $wpdb;

    $rowHeaderLine = '<div class="dwrr-training-items firstHeader"><div class="dwrr-training-item legend"><div class="t"><div class="tr">
                        <div class="td t-name" style="width: 300px;">Szkolenie</div>
                        <div class="td t-formp">Osoba zgłaszająca</div>
                        <div class="td t-company" style="width: auto;">Firma</div>
                        <div class="td t-promo">Promocja</div>
                        <div class="td t-created">Data dodania</div>
                        </div></div></div></div>';

    $dbData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}trainees WHERE 1 = 1 AND  team_persons LIKE '%\"{$post->ID}\"%' ORDER BY createdAt DESC", ARRAY_A );


    if(!empty($dbData)){

        $currentTrainingId = null;
        $currentTrainingDate = null;
        $currentTrainingMembers = null;
        $currentCompany = null;
        $currentFormPerson = null;
        $dates = array();
        $loop = 0;
        echo '<div class="dwrr">';

            echo $rowHeaderLine;

            echo '<div class="dwrr-training-items">';

            foreach($dbData as $data){

                $memberData = json_decode($data['team_persons'], true);
                $dates = get_post_meta($data['training_id'], 'training_date_place_trainer_relation', true);

                $loop++;
                echo '<div class="dwrr-training-item '.( ($loop % 2 == 0) ? 'odd' : 'even' ).'">';
                echo '<div class="t">';
                echo '<div class="tr">';
                echo '<div class="td t-name" style="width: 300px;">'.get_the_title($data['training_id']).'</div>';
                echo '<div class="td t-formp">'.( (isset($data['reporting_person']) && $data['reporting_person'] != "0") ? ((get_field('imie', $data['reporting_person']). ' ' .get_field('nazwisko', $data['reporting_person']))) : '').'</div>';
                echo '<div class="td t-company" style="width: auto;">'.get_the_title($data['company_id']).'</div>';
                echo '<div class="td t-promo">';

                if(isset($dates[$data['training_time']]['date_promo']) && !empty($dates[$data['training_time']]['date_promo'])){
                    if(strtotime($dates[$data['training_time']]['date_promo']) > $memberData[get_the_ID()]){
                        echo '<div class="ch"';
                        if(isset($dates[$data['training_time']]['price_promo_text']) && !empty($dates[$data['training_time']]['price_promo_text'])){
                            echo ' data-text="'.$dates[$data['training_time']]['price_promo_text'].'"';
                        }
                        echo'></div>';
                    }
                }

                echo '</div>';
                echo '<div class="td t-created">'.date('Y-m-d H:i', $memberData[get_the_ID()]).'</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

            }

        echo '</div>';
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