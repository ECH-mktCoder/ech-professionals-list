<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Professionals_List
 * @subpackage Ech_Professionals_List/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<div class="echPlg_wrap">
    <h1>ECH Professionals List General Settings</h1>

    <div class="plg_intro">
        <p> More shortcode attributes and guidelines, visit <a href="#" target="_blank">Github</a>. </p>
        <div class="shtcode_container">
            <pre id="sample_shortcode">[ech_pl]</pre>
            <div id="copyMsg"></div>
            <button id="copyShortcode">Copy Shortcode</button>
        </div>
    </div>

    <div class="form_container">
        <form method="post" id="ech_pl_settings_form">
        <?php 
            settings_fields( 'ech_pl_gen_settings' );
            do_settings_sections( 'ech_pl_gen_settings' );
        ?>
            <h2>Settings</h2>

            <h3>Live</h3>
            <div class="form_row">
                <?php $getVetPcID_live = get_option( 'ech_pl_vet_pcid_live' ); ?>
                <label>Vet product_category_id value (Live): </label>
                <input type="text" name="ech_pl_vet_pcid_live" id="ech_pl_vet_pcid_live" pattern="[0-9]{1,}" value="<?=$getVetPcID_live?>">
            </div>
            <div class="form_row">
                <?php $getDrPcID_live = get_option( 'ech_pl_dr_pcid_live' ); ?>
                <label>Doctor product_category_id value (Live): </label>
                <input type="text" name="ech_pl_dr_pcid_live" id="ech_pl_dr_pcid_live" pattern="[0-9]{1,}" value="<?=$getDrPcID_live?>">
            </div>



            <h3>Dev / UAT</h3>
            <div class="form_row">
                <?php $getVetPcID_dev = get_option( 'ech_pl_vet_pcid_dev' ); ?>
                <label>Vet product_category_id value (Dev): </label>
                <input type="text" name="ech_pl_vet_pcid_dev" id="ech_pl_vet_pcid_dev" pattern="[0-9]{1,}" value="<?=$getVetPcID_dev?>">
            </div>
            <div class="form_row">
                <?php $getDrPcID_dev = get_option( 'ech_pl_dr_pcid_dev' ); ?>
                <label>Doctor product_category_id value (Dev): </label>
                <input type="text" name="ech_pl_dr_pcid_dev" id="ech_pl_dr_pcid_dev" pattern="[0-9]{1,}" value="<?=$getDrPcID_dev?>">
            </div>






            <h2>General</h2>
            <div class="form_row">
                <?php $getApiEnv = get_option( 'ech_pl_apply_api_env' ); ?>
                <label>Connect to Global CMS API environment : </label>
                <select name="ech_pl_apply_api_env" id="">
                    <option value="0" <?= ($getApiEnv == "0") ? 'selected' : '' ?>>Dev/UAT</option>
                    <option value="1" <?= ($getApiEnv == "1") ? 'selected' : '' ?>>Live</option>
                </select>
            </div>
            <div class="form_row">
                <?php $getPPP = get_option( 'ech_pl_ppp' ); ?>
                <label>Post per page : </label>
                <input type="text" name="ech_pl_ppp" id="ech_pl_ppp" pattern="[0-9]{1,}" value="<?=$getPPP?>">
            </div>
            <div class="form_row">
                <?php $getDisplayDrType = get_option( 'ech_pl_display_dr_type' ); ?>
                <label>Display Dr Type : </label>
                <select name="ech_pl_display_dr_type" id="">
                    <option value="all" <?= ($getDisplayDrType == "all") ? 'selected' : '' ?>>All</option>
                    <option value="dr" <?= ($getDisplayDrType == "dr") ? 'selected' : '' ?>>Doctors Only</option>
                    <option value="vet" <?= ($getDisplayDrType == "vet") ? 'selected' : '' ?>>Vet Only</option>
                </select>
            </div>
            <div class="form_row">
                <?php $getListStyle = get_option( 'ech_pl_get_style' ); ?>
                <label>Apply List Style : </label>
                <select name="ech_pl_get_style" id="">
                    <option value="ech_web" <?= ($getListStyle == "ech_web") ? 'selected' : '' ?>>ECH Website Style</option>
                    <option value="ec_vet" <?= ($getListStyle == "ec_vet") ? 'selected' : '' ?>>EC Vet Style</option>
                </select>
            </div>
            <div class="form_row">
                <?php $getBreadcrumb = get_option('ech_pl_enable_breadcrumb'); ?>
                <label>Apply Breadcrumb on "Single Profile" and "Categories / Tags" pages: </label>
                <select name="ech_pl_enable_breadcrumb" id="">
                    <option value="0" <?= ($getBreadcrumb == "0") ? 'selected' : '' ?>>No</option>
                    <option value="1" <?= ($getBreadcrumb == "1") ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>


            <div class="form_row">
                <button type="submit"> Save </button>
            </div>
        </form>
        <div class="statusMsg"></div>


    </div> <!-- form_container -->
</div>

