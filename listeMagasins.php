<?php
include("commun.php");

$sql="select magasinId, magasinLib from magasin";
//echo $sql;
//execution
$result=mysql_query($sql);
//le tableau
$monTableau=array();
if(mysql_num_rows($result))//s'il y a un resultat
{
	while($ligne=mysql_fetch_assoc($result))
	{
		$monTableau['magasinInfos'][]=$ligne;
	}
}
echo json_encode($monTableau); 
?>