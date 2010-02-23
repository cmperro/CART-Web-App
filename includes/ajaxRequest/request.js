var xmlhttp;

function sendRequest(email)
{
xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Browser does not support HTTP Request");
  return;
  }
var url="includes/ajaxRequest/sendRequest.php";
url=url+"?email="+email;
xmlhttp.onreadystatechange=stateChanged;
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
}

function stateChanged()
{
  if (xmlhttp.readyState==4)
  {
  document.getElementById("top").innerHTML=xmlhttp.responseText;
  }
}

function GetXmlHttpObject()
{
var objXMLHttp=null;
if (window.XMLHttpRequest)
  {
  objXMLHttp=new XMLHttpRequest();
  }
else if (window.ActiveXObject)
  {
  objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
return objXMLHttp;
} 
