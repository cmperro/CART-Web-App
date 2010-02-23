<?php

/**
 *	phpTreeGraph
 *	Species hierarchy demo with images
  * 	@author Mathias Herrmann
**/

//include GD rendering class
require_once('./classes/GDRenderer.php');

//create new GD renderer, optinal parameters: LevelSeparation,  SiblingSeparation, SubtreeSeparation, defaultNodeWidth, defaultNodeHeight

$objTree = new GDRenderer(20, 20, 40, 175, 20);



//add nodes to the tree, parameters: id, parentid optional text, width, height, image(path)
$objTree->add(1,0,'102 Students total');
$objTree->add(2,1,'54 passed / 48 failed');
$objTree->add(3,2,'They got 2 right');
$objTree->add(4,2,'They got 2 wrong');
$objTree->add(5,3,'47 passed / 12 failed');
$objTree->add(6,4,'7 passed / 36 failed');
$objTree->add(7,5,'They got 9 right');
$objTree->add(8,5,'They got 9 wrong');
$objTree->add(9,7,'13 passed / 4 failed');
$objTree->add(10,8,'4 passed / 10 failed');
$objTree->add(11,6,'They got 1 right');
$objTree->add(12,6,'They got 1 wrong');
$objTree->add(13,11,'10 passed / 2 failed');
$objTree->add(14,12,'2 passed / 6 failed');

											


$objTree->setNodeLinks(GDRenderer::LINK_BEZIER);

$objTree->setBGColor(array(255, 183, 111));
$objTree->setNodeColor(array(0, 128, 255));
$objTree->setLinkColor(array(0, 64, 128));

$objTree->setTextColor(array(255, 255, 255));
$objTree->setFTFont('./fonts/Vera.ttf', 12, 0, GDRenderer::CENTER|GDRenderer::TOP);

$objTree->stream();

?>
