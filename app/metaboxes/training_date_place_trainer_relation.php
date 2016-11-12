<?php

function domwiedzy_metabox_trainings_rel() {

    add_meta_box(
        'domwiedzy_metabox_trainings_rel',
        __( 'Przydział relacji DATA - MIEJSCE - PROWADZĄCY', PROJECT_SLUG ),
        'domwiedzy_metabox_trainings_rel_body',
        'szkolenia',
        'normal',
        'low'
    );
}
add_action( 'add_meta_boxes', 'domwiedzy_metabox_trainings_rel' );


function domwiedzy_metabox_trainings_rel_body( $post ) {

    // Add a nonce field so we can check for it later.
    //wp_nonce_field( 'myplugin_meta_box', 'myplugin_meta_box_nonce' );

    $currentPostData = get_post_meta($post->ID, 'training_date_place_trainer_relation', true);
    ?>

    <div id="dwmtr-container">


        <?php if(!empty($post)): ?>
            <div class="dwmtr-container-body">
                <?php

                    if(!empty($currentPostData)):

                        $archive = false;

                        foreach($currentPostData as $key => $val):

                            echo '<div class="dw-date-row';
                                if((strtotime($val['date']) < strtotime(date('d.m.Y',time())))){
                                    echo ' old hidden';
                                    $archive = true;
                                }
                                echo '"><div class="t">';
                                    echo '<div class="tr">';
                                        echo '<div class="td">';
                                            echo $val['date'];
                                            echo (isset($val['date_pause']) && !empty($val['date_pause'])) ? '<br /><i>'.$val['date_pause'].'</i>' : '';
                                        echo '</div>';
                                        echo '<div class="td">';
                                            echo ($val['days'] > 1) ? $val['days'] .' dni' : $val['days']. ' dzień';
                                        echo '</div>';
                                        echo '<div class="td">';

                                            if(isset($val['date_promo']) && !empty($val['date_promo'])){
                                                echo '<del>'.$val['price'].'</del>';
                                                echo (isset($val['price_promo']) && !empty($val['price_promo'])) ? '<br /><b>'.$val['price_promo'].'</b>' : '';
                                                echo (isset($val['date_promo']) && !empty($val['date_promo'])) ? '<br />do dnia: '.$val['date_promo'] : '';
                                                echo (isset($val['price_promo_text']) && !empty($val['price_promo_text'])) ? '<br /><small>'.$val['price_promo_text'].'</small>' : '';
                                            }else{
                                                echo $val['price'];
                                            }

                                        echo '</div>';
                                        echo '<div class="td">';
                                            foreach($val['places'] as $v):
                                                $tt = get_term_by('id', $v, 'szkolenia_place');
                                                echo '<a href="/wp-admin/edit-tags.php?action=edit&taxonomy=szkolenia_place&tag_ID='.$v.'&post_type=szkolenia&wp_http_referer=%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Dszkolenia_place%26post_type%3Dszkolenia" title="">'.$tt->name.'</a>';
                                            endforeach;
                                        echo '</div>';
                                        echo '<div class="td">';
                                            foreach($val['trainers'] as $v):
                                            echo '<a href="/wp-admin/post.php?post='.$v.'&action=edit" title="">'.get_the_title($v).'</a>';
                                           endforeach;
                                        echo '</div>';
                                        echo '<div class="td">';
                                            echo '<button class="button button-primary dw-date-row-delete" data-rel="'.$key.'">Usuń</button>';
                                            echo '<button class="button button-primary dw-date-row-edit" data-rel="'.$key.'">Edytuj</button>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';

                        endforeach;

                    endif;

                ?>
            </div>
            <div class="buttons">
                <?php if($archive): ?>
                    <button class="button button-primary button-large" id="dwmtr-container-showPast">Pokaż archiwalne</button>
                <?php endif; ?>
                <button class="button button-primary button-large" id="dwmtr-container-add">Dodaj termin</button>
            </div>
        <?php endif; ?>

    </div>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script type="text/javascript">
        jQuery(function($){
            $(document).ready(function(){

                $('#dwmtr-container-add').on('click', function(event){
                    event.preventDefault();
                    $('#dwmtr-modal').show();
                });
                $('#dwmtr-container-showPast').on('click', function(event){
                    event.preventDefault();
                    $('.dw-date-row.old').toggleClass('hidden').promise().done(function(){
                        if($('.dw-date-row.old:first').hasClass('hidden')){
                            $('#dwmtr-container-showPast').text('Pokaż archiwalne');
                        }else{
                            $('#dwmtr-container-showPast').text('Ukryj archiwalne');
                        }
                    });

                });
                $('#dwmtr-modal-close').on('click', function(event){
                    event.preventDefault();
                    $('#dwmtr-modal').hide();
                    $('#dwmtr-modal').find("input").val("");
                    $('#dwmtr-modal').find("input[name='price']").val("0 zł + 23% VAT");
                    $('#dwmtr-modal').find("select option").prop('selected', false);
                });
                $( "#dwmtr-moda-datepicker, #dwmtr-moda-datepicker2, #dwmtr-moda-datepicker3" ).datepicker();

                $.datepicker.regional['pl'] = {
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
                    yearSuffix: ''};
                $.datepicker.setDefaults($.datepicker.regional['pl']);


                initSortableUl();

            });

            var $CFform = $('#dwmtr-modal-form');

            $('#dwmtr-modal-add').live("click", function( event ) {
                event.preventDefault();
                jQuery('#dwmtr-modal-add').text('Dodaj');
                var $CFstatus = $("#dwmtr-modal-form-result");
                $CFstatus.html("");
                $CFform.find(".error").removeClass("error");

                var trainters = [];
                jQuery('ul[name="trainers"] li.selected').each(function(){
                    var id = jQuery(this).attr('data-value');
                    trainters.push(id);
                });

                $.ajax({
                    type: "POST",
                    async: false,
                    dataType: 'json',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {
                        'action': 'dom_wiedzy_add_training_date',
                        'serialize': $('#dwmtr-modal-form input, #dwmtr-modal-form select').serialize(),
                        'post': <?php echo $post->ID; ?>,
                        'trainers' : trainters
                    },
                    success: function(data)
                    {
                        if(data.valid === true){
                            dw_metabox_loaddata();
                            $CFstatus.html(data.alert);
                            $CFform.find(".error").removeClass("error");
                            $CFform.find("input").val("");
                            $CFform.find("input[name='price']").val("0 zł + 23% VAT");
                            $CFform.find("select option").prop('selected', false);
                            $CFform.find("input[type=checkbox]").prop('checked', false);
                            setTimeout(function(){
                                $CFstatus.html("");
                                $('#dwmtr-modal').hide();
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

            $("#dwmtr-modal-form .error").live("change keyup",function(){
                if($(this).val() != ""){
                    $(this).removeClass("error");
                }
            });

            dwmetabox_delete();
            dwmetabox_edit();

        });

        var initSortableUl = function () {
            jQuery('ul[name="trainers"]').on('click', 'li', function (e) {
                if (e.ctrlKey || e.metaKey) {
                    jQuery(this).toggleClass("selected");
                } else {
                    jQuery(this).addClass("selected").siblings().removeClass('selected');
                }
            }).sortable({
                connectWith: "ul",
                delay: 150,
                revert: 0,
                helper: function (e, item) {
                    if (!item.hasClass('selected')) {
                        item.addClass('selected').siblings().removeClass('selected');
                    }

                    var elements = item.parent().children('.selected').clone();

                    item.data('multidrag', elements).siblings('.selected').remove();

                    var helper = jQuery('<li/>');
                    return helper.append(elements);
                },
                stop: function (e, ui) {
                    var elements = ui.item.data('multidrag');
                    ui.item.after(elements).remove();
                }

            });
        }

        var dw_metabox_loaddata = function(){
            var box = jQuery('#dwmtr-container .dwmtr-container-body');
            box.addClass('loading');
            jQuery.ajax({
                type: "POST",
                async: false,
                dataType: 'json',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    'action': 'dom_wiedzy_load_training_data',
                    'post': <?php echo $post->ID; ?>
                },
                success: function(data)
                {
                    box.html(data.html);
                    box.removeClass('loading');
                }
            });
        }

        var dwmetabox_delete = function(){
            jQuery('.dw-date-row-delete').live("click", function(e) {
                e.preventDefault();
                var key = jQuery(this).attr('data-rel');
                if (window.confirm("Wszystkie powiązane zapisy na szkolenia zostaną usunięte. Czy na pewno chcesz to zrobić?")) {
                    jQuery.ajax({
                        type: "POST",
                        async: false,
                        dataType: 'json',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            'action': 'dom_wiedzy_load_training_data_del',
                            'post': <?php echo $post->ID; ?>,
                            'key': key
                        },
                        success: function(data)
                        {
                            if(data.valid == true){
                                dw_metabox_loaddata();
                            }
                        }
                    });
                }
            });
        }
        var dwmetabox_edit = function(){
            jQuery('.dw-date-row-edit').live("click", function(e) {
                e.preventDefault();
                jQuery('#dwmtr-modal-add').text('Aktualizuj');
                var $CFform = jQuery('#dwmtr-modal-form');
                var key = jQuery(this).attr('data-rel');
                jQuery.ajax({
                    type: "POST",
                    async: false,
                    dataType: 'json',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {
                        'action': 'dom_wiedzy_load_training_data_edit',
                        'post': <?php echo $post->ID; ?>,
                        'key': key
                    },
                    success: function(data)
                    {
                        if(data.valid == true){

                            $CFform.find('input[name="date"]').val(data.trdata.date);
                            $CFform.find('input[name="price"]').val(data.trdata.price);
                            $CFform.find('input[name="days"]').val(data.trdata.days);
                            $CFform.find('input[name="key"]').val(key);

                            if(data.trdata.date_pause != undefined && data.trdata.date_pause.length){
                                $CFform.find("input[name='cb1']").prop('checked', true);
                                $CFform.find('input[name="date2"]').val(data.trdata.date_pause);
                            }

                            if(data.trdata.price_promo != undefined && data.trdata.price_promo.length){
                                $CFform.find("input[name='cb2']").prop('checked', true);
                                if(data.trdata.date_promo != undefined && data.trdata.date_promo.length){
                                    $CFform.find('input[name="date3"]').val(data.trdata.date_promo);
                                }
                                if(data.trdata.price_promo_text != undefined && data.trdata.price_promo_text.length){
                                    $CFform.find('input[name="price_text"]').val(data.trdata.price_promo_text);
                                }
                                $CFform.find('input[name="price_promo"]').val(data.trdata.price_promo);
                            }

                            if(data.trdata.places != undefined && data.trdata.places.length){
                                jQuery.each(data.trdata.places, function (i,v) {
                                    $CFform.find("select option[value='"+v+"']").attr('selected', true);
                                });
                            }
                            var sortableUl = jQuery('ul[name="trainers"]');
                            if(data.trdata.trainers != undefined && data.trdata.trainers.length){
                                jQuery.each(data.trdata.trainers, function (i,v) {
                                    var li = sortableUl.find("li[data-value='"+v+"']");
                                    var cloneLi = li.clone();
                                    sortableUl.prepend(cloneLi);
                                    li.remove();
                                    cloneLi.addClass('selected');
                                });
                                //initSortableUl();
                            }

                            jQuery('#dwmtr-modal').show();
                        }
                    }
                });
            });
        }
    </script>

    <div id="dwmtr-modal">
        <div id="dwmtr-modal-form-result"></div>
        <header>Wypełnij wszystkie pola</header>
        <div id="dwmtr-modal-form">
            <div class="inputs">
                <label>Data rozpoczęcia</label>
                <label>Cena</label>
                <label>Ilość dni</label>
                <input type="text" name="date" value="" placeholder="Data wydarzenia" id="dwmtr-moda-datepicker" readonly/>
                <input type="text" name="price" value="0 zł + 23% VAT" placeholder="Podaj cenę"/>
                <input type="number" name="days" step="1" min="1" placeholder="Liczba dni"/>
                <input id="dwmtr-cb1" type="checkbox" name="cb1" value="true" />
                <label for="dwmtr-cb1" class="dwcheckbox">Przerwa w szkoleniu</label>
                <section>
                    <label>Termin</label>
                    <input type="text" name="date2" value="" style="width: 100%" placeholder="Nadpisz standardową informację o dacie szkolenia"/>
                </section>
                <div class="break"></div>
                <input id="dwmtr-cb2" type="checkbox" name="cb2" value="true" >
                <label for="dwmtr-cb2" class="dwcheckbox">Cena promocyjna</label>
                <section>
                    <input type="text" name="date3" value="" placeholder="Koniec promocji" id="dwmtr-moda-datepicker3"  class="inp_w1" readonly/>
                    <input type="text" name="price_promo" value="" placeholder="Cena promocyjna"  class="inp_w2"/>
                    <input type="text" name="price_text" value="" placeholder="Opis promocji"  class="inp_w3"/>
                </section>
                <div class="break"></div>
                <fieldset>
                    <label>Miejsca szkolenia</label>
                    <select name="places[]" multiple>
                        <?php
                            $places = get_terms('szkolenia_place', array('hide_empty' => false) );
                            if(!empty($places))
                                foreach($places as $place):
                                    echo '<option value="'.$place->term_id.'">'.$place->name.'</option>';
                                endforeach;
                        ?>
                    </select>
                </fieldset>
                <fieldset>
                    <label>Prelegenci</label>
                    <ul name="trainers">
                        <?php
                        $trainers = new WP_Query(array(
                            'post_type' => 'prelegenci',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'orderby' => 'menu_order date',
                            'order' => 'ASC DESC'
                        ));

                        if(!empty($trainers->posts))
                            foreach($trainers->posts as $trainer):
                                echo '<li data-value="'.$trainer->ID.'">'.$trainer->post_title.'</li>';
                            endforeach;

                        ?>
                    </ul>
                </fieldset>
                <input type="hidden" name="key" value="" />
            </div>
            <button class="button button-primary button-large" id="dwmtr-modal-close">Anuluj</button>
            <button class="button button-primary button-large" id="dwmtr-modal-add">Dodaj</button>
        </div>
    </div>

<?php
}


/*
 * Action for save
 */


add_action( 'wp_ajax_dom_wiedzy_load_training_data', 'dom_wiedzy_load_training_data_callback' );
add_action( 'wp_ajax_nopriv_dom_wiedzy_load_training_data', 'dom_wiedzy_load_training_data_callback' );
function dom_wiedzy_load_training_data_callback() {
    $html = "";
    parse_str($_POST['post'], $post_id);
    $post_id = key($post_id);

    $currentPostData = get_post_meta($post_id, 'training_date_place_trainer_relation', true);

    if(!empty($currentPostData)):

        foreach($currentPostData as $key => $val):

            $html .= '<div class="dw-date-row';
            $html .= ((strtotime($val['date']) < strtotime(date('d.m.Y',time())))) ? ' old' : '';
            $html .='">';
            $html .= '<div class="t">';
            $html .= '<div class="tr">';
            $html .= '<div class="td">';
            $html .= $val['date'];
            $html .= (isset($val['date_pause']) && !empty($val['date_pause'])) ? '<br /><i>'.$val['date_pause'].'</i>' : '';
            $html .= '</div>';
            $html .= '<div class="td">';
            $html .= ($val['days'] > 1) ? $val['days'] .' dni' : $val['days']. ' dzień';
            $html .= '</div>';
            $html .= '<div class="td">';

            if(isset($val['date_promo']) && !empty($val['date_promo'])){
                $html .= '<del>'.$val['price'].'</del>';
                $html .= (isset($val['price_promo']) && !empty($val['price_promo'])) ? '<br /><b>'.$val['price_promo'].'</b>' : '';
                $html .= (isset($val['date_promo']) && !empty($val['date_promo'])) ? '<br />do dnia: '.$val['date_promo'] : '';
                $html .= (isset($val['price_promo_text']) && !empty($val['price_promo_text'])) ? '<br /><small>'.$val['price_promo_text'].'</small>' : '';
            }else{
                $html .= $val['price'];
            }

            $html .= '</div>';
            $html .= '<div class="td">';
            foreach($val['places'] as $v):
                $tt = get_term_by('id', $v, 'szkolenia_place');
                $html .= '<a href="/wp-admin/edit-tags.php?action=edit&taxonomy=szkolenia_place&tag_ID='.$v.'&post_type=szkolenia&wp_http_referer=%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Dszkolenia_place%26post_type%3Dszkolenia" title="">'.$tt->name.'</a>';
            endforeach;
            $html .= '</div>';
            $html .= '<div class="td">';
            foreach($val['trainers'] as $v):
                $html .= '<a href="/wp-admin/post.php?post='.$v.'&action=edit" title="">'.get_the_title($v).'</a>';
            endforeach;
            $html .= '</div>';
            $html .= '<div class="td">';
            $html .= '<button class="button button-primary dw-date-row-delete" data-rel="'.$key.'">Usuń</button>';
            $html .= '<button class="button button-primary dw-date-row-edit" data-rel="'.$key.'">Edytuj</button>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

        endforeach;

    endif;

    $arr = array('html'=>$html);
    echo json_encode($arr);
    wp_die();
}


add_action( 'wp_ajax_dom_wiedzy_load_training_data_del', 'dom_wiedzy_load_training_data_del_callback' );
add_action( 'wp_ajax_nopriv_dom_wiedzy_load_training_data_del', 'dom_wiedzy_load_training_data_del_callback' );
function dom_wiedzy_load_training_data_del_callback() {

    $valid = false;

    parse_str($_POST['post'], $post_id);
    parse_str($_POST['key'], $key);

    $post_id = key($post_id);
    $key = key($key);

    $currentPostData = get_post_meta($post_id, 'training_date_place_trainer_relation', true);

    unset($currentPostData[$key]);

    ksort($currentPostData);

    if( update_post_meta($post_id, 'training_date_place_trainer_relation', $currentPostData) ){
        $valid = true;

        global $wpdb;
        $wpdb->delete( $wpdb->prefix.'trainees', array( 'training_id' => $post_id, 'training_time' => $key ), array( '%d', '%d' ) );

    }


    $arr = array('valid'=>$valid);
    echo json_encode($arr);
    wp_die();
}

add_action( 'wp_ajax_dom_wiedzy_load_training_data_edit', 'dom_wiedzy_load_training_data_edit_callback' );
add_action( 'wp_ajax_nopriv_dom_wiedzy_load_training_data_edit', 'dom_wiedzy_load_training_data_edit_callback' );
function dom_wiedzy_load_training_data_edit_callback() {

    $valid = false;
    $data = array();

    parse_str($_POST['post'], $post_id);
    parse_str($_POST['key'], $key);

    $post_id = key($post_id);
    $key = key($key);

    $currentPostData = get_post_meta($post_id, 'training_date_place_trainer_relation', true);

    if(isset($currentPostData[$key]) && !empty($currentPostData[$key])){
        $valid = true;
        $data = $currentPostData[$key];
    }

    $arr = array('valid'=>$valid, 'trdata' => $data);
    echo json_encode($arr);
    wp_die();
}

add_action( 'wp_ajax_dom_wiedzy_add_training_date', 'dom_wiedzy_add_training_date_callback' );
add_action( 'wp_ajax_nopriv_dom_wiedzy_add_training_date', 'dom_wiedzy_add_training_date_callback' );
function dom_wiedzy_add_training_date_callback() {


    $valid = false;
    $i = 0;
    $target = 4;
    $data = array();
    $alert = array();
    $str_alert = "";
    $test = "";
    $debug = false;

    parse_str($_POST['serialize'], $data);
    parse_str($_POST['post'], $post_id);

    $trainters = $_POST['trainers'];


    $post_id = key($post_id);

    if(isset($data['date']) && !empty($data['date'])){
        $i++;
        $key = strtotime($data['date']);
        if(isset($data['key']) && !empty($data['key'])){
            $key = $data['key'];
        }
    }else{
        $alert['date'] = __('Podaj datę', PROJECT_SLUG);
    }

    if(isset($data['days']) && !empty($data['days'])){
        $i++;
    }else{
        $alert['days'] = __('Podaj liczbę dni', PROJECT_SLUG);
    }

    if(isset($data['price']) && !empty($data['price']) && $data['price'] != "0 zł + 23% VAT"){
        $i++;
    }else{
        $alert['price'] = __('Podaj cenę', PROJECT_SLUG);
    }

    if(isset($data['places']) && !empty($data['places'])){
        $i++;
    }else{
        $alert['places[]'] = __('Nie wybrano miejsca', PROJECT_SLUG);
    }

    if(!empty($alert)){
        foreach($alert as $al){
            $str_alert .= '<li>'.$al.'</li>';
        }
        $str_alert = '<ul class="result_error">'.$str_alert.'</ul>';
    }

    if($i == $target){

            $insertData = array(
                $key => array(
                    'date' => $data['date'],
                    'days' => $data['days'],
                    'price' => $data['price'],
                    'places' => $data['places'],
                    'trainers' => $trainters,
                )
            );



            if(isset($data['cb1']) && !empty($data['cb1']) && $data['cb1'] == 'true'){
                if(isset($data['date2']) && !empty($data['date2'])){
                    $insertData[$key]['date_pause'] = $data['date2'];
                }
            }
            if(isset($data['cb2']) && !empty($data['cb2']) && $data['cb2'] == 'true'){
                if(isset($data['date3']) && !empty($data['date3'])){
                    $insertData[$key]['date_promo'] = $data['date3'];
                }
                if(isset($data['price_promo']) && !empty($data['price_promo'])){
                    $insertData[$key]['price_promo'] = $data['price_promo'];
                }
                if(isset($data['price_text']) && !empty($data['price_text'])){
                    $insertData[$key]['price_promo_text'] = $data['price_text'];
                }
            }

            if($debug) tab($insertData);

            $currentPostData = get_post_meta($post_id, 'training_date_place_trainer_relation', true);

            if($currentPostData === ""){
                $valid = add_post_meta($post_id, 'training_date_place_trainer_relation', $insertData);
            }else if($currentPostData === NULL){
                $valid = update_post_meta($post_id, 'training_date_place_trainer_relation', $insertData);
            }else{
                if($debug) tab($key);
                if($debug) tab($currentPostData[$key]);
                if($debug) tab($insertData[$key]);

                if(
                    isset($currentPostData[$key]) && !empty($currentPostData[$key]) &&
                    ( $currentPostData[$key] == $insertData[$key] ) ||
                    (   $currentPostData[$key]['places'] == $insertData[$key]['places'] &&
                        $currentPostData[$key]['date'] == $insertData[$key]['date']
                    )

                ){

                    // editing
                    $test = "1";

                    $currentPostData[$key] = $insertData[$key];

                    $insertData = $currentPostData;

                }else if( $currentPostData[$key]['date'] == $insertData[$key]['date'] && $currentPostData[$key]['places'] != $insertData[$key]['places']){

                    $test = "2";

                    $newIndex = createNextTrainingTimeIndex($currentPostData, $key);

                    $currentPostData[ $newIndex ] = $insertData[$key];
                    $insertData = $currentPostData;

                }else{

                    $test = "3";
                    // adding
                    $insertData = $currentPostData + $insertData;
                }
                ksort($insertData);

                $valid = update_post_meta($post_id, 'training_date_place_trainer_relation', $insertData);
            }

            if($valid) {
                wp_set_post_terms( $post_id, $data['places'], 'szkolenia_place' );
                $valid = true;
                $alert = '<span class="correct">'.__('Pozycja została dodana', PROJECT_SLUG).'</span>';
            }

    }
    $arr = array('valid'=>$valid,'alert'=>$alert, 'alertstr' => $str_alert, 'test'=>$test);
    echo json_encode($arr);
    wp_die();
}

function createNextTrainingTimeIndex($array, $currentValue){
    $keys = array_keys($array);
    $newKey = $currentValue + 1;
    if(in_array($newKey, $keys)){
        $newKey = createNextTrainingTimeIndex($array, $newKey);
    }
    return $newKey;

}