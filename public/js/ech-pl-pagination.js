var drPageInitial = true;



jQuery(document).ready(function(){
    var currentPage = jQuery(".ech_dr_pagination").data("current-page");
    ECHDr_paginationGenerate(currentPage);

});





function ECHDr_paginationGenerate(page){

    var maxPage = jQuery(".ech_dr_pagination").data("max-page");
    var pagination = '<ul><li><a href="#" onclick="ECHDr_paginationGenerate(ECHDr_checkPrevious(' + page + ')) ">&laquo;</a></li>';

    /* Page Range Calculation */
    var range = ECHDr_pageRange(page, maxPage);
    var start = range.start;
    var end = range.end;
    //console.log('start: '+start + ' | end: ' + end);


    for (var page_id = start; page_id <= end; page_id++) {
        if (page_id != page) pagination += '<li><a href="#" onclick="ECHDr_paginationGenerate(' + page_id + ')">' + page_id + '</a></li>';

        else pagination += '<li class="active"><span>' + page_id + '</span></li>';
    }

    pagination += '<li><a href="#" onclick="ECHDr_paginationGenerate(ECHDr_checkNext(' + page + ',' + maxPage + '))">&raquo;</a></li></ul>';



    if(!drPageInitial) {        
        ECHDr_load_more_dr(page);
    }

    

    /* Appending Pagination */
    jQuery('.ech_dr_pagination ul').remove();
    jQuery('.ech_dr_pagination').append(pagination);


    // change data-current-page value
    jQuery(".ech_dr_pagination").data("current-page", page);
    jQuery(".ech_dr_pagination").attr("data-current-page", page);


    drPageInitial = false;
}









/* Pagination Navigation */

function ECHDr_checkPrevious(id) {
    if (id > 1) {
        return (id - 1);
    }
    return 1;
}



/* Pagination Navigation */

function ECHDr_checkNext(id, pageCount) {
    if (id < pageCount) {
        return (id + 1);
    }
    return id;
}



/* Page Range calculation Method for Pagination */

function ECHDr_pageRange(page, pageCount) {

    var start = page - 2,
        end = page + 2;

    if (end > pageCount) {
        start -= (end - pageCount);
        end = pageCount;
    }

    if (start <= 0) {
        end += ((start - 1) * (-1));
        start = 1;
    }

    end = end > pageCount ? pageCount : end;

    return {
        start: start,
        end: end
    };
}