<?php

/**
 * phpTreeGraph
 *
 *
 * PHP version 5
 * @copyright  Mathias Herrmann 2007
 * @author     Mathias Herrmann <mathias_herrmann@arcor.de>
 * @license    LGPL
  *                                                                          *
  * This PHP class is free software; you can redistribute it and/or          *
  * modify it under the terms of the GNU Lesser General Public               *
  * License as published by the Free Software Foundation; either             *
  * version 2.1 of the License, or (at your option) any later version.       *
  *                                                                          *
  * This PHP class is distributed in the hope that it will be useful,        *
  * but WITHOUT ANY WARRANTY; without even the implied warranty of           *
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU         *
  * Lesser General Public License for more details.                          *
  *                                                                          *
  *                                                                          *
  *                                                                          *
  *
 */

 
 
class Node
{
	public $id = 0;
	public $h = 0; 
	public $w = 0; 
	public $x = 0;
	public $y = 0;
	public $leftNeighbor = 0;
	public $rightNeighbor = 0;
	public $childs = array();
	public $prelim = 0;
	public $modifier = 0;
	public $nodeParent = 0; 
	public $message;
	public $links = array();
	public $image;
	
	public function __construct($id, $pid, $w, $h, $message = '', $image = null)
	{
		$this->id = $id;
		$this->pid = $pid;
		$this->w = $w;
		$this->h = $h;
		$this->message = $message;
		$this->image = $image;
	}
	
	public function numChilds()
	{
		return count($this->childs);
	}
	
	public function getLeftSibling()
	{
		if($this->leftNeighbor && $this->leftNeighbor->nodeParent === $this->nodeParent)
		{
			return $this->leftNeighbor;
		}
		else
		{
			return false;	
		}
	}
	
	public function getRightSibling()
	{
		if($this->rightNeighbor && $this->rightNeighbor->nodeParent === $this->nodeParent)
        {
			return $this->rightNeighbor;
		}
		else
		{
			return false;
		}
	}
	
	public function getChildAt($i)
	{
		if(isset($this->childs[$i]))
		{
			return $this->childs[$i];
		}
		else
		{
			return false;
		}
	}
	
	public function getChildrenCenter()
	{
		$node = $this->getChildAt(0);
		$node1 = $this->getChildAt(count($this->childs)-1);
		return $node->prelim + (($node1->prelim - $node->prelim) + $node1->w) / 2;	
	}
	
	public function getLinks()
	{
		$xa = 0; $ya = 0; $xb = 0; $yb = 0; $xc = 0; $yc = 0; $xd = 0; $yd = 0;
		$xa = $this->x + ($this->w / 2);
		$ya = $this->y + $this->h;
	
		foreach($this->childs as $child)
		{
			$xd = $xc = $child->x + ($child->w / 2);
			$yd = $child->y;
			$xb = $xa;
			$yb = $yc = $ya + ($yd - $ya) / 2;
			$this->links[$child->id]['xa'] = $xa;
			$this->links[$child->id]['ya'] = $ya;
			$this->links[$child->id]['xb'] = $xb;
			$this->links[$child->id]['yb'] = $yb;
			$this->links[$child->id]['xc'] = $xc;
			$this->links[$child->id]['yc'] = $yc;
			$this->links[$child->id]['xd'] = $xd;
			$this->links[$child->id]['yd'] = $yd;
		}
	}
	
	public function __toString()
	{
		return (string)$this->id;
	}
	
}

?>
