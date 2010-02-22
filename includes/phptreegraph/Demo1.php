<?php

/**
 *	phpTreeGraph
 *	Linux filesystem hierarchy demo
  * 	@author Mathias Herrmann
**/

//include GD rendering class
require_once('./classes/GDRenderer.php');

//create new GD renderer, optinal parameters: LevelSeparation,  SiblingSeparation, SubtreeSeparation, defaultNodeWidth, defaultNodeHeight
$objTree = new GDRenderer(30, 10, 30, 50, 20);

//add nodes to the tree, parameters: id, parentid optional text, width, height, image(path)
$objTree->add(1,0,'/', 10);
$objTree->add(2,1,'bin');
$objTree->add(3,1,'boot');
$objTree->add(4,1,'dev');
$objTree->add(5,1,'etc');
$objTree->add(6,1,'home');
$objTree->add(7,1,'lib');
$objTree->add(8,1,'lost+found', 100);
$objTree->add(9,1,'mnt');
$objTree->add(10,1,'proc');
$objTree->add(11,1,'root');
$objTree->add(12,1,'sbin');
$objTree->add(13,1,'tmp');
$objTree->add(14,1,'usr');
$objTree->add(15,1,'var');
$objTree->add(16,5,'rc.d');
$objTree->add(17,5,'skel');
$objTree->add(18,5,'X11');
$objTree->add(19,14,'bin');
$objTree->add(20,14,'local');
$objTree->add(21,14,'include');
$objTree->add(22,14,'lib');
$objTree->add(23,14,'man');
$objTree->add(24,14,'sbin');
$objTree->add(25,14,'src');
$objTree->add(26,14,'X11 R6');
$objTree->add(27,15,'tmp');
$objTree->add(28,15,'spool');
$objTree->add(29,20,'bin');
$objTree->add(30,20,'sbin');
$objTree->add(31,25,'linux');
$objTree->add(32,28,'lpd');
$objTree->add(33,28,'mail');
$objTree->add(34,28,'uucp');
$objTree->add(35,28,'cron');

//$objTree->setNodeLinks(GDRenderer::LINK_BEZIER);

$objTree->setBGColor(array(255, 255, 255));
$objTree->setNodeColor(array(0, 128, 255));
$objTree->setLinkColor(array(0, 64, 128));
//$objTree->setNodeLinks(GDRenderer::LINK_BEZIER);
$objTree->setNodeBorder(array(0, 128, 255), 2);
$objTree->setFTFont('./fonts/Vera.ttf', 10, 0, GDRenderer::CENTER|GDRenderer::TOP);

$objTree->stream();

?>
