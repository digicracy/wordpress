		url = "http://www.omgubuntu.co.uk/api/"
		pre = "april@omg-ubuntu:~/"
		last_key = ""
		dragging = false
		commands = ['help','cat','man','ls','logout','cd','open','rm','moo','clear', 'vim','emacs', 'wget','fortune']
		helptext = {}
		helptext.cat  = 'List contents of a file'
		helptext.cd   = 'Change directory'
		helptext.clear= 'Clears the terminal screen'
		helptext.fortune = 'Print a random, hopefully interesting, adage'
		helptext.help = 'Get help on a command'
		helptext.logout = '<b style="color:#cdf">Reset the X11 server (return to regular OMG!Ubuntu! site)</b>'
		helptext.ls   = 'List files in the current directory'
		helptext['moo'] = 'Get your daily dose of milk'
		helptext.open = 'Open the passed file'
		helptext.rm   = 'Removes files or directories'
		helptext.wget = 'GNU Wget 1.12, a non-interactive network retriever'

		input_blocked = false

		path = []
		hist = []
		hpos = 0
		cache = {}

		dirs = {}
		dirs.articles =   {type: 'folder', dname: 'articles', cb: 'parse_dates',      json:'get_date_index', extra: [{type: 'folder', dname: 'latest', cb: 'parse_posts', json:'get_recent_posts', include:'slug,title,id,date'}] }
		dirs.categories = {type: 'folder', dname: 'categories', cb: 'parse_categories', json: 'get_category_index'}
		dirs.tags =       {type: 'folder', dname: 'tags', cb: 'parse_tags',       json: 'get_tag_index'}
		dirs.authors =    {type: 'folder', dname: 'authors', cb: 'parse_authors',    json: 'get_author_index'}

		dirs['about.txt'] = {type: 'file',dname: 'about.txt', content: 'This shell serves as a fallback for when OMG!Ubuntu!\'s graphics driver fails miserably - if you are seeing this something has gone horribly wrong.\n\n<a href="http://bit.ly/ddNy6G">This link may be able to help you</a>\n\n<span style="color:#666;font-size: 7pt">(just kidding, type "logout" to go back to the normal site)</span>\n' }

		$(function() {
			// failure anims
			a_t = {}
			a_t[5] = function() { $(".failure.x").show() }
			a_t[10] = function() { $(".failure.x").hide() }
			a_t[11] = function() { $(".failure.purple, .failure.x").show() }
			a_t[15] = function() { $(".failure.x").hide() }
			a_t[40] = function() { $(".failure.x").show().css("bottom","300px") }
			a_t[50] = function() { $(".failure.x").show().css("bottom","0px").css("top","300px") }

			a_t[100] = function() { $(".failure").hide(); $(".failure.purple").show().css("background", "#111") }

			lin = "X.Org X Server 1.9.0\nRelease Date: 2010-08-20\n[    13.797] X Protocol Version 11, Revision 0\n[    13.797] Build Operating System: Linux 2.6.24-28-server x86_64 Ubuntu\n[    13.797] Current Operating System: Linux rich-maverick 2.6.35-28-generic #49-Ubuntu SMP Tue Mar 1 14:39:03 UTC 2011 x86_64\n[    13.797] Kernel command line: BOOT_IMAGE=/boot/vmlinuz-2.6.35-28-generic root=UUID=e0cb12cb-f880-40a0-9144-fcc2de54bed8 ro quiet splash\n[    13.797] Build Date: 09 January 2011  12:14:27PM\n[    13.797] xorg-server 2:1.9.0-0ubuntu7.3 (For technical support please see http://www.ubuntu.com/support) \n[    13.797] Current version of pixman: 0.18.4\n[    13.797] 	Before reporting problems, check http://wiki.x.org\n	to make sure that you have the latest version.\n[    13.797] Markers: (--) probed, (**) from config file, (==) default setting,\n	(++) from command line, (!!) notice, (II) informational,\n	(WW) warning, (EE) error, (NI) not implemented, (??) unknown.\n[    13.798] (--) PCI: (0:2:0:0) 10de:0849:1043:82f2 rev 162, Mem @ 0xf9000000/16777216, 0xc8000000/134217728, 0xc6000000/33554432, I/O @ 0x0000dc00/128, BIOS @ 0x????????/131072\n[    13.798] (--) PCI:*(0:3:0:0) 10de:05e2:1043:8301 rev 161, Mem @ 0xfd000000/16777216, 0xd0000000/268435456, 0xfa000000/33554432, I/O @ 0x0000ec00/128, BIOS @ 0x????????/524288\n\nDaisy, Daisy, give me your answer true.\nI'm half-crazy all for the love of you..".split("\n")

			ati = 100
			$.each(lin, function(i)
			{
				ati = ati + 3
				a_t[ati] = function() { $(".failure.purple").append(lin[i]+"<br/>") }
			})

			a_t[ati + 40] = function() { $(".failure.purple").empty() }
			a_t[ati + 50] = function() { $(".failure").remove() }

			$.each( a_t, function(timer)
			{
				setTimeout( this, 3000 + (timer * 10) )
			})


			// force focuses
			f = function() { document.getElementById('input').focus().click() }
			setInterval(f,50)

			// handle wheel scrolling
			$(document).bind("mousewheel", function(e)
			{
				f = function()
				{
					oh = $("#wrap").height() + 25
					ih = Math.max( oh, $("#scrollback").innerHeight() )
					dh = ih - oh
					ip = $("#wrap").scrollTop()

					scroll_height = Math.round(oh * oh / ih)
					scroll_offset = (ip - 50) / dh
					scroll_offset = Math.round( (oh - scroll_height - 60) * scroll_offset )

					if( dh == 0 || isNaN(scroll_offset) )
						return $("#scrollbars").css("opacity",0).hide()
					else
						$("#scrollbars:hidden").css("opacity",0).show()

					$("#scrollbars").css({height: scroll_height + "px", top: Math.max(scroll_offset + 30) + "px" })
				}
				setTimeout(f,10)
			}).mousewheel()

			// handle dragging on the scrollbar
			$("#scrollbars").mousedown( function(e)
			{
				offset = e.offsetY ? e.offsetY : (e.pageY ? e.pageY - $(this).offsetTop : false)

				dragging = offset

				return false		
			})

			$(document).mouseup( function(e)
			{
				dragging = false
				return false
			}).mousemove( function(e)
			{
				right = Math.abs( $("body").width() - e.pageX ) - 200

				right = Math.max(0, 0 - right)
				op = right / 200
				$("#scrollbars").css("opacity", op)

				if( typeof(dragging) != "undefined" && dragging !== false )
				{
					p = e.pageY - dragging
					
					oh = $("#wrap").height()
					ih = $("#scrollback").innerHeight()
					dh = ih - oh

					sh = $("#scrollbars").height()
					mx = oh - sh

					// i have no idea where the need for this 400px came from...
					ratio = ih / (mx + 400)

					$("#wrap").scrollTop( Math.round(ratio * (p-15) ))

					if( p < 5 ) p = 5
					if( p > mx - 5) p = mx - 5

				
					$("#scrollbars").css({top: p})
					
				}
			})

			// handle input cases
			$("#input").keydown( function( e )
			{
				// get the keycode
				key = (typeof(e.keyCode) != "undefined" ? e.keyCode : (typeof(e.which) != "undefined" ? e.which : e.charCode))

				enter = 13
				tab = 9
				up = 38
				down = 40
				left = 37

				// catch tabs, do autocompletion run
				if( key == tab )
				{	
					$(this).val( auto_complete($(this).val()) )
					last_key = tab
					return false
				}
				// execute on enter
				else if( key == enter )
				{
					execute( $(this).val() )
					$(this).val(get_pre())

					// scroll to bottom on all output, delayed by 50ms to allow DOM update
					f = function()
					{
						$("#wrap").scrollTop($("#scrollback").height() + 500)
						$(document).mousewheel()
					}
					setTimeout(f,50)
				}
				// move up in history, if possible
				else if( key == up )
				{
					if( hist.length == 0 ) return false;

					if( hpos >= 1 ) hpos--
					$(this).val( get_pre() + hist[hpos] )

					return false
				}
				// move down in history if possible or clear input
				else if( key == down )
				{
					if( hpos < hist.length ) hpos++
					
					if( hpos == hist.length ) $(this).val( get_pre() )
					else $(this).val( get_pre() + hist[hpos] )
					
					return false
				}
				// move left in input, but not past the preamble length
				else if( key == left )
				{
					o = $(this)[0]
					if(o.createTextRange)
					{
						r = document.selection.createRange().duplicate()
						r.moveEnd('character', o.value.length)
						if(r.text == '') pos = o.value.length
						else pos = o.value.lastInfexOf(r.text)
					}
					else pos = o.selectionStart

					if( pos <= get_pre().length ) return false
				}

				// make sure the preamble is shown, regardless of inputs
				if($(this).val().length < get_pre().length + 1)
					$(this).val(get_pre())


				last_key = key
			}).val(get_pre())

			// resize boxes so the h-scroll bar doesn't show
			$(window).resize( function() { $("#input, #scrollback").css( "width", ($("body").width() - 40) + "px" ) }).resize()
			
		})


		function handle_click(obj)
		{
			mat = obj.match(/\.uk\/(20[01][0-9])\/([0-9]+)\/(.+?)\//)

			r = function() { window.location = obj; return false; }

			if(!mat) return r();
			dir = ["articles",mat[1],mat[2]]
			files = get_dir_contents(dir)

			if(!files) return r();

			found = false;
			$.each(files, function() {
				if(trim(this.url) == trim(mat[3]))
				{
					found = true
					post = this.dname
				}
			})

			if(!found) return r();

			// console.log( "cd + cat", dir.join("/"), post)

			path = []
			execute( get_pre() + "cd " + dir.join("/") )
			execute( get_pre() + "cat " + post )
			$("#input").val(get_pre())
		}