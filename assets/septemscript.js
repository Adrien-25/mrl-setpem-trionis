window.addEventListener("load", function(){
    let tabs = document.querySelectorAll('ul.nav-tabs > li');

    for(i=0; i < tabs.length; i++){
        tabs[i].addEventListener("click", switchTab);
    }

    function switchTab(event){
        event.preventDefault();

        document.querySelector("ul.nav-tabs li.active").classList.remove("active");
        document.querySelector(".tab-pane.active").classList.remove("active");

        let clickedTab = event.currentTarget;
        let anchor = event.target;
        let activePaneID = anchor.getAttribute("href");

        clickedTab.classList.add("active");
        document.querySelector(activePaneID).classList.add("active");
    }


    
    // load_data();    
    // function load_data(page){
    //     let xhr = new XMLHttpRequest();

    //     xhr.open('POST', my_ajax_object.ajax_url, true);

    //     xhr.onload = function(){
    //         if(this.status == 200){
    //             let data = this.responseText;
    //             document.querySelector('.septem-container').innerHTML = data;
    //         }
    //     }

    //     xhr.send("page=" + page);
    // }
    

    // let paginationButtons = document.getElementsByClassName('pagination_link');
    // console.log(paginationButtons);
    // for(let j = 0; j < paginationButtons.length; j++){
    //     console.log(paginationButtons.item(j));
    //     paginationButtons[j].addEventListener('click', (e) => {
    //         console.log('ef');
    //         var page = e.target.getAttribute("id");
    //         load_data(page);
    //     })
    // }
    
});
    
jQuery(document).ready( function($) {

    load_data(1);
    function load_data(page, query = ''){
        $.ajax({
            url: my_ajax_object.ajax_url,
            method:"POST",
            data: {page:page, query:query},
            success: function (data) {
                $('#septem-container').html(data);
            }
        });
    }

    $(document).on('click', '.page-link', function(event){
        var page = $(this).data('page_number');
        var query = $('#auditeurSearch').val();
        if($(event.target).attr('class') == "page-link active"){
            return;
        };
        if($(event.target).attr('class') == "page-link dot"){
            return;
        };
        load_data(page, query);
    });

    $('#auditeurSearch').keyup(function(){
        var query = $('#auditeurSearch').val();
        load_data(1, query);
    });
});