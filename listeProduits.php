<?php
include("commun.php");
if(isset($_GET['rayon']))
{
	$nomRayon=$_GET['rayon'];

$sql = "select produitId,produitLib from produit where rayonId=(select rayonId from rayon where rayonLib='$nomRayon')"; 
$result = mysql_query($sql);
$json = array();

if(mysql_num_rows($result))
{
	while($row=mysql_fetch_assoc($result))
	{
		$json['produitsDuRayon'][]=$row;
	}
}
echo json_encode($json); 
}
?> 
