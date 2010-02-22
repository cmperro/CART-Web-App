<?php

/*
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

 
require_once('Tree.php');

class GDRenderer extends Tree
{
	const LINK_DIRECT = 1;
	const LINK_NORMAL = 2;
	const LINK_BEZIER = 3;
	const LINK_NONE = 4;
	const CENTER = 1;
	const LEFT = 5;
	const RIGHT = 10;
	const TOP = 16;
	const BOTTOM = 33;
	
	private $linktype;
	private $bgColor = array(255, 255, 255);
	private $nodeColor = array(0, 128, 255);
	private $nodeBorder = array();
	private $linkColor = array(0, 0, 0);
	private $borderWidth = 0;
	private $bgImage;
	private $textColor = array(0, 0, 0);
	private $ftFont;
	private $ftFontSize;
	private $ftFontAngle;
	private $Align = self::CENTER;
	private $img;
	
	
	/**
	 * sets the style of the node connectors
	 * LINK_DIRECT draw direct lines
	 * LINK_NORMAL draw normal style
	 * LINK_BEZIER draw bezier lines
	 * @param int $type 
	 */
	public function setNodeLinks($type)
	{
		$this->linktype = $type;
	}


	/**
	 * sets the backgroundcolor of the graph
	 * example array(255, 255, 225) is white
	 * @param array $arrColor 
	 */
	public function setBGColor($arrColor)
	{
		$this->bgColor = $arrColor;
	}


	/**
	 * sets the node backgroundcolor
	 * example array(255, 255, 225) is white
	 * @param array $arrColor 
	 */
	public function setNodeColor($arrColor)
	{
		$this->nodeColor = $arrColor;
	}

	
	/**
	 * sets the node border
	 * @param array $arrColor
	 * @param int $border 
	 */
	public function setNodeBorder($arrColor, $border)
	{
		$this->nodeBorder = $arrColor;
		$this->borderWidth = $border;
	}


	/**
	 * sets the color of the connectors
	 * example array(0, 0, 0) is black
	 * @param array $arrColor 
	 */
	public function setLinkColor($arrColor)
	{
		$this->linkColor = $arrColor;
	}


	/**
	 * sets the color of the text
	 * example array(0, 0, 0) is black
	 * @param array $arrColor 
	 */
	public function setTextColor($arrColor)
	{
		$this->textColor = $arrColor;
	}
	
	
	/**
	 * use TrueType Fonts
	 * Align: CENTER,TOP,BOTTOM,LEFT,RIGHT ex. GDRenderer::TOP|GDRenderer::LEFT
	 * @param string $font
	 * @param int $size
	 * @param int $angle
	 * @param int $align
	 */
	public function setFTFont($font, $size, $angle = 0, $align = self::CENTER)
	{
		if(!file_exists($font))
		{
			return false;
		}
		$this->ftFont = $font;
		$this->ftFontSize = $size;
		$this->ftFontAngle = $angle;
		$this->Align = $align;
	}
	
	protected function render()
	{
		if(!extension_loaded('gd'))
		{
			throw new Exception('GD not loaded!');
		}
		parent::render();
		$this->img = imagecreate($this->getWidth(), $this->getHeight());
		imagecolorallocate($this->img, $this->bgColor[0], $this->bgColor[1], $this->bgColor[2]);
		$nodeBG = imagecolorallocate($this->img, $this->nodeColor[0], $this->nodeColor[1], $this->nodeColor[2]);
		$linkCol = imagecolorallocate($this->img, $this->linkColor[0], $this->linkColor[1], $this->linkColor[2]);
		$textColor = imagecolorallocate($this->img, $this->textColor[0], $this->textColor[1], $this->textColor[2]);
		if($this->borderWidth > 0)
		{
			$borderCol = imagecolorallocate($this->img, $this->borderColor[0], $this->borderColor[1], $this->borderColor[2]);
		}
		
		while($this->hasNext())
		{
			$node = $this->next();
			if(!is_null($node->image) && file_exists($node->image))
			{
				$path_parts = pathinfo($node->image);
				$file_ext = strtolower($path_parts['extension']);
				$this->imgsize = getimagesize($node->image);
				switch ($file_ext)
				{
					case 'gif':
					$strSourceImage = imagecreatefromgif($node->image);
					break;

					case 'jpg':
					case 'jpeg':
						$strSourceImage = imagecreatefromjpeg($node->image);
						break;

					case 'png':
						$strSourceImage = imagecreatefrompng($node->image);
						$gdinfo = gd_info();
						if (version_compare($gdinfo["GD Version"], '2.0.1', '>='))
						{
							imageantialias($this->img, true);
							imagealphablending($this->img, false);
							imagesavealpha($this->img, true);
							imagefilledrectangle($this->img, $node->x, $node->y, $node->w, $node->h, imagecolorallocatealpha($this->img, 255, 255, 255, 127));
						}
						break;
				}
				imagecopyresampled($this->img, $strSourceImage, $node->x, $node->y, 0, 0, $node->w, $node->h, $this->imgsize[0], $this->imgsize[1]);
			}
			else
			{
				imagefilledrectangle($this->img, $node->x, $node->y , $node->x + $node->w, $node->y + $node->h , $nodeBG);
			}
			if($this->borderWidth > 0)
			{
				for ($i = 0; $i < $this->borderWidth; $i++)
				{
					imagerectangle($this->img, $node->x + $i, $node->y + $i, $node->x + $node->w - $i, $node->y + $node->h - $i, $borderCol);
				}
			}
			switch($this->linktype)
			{
				case self::LINK_DIRECT:
					foreach($node->links as $link)
					{
						imageline ( $this->img, $link['xa'], $link['ya'], $link['xd'], $link['yd'], $linkCol );
					}
					break;
				
				case self::LINK_BEZIER:
					foreach($node->links as $link)
					{
						for ($t=0;$t<=1;$t=$t+.001)
					    {
							$xt = $link['xa'] * pow((1 - $t), 3) + $link['xb'] * 3 * $t * pow(1-$t, 2) + $link['xc'] * 3 * pow($t, 2) * (1-$t) + $link['xd'] * pow($t, 3);
							$yt = $link['ya'] * pow((1 - $t), 3) + $link['yb'] * 3 * $t * pow(1-$t, 2) + $link['yc'] * 3 * pow($t, 2) * (1-$t) + $link['yd'] * pow($t, 3);
							imagesetpixel($this->img, $xt, $yt, $linkCol);
					    }
					}
					break;
				case self::LINK_NONE:
					break;
					
				default:
					foreach($node->links as $link)
					{
						imageline ( $this->img, $link['xa'], $link['ya'], $link['xb'], $link['yb'], $linkCol );
						imageline ( $this->img, $link['xb'], $link['yb'], $link['xc'], $link['yc'], $linkCol );
						imageline ( $this->img, $link['xc'], $link['yc'], $link['xd'], $link['yd'], $linkCol );
					}
			}
			
			if(!strlen($this->ftFont))
			{
				imagestring( $this->img, 4, $node->x + $this->borderWidth, $node->y, $node->message, $textColor);
			}
			else
			{
				$x = 0;
				$y = 0;
				$x2 = 0;
				$y2 = 0;
				$fttext = imageftbbox ($this->ftFontSize, $this->ftFontAngle, $this->ftFont,$node->message);
				$x = ($fttext[0] <= $fttext[6]) ? $fttext[0] : $fttext[6];
				$y = ($fttext[5] >= $fttext[7]) ? $fttext[5] : $fttext[7];
				$x2 = ($fttext[2] >= $fttext[4]) ? $fttext[2] : $fttext[4];
				$y2 = ($fttext[1] >= $fttext[3]) ? $fttext[1] : $fttext[3];
				$w = $x2 - $x;
				$h = y2 - $y;
				$left = ($node->w - $w) / 2;
				$top = ($node->h - $h) / 2;
				
				switch($this->Align)
				{
					case self::CENTER|self::CENTER :
						imagefttext( $this->img , $this->ftFontSize , $this->ftFontAngle , $node->x + $left, $node->y + $top + $h +$y2, $textColor , $this->ftFont , $node->message);
						break;
					case self::CENTER|self::TOP:
					case self::TOP :
						imagefttext( $this->img , $this->ftFontSize , $this->ftFontAngle , $node->x + $left, $node->y + $this->borderWidth + $h, $textColor , $this->ftFont , $node->message);
						break;
					case self::CENTER|self::BOTTOM :
						imagefttext( $this->img , $this->ftFontSize , $this->ftFontAngle , $node->x + $left, $node->y + $node->h - $this->borderWidth , $textColor , $this->ftFont , $node->message);
						break;
					case self::LEFT|self::CENTER :
						imagefttext( $this->img , $this->ftFontSize , $this->ftFontAngle , $node->x + $this->borderWidth, $node->y + $top + $h +$y2, $textColor , $this->ftFont , $node->message);
						break;
					case self::LEFT|self::TOP :
						imagefttext( $this->img , $this->ftFontSize , $this->ftFontAngle , $node->x + $this->borderWidth, $node->y + $this->borderWidth + $h , $textColor , $this->ftFont , $node->message);
						break;
					case self::LEFT|self::BOTTOM :
						imagefttext( $this->img , $this->ftFontSize , $this->ftFontAngle ,$node->x + $this->borderWidth, $node->y + $node->h - $this->borderWidth, $textColor , $this->ftFont , $node->message);
						break;
					case self::RIGHT|self::CENTER :
					case self::RIGHT :
						imagefttext( $this->img , $this->ftFontSize , $this->ftFontAngle , $node->x + $node->w - $this->borderWidth - $w, $node->y + $top + $h +$y2, $textColor , $this->ftFont , $node->message);
						break;
					case self::RIGHT|self::TOP :
						imagefttext( $this->img , $this->ftFontSize , $this->ftFontAngle , $node->x + $node->w - $this->borderWidth - $w, $node->y + $this->borderWidth + $h , $textColor , $this->ftFont , $node->message);
						break;
					case self::RIGHT|self::BOTTOM :
						imagefttext( $this->img , $this->ftFontSize , $this->ftFontAngle ,$node->x + $node->w - $this->borderWidth - $w, $node->y + $node->h - $this->borderWidth, $textColor , $this->ftFont , $node->message);
						break;
					default:
						imagefttext( $this->img , $this->ftFontSize , $this->ftFontAngle , $node->x + $left, $node->y + $this->borderWidth + $h, $textColor , $this->ftFont , $node->message);
				}
			}
		}
	}
	
	
	/**
	 * get the image as stream
	 */
	public function stream()
	{
		if(empty($this->img))
		{
			$this->render();
		}
		header('Content-type: image/png');
		imagepng($this->img);
	}
	
	
	/**
	 * save the image to file
	 * @param string $file 
	 */
	
	public function save($file)
	{
		if(empty($this->img))
		{
			$this->render();
		}
		imagepng($this->img, $file);
	}
}
?>
