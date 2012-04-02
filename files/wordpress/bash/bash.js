function bash_fortune()
{
	f = []
	f.push("Half of writing history is hiding the truth.")
	f.push("Don't pay anybody in advance. And don't ride in anything with a Capissen 38 engine, they fall right out of the sky.")
	f.push("Love. You can learn all the math in the 'Verse, but you take a boat in the air that you don't love, she'll shake you off just as sure as a turn of the worlds. Love keeps her in the air when she oughta fall down, tells you she's hurtin' before she keels. Makes her a home.")
	f.push("There's a reason you separate military and the police. One fights the enemies of the state, the other serves and protects the people. When the military becomes both, then the enemies of the state tend to become the people.")
	f.push("Do what comes naturally.  Seethe and fume and throw a tantrum.")
	f.push("You are confused; but this is your normal state.")
	f.push("Intelligence is the ability to avoid doing work, yet getting the work done.")
	f.push("The Linux philosophy is 'Laugh in the face of danger'. Oops. Wrong One. 'Do it yourself'. Yes, that's it.")
	f.push("Vision is the art of seeing what is invisible to others.")
	f.push("We must all obey the great law of change. It is the most powerful law of nature.")

	return f[ Math.floor(f.length * Math.random()) ]
}

function bash_man(c)
{
	return bash_help(c)
}

function bash_help(c)
{
	if( c.length )
		if(typeof(helptext[c]) != "undefined")
			return c + "\t" + helptext[c]
		else
			return "bash: help: no help topics match `"+c+"`. Try `help` to see available commands"
	ret = [ "This shell serves as a fallback for when OMG!Ubuntu!\'s graphics driver fails miserably - if you are seeing this something has gone horribly wrong.\n" ]
	for( var i in helptext ) ret.push( i + "\t" + helptext[i] )
	return ret.join("\n")
}

function bash_rm(c)
{
	if(Math.random() > 0.5 )
		return "ZOMG! You just broke the internet!"
	else
		return "Curse your sudden but inevitable betrayal!"
}


function bash_moo()
{
	return "\n&nbsp;&nbsp;       (__)\n         (oo)\n   /------\\/\n  / |    ||  \n *  /\\---/\\ \n    ~~   ~~ "
}

function bash_emacs()
{
	return "Go shave your neckbeard!"
}

function bash_wget()
{
	return "<img src='wget.jpg' />"
}

function bash_vim()
{
	return "Silly rabbit, vim is for kids!"
}

function bash_ls(c)
{
	tp = handle_dir(c)

	list = get_dir_contents(tp)

	ret = []
	$.each(list, function(i) { if(typeof(this.dname) != "undefined") ret.push("<!--"+i+"--><span class='type-"+this.type+"'>"+this.dname.replace(/ /g, "&nbsp;")+"</span>") })

	f = function(a,b) {
		am = a.match(/^<!--([0-9]+)-/)
		bm = b.match(/^<!--([0-9]+)-/)
		if(am && bm && am.length >= 2 && bm.length >= 2)
			return (parseInt(am[1]) - parseInt(bm[1]))
		else
			return (a < b)
	}

	ret = ret.sort(f)

	sorted = true
	for( var i = 1; i < ret.length; i++ )
		if( ret[i-1] > ret[i] )
			sorted = false

	if( !sorted) ret = ret.sort()

	return ret.join("\n")
}

function bash_open(c)
{
	tp = handle_dir(c)

	if( is_file(tp) )
		return bash_cat(c)

	if( bash_cd(c) )
		return "open: " + c + ": No such file or directory"

	return false;
}

function bash_cat(c)
{
	tp = handle_dir(c)

	file = is_file(tp)
	if( !file && !is_directory(tp) )
		return "cat: " + c + ": No such file or directory"
	else if( !file )
		return "cat: " + c + ": Is a directory"
	else if(typeof(file.json) != "undefined")
		return get_data(file)
	else
		return file.content
}

function handle_dir(c)
{
	tp = path.slice()
	cs = c.split("/")
	for( var i = 0; i < cs.length; i++ )
	{
		if( cs[i] == "." )
			continue
		if( cs[i] == ".." )
			tp.pop()
		else if( cs[i].length == 0 )
			continue
		else
			tp.push(cs[i])
	}
	return tp	
}

function bash_cd(c)
{
	if( c == "/" || c == "" )
	{
		path = []
		return false;
	}

	tp = handle_dir(c)

	console.log(tp)

	if( is_directory(tp) )
		path = tp
	else if( is_file(tp) )
		return "bash: cd: "+c+": Not a directory"
	else
		return "bash: cd: "+c+": No such file or directory"
}

function bash_filler()
{
	return ".\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n."
}

function bash_clear()
{
	setTimeout( function() { $("#scrollback").html("") }, 50 )
	return false;
}

function bash_logout()
{
	window.location = "/?fooled=you"
}