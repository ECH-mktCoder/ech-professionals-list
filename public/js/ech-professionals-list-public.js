(function( $ ) {
	'use strict';

	$(function () {
		$(".echdr_filter_dropdown_checkbox").click(function () {
			$(this).toggleClass("echdr_ddc_is_active");
		});
		
	
		$(".echdr_filter_dropdown_checkbox ul").click(function(e) {
			e.stopPropagation();
		});

		// Change specialty options when clicked dr type
		$('.ech_dr_filter_container .filter_drType').on('change', function(){
			var typeID = $(this).val();
			ECHDr_updateSpecOptions(typeID);
		});

		// filter submit button
		$('.ech_dr_filter_container .dr_filter_btn').click(function(){
		  $(".echdr_filter_dropdown_checkbox").removeClass("echdr_ddc_is_active");
		  ECHDr_getFilteredDr();
		});


		$(document).on('click',function(e){
			// hide region filter dropdown checkbox when clicked outside of the dropdown checkbox area
			if($(e.target).is(".echdr_filter_dropdown_checkbox, .echdr_filter_dropdown_checkbox ul") === false) {
				$(".echdr_filter_dropdown_checkbox").removeClass("echdr_ddc_is_active");
			}        
		});
	  
	}); // ready
	 

})( jQuery );







/*************************************************
 * Load More Post JS
 *************************************************/
function ECHDr_load_more_dr(topage) {
	jQuery(".ech_dr_container .loading_div").css("display", "block");
	jQuery(".all_drs_container").html("");
  
  
	var ppp = jQuery(".all_drs_container").data("ppp");  

	var toPage = topage;
  
	var filterBrand = jQuery(".all_drs_container").data("brandid");
	var filterChannel = jQuery(".all_drs_container").data("channel");
	var filterRegion = jQuery(".all_drs_container").data("region");
	var filterSp = jQuery(".all_drs_container").data("specialty");
	var filterDrType = jQuery(".all_drs_container").data("drtype");
	
	var ajaxurl = jQuery(".ech_dr_pagination").data("ajaxurl");
  
	

	jQuery.ajax({
	  url: ajaxurl,
	  type: "post",
	  data: {
		ppp: ppp,
		toPage: toPage,
		filterChannel: filterChannel,
		filterBrand: filterBrand,
		filterRegion: filterRegion,
		filterSp: filterSp,
		filterDrType: filterDrType,
		action: "ECHPL_load_more_dr",
	  },
	  success: function (res) {
		var jsonObj = JSON.parse(res);
		//console.log(jsonObj);
		jQuery(".all_drs_container").html(jsonObj.html);      
		jQuery(".ech_dr_container .loading_div").css("display", "none");
		jQuery('html, body').animate({ scrollTop: jQuery('.echdr_page_anchor').offset().top }, 0);
	  },
	  error: function (res) {
		console.error(res);
	  },
	});
  }
  
  
  
  
  /*************************************************
   * Get filtered dr posts
   *************************************************/
  function ECHDr_getFilteredDr() {
	// get filter value
	var filter_region_arr = [];  
	var filter_spec = jQuery(".filter_spec").val();
	var filter_drType = jQuery(".filter_drType").val();
	var ppp = jQuery(".all_drs_container").data("ppp");
	
  
	jQuery.each(jQuery(".filter_regions_container input[name='region']:checked"), function(){
	  filter_region_arr.push(jQuery(this).val());
	});
  
	var filter_region = filter_region_arr.toString();
	//console.log('region: '+ filter_region + ' | spec: ' + filter_spec + ' | ppp: ' + ppp);
  
  
	// clean DOM 
	jQuery(".ech_dr_container .loading_div").css("display", "block");
	jQuery(".all_drs_container").html("");
	jQuery(".ech_dr_pagination").html("");
  
	// ajax
	var ajaxurl = jQuery(".ech_dr_pagination").data("ajaxurl");
  
	
	jQuery.ajax({
	  url: ajaxurl,
	  type: "post",
	  data: {
		ppp: ppp,
		filter_spec: filter_spec,
		filter_region: filter_region,
		filter_drType: filter_drType,
		action: "ECHPL_filter_dr_list",
	  },
	  success: function (res) {
		var jsonObj = JSON.parse(res);

		//console.log('max page: ' + jsonObj.max_page);
		jQuery(".all_drs_container").html(jsonObj.html);
		jQuery(".ech_dr_container .loading_div").css("display", "none");
  
		jQuery(".all_drs_container").data("region", filter_region);
		jQuery(".all_drs_container").attr("data-region", filter_region);
  
		jQuery(".all_drs_container").data("specialty", filter_spec);
		jQuery(".all_drs_container").attr("data-specialty", filter_spec);

		jQuery(".all_drs_container").data("drtype", filter_drType);
		jQuery(".all_drs_container").attr("data-drtype", filter_drType);

		if(jsonObj.max_page > 1) {
		  jQuery(".ech_dr_pagination").attr("data-max-page", jsonObj.max_page);     
		  jQuery(".ech_dr_pagination").data("max-page", jsonObj.max_page);  
		  ECHDr_paginationGenerate(1);
		  jQuery(".ech_dr_pagination").css("display", "block");
  
		} else {
		  jQuery(".ech_dr_pagination").css("display", "none");
		}
	  },
	  error: function (res) {
		console.error(res);
	  },
	});
  }




  function ECHDr_updateSpecOptions(typeID) {
	// ajax
	var ajaxurl = jQuery(".ech_dr_pagination").data("ajaxurl");
	jQuery(".filter_spec").html('<option value="" disabled selected> loading </option>');
	jQuery.ajax({
		url: ajaxurl,
		type: "post",
		data: {
			filter_drType: typeID,
			action: "ECHPL_update_spec_options",
		},
		success: function (res) {
			jQuery(".filter_spec").html(res);
		}
	});
  }