# ech-professionals-list
A Wordpress plugin to display a list of ECH healthcare professionals. This plugin integrates with the global ECH articles CMS. 

To ensure proper functionality, please ensure that you have the TranslatePress plugin, Astra theme, and Elementor page builder installed on your WordPress site.


## Installation
1. Before installing the plugin, create a new page with a slug `healthcare-professionals`
2. Install and activate the plugin


## Usage 
To display the professionals list, enter shortcode
```
[ech_pl]
```

To display the professionals list by specialty ID / brand ID, enter shortcode
(without filter section, load more dr will be displayed in the same page)
```
[ech_pl_by_spec]
```


## Shortcode Attributes

Attribute | Description
----------|-------------
ppp (INT) | post per page. Default vaule is `12`
channel_id (INT) | select article channels between ECH app and website. Default value is `4` (website)
dr_type (String) | `all` / `vet` / `dr`. Used to control the display of doctors based on their type. This will override the "Display Dr Type" setting on plugin admin page. 
spec_id (INT) | Specialty ID
brand_id (INT) | Brand ID


### A guideline for echealthcare.com installation
Below installation guideline is for EC Healthcare website
1. Install and activiate the plugin
2. Copy and paste the below CSS code in child folder `style.css` 
```
/**** ^^^ Mega Menu - Doctor *****/
.mega_menu_drs_container {
	width: 100%;
	display: flex;
	margin-top: 30px;
}
.mega_menu_drs_container .drs_cate_container {
	width: 20%;
	border-right: 1px solid #fff;
}
.mega_menu_drs_container .drs_spec_container,
.mega_menu_drs_container .vet_spec_container {
	width: 79%;
	display: none;
}
.mega_menu_drs_container ul li a {
	color: #fff;
	font-family: "FuturaPT-Light",Sans-serif!important;
	font-weight: 300;
    letter-spacing: 1px;
	font-size: 15px;
	line-height: 1em;
}
.mega_menu_drs_container .drs_cate_container ul {
	list-style: none;
}
.mega_menu_drs_container .drs_spec_container ul li,
.mega_menu_drs_container .vet_spec_container ul li {
	display: inline-block;
	width: 25%;
	padding: 0 5px;
	vertical-align: top;
}


.mega_menu_drs_container .spec_title {
	color: #AEAEAE;
    font-family: "FuturaPT-Book", Sans-serif;
    font-size: 18px;
    font-weight: 400;
    line-height: 1.3em;
    letter-spacing: 1.3px;
	padding-left: 50px;
	margin-bottom: 15px;
}
/**** ^^^ (END)Mega Menu - Doctor *****/
```
3. Head to XYZ PHP Code, copy and paste the below code
```
<?php 
// Must activiate ech-professionals plugin to use below functions
$plugin_info = new Ech_Professionals_List();
$plugin_public = new Ech_Professionals_List_Public($plugin_info->get_plugin_name(), $plugin_info->get_version());


// Get Dr Specialties list
$getDrTypeID = $plugin_public->ECHPL_get_dr_type_id('Doctor'); // case-sensitive!!
$drAPI = $plugin_public->ECHPL_get_api_domain() . '/v1/api/get_specialty_list?get_type=4&channel_id=4&product_category_id='.$getDrTypeID;
$getDrSpJson = $plugin_public->ECHPL_curl_json($drAPI);
$drJson_arr = json_decode($getDrSpJson, true);


// Get Vet Specialties list
$getVetTypeID = $plugin_public->ECHPL_get_dr_type_id('Vet'); // case-sensitive!!
$vetAPI = $plugin_public->ECHPL_get_api_domain() . '/v1/api/get_specialty_list?get_type=4&channel_id=4&product_category_id='.$getVetTypeID;
$getVetSpJson = $plugin_public->ECHPL_curl_json($vetAPI);
$vetJson_arr = json_decode($getVetSpJson, true);


// html structure
$output = '';

$output .= '<div class="mega_menu_drs_container">';
    $output .= '
    <div class="drs_cate_container">
        <ul>
            <li><a href="/healthcare-professionals/?dr_type=doctor" id="mega-menu-hover-drs">Doctor Professionals</a></li>
            <li><a href="/healthcare-professionals/?dr_type=vet" id="mega-menu-hover-vet">Vet Professionals</a></li>
        </ul>
    </div>'; //service_cate_container

    $output .= '<div class="drs_spec_container" id="mega_menu_drs_spec">';
        $output .= '<div class="spec_title">Doctor Specialties</div>';
        $output .= '<ul>';
        foreach($drJson_arr['result']['result'] as $sp) {
            $output .= '<li><a href="/healthcare-professionals/specialty-categories/'.$sp['forever_specialty_id'].'">'.$plugin_public->ECHPL_echolang([ $sp['en_name'], $sp['tc_name'], $sp['cn_name'] ]).'</a></li>';
        }
        $output .= '</ul>';
    $output .= '</div>'; // drs_spec_container


    $output .= '<div class="vet_spec_container" id="mega_menu_vet_spec">';
        $output .= '<div class="spec_title">Vet Specialties</div>';
        $output .= '<ul>';
        foreach($vetJson_arr['result']['result'] as $sp) {
            $output .= '<li><a href="/healthcare-professionals/specialty-categories/'.$sp['forever_specialty_id'].'">'.$plugin_public->ECHPL_echolang([ $sp['en_name'], $sp['tc_name'], $sp['cn_name'] ]).'</a></li>';
        }
        $output .= '</ul>';
    $output .= '</div>'; // vet_spec_container

$output .= '</div>'; //mega_menu_brands_container


echo $output;

?>



<script>
    jQuery(function(){
        jQuery('#mega-menu-hover-drs').hover(function(){
            jQuery('#mega_menu_drs_spec').css('display', 'block');
            jQuery('#mega_menu_vet_spec').css('display', 'none');
        });

        jQuery('#mega-menu-hover-vet').hover(function(){
            jQuery('#mega_menu_vet_spec').css('display', 'block');
            jQuery('#mega_menu_drs_spec').css('display', 'none');
        });
    });

</script>
```

4. Head to Elementor Templates and create a doctor mega menu 
5. Copy and paste the mega-menu-dr shortcode from XYZ PHP Code into the doctor mega menu template
6. Head to "Appearance > Menus", add a "Custom Links" (URL: #, Link Text: Healthcare Professionals) 
7. Click the "Mega Menu" button in the Healthcare Professionals Custom Links 
8. Setup the Mega Menu for Doctors. Sub menu display mode: Mega Menu - Standard Layout
