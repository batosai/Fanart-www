<?php

class Opsone_ImageResizer
{
  private $src;
  private $src_width;
  private $src_height;

  private $dst;
  private $dst_width;
  private $dst_height;

  private $image_type;

  /*function __construct($file, $dst_width = 0, $dst_height = 0, $priority = 'width')
  {
    list($this->src_width, $this->src_height, $this->image_type) = getimagesize($file);

    $dst_width = $dst_width ? $dst_width : $this->src_width;
    $dst_height = $dst_height ? $dst_height : $this->src_height;

    $this->dst_width = (int) $dst_width;
    $this->dst_height = (int) $dst_height;

    $work_width = $this->src_width;
    $work_height = $this->src_height;

    $ratio_width = $work_width / $work_height;
    $ratio_height = $work_height / $work_width;

    $ratio_work = $work_width / $work_height;
    $ratio_dst = $dst_width / $dst_height;

    if ($work_width < $dst_width || $work_height < $dst_height)
    {
      if ($ratio_dst < $ratio_work || $priority == 'width')
      {
        $work_width = floor($dst_height / $ratio_height);
        $work_height = $dst_height;
      }
      else
      {
        $work_height = floor($dst_width / $ratio_width);
        $work_width = $dst_width;
      }
    }
    else
    {
      if ($ratio_dst >= $ratio_work && $priority == 'height')
      {
        $work_width = $dst_width;
        $work_height = floor($dst_width / $ratio_width);
      }
      else
      {
        $work_height = $dst_height;
        $work_width = floor($dst_height / $ratio_height);
      }
    }

    $src = imagecreatefromstring(file_get_contents($file));

    $this->dst = imagecreatetruecolor($work_width, $work_height);

    if($this->image_type == IMAGETYPE_PNG) {
      imagealphablending($this->dst, false);
      imagesavealpha($this->dst, true);
    }

    imagecopyresampled($this->dst, $src, 0, 0, 0, 0, imagesx($this->dst), imagesy($this->dst), $this->src_width, $this->src_height);
  }
  */

  function __construct($file, $dst_width = 0, $dst_height = 0)
  {
    list($this->src_width, $this->src_height, $this->image_type) = getimagesize($file);

    $dst_width = $dst_width ? $dst_width : $this->src_width;
    $dst_height = $dst_height ? $dst_height : $this->src_height;

    $this->dst_width = (int) $dst_width;
    $this->dst_height = (int) $dst_height;

    $origin_width = $this->src_width;
    $origin_height = $this->src_height;

    $ratio_width = $origin_width / $origin_height;

    // Le principe est de tester en 2 temps le redimensionnement : d'abord sur x (width) et si le 1er test n'est pas concluant alors ensuite sur y (height)

    // TEST 1 : on force la valeur de work_width à dst_width et on recalcule proportionnellement work_height
    $work_width = $dst_width;
    $work_height = floor($work_width / $ratio_width);

    if ($work_height > $dst_height)
    {
      // => TEST 2 : on force la valeur de work_height à dst_height et on recalcule work_width proportionellement et le tour est joué !
      $work_height = $dst_height;
      $work_width = floor($work_height * $ratio_width);
    }

    $src = imagecreatefromstring(file_get_contents($file));

    $this->dst = imagecreatetruecolor($work_width, $work_height);

    if($this->image_type == IMAGETYPE_PNG) {
      imagealphablending($this->dst, false);
      imagesavealpha($this->dst, true);
    }

    imagecopyresampled($this->dst, $src, 0, 0, 0, 0, imagesx($this->dst), imagesy($this->dst), $this->src_width, $this->src_height);
  }

  function background($red = 255, $green = 255, $blue = 255, $alpha = 127)
  {
    $dst = imagecreatetruecolor($this->dst_width, $this->dst_height);
    imagefill($dst, 0, 0, imagecolorallocatealpha($dst, $red, $green, $blue, $alpha));

    $dst_x = round(($this->dst_width - imagesx($this->dst)) / 2);
    $dst_y = round(($this->dst_height - imagesy($this->dst)) / 2);

    if($this->image_type == IMAGETYPE_PNG) {
      imagealphablending($dst, false);
      imagesavealpha($dst, true);
    }

    imagecopy($dst, $this->dst, $dst_x, $dst_y, 0, 0, imagesx($this->dst), imagesy($this->dst));

    $this->dst = $dst;
  }

  function save($file, $image_type=null)
  {
    if (is_null($image_type)) {
      $image_type = $this->image_type;
    }

     switch ($image_type)
    {
      case IMAGETYPE_JPEG:
        return imagejpeg($this->dst, $file, 90);
      break;

      case IMAGETYPE_GIF:
        return imagegif($this->dst, $file);
      break;

      case IMAGETYPE_PNG:
        return imagepng($this->dst, $file, 1);
      break;
    }
  }
}
