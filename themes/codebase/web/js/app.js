$(function () {

    $(document).on('click','.block-minimize-btn',function(){
        if($(this).hasClass('extended')){
            $(this).parents('.block').find('.block-content').stop().slideUp();
            $(this).removeClass('extended');
            $(this).find('i').addClass('si-arrow-down').removeClass('si-arrow-up');
        }else{
            $(this).parents('.block').find('.block-content').stop().slideDown();
            $(this).addClass('extended');
            $(this).find('i').addClass('si-arrow-up').removeClass('si-arrow-down');
        }
    });
   
});
