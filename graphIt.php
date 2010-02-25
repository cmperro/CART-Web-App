<?php
session_start();

$graph = $_SESSION['graph'];

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




$objTree = new GDRenderer(20,20,40,200,20);

$objTree->add(1,0, $graph[0][0]);
$objTree->add(2,1, $graph[0][1]);
$objTree->add(3,2, $graph[0][2]);
$niente = array_shift($graph);





$objTree->setNodeLinks(GDRenderer::LINK_BEZIER);
$objTree->setBGColor(array(255, 183, 111));
$objTree->setNodeColor(array(0, 128, 255));
$objTree->setLinkColor(array(0, 64, 128));
$objTree->setTextColor(array(255, 255, 255));
$objTree->setFTFont('./fonts/Vera.ttf', 12, 0, GDRenderer::CENTER|GDRenderer::TOP);
$objTree->stream();


	
?>