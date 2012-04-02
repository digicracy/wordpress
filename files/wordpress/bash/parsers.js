function get_data(str, nm)
{
	if(typeof(str) == 'object')
	{
		if( typeof(str.cb) != "undefined") cb = str.cb
		str = $.param(str)
	}

	if(typeof(cache[str]) != "undefined")
		return cache[str]
	
	//$("#loading").fadeIn(500)
	input_blocked = true

	$.ajax.async = false
	xhr = new XMLHttpRequest()				
	xhr.open("GET", url + "?" + str, false)
	xhr.send(null)

	response = JSON.parse(xhr.responseText)


	if(typeof(cb) != "undefined")
		response = eval(cb+"(response, nm)")

	cache[str] = response
	
	input_blocked = false

	return response
}

function parse_posts_pages(obj, jso, cb, nm)
{
	ret = {type: 'folder', dname: nm }
	i = 0
	$.each(obj, function()
	{
		cat = {type: 'folder', dname: this.title }

		if(this.post_count <= 20)
		{
			cat.type = 'file'
			cat.cb = cb
			cat.json = jso
			cat.include = 'slug,title,id,date'
			cat.count = 20
			cat.slug = this.slug
		}
		else
		{
			for( var i = 0; i < Math.ceil(this.post_count / 20); i++ )
			{
				cat[i] = {type: 'folder', dname: 'Page '+(i+1), cb: cb, json: jso, include: 'slug,title,id,date', page: i, slug: this.slug, count: 20}
			}
		}

		ret[this.id] = cat
	})

	return ret
}

function parse_categories(str,nm)
{
	return parse_posts_pages(str.categories, 'get_category_posts', 'parse_posts', nm)
}

function parse_tags(str,nm)
{
	ret = {type: 'folder', dname: nm}

	pres = []
	prel = {}
	slugs = {}

	$.each(str.tags, function()
	{
		s = this.slug.substr(0,1)
		if( typeof(ret[s]) == "undefined" )
		{
			pres.push(s)
			prel[s] = 0
			slugs[s] = []
			ret[s] = {type:'folder', dname: s}
		}

		prel[s] = prel[s] + 1
		slugs[s].push(this.slug)
		ret[s][this.slug] = {type: 'folder', dname: this.title, cb: "parse_posts", json: "get_tag_posts", include: "slug,title,id,date", slug: this.slug}
	})
	out = {type:'folder', dname: nm}

	$.each(pres, function(i)
	{
		s = pres[i]
		slug = slugs[s][0]
		if(prel[s] == 1)
			out[slug] = ret[s][slug]
		else
			out[s] = ret[s]
	})

	return out
}

function parse_authors(str,nm)
{
	ret = {type: 'folder', dname: nm }
	i = 0
	$.each(str.authors, function()
	{
		cat = {type: 'folder', dname: this.name }

		cat.cb = "parse_posts"
		cat.json = 'get_author_posts'
		cat.include = 'slug,title,id,date'
		cat.slug = this.slug
		cat.count = 100

		ret[this.slug] = cat
	})
	return ret
}



function parse_posts(data, nm)
{

	ret = {type: 'folder', dname: nm}
	i = 0
	$.each(data.posts, function()
	{
		ret[this.date.replace(/[ :-]+/g, "")] = {type: 'file', url: this.slug, dname: this.title, cb: 'parse_post', json:'get_post', id: this.id}
	})
	// console.log("parse posts", ret)
	return ret
}

function parse_post(data)
{
	data = data.post			
	title = data.title
	content = $("<div>"+data.content+"</div>");
	content.find("style, img, .gallery, *:empty, a[href$=png], a[href$=jpg], a[href$=jpeg], embed, object, param").remove()
	content.find("a").each( function()
	{
		$(this).attr("href", "javascript: handle_click('"+$(this).attr("href")+"')")
	})
	content = content.html().replace(/[\s]+/g, " ")

	pad = title.replace(/./g, "-")+"--"

	return {type: 'file', dname: title, content: "\n"+pad+"\n> "+title+"\n"+pad+"\n\n"+content}
}

function parse_dates(data,nm)
{
	months = {"01":"January", "02":"February", "03":"March", "04":"April", "05":"May", "06":"June", "07":"July", "08":"August", "09":"September", "10":"October", "11":"November", "12":"December"}
	ret = {type: 'folder', dname: nm}
	$.each(data.tree, function(i,v)
	{
		year = {type: 'folder', dname: i}
		$.each(data.tree[i], function(j)
		{
			year[j] = {type: 'folder', dname: months[j], cb:'parse_posts', json:'get_date_posts','date': i+"-"+j,'count':this.toString(), include:'slug,title,id,date'}
		})
		ret[i] = year
	})
	return ret
}