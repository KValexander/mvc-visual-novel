// Checking received pages
function xhr_check(html) {
	if(html.includes("<!DOCTYPE html>")) return false;
	else return true;
}
// Checking for trailing slash
function slash_check(str) {
	let length = str.length, first, last;
	if(length <= 1) return str;
	first = str.charAt(0);
	if(first != "/") str = "/" + str;
	last = str.substring(length - 1);
	if(last == "/")
		return str.substring(0, length - 1)
	else return str;
}