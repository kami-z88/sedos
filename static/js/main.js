// send-message.php --------------------------------------------------------
$(document).ready(function(){ 
    $('#characterLeft').text('140 characters left');
    $('#message').keyup(function () {
        var max = 140;
        var len = $(this).val().length;
        if (len >= max) {
            $('#characterLeft').text('You have reached the limit');
            $('#characterLeft').addClass('red');
            $('#btnSubmit').addClass('disabled');            
        } 
        else {
            var ch = max - len;
            $('#characterLeft').text(ch + ' characters left');
            $('#btnSubmit').removeClass('disabled');
            $('#characterLeft').removeClass('red');            
        }
    });    
});

$("textarea#message").keydown(function(){
    $(".alert").remove();
});



$(document).ready(function () {
    if($('.tree')){
        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
        $('.tree li.parent_li > span').on('click', function (e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(":visible")) {
                children.hide('fast');
                $(this).attr('title', 'Expand this branch').find(' > i').addClass('fa-plus').removeClass('fa-minus');
            } else {
                children.show('fast');
                $(this).attr('title', 'Collapse this branch').find(' > i').addClass('fa-minus').removeClass('fa-plus');
            }
            e.stopPropagation();
        });
        $('.tree li.parent_li > span').click();
    }
});

$('.btn-kickout-user').click(function(){
    var uid = $(this).val();
    $('#uid-to-kick').val(uid);
    $('input[type="checkbox"]').prop('checked', false);
});

$('.btn-remove-invitation').click(function(){
    var phone = $(this).val();
    $('#remove-invitation-phone').val(phone);
});
$('.btn-unblock-member').click(function(){
    var uid = $(this).val();
    $('#unblock-user-id').val(uid);
});


