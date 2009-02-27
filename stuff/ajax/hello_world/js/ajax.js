var xmlHttp = creazaXmlHttp();
/*
Returneaza obiectul XMLHttpRequest creat
*/
function creazaXmlHttp()
{
    var xmlHttp;
    // pentru IE
    if(window.ActiveXObject)
    {
        try
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (e)
        {
            xmlHttp = false;
        }
    }
//Pt Mozilla sau alte browsere
    else
    {
        try
        {
            xmlHttp = new XMLHttpRequest();
        }
        catch (e)
        {
            xmlHttp = false;
       }
    }

    if (!xmlHttp)
        alert("Nu s a putut crea obiectul XMLHttpRequest .");
    else
        return xmlHttp;
}
/*
Acesta functie preia numerele introduse in input -uri si le trimite php-ului pentru calculare
*/
function calculeaza()
{
    // verificam daca xmlHttp este liber
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
    {
        //obtinem cele doua valori ale numerelor

        var nr1 = document.getElementById('nr1').value;
        var nr2 = document.getElementById('nr2').value;

        // executam script-ul php de adunare trimitand cele doua numere ca paramentri prin GET
        xmlHttp.open("GET", "php/calculeaza.php?nr1=" + nr1+"&nr2="+nr2, true);

        //definim functia care se va ocupa de manipularea rezultatului primit de la script-ul php
        xmlHttp.onreadystatechange = gestioneazaRezultat;

        // trimitem cererea catre server
        xmlHttp.send(null);
    }
}
/*
Acesta este functia de "callback" care primeste rezultatul de la php
*/
function gestioneazaRezultat()
{
    // daca rezultatul este obtinut
    if (xmlHttp.readyState == 4)
    {
        // status = 200 arata faptul ca cererea a fost rezolvata cu succes
        if (xmlHttp.status == 200)
        {
            // extragem rezultatul
            raspuns = xmlHttp.responseText;

            //modificam div-ul rezultat cu rezultatul
            document.getElementById("rezultat").innerHTML = '<b> Suma =' + raspuns + '</b>';
       }
        // daca statusul este diferit avem o problema
        else
        {
            alert("Problema la accesare: " +xmlHttp.statusText);
        }
    }
} 