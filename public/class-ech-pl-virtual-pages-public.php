<?php



class Ech_PL_Virtual_Pages_Public {

    public static function dr_profile_output() {

		$therapistid = get_query_var('therapistid', 'none');

		if ( $therapistid === 'none' ) { echo '<script>window.location.replace("/healthcare-professionals");</script>'; }

		include('partials/ech-pl-single-profess-view.php');

	}  //--end dr_profile_output()


    public function dr_category_list_output() {
        $specialtyid = get_query_var('specialtyid', 'none');

		if ( $specialtyid === 'none' ) { echo '<script>window.location.replace("/healthcare-professionals");</script>'; }
		
		include('partials/ech-pl-category-list-view.php');

    } //dr_category_list_output

} // class