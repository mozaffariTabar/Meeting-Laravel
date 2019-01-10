$(document).ready(function(){
    $(".profile img").click(function(){
        $(this).siblings("input[type=file]").click();
    });
    $(".profile input[type=file]").change(function(){
        $(this).closest("form").submit();
    });
    $("#meeting_list").change(function(){
        changeGuestTable();
    });
    changeGuestTable();
});
function changeGuestTable() {
    $("#guests_table tr").hide().filter('[meetId='+$("#meeting_list").val()+']').show();
}