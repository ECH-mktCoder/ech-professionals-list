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
$therapistid = get_query_var('therapistid', 'none');

if ($therapistid == 'none') { echo "<script>window.location.replace('/healthcare-professionals')</script>"; }

$args = array(
    'therapistid' => $therapistid,
);


$plugin_info = new Ech_Professionals_List();
$plugin_public = new Ech_Professionals_List_Public($plugin_info->get_plugin_name(), $plugin_info->get_version());


$api_link = $plugin_public->ECHPL_gen_single_dr_api_link($args);

$get_post_json = $plugin_public->ECHPL_curl_json($api_link);

$json_arr = json_decode($get_post_json, true);


// check if single dr is vet
$getApiEnv = get_option( 'ech_pl_apply_api_env' ); 
if ( $getApiEnv == "0") {
    $vetProdCateID = '30558';
} else {
    $vetProdCateID = '30436';
}
$isVet = false;
foreach ($json_arr['personnel']['specialty'] as $specArr) {
    if($vetProdCateID == $specArr['product_category_id']) {
        $isVet = true;
    }
}

$en_name = [
    1 => $json_arr['personnel']['en_salutation'] ." ". $json_arr['personnel']['en_name'],
    2 => $json_arr['personnel']['en_name'].$json_arr['personnel']['en_salutation'],
];
$tc_name = [
    1 => $json_arr['personnel']['tc_salutation'] ." ". $json_arr['personnel']['tc_name'],
    2 => $json_arr['personnel']['tc_name'].$json_arr['personnel']['tc_salutation'],
];
$cn_name = [
    1 => $json_arr['personnel']['cn_salutation'] ." ". $json_arr['personnel']['cn_name'],
    2 => $json_arr['personnel']['cn_name'].$json_arr['personnel']['cn_salutation'],
];

?>



<div class="all_single_dr_wrap">
    <?php if (get_option('ech_pl_enable_breadcrumb') == 1): ?>
        <div class="sp_breadcrumb">
            <div><a href="<?= site_url() ?>"><?= $plugin_public->ECHPL_echolang(['Home', '主頁', '主页']) ?></a> > <a href="<?= site_url() . '/healthcare-professionals/' ?>"><?= $plugin_public->ECHPL_echolang(['Healthcare Professionals', '醫護專業人員', '医护专业人员']) ?></a> > <?= $plugin_public->ECHPL_echolang([$json_arr['personnel']['en_salutation'] . ' ' . $json_arr['personnel']['en_name'], $json_arr['personnel']['tc_name'] . $json_arr['personnel']['tc_salutation'], $json_arr['personnel']['cn_name'] . $json_arr['personnel']['cn_salutation']]) ?> </div>
        </div> <!-- sp_breadcrumb -->
    <?php endif; ?>

    <?php 
        $backToListURLParam = '';
        if($isVet) {
            $backToListURLParam = '?dr_type=vet';
        }
    ?>
    <div class="ECHDr_back_to_medical_list"><a href="<?= site_url() ?>/healthcare-professionals/<?=$backToListURLParam?>"> <?= $plugin_public->ECHPL_echolang(['Back to healthcare professionals', '返回醫護專業人員', '返回医护专业人员']) ?></a></div>



    <div class="single_dr_container">
        <div class="profile_container">
            <img src="<?= $json_arr['personnel']['avatar'] ?>" alt="">
            <?php if ($json_arr['personnel']['available_to_book'] == 1) : ?>
                <div class="dr_booking"><a href="<?= $json_arr['personnel']['doctor_whats_app_link'] ?>" target="_blank"><?= $plugin_public->ECHPL_echolang(['Book an Appointment', '預約醫生', '预约医生']) ?></a></div>
            <?php endif; ?>
        </div> <!-- profile_container -->



        <div class="info_container">
            <h1 class="dr_name"><?= $plugin_public->ECHPL_echolang([$en_name[$json_arr['personnel']['en_is_pre']], $tc_name[$json_arr['personnel']['tc_is_pre']], $cn_name[$json_arr['personnel']['cn_is_pre']]]) ?></h1>


            <div class="spec_container">
                <?php
                $specArrEN = array();
                $specArrZH = array();
                $specArrSC = array();

                foreach ($json_arr['personnel']['specialty'] as $specArr) {
                    array_push($specArrEN, $specArr['en_name']);
                    array_push($specArrZH, $specArr['tc_name']);
                    array_push($specArrSC, $specArr['cn_name']);
                }
                ?>

                <?= $plugin_public->ECHPL_echolang([implode(', ', $specArrEN), implode(', ', $specArrZH), implode(', ', $specArrSC)]) ?>
            </div> <!-- spec_container -->



            <div class="lang_container">
                <?php
                $drLangEN = array();
                $drLangZH = array();
                $drLangSC = array();


                foreach ($json_arr['personnel']['language'] as $langArr) {
                    array_push($drLangEN, $langArr['en_name']);
                    array_push($drLangZH, $langArr['tc_name']);
                    array_push($drLangSC, $langArr['cn_name']);
                }

                ?>

                <?= $plugin_public->ECHPL_echolang(['Languages', '語言', '语言']) ?>: <?= $plugin_public->ECHPL_echolang([implode(', ', $drLangEN), implode(', ', $drLangZH), implode(', ', $drLangSC)]) ?>
            </div>



            <div class="edu_container">
                <div class="sub_title"><?= $plugin_public->ECHPL_echolang(['Qualifications', '學歷', '学历']) ?>:</div>

                <?= $plugin_public->ECHPL_echolang([$plugin_public->ECHPL_replace_newline($json_arr['personnel']['en_seniority']), $plugin_public->ECHPL_replace_newline($json_arr['personnel']['tc_seniority']), $plugin_public->ECHPL_replace_newline($json_arr['personnel']['cn_seniority'])]) ?>
            </div> <!-- edu_container -->



            <div class="clinics_container">
                <div class="sub_title"><?= $plugin_public->ECHPL_echolang(['Related Clinics', '相關診所', '相关诊所']) ?></div>

                <?php foreach ($json_arr['personnel']['shop'] as $shops) : ?>
                    <?php
                    $addressArrEN = json_decode($shops['en_address'], true);
                    $addressArrZH = json_decode($shops['tc_address'], true);
                    $addressArrSC = json_decode($shops['cn_address'], true);
                    ?>

                    <div class="single_clinic">
                        <div class="clinic_name"><?= $plugin_public->ECHPL_echolang([$shops['en_name'], $shops['tc_name'], $shops['cn_name']]) ?></div>

                        <div class="location"><?= $plugin_public->ECHPL_echolang([$plugin_public->ECHPL_clinic_locations($addressArrEN), $plugin_public->ECHPL_clinic_locations($addressArrZH), $plugin_public->ECHPL_clinic_locations($addressArrSC)]) ?></div>

                    </div>



                <?php endforeach; ?>

            </div> <!-- clinics_container -->

        </div> <!-- info_container -->

    </div> <!-- single_dr_container -->

</div> <!-- all_single_dr_wrap -->