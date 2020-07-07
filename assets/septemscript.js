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
    
});
    
jQuery(document).ready( function($) {
    let updateValue;

    load_data(1);
    function load_data(page, query = '', update_value = '', update_id = '', actual_value = ''){
        $.ajax({
            url: my_ajax_object.ajax_url,
            method:"POST",
            //data = ce qu'on peut récupérer en $_POST
            data: {page:page, query:query, update_value:update_value, update_id:update_id, actual_value:actual_value},
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

    /*On récupère les lettres tapé dans la barre de recherche et on les envoie en $_POST
    pour pouvoir les récupérer avec PHP et voir si ça match avec certians élement de la base de donnés*/
    $('#auditeurSearch').keyup(function(){
        var query = $('#auditeurSearch').val();
        load_data(1, query);
    });

    //Ajoute/supprime les points de septem
    $(document).on('click', '.js_control', function(e){
        let classes = $(e.target).attr('class');
        let classe = classes.split(' ');
        let updateId = classe[1];
        let control = classe[0];
        let hebdoArray = $('.septem-hebdo');
        let page = $('.js_active').data('active_page');
        let actualValue;
        console.log(hebdoArray);
        hebdoArray.each(function(){
            if(this.id == updateId){
                actualValue = this.value;
                if(control === 'plus'){
                    updateValue = ++this.value;
                } else if(control === 'minus' && actualValue != 0){
                    updateValue = --this.value;
                }
                load_data(page, '', updateValue, updateId, actualValue );
            }
        });
        
    });
});