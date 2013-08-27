function parseQueryString() {
    var query = (location.search || '?').substr(1),
        map   = {};
    query.replace(/([^&=]+)=?([^&]*)(?:&+|$)/g, function(match, key, value) {
        (map[key] = map[key] || []).push(value);
    });
    return map;
}

function parseString()
{
	var query = location.search;
	query = query.replace(/\?/, "");
	query = query.replace(/\+/, " ");

	var elements = query.split(/&/);

	var map = {};
	for(var i = 0; i < elements.length; i +=1)
	{
		var obj = elements[i].split(/=/);
		map[obj[0]] = decodeURIComponent( obj[1] );
	}

	return map;
}