$(document).ready(function(){
    load_data();
    function load_data(search){
        $.ajax({
            url:"./search/ajaxLiveSearch.php",
            method:"POST",
            data:{query: search},
            success:function(data){
                    $('#txtDisplay').html(data);
            }
        });
    }
    $('#txtSearch').keyup(function(){
        var search = $(this).val();
        if(search !== ''){
                load_data(search);
        }
        else{
                load_data();
        }
    });
});