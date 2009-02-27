<?php
/*
id | nume | parent_id
1 | categ 1 | 0
2 | categ 1.1 | 1
3 | categ 1.2 | 1
4 | categ 2 | 0
5 | categ 2.1 | 4
6 | categ 2.2 | 4

    code:
*/
    function listCateg($parent_id = 0)
    {
       $q = mysql_query("select * from categori where parent_id = {$parent_id}");
       while ($r = mysql_fetch_object($q))
       {
          $r->parent_id == 0 ? print "<b>{$r->nume}</b><br>" : print $r->nume."<br>";
          listCateg($r->id);
       }
    }

    listCateg();
?>