
console.log(httpGet('http://solarprotocol.net/api/v1/chargecontroller.php?file=1'));

function httpGet(theUrl)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText;
}