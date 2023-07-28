<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Professionals_List
 * @subpackage Ech_Professionals_List/public/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php 
$ppp = get_option('ech_pl_ppp');

$specialty_id = get_query_var('specialtyid', 'none');
if ($specialty_id == 'none') { echo "<script>window.location.replace('/healthcare-professionals')</script>"; }


$args = array(
    'specialty_id' => $specialty_id,
    'page_size' => $ppp
);

$plugin_info = new Ech_Professionals_List();
$plugin_public = new Ech_Professionals_List_Public($plugin_info->get_plugin_name(), $plugin_info->get_version());

$api_link = $plugin_public->ECHPL_gen_profList_api_link($args);

$get_post_json = $plugin_public->ECHPL_curl_json($api_link);
$json_arr = json_decode($get_post_json, true);

?>


<link rel="stylesheet" href="<?= plugin_dir_url(__DIR__) ?>css/ech-pl-category-list.css">


<?php 
    $getSpName_json = $plugin_public->ECHPL_get_specialty_name($specialty_id);
    $spNameArr = json_decode($getSpName_json, true);
    $sp_name = $plugin_public->ECHPL_echolang([ $spNameArr['en'], $spNameArr['zh'], $spNameArr['sc']]);
?>



<div class="ech_dr_sp_list_all_wrap">
    <div class="sp_breadcrumb">
        <div><a href="<?= site_url() ?>"><?= $plugin_public->ECHPL_echolang(['Home', '主頁', '主页']) ?></a> > <a href="<?=site_url() . '/healthcare-professionals/' ?>"><?=$plugin_public->ECHPL_echolang(['Healthcare Professionals','醫護專業人員','医护专业人员'])?></a> > <?=$plugin_public->ECHPL_echolang(['Specialist','專科','专科']).': '.$sp_name ?> </div>
    </div> <!-- sp_breadcrumb -->

    <div class="echdr_page_anchor"></div>


    <div class="ECHDr_back_to_medical_list"><a href="<?=site_url()?>/healthcare-professionals/"> <?= $plugin_public->ECHPL_echolang(['Back to healthcare professionals', '返回醫護專業人員', '返回医护专业人员']) ?></a></div>
    <div class="ECHDr_search_title">
        <p><span><?=$plugin_public->ECHPL_echolang(['Specialist','專科','专科'])?>: </span><?=$sp_name?> </p>
    </div>


    <div class="ech_dr_container">
        <div class="loading_div"><p><?=$plugin_public->ECHPL_echolang(['Loading...','載入中...','载入中...'])?></p></div>
        
        <div class="all_drs_container" data-ppp="<?=$ppp?>" data-channel="4" data-brandid="" data-region="" data-specialty="<?=$specialty_id?>">
            <?php foreach($json_arr['result'] as $dr): ?>
            <?=$plugin_public->ECHPL_load_card_template($dr)?>
            <?php endforeach; ?>
        </div> <!-- all_drs_container -->

        <?php 
            /*** pagination ***/
            $total_posts = $json_arr['count'];
            $max_page = ceil($total_posts/$ppp);
        ?>
        <div class="ech_dr_pagination" data-current-page="1" data-max-page="<?=$max_page?>" data-topage="" data-ajaxurl="<?=get_admin_url(null, 'admin-ajax.php')?>"></div>


    </div> <!-- ech_dr_container -->


</div> <!-- ech_dr_sp_list_all_wrap -->