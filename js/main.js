$(function () {
		
			//$('div').addClass('showBorder');
			$( "button" ).button();
			
			$('#header div#navigation ul li').bind('click',function (event) {
				//$(this).html("selected");
				$(this).addClass("current");
				//alert($(this).text());
				event.stopPropagation();
				return true;
			});
			
			$("#accordion").accordion();
})	