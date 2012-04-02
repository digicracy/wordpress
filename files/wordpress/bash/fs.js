
function get_pre()
{
	return pre + path.join("/") + "$ "
}



function execute(input)
{
	if( input_blocked === true )
		return "\n"

	// replace escaped spaces with %20 in input
	bits = input.replace(/\\ /g, " ").replace(get_pre(),"").replace(/"/g, "").split(" ")

	// no bits, do SFA
	if( bits[0].length == 0 )
		return;

	// is it a valid command?
	valid = false
	for( var i in commands ) if( commands[i] == bits[0] ) valid = true
	if( !valid ) return "bash: " + bits[0] + ": command not found"

	fn = "bash_" + bits[0]
	args = bits.length >= 2 ? bits.slice(1).join(" ") : '' 

	if( trim(args) == "--help" ) { args = fn; fn = "bash_help" }

	// console.log( "exec", fn, args, eval(fn))

	out = eval(fn+ '(args)')
	
	output(input)
	if(out) output(out)

	if( hist[hist.length - 1] != trim(fn.substr(5)+" "+args) )
		hist.push(trim(fn.substr(5)+" "+args))
	hpos = hist.length
}

function output(input)
{
	$("#scrollback").html( $("#scrollback").html() + "\n" + trim(input) )

	// scroll to bottom on all output, delayed by 50ms to allow DOM update
	f = function()
	{
		$("#wrap").scrollTop($("#scrollback").height() + 500)
		$(document).mousewheel()
	}
	setTimeout(f,50)
}

function trim(input)
{
		return $.trim(input)
}



function get_dir_contents(tp)
{
	if( tp.length == 0 )
	{
		list = {type: 'folder'}
		$.each(dirs, function(i) { 
			list[i] = {type: dirs[i].type, dname: i, cb: this}
		})
		return list
	}

	list = dirs

	for( var i = 0; i < tp.length; i++ )
	{
		if(typeof(list[tp[i]]) == "undefined")
		{	
			found = false
			$.each(list, function(j) {
				if(this.dname == tp[i] && found === false)
				{
					found = true
					list = list[j]
					nm = this.dname
				}
			})
			if( found === false ) return false;
		}
		else
		{
			list = list[tp[i]]
			nm = tp[i]
		}
		olx = ( typeof(list.extra) == "undefined" ) ? false : list.extra;

		if(typeof(list.json) != "undefined") list = get_data(list, nm)
		if( olx ) $.each(olx, function(i) { list[i] = this })
	}

	if( typeof(list) == "undefined" )
		return false;
				
	return list
}

function is_directory(tp)
{
	list = get_dir_contents(tp)

	console.log( "is_directory", tp, list )

	return (list && (list.type == 'folder' || list.type == 'link')) ? list : false
}

function is_file(tp)
{
	list = get_dir_contents(tp)

	// console.log("is_file", tp, list)

	return (list && list.type == 'file') ? list : false
}