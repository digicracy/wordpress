function auto_complete(input)
{
	bits = input.replace(/\\ /g, " ").replace(get_pre(),"").replace(/"/g, "").split(" ")
	fn   = bits[0]
	args = trim(bits.length >= 2 ? bits.slice(1).join(" ") : '')

	if( fn.length == 0 ) return input

	// command or file?
	fna = false
	if( bits.length == 1 )
	{
		fna = true
		results = auto_complete_command( fn )
	}
	else results = auto_complete_file( args )

	// if double-tap show long results list
	if(last_key == 9 && results.length > 1 ) // multiple matches, show them nicely
	{
		if( input.substr(-1) == '"' ) input = input.substr(0,input.length - 1)
		last_key = 0
		output(input)
		output(results.join("\n").replace(/ /g, "&nbsp;"))
		return input
	}
	// else if only single result, set text to this
	else if( results.length == 1 )
	{
		if( results[0].match(" ") ) results[0] = '"'+results[0]+'"'
		return get_pre() + (fna ? '' : (fn + " ")) + results[0] // only 1 match, output it
	}
	// else if multiple results without double-tab, show longest common preceding substring
	else if(results.length > 1)
	{
		// try find a common pre-string...
		function c(a,b)
		{
			out = ""
			j = 0
			while( j < Math.min(a.length,b.length) )
				if( a.substr(j,1) == b.substr(j,1) )
					out += a.substr(j++,1)
				else break;
			return out
		}
		lcs = results[0]
		for( var i = 1; i < results.length; i++ ) lcs = c(lcs, results[i]);				
		if( lcs.match(" ") ) lcs = '"'+lcs

		return get_pre() + (fna ? '' : (fn + " ")) + lcs
	}
	// otherwise there are no matches
	else
	{
		return input // no matches, or multiples without pre-tab, do nothing
	}
}

// loop through commands, return ^matches
function auto_complete_command(input)
{
	results = []
	r = new RegExp("^" + input, "gi" )
	for( var i in commands )
		if( commands[i].match(r) )
			results.push(commands[i])

	// console.log("command", results)
	return results
}

// loop through files, accounting for dname's
function auto_complete_file(input)
{
	results = []
	files = get_dir_contents(path)
	r = new RegExp("^" + input, "gi" )

	for( var i in files )
	{
		if( i == "type" || i == "dname" ) continue;
		if( typeof(files[i].dname) != "undefined" && files[i].dname.match(r) )
			results.push(files[i].dname)
		else if( i.match(r) )
			results.push(i)
	}
	return results
}