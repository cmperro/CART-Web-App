<?php

/**
 *	phpTreeGraph
 *	Species hierarchy demo with images
  * 	@author Mathias Herrmann
**/

//include GD rendering class
require_once('./classes/GDRenderer.php');

//create new GD renderer, optinal parameters: LevelSeparation,  SiblingSeparation, SubtreeSeparation, defaultNodeWidth, defaultNodeHeight
$objTree = new GDRenderer(30, 10, 30, 100, 20);



//add nodes to the tree, parameters: id, parentid optional text, width, height, image(path)
$objTree->add(1,0,'species', 90);
$objTree->add(2,1,'plants');
$objTree->add(3,1,'fungi', 90, 119, 'fungi.png');
$objTree->add(4,1,'lichens');
$objTree->add(5,1,'animals');
$objTree->add(6,2,'mosses');
$objTree->add(7,2,'ferns', 60);
$objTree->add(8,2,'gymnosperms', 120);
$objTree->add(9,2,'dicotyledons', 120);
$objTree->add(10,2,'monocotyledons', 130);
$objTree->add(11,5,'invertebrates');
$objTree->add(12,5,'vertebrates');
$objTree->add(13,11,'insects');
$objTree->add(14,11,'molluscs');
$objTree->add(15,11,'crustaceans');		
$objTree->add(16,11,'others');								
$objTree->add(17,12,'fish', 131, 69 ,'fish.png');
$objTree->add(18,12,'amphibians', 143, 107, 'frosch.png');
$objTree->add(19,12,'reptiles', 115, 124, 'croc.png');		
$objTree->add(20,12,'birds');								
$objTree->add(21,12,'mammals');												


$objTree->setNodeLinks(GDRenderer::LINK_BEZIER);

$objTree->setBGColor(array(255, 183, 111));
$objTree->setNodeColor(array(0, 128, 255));
$objTree->setLinkColor(array(0, 64, 128));

$objTree->setTextColor(array(255, 255, 255));
$objTree->setFTFont('./fonts/Vera.ttf', 12, 0, GDRenderer::CENTER|GDRenderer::TOP);

$objTree->stream();

?>
