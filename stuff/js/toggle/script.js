function hideElement(row)
{
  document.getElementById(row).style.display = 'none';
}
function toggle(row) {
 if( document.getElementById(row).style.display=='none' ){
   document.getElementById(row).style.display = '';
 }else{
   document.getElementById(row).style.display = 'none';
 }
}

function toggleX(thisname) {
  tr=document.getElementsByTagName('tr')
  for (i=0;i<tr.length;i++){
    if (tr[i].getAttribute(thisname)){
      if ( tr[i].style.display=='none' ){
        tr[i].style.display = '';
      }
      else {
        tr[i].style.display = 'none';
      }
    }
  }
}
