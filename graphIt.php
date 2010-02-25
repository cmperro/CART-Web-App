<?php

session_start();

$graph = $_SESSION['graph'];
$filename = rtrim($_SESSION['filename'],".xls");

require_once 'includes/phptreegraph/classes/GDRenderer.php';

$rw = array();

function trim_value(&$value)
{
	$value = trim($value);
}



for($j = 1; $j < sizeof($graph); $j++)
{
	array_walk($graph[$j], 'trim_value');
}


for($i = 1; $i < sizeof($graph); $i++)
{
	if(strstr($graph[$i][0], 'w') == FALSE)
	{
		$rw [] = 'r';
	}
	else
	{
		$rw [] = 'w';
	}
}



$objTree = new GDRenderer(20,500,100,200,20);

$objTree->add(1,0, $graph[0][0]);
$objTree->add(2,1, $graph[0][1]);
$objTree->add(3,2, $graph[0][2]);

$parent = 3;
$cache = array();
$multiplier = 1;
$graph_size = sizeof($graph);


for($k = 1; $k < $graph_size; $k++)
{
	$offset = $multiplier * 4;
	if($rw[$k - 1] == 'r')
	{
		$cache [] = $parent;
		$objTree->add($offset,$parent, $graph[$k][0]);
		$objTree->add($offset+1,$offset, $graph[$k][1]);
		$objTree->add($offset+2,$offset+1, $graph[$k][2]);
		$objTree->add($offset+3,$offset+2, $graph[$k][3]);
		//$parent = $parent + 4;
		$parent = $offset + 3;
	}
	else
	{
		$new_parent = array_pop($cache);
		$objTree->add($offset,$new_parent, $graph[$k][0]);
		$objTree->add($offset+1,$offset, $graph[$k][1]);
		$objTree->add($offset+2,$offset+1, $graph[$k][2]);
		$objTree->add($offset+3,$offset+2, $graph[$k][3]);
		//$parent = $new_parent + 4;
		$parent = $offset + 3;
	}
	$multiplier = $multiplier + 1;
}




$objTree->setNodeLinks(GDRenderer::LINK_BEZIER);
$objTree->setBGColor(array(255, 183, 111));
$objTree->setNodeColor(array(0, 128, 255));
$objTree->setLinkColor(array(0, 64, 128));
$objTree->setTextColor(array(255, 255, 255));
$objTree->setFTFont('./fonts/Vera.ttf', 12, 0, GDRenderer::CENTER|GDRenderer::TOP);

//display image
//$objTree->stream();

//save image to server in saved_pngs
$saveTarget = "saved_pngs/".$filename.".png";
$objTree->save($saveTarget);

?>

<h3>Generated CART</h3>
A .png image has been generated from <?php echo $filename.".xls"; ?><br><br>
 
<a href="<?php echo $saveTarget; ?>">
<img src="<?php echo $saveTarget; ?>" style="height:500px; width:700px;"/>
</a>

<?php
//forces browser to open save dialogue
//must modify header properly first
//readfile('saved_pngs/myCART.png');
?>
