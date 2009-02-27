<html>
<head>
<!-- http://www.w3schools.com/ajax/ajax_example.asp -->
<script language="javascript" type="text/javascript">
var xmlHttp

function showHint(str)
	{
	if (str.length==0)
		{ 
		document.getElementById("txtHint").innerHTML=""
		return
		}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
		{
		alert ("Browser does not support HTTP Request")
		return
		} 
	var url="p1.php"
	url=url+"?p="+str
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
	} 

function stateChanged() 
	{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{ 
		document.getElementById("txtHint").innerHTML=xmlHttp.responseText 
		} 
	} 

function GetXmlHttpObject()
	{ 
	var objXMLHttp=null
	if (window.XMLHttpRequest)
		{
		objXMLHttp=new XMLHttpRequest()
		}
	else if (window.ActiveXObject)
		{
		objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
		}
	return objXMLHttp
	} 
</script> 
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%">Meniu</td>
    <td>Continut</td>
  </tr>
  <tr>
    <td><a href="pagina1.php" id="txt1" onclick="showHint('pagina 1'); return false">Pagina 1</a><br>
<a href="pagina2.php" id="txt2" onclick="showHint('pagina 2'); return false">Pagina 2</a><br>
<a href="pagina3.php" id="txt3" onclick="showHint('pagina 3'); return false">Pagina 3</a></td>
    <td><span id="txtHint"></span></td>
  </tr>
</table>

</body>
</html>