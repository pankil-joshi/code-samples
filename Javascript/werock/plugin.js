$('span.chunks').css("visibility","hidden").stop().animate({"opacity":"0"},2000);
(function myLoop (i) {          
   setTimeout(function () {   
      $('span[data-id='+i+']').css("visibility","visible").stop().animate({"opacity":"1"},200);            
      if (--i) myLoop(i);     
   }, 1400)
})(string.length);
for (var i = 0; i < string.length; i++) {
	string[i]
};

$('span.chunks').hover(function(){
		

     var active_id = $(this).data('id');  
		function search(id){
    for (var i=0; i < string.length; i++) {
        if (string[i].id == id) {
            return string[i].text;
        }
    }
}
$('.word').css('cursor','none');
$('<p class="tooltip"></p>')
        .text(search(active_id))
        .appendTo('body');
        setTimeout(animate_tool_tip, 100);
function animate_tool_tip() {
        $('.tooltip').stop().addClass('hover');
      }
	},
function() {
	$(this).stop().animate({"opacity":"1"},1400);
        // Hover out code
        $('.tooltip').remove();
}).mousemove(function(e) {
        var mousex = e.pageX-160;
        var mousey = e.pageY;
        $('.tooltip')
        .css({ top: mousey, left: mousex })
}
);
$('.chunks').on('click',function() {
  $('.tooltip').remove();
$('.outer').addClass('clicked');
$('.overlay').addClass('clicked');
$('.pop_up').addClass('clicked');
var active_id = $(this).data('id');  
var text = [];
var name = [];
		function search(id){
    for (var i=0; i < string.length; i++) {
        if (string[i].id == id) {
            text = string[i].text;
            name = string[i].name;
        }
    }
}
search(active_id);

$('.overlay').after('<div class="pop_up"><div class="pop_up_content"><span class="name">'+name+'</span><br /><span class="pop_up_text">'+text+'</span></div></div>');
setTimeout(animate_pop_up, 200);
function animate_pop_up() {
$('.pop_up_content').addClass('clicked');
setTimeout(animate_name, 1000);
function animate_name () {
	$(".name").css("left", function(){
    return ($(".pop_up_content").width() - $(this).width()) / 2;
});
	$('.name').addClass('clicked');
}
}
});
$('.overlay').on('click',function() {
$('.outer').removeClass('clicked');
$('.overlay').removeClass('clicked');
$('.pop_up').remove();


});
/*
var new_number = 0
setInterval(hide_chunk, 3000);
function hide_chunk(){
	new_number = random_number(0,6);
$('span[data-id='+new_number+']').stop().animate({"opacity":"0"},2000);
setTimeout(show_chunk, 2000);
function show_chunk(){
	$('span[data-id='+new_number+']').stop().animate({"opacity":"1"},2000);
}
}
*/
function random_number(min,max) {

    return (Math.round((max-min) * Math.random() + min));
}
var yPrev = 0;
    jQuery(document).ready(function(){

       $(document).mousemove(function(e){
         yPrev>e.pageY ? $('.word').css({"margin-top":"20px","margin-bottom":"0px"}) : $('.word').css({"margin-top":"0px","margin-bottom":"20px"});
           yPrev=e.pageY;
           
       }); 
    })
   