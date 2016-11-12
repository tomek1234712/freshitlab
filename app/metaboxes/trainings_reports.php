<?php

function domwiedzy_trainings_reposrts_menu_page() {
    $trainings_reposrts = add_menu_page(
        __( 'Uczestnicy szkoleń', PROJECT_SLUG ),
        __( 'Uczest. szkoleń', PROJECT_SLUG ),
        'manage_options',
        'training_reposts',
        'domwiedzy_trainings_reposrts_menu_page_html',
        'dashicons-universal-access',
        4
    );
    add_action( 'load-' . $trainings_reposrts, 'domwiedzy_load_admin_js' );
}
add_action( 'admin_menu', 'domwiedzy_trainings_reposrts_menu_page' );

function domwiedzy_load_admin_js(){
    add_action( 'admin_enqueue_scripts', 'domwiedzy_enqueue_admin_js' );
}

function domwiedzy_enqueue_admin_js(){

    wp_enqueue_style('domwiedzy-chosen-css', ASSETS_DIR . '/css/chosen.css' );

    wp_enqueue_script( 'pdfmake.min', ASSETS_DIR . '/js/pdf/pdfmake.min.js', array( 'jquery' ) );

    wp_enqueue_script( 'domwiedzy-vfs_fonts', ASSETS_DIR . '/js/pdf/vfs_fonts.js', array( 'jquery' ) );

    wp_enqueue_script( 'domwiedzy-vfs_fonts-gen', ASSETS_DIR . '/js/pdf/gen_pdf.js', array( 'jquery' ) );
    wp_enqueue_script( 'domwiedzy-chosen-jquery', ASSETS_DIR . '/js/chosen.jquery.min.js', array( 'jquery' ) );

    wp_localize_script(
        'domwiedzy-vfs_fonts-gen',
        'myLocalized',
        array(
            'ajaxurl' => admin_url('admin-ajax.php')
        )
    );


}


/**
 * Display a domwiedzy_trainings_reposrts
 */

function domwiedzy_trainings_reposrts_menu_page_html(){

    $showArchive = (isset($_GET['showarchive']) && $_GET['showarchive'] == 'true') ? true : false;
    $archiveItemExist = false;

    // elements
    function printRowHeaderLine(){
        echo '<div class="dwrr-training-items"><div class="dwrr-training-item legend"><div class="t"><div class="tr"><div class="td t-lp">Lp.</div><div class="td t-name">Nazwisko i imię</div><div class="td t-formp">Osoba zgłaszająca</div><div class="td t-company">Firma</div><div class="td t-promo">Promocja</div><div class="td t-created">Data dodania</div><div class="td t-actions">Akcje</div></div></div></div></div>';
    }

    function printTrainingHeader($trainingId){
        echo '<div class="dwrr-training">' . get_the_title($trainingId) . '</div>';
    }

    function printActionsBar($trainingId, $trainingTime, $trainingData = array(), $isArchived = false){
        echo '<div class="dwrr-training-time" data-date="'.$trainingTime.'">';
            echo '<button class="button button-primary b-extrasmall b-print" data-rel="'.$trainingId.'_'.$trainingTime.'">Drukuj</button>';
            echo '<button class="button button-primary b-extrasmall b-genpdf" data-action="open" data-rel="'.$trainingId.'_'.$trainingTime.'">Otwórz PDF</button>';
            echo '<button class="button button-primary b-extrasmall b-genpdf" data-action="download" data-rel="'.$trainingId.'_'.$trainingTime.'">Pobierz PDF</button>';
            echo '<button class="button button-primary b-extrasmall b-toarchive" data-action="download" data-rel="'.$trainingId.'_'.$trainingTime.'">' . ( ($isArchived) ? 'Archiwizuj' : 'Cofnij archiwizację' ) . '</button>' ;
            if($trainingData['price']) echo '<i>' . $trainingData['price'] . '</i>';
            if($trainingData['date']) echo '<b>' . $trainingData['date'] . '</b>';
            if($trainingData['places']) echo '<span>' . join(', ', array_map(function($it){
                    $t = get_term_by('id', $it, 'szkolenia_place');
                    return $t->name;
                }, $trainingData['places'])) . '</span>';
        echo '</div>';
    }

    // print general heading html

    echo '<h1>'.__('Uczestnicy szkoleń - raport', PROJECT_SLUG).'</h1>';
    echo '<div class="buttonpanel">';
        echo '<button class="button button-primary" id="add_member_manually">Dodaj uczestnika</button>';
        if(!$showArchive):
            echo '<a class="button button-primary action-archive-button" title="Pokaż archiwum" href="/wp-admin/admin.php?page=training_reposts&showarchive=true">Pokaż archiwum</a>';
        else:
            echo '<a class="button button-primary action-archive-button" title="Ukryj archiwum" href="/wp-admin/admin.php?page=training_reposts">Ukryj archiwum</a>';
        endif;
    echo '</div>';

    // database loop

    global $wpdb;
    $dbData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}trainees WHERE 1 = 1 ORDER BY createdAt DESC", ARRAY_A );

    if(!empty($dbData)){

        // reorder DB items
        $dbDataTrainingPersons = [];
        foreach($dbData as $item){

            $persons = json_decode($item['team_persons'], true);
            if(!empty($persons)){
                foreach($persons as $personId => $personRegisterDate){

                    $dbDataTrainingPersons[$item['training_time'] . '_' . $item['training_id']][$personId] = array(
                        'recordId' => $item['id'],
                        'companyId' => $item['company_id'],
                        'trainingId' => $item['training_id'],
                        'trainingTime' => $item['training_time'],
                        'reportingPerson' => $item['reporting_person'],
                        'createdAt' => $item['createdAt'],
                        'updatedAt' => $item['updatedAt'],
                        'personAddedDate' => $personRegisterDate,
                    );

                }
            }

        }

        // sorting by training date ASC

        ksort($dbDataTrainingPersons);

        // print output html

        echo '<div class="dwrr">';

        foreach($dbDataTrainingPersons as $trainingDateId => $trainingPersonData){

            $trainingDateIdExplode = explode('_' , $trainingDateId);
            $trainingTime = $trainingDateIdExplode[0];
            $trainingId = $trainingDateIdExplode[1];
            $trainingMagnifiedData = get_post_meta($trainingId, 'training_date_place_trainer_relation', true);
            $isArchived = ( !isset($trainingMagnifiedData[$trainingTime]['archive']) || $trainingMagnifiedData[$trainingTime]['archive'] == false );
            if($isArchived) $archiveItemExist = true;

            // hide/show archived items

            if(!$isArchived && !$showArchive)
                continue;

            // print single training date

            echo '<div id="dwrr_rep_'. $trainingId .'" class="dwrr-item" data-date="'. $trainingTime .'">';

                printTrainingHeader($trainingId);

                printActionsBar($trainingId, $trainingTime, array(
                    'price' => isset($trainingMagnifiedData[$trainingTime]['price']) ? $trainingMagnifiedData[$trainingTime]['price'] : false,
                    'date' => isset($trainingMagnifiedData[$trainingTime]['date']) ? $trainingMagnifiedData[$trainingTime]['date'] : false,
                    'places' => isset($trainingMagnifiedData[$trainingTime]['places']) ? $trainingMagnifiedData[$trainingTime]['places'] : false
                ), $isArchived);

                printRowHeaderLine();

                // print persons in single training date

                if(is_array($trainingPersonData) && !empty($trainingPersonData)){

                    add_filter('posts_orderby', 'forse_posts_orderby_title', 999, 2);
                    $items = new WP_Query(array(
                        'post_type'         => 'osobykontaktowe',
                        'post_status'       => 'publish',
                        'posts_per_page'    => -1,
                        'orderby'           => 'title',
                        'order'             => 'ASC',
                        'post__in'          => array_keys($trainingPersonData),
                    ));
                    remove_filter('posts_orderby', 'forse_posts_orderby_title');

                    // print sort members

                    if($items->have_posts()):

                        $loop = 0;

                        echo '<div class="dwrr-training-items">';

                        while($items->have_posts()): $items->the_post();

                            $loop++;
                            $personId = get_the_ID();

                            echo '<div class="dwrr-training-item '.( ($loop % 2 == 0) ? 'odd' : 'even' ).'">';
                            echo '<div class="t">';
                            echo '<div class="tr">';
                            echo '<div class="td t-lp">'.$loop.'.</div>';
                            echo '<div class="td t-name">'.get_field('nazwisko', $personId). ' ' .get_field('imie', $personId).'</div>';
                            echo '<div class="td t-formp">'.( (isset($trainingPersonData[$personId]['reportingPerson']) && $trainingPersonData[$personId]['reportingPerson'] != "0") ? ( get_field('nazwisko', $trainingPersonData[$personId]['reportingPerson']). ' ' .get_field('imie', $trainingPersonData[$personId]['reportingPerson']) ) : '').'</div>';
                            echo '<div class="td t-company">'.get_the_title($trainingPersonData[$personId]['companyId']).'</div>';
                            echo '<div class="td t-promo">';

                            if(isset($trainingMagnifiedData['date_promo']) && !empty($trainingMagnifiedData['date_promo'])){
                                if(strtotime($trainingMagnifiedData['date_promo']) > $trainingPersonData[$personId]['personAddedDate']){
                                    echo '<div class="ch"';
                                    if(isset($trainingMagnifiedData['price_promo_text']) && !empty($trainingMagnifiedData['price_promo_text'])){
                                        echo ' data-text="'.$trainingMagnifiedData['price_promo_text'].'"';
                                    }
                                    echo'></div>';
                                }
                            }

                            echo '</div>';
                            echo '<div class="td t-created">'.date('Y-m-d H:i', $trainingPersonData[$personId]['personAddedDate']).'</div>';
                            echo '<div class="td t-actions">';
                            echo '<a href="/wp-admin/post.php?post='.get_the_ID().'&action=edit" class="button button-primary b-edit" data-rel="'.$trainingPersonData[$personId]['recordId'].'_team_persons_'.get_the_ID().'">Edytuj</a>';
                            echo '<button class="button button-primary b-delete" data-rel="'.$trainingPersonData[$personId]['recordId'].'_'.get_the_ID().'">Usuń</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';

                        endwhile;

                        echo '</div>';

                    endif;

                }



            echo '</div>';

        }

        echo '</div>';

        // testing

        //tab($dbDataTrainingPersons);
        //tab($dbDataReorder);

    }else{
        _e('Brak danych', PROJECT_SLUG);
    }
    ?>
    <div id="dwmtr-modal-amm">
    <div id="dwmtr-modal-amm-result"></div>
    <header>Dodaj nowego uczestnika</header>
        <div id="dwmtr-modal-amm-form">
            <div class="inputs">
                <fieldset>
                    <label>Data dodania</label>
                    <input type="text" name="date" value="" placeholder="<?php echo date('Y-m-d H:i'); ?>" id="dwmtr-moda-datepicker"  readonly class="dwmtr-modal-amm-input"/>
                </fieldset>
                <fieldset>
                    <label>Uczestnik</label>
                    <select name="member" class="dwmtr-modal-amm-input chosen-select">
                        <option selected disabled>wybierz</option>
                        <?php
                        $trainers = new WP_Query(array(
                            'post_type' => 'osobykontaktowe',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'orderby' => 'menu_order date',
                            'order' => 'ASC DESC',
                            'meta_key'		=> 'typ',
                            'meta_value'	=> 'uczestnik'
                        ));

                        if(!empty($trainers->posts))
                            foreach($trainers->posts as $trainer):
                                echo '<option value="'.$trainer->ID.'">'.$trainer->post_title.'</option>';
                            endforeach;
                        ?>
                    </select>
                </fieldset>
                <fieldset>
                    <label>Zgłaszający</label>
                    <select name="mentor" class="dwmtr-modal-amm-input chosen-select">
                        <option selected disabled>wybierz</option>
                        <?php
                        $trainers = new WP_Query(array(
                            'post_type' => 'osobykontaktowe',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'orderby' => 'menu_order date',
                            'order' => 'ASC DESC',
                            'meta_key'		=> 'typ',
                            'meta_value'	=> 'zglaszajacy'
                        ));

                        if(!empty($trainers->posts))
                            foreach($trainers->posts as $trainer):
                                echo '<option value="'.$trainer->ID.'">'.$trainer->post_title.'</option>';
                            endforeach;
                        ?>
                    </select>
                </fieldset>
                <fieldset>
                    <label>Firma</label>
                    <select name="company" class="dwmtr-modal-amm-input chosen-select">
                        <option selected disabled>wybierz</option>
                        <?php
                        $trainers = new WP_Query(array(
                            'post_type' => 'firmy',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'orderby' => 'menu_order date',
                            'order' => 'ASC DESC',
                        ));

                        if(!empty($trainers->posts))
                            foreach($trainers->posts as $trainer):
                                echo '<option value="'.$trainer->ID.'">'.$trainer->post_title.'</option>';
                            endforeach;
                        ?>
                    </select>
                </fieldset>
                <fieldset>
                    <label>Szkolenie</label>
                    <select name="training" class="dwmtr-modal-amm-input chosen-select">
                        <option selected disabled>wybierz</option>
                        <?php
                        $trainers = new WP_Query(array(
                            'post_type' => 'szkolenia',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'orderby' => 'menu_order date',
                            'order' => 'ASC DESC',
                        ));

                        if(!empty($trainers->posts))
                            foreach($trainers->posts as $trainer):
                                echo '<option value="'.$trainer->ID.'">'.$trainer->post_title.'</option>';
                            endforeach;
                        ?>
                    </select>
                </fieldset>
                <fieldset>
                    <label>Data szkolenia</label>
                    <select name="time" class="dwmtr-modal-amm-input" disabled>
                        <option selected disabled>wybierz szkolenie</option>
                    </select>
                </fieldset>
                <button class="button button-primary button-large" id="dwmtr-modal-amm-close">Anuluj</button>
                <button class="button button-primary button-large" id="dwmtr-modal-amm-add">Dodaj</button>
            </div>
        </div>
    </div>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script type="text/javascript">
        var $ = jQuery;
        jQuery(function($){
            $(document).ready(function(){
                <?php if($archiveItemExist): ?>
                    jQuery('.action-archive-button').show();
                <?php endif; ?>

//                var $wrapper = $('.dwrr');
//                $wrapper.find('.dwrr-item').sort(function (a, b) {
//                    return +$(a).find('.dwrr-training-time:first').attr('data-date') - +$(b).children('.dwrr-training-time:first').attr('data-date');
//                }).appendTo( $wrapper );

                $(".chosen-select").chosen({
                    width: "85.5%",
                    no_results_text: "Brak wyników!"
                });
                jQuery('.button.b-delete').live("click", function(e) {
                    e.preventDefault();
                    var key = jQuery(this).attr('data-rel');
                    if (window.confirm("<?php _e('Czy na pewno chcesz wypisać tego uczestnika z szkolenia?', PROJECT_SLUG);?>")) {
                        jQuery.ajax({
                            type: "POST",
                            async: false,
                            dataType: 'json',
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            data: {
                                'action': 'dom_wiedzy_unregister_from_training',
                                'key': key
                            },
                            success: function(data)
                            {
                                if(data.valid == true){
                                    window.location.reload();
                                }
                            }
                        });
                    }
                });
                jQuery('#add_member_manually').live("click", function(e) {
                    e.preventDefault();
                    jQuery('#dwmtr-modal-amm').show();

                });
                jQuery('#dwmtr-modal-amm-close').live("click", function(e) {
                    e.preventDefault();
                    jQuery('#dwmtr-modal-amm-form').find("input").val("");
                    jQuery('#dwmtr-modal-amm-form').find("select option").prop('selected', false);
                    jQuery('#dwmtr-modal-amm-form').find("select option:first").prop('selected', true);
                    jQuery('#dwmtr-modal-amm').hide();

                });
                jQuery('select[name="training"]').live("change", function(e) {
                    e.preventDefault();
                    console.log(jQuery(this));
                    var v = $(this).val();
                    jQuery.ajax({
                        type: "POST",
                        async: false,
                        dataType: 'json',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            'action': 'dom_wiedzy_select_training',
                            'key': v
                        },
                        success: function(data)
                        {
                            if(data.valid == true){
                                jQuery('select[name="time"]').html(data.html);
                                jQuery('select[name="time"]').prop('disabled', false);
                            }
                        }
                    });
                });


                var $CFform = $('#dwmtr-modal-amm-form');

                $('#dwmtr-modal-amm-add').on("click", function( event ) {
                    event.preventDefault();

                    var $CFstatus = $("#dwmtr-modal-amm-result");
                    $CFstatus.html("");
                    $CFform.find(".error").removeClass("error");

                    $.ajax({
                        type: "POST",
                        async: false,
                        dataType: 'json',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            'action': 'dom_wiedzy_add_training_member',
                            'serialize': $('#dwmtr-modal-amm-form input, #dwmtr-modal-amm-form select').serialize()
                        },
                        success: function(data)
                        {
                            if(data.valid === true){
                                $CFstatus.html(data.alert);
                                $CFform.find(".error").removeClass("error");
                                $CFform.find("input").val("");
                                $CFform.find("select option").prop('selected', false);
                                $CFform.find("select option:first").prop('selected', true);
                                setTimeout(function(){
                                    $CFstatus.html("");
                                    $('#dwmtr-modal-amm').hide();
                                    window.location.reload();
                                },600);
                            }
                            else
                            {
                                var target = data.alert;
                                setTimeout(function(){
                                    for (var k in target){
                                        if (target.hasOwnProperty(k)) {
                                            if(target[k] != ""){
                                                $CFform.find("input[name='"+k+"']").addClass("error");
                                                $CFform.find("select[name='"+k+"']").addClass("error");
                                            }
                                        }
                                    }
                                },100);
                                $CFstatus.html(data.alertstr);

                            }
                        }
                    });
                    return false;
                });

                $("#dwmtr-modal-amm-form .error").live("change keyup",function(){
                    if($(this).val() != ""){
                        $(this).removeClass("error");
                    }
                });


                jQuery('#dwmtr-modal-amm-add').live("click", function(e) {
                    e.preventDefault();
                    var key = jQuery(this).attr('data-rel');
                    if (window.confirm("<?php _e('Czy na pewno chcesz przenieść do archiwum?', PROJECT_SLUG);?>")) {
                        jQuery.ajax({
                            type: "POST",
                            async: false,
                            dataType: 'json',
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            data: {
                                'action': 'dom_wiedzy_unregister_from_toggle_archive',
                                'key': key
                            },
                            success: function(data)
                            {
                                if(data.valid == true){
                                    window.location.reload();
                                }
                            }
                        });
                    }
                });
                jQuery('.button.b-toarchive').live("click", function(e) {
                    e.preventDefault();
                    var key = jQuery(this).attr('data-rel');
                    if (window.confirm("<?php _e('Czy na pewno chcesz przenieść do archiwum?', PROJECT_SLUG);?>")) {
                        jQuery.ajax({
                            type: "POST",
                            async: false,
                            dataType: 'json',
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            data: {
                                'action': 'dom_wiedzy_unregister_from_toggle_archive',
                                'key': key
                            },
                            success: function(data)
                            {
                                if(data.valid == true){
                                    window.location.reload();
                                }
                            }
                        });
                    }
                });


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

            var $j = jQuery.noConflict();

                $j( "#dwmtr-moda-datepicker" ).datepicker({
                dateFormat: 'yy-mm-dd'
            });

                $j.datepicker.regional['pl'] = {
                closeText: 'Zamknij',
                prevText: '&#x3c;Poprzedni',
                nextText: 'Następny&#x3e;',
                currentText: 'Dziś',
                monthNames: ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec',
                    'Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'],
                monthNamesShort: ['Sty','Lu','Mar','Kw','Maj','Cze',
                    'Lip','Sie','Wrz','Pa','Lis','Gru'],
                dayNames: ['Niedziela','Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota'],
                dayNamesShort: ['Nie','Pn','Wt','Śr','Czw','Pt','So'],
                dayNamesMin: ['N','Pn','Wt','Śr','Cz','Pt','So'],
                weekHeader: 'Tydz',
                dateFormat: 'dd.mm.yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };

                $j.datepicker.setDefaults($j.datepicker.regional['pl']);

        });
    });
    </script>
<?php }

add_action( 'wp_ajax_dom_wiedzy_unregister_from_training', 'dom_wiedzy_unregister_from_training_callback' );
add_action( 'wp_ajax_nopriv_dom_wiedzy_unregister_from_training', 'dom_wiedzy_unregister_from_training_callback' );
function dom_wiedzy_unregister_from_training_callback() {

    $valid = false;

    parse_str($_POST['key'], $key);
    $key = key($key);
    $explode = explode('_', $key);

    $row_id = $explode[0];
    $team_person_id = $explode[1];

    global $wpdb;

    $sqlQuery = "SELECT team_persons FROM {$wpdb->prefix}trainees WHERE 1 = 1 AND id = {$row_id}";
    $dbData = $wpdb->get_results( $sqlQuery, ARRAY_A );

    if(!empty($dbData)){
        $persons = json_decode($dbData[0]['team_persons'], true);

        if(count($persons) > 1){

            unset($persons[$team_person_id]);

            $persons = json_encode($persons);

            $valid = $dataSaveObject['update'] = $wpdb->update(
                $wpdb->prefix.'trainees',
                array(
                    'team_persons' => $persons,
                    'updatedAt' => date('Y-m-d H:i:s', time())
                ),
                array( 'id' => $row_id ),
                array( '%s','%s'),
                array( '%d' )
            );

        }else{
            $valid = $wpdb->delete( $wpdb->prefix.'trainees', array( 'id' => $row_id ), array( '%d' ) );
        }
    }

    $arr = array('valid'=>$valid);
    echo json_encode($arr);
    wp_die();
}

add_action( 'wp_ajax_dom_wiedzy_unregister_from_toggle_archive', 'dom_wiedzy_unregister_from_toggle_archive_callback' );
add_action( 'wp_ajax_nopriv_dom_wiedzy_unregister_from_toggle_archive', 'dom_wiedzy_unregister_from_toggle_archive_callback' );
function dom_wiedzy_unregister_from_toggle_archive_callback() {

    $valid = false;

    parse_str($_POST['key'], $key);
    $key = key($key);
    $explode = explode('_', $key);

    $training_id = $explode[0];
    $training_time = $explode[1];

    $currentPostData = get_post_meta($training_id, 'training_date_place_trainer_relation', true);

    if(!empty($currentPostData) && isset($currentPostData[$training_time])){

        if(isset($currentPostData[$training_time]['archive'])){
            if($currentPostData[$training_time]['archive'] == true){
                $currentPostData[$training_time]['archive'] = false;
            }else{
                $currentPostData[$training_time]['archive'] = true;
            }
        }else{
            $currentPostData[$training_time]['archive'] = true;
        }

        $valid = update_post_meta($training_id, 'training_date_place_trainer_relation', $currentPostData);

    }

    $arr = array('valid'=>$valid);
    echo json_encode($arr);
    wp_die();
}

add_action( 'wp_ajax_dom_wiedzy_select_training', 'dom_wiedzy_select_training_callback' );
add_action( 'wp_ajax_nopriv_dom_wiedzy_select_training', 'dom_wiedzy_select_training_callback' );
function dom_wiedzy_select_training_callback() {

    $valid = false;

    parse_str($_POST['key'], $key);
    $key = key($key);

    $selectOptions = '<option selected disabled>wybierz datę</option>';

    $dates = get_training_rel_data($key);

    if(isset($dates) && !empty($dates)){
        foreach($dates as $trainingTimestamp => $value){

            $valid = true;

            $date = $dates[$trainingTimestamp]['date'];
            $days = $dates[$trainingTimestamp]['days'];

            if( !( isset($dates[$trainingTimestamp]['date_pause']) && !empty($dates[$trainingTimestamp]['date_pause']) ) ){
                if($days > 1){
                    $dateOffset = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " + ".($days - 1)." day");

                    $datesRange = array(
                        dateDifference(date('d.m.Y', strtotime(date("Y-m-d H:i:s", strtotime($date))) ), date('d.m.Y', $dateOffset )),
                        date('d.m.Y', $dateOffset )
                    );
                    $printDate = join('.',$datesRange[0]) . ' - ' . $datesRange[1];
                }else{
                    $printDate = $date;
                }
            }else{
                $printDate = $dates[$trainingTimestamp]['date_pause'];
            }

            $selectOptions .= '<option value="'.$trainingTimestamp.'">'.$printDate.'</option>';

        }
    }

    $arr = array('valid' => $valid, 'html' => $selectOptions);
    echo json_encode($arr);
    wp_die();
}

add_action( 'wp_ajax_dom_wiedzy_add_training_member', 'dom_wiedzy_add_training_member_callback' );
add_action( 'wp_ajax_nopriv_dom_wiedzy_add_training_member', 'dom_wiedzy_add_training_member_callback' );
function dom_wiedzy_add_training_member_callback() {


    $valid = false;
    $dataSaveObject = false;
    $i = 0;
    $target = 4;
    $data = array();
    $alert = array();
    $str_alert = "";

    parse_str($_POST['serialize'], $data);

    if(isset($data['date']) && !empty($data['date'])){

        $data['date'] = strtotime($data['date']);
    }else{
        $data['date'] = time();
    }

    if(isset($data['member']) && !empty($data['member'])){
        $i++;
    }else{
        $alert['member'] = __('Dodaj uczestnika', PROJECT_SLUG);
    }

    if(isset($data['company']) && !empty($data['company'])){
        $i++;
    }else{
        $alert['company'] = __('Dodaj firmę', PROJECT_SLUG);
    }

    if(isset($data['training']) && !empty($data['training'])){
        $i++;
    }else{
        $alert['training'] = __('Wybierz szkolenie', PROJECT_SLUG);
    }

    if(isset($data['time']) && !empty($data['time'])){
        $i++;
    }else{
        $alert['time'] = __('Dodaj datę szkolenia', PROJECT_SLUG);
    }


    if(!empty($alert)){
        foreach($alert as $al){
            $str_alert .= '<li>'.$al.'</li>';
        }
        $str_alert = '<ul class="result_error">'.$str_alert.'</ul>';
    }

    if($i == $target){

        global $wpdb;

        $data['team_persons'] = json_encode(array($data['member'] => $data['date']));

        if( !(isset($data['mentor']) && !empty($data['mentor']))){
            $data['mentor'] = 0;
        }

        $testExistEqualRow = is_user_in_report_table($data['training_time'], array(
            'reporting_person' => $data['reporting_person'],
            'team_persons' => $data['team_persons'],
            'training_id' => $data['training_id']
        ));

        $testExistEqualTeamRow = is_user_in_report_table($data['training_time'], array(
            'reporting_person' => $data['reporting_person'],
            'team_persons' => null,
            'training_id' => $data['training_id']
        ));

        if(!empty($testExistEqualTeamRow) && empty($testExistEqualRow)){

            // update team in existed row

            if($testExistEqualTeamRow[0]['team_persons'] != $data['team_persons']){

                $dbTp = json_decode($testExistEqualTeamRow[0]['team_persons'], true);
                $locTp = json_decode($data['team_persons'], true);
                $newTp = $dbTp + $locTp;
                //$newTp = array_unique($newTp);
                $saveTraineesData['team_persons'] = json_encode($newTp);
            }

            $valid = $dataSaveObject['update'] = $wpdb->update(
                $wpdb->prefix.'trainees',
                array(
                    'training_id' => $data['training'],
                    'company_id' => $data['company'],
                    'reporting_person' => $data['mentor'],
                    'team_persons' => $data['team_persons'],
                    'updatedAt' => date('Y-m-d H:i:s', time())
                ),
                array( 'id' => $testExistEqualTeamRow[0]['id'] ),
                array('%d','%d','%d','%s','%s'),
                array( '%d' )
            );

        }else{

            // add row if not exist

            $valid = $dataSaveObject['insert'] = $wpdb->insert(
                $wpdb->prefix.'trainees',
                array(
                    'training_id' => $data['training'],
                    'company_id' => $data['company'],
                    'training_time' => $data['time'],
                    'reporting_person' => $data['mentor'],
                    'team_persons' => $data['team_persons'],
                    'createdAt' => date('Y-m-d H:i:s', time())
                ),
                array('%d','%d','%d','%d','%s','%s')
            );
        }

        if($valid) {
            $valid = true;
            $alert = '<span class="correct">'.__('Pozycja została dodana', PROJECT_SLUG).'</span>';
        }

    }
    $arr = array('valid'=>$valid,'alert'=>$alert, 'alertstr' => $str_alert, 'test' => $dataSaveObject, 't' => $i .'=='.$target);
    echo json_encode($arr);
    wp_die();
}
?>