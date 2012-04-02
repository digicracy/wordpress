var ht = []
var htp = 1

var hspeed = 800
var hinter = 7000

$( function()
{	
	bind_links()
	set_title()
	set_font()

	hash = window.location.hash
	anchor = $("#nav a[href="+hash+"]")

	if(hash.length > 0 && anchor.length == 1)
		anchor.click()

	$("#body #header h1 span").each( function() {	ht.push($(this).text())	})
	$("#body #header h1 span:gt(0)").remove()
	setInterval( show_next_ht, hinter );
})

function set_font()
{
	n = navigator.userAgent
	if( document.location.search.match('ubuntu') || $.browser.msie || n.match("iPhone") || n.match("iPod") || n.match("iPad") )
	{
		$("#body").css("font-family", "Ubuntu, Verdana, Sans, sans-serif").css("font-size","0.9em")
	}
}

function set_title()
{
	ti = $(".title")
	
	if(ti.length)
	{
		$("title").text(ti.text())
		document.title = ti.text()
	}
}

function show_next_ht()
{
	if( htp == ht.length )
		htp = 0

	next = ht[htp++]

	$("#body #header h1").append("<span>"+next+"</span>").animate({"margin-left": "-940px"}, hspeed, function()
	{
		$("#body #header h1").css("margin-left", "0px").find("span:first").remove()
	})
}

function bind_links()
{
	$("#nav a, .subnav a").unbind("click").bind("click",function()
	{
		$(this).blur()
		url = $(this).attr("href").replace("#","")

		$("#nav .active").removeClass("active")
		$("#nav a[href=#"+url+"]").parent().addClass("active")
		
		$.get(url + "&ajax", function(data)
		{
			$("#body > *:gt(1)").remove()
			$(data).appendTo("#body")

			bind_links()
			set_title()
			scroll_to_top()
			set_font()
		})
	}).attr("href", function(i,h) { return ("#"+h).replace(/#+/,"#")  })
}

function scroll_to_top()
{
	if( typeof( window.pageYOffset ) == 'number' ) {
		//Netscape compliant
		y = window.pageYOffset;
		x = window.pageXOffset;
	} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
		//DOM compliant
		y = document.body.scrollTop;
		x = document.body.scrollLeft;
	} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
		//IE6 standards compliant mode
		y = document.documentElement.scrollTop;
		x = document.documentElement.scrollLeft;
	}

	c = 200
	t = $("#nav").position().top

	console.log(t)

	y = Math.max(t, y)

	if(y <= t) return;

	window.scrollTo(x, Math.max( t,  y - c))
	setTimeout(scroll_to_top, 25)
}