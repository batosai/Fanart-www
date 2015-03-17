<?php

class FileController extends Zend_Controller_Action
{
  private $_table;

  public function init()
  {
    $this->_helper->layout->disableLayout();
    $this->_helper->ViewRenderer->setNoRender();

    $this->_table = new Model_DbTable_Files();
  }

  public function getAction()
  {
    $guid = str_replace('.jpg', '', $this->_getParam('guid'));
    if (!$file = $this->_table->findByGuid($guid)) {
      return;
    }

    $expires = 60*60*24*15;
    header('Pragma: public');
    header('Cache-Control: maxage=' . $expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
    header('Content-Type: ' . mime_content_type($file->getPath()));

    readfile($file->getPath());
  }

  public function cacheAction()
  {
    $width = (int) $this->_getParam('width');
    $height = (int) $this->_getParam('height');

    if (!$file = $this->_table->findByGuid($this->_getParam('guid'))) {
      return;
    }

    $filePath = $file->getPath();

    $tmpFile = sprintf('%s/../temp/cache/images/%s', APPLICATION_PATH, sha1($this->_request->getRequestUri()));

    if (!is_file($tmpFile) || filemtime($filePath) != filemtime($tmpFile))
    {
      try
      {
        $img = new Imagick($filePath);
        $canvas = new Imagick();
        $canvas->newImage($width, $height, "white");
        $canvas->setFormat("jpg");

        if ($exif = @exif_read_data($filePath))
        {
          /*if (@$exif['Orientation'])
          {
            switch ($exif['Orientation'])
            {
              case Imagick::ORIENTATION_TOPRIGHT:
                $img->flipImage();
                break;
              case Imagick::ORIENTATION_BOTTOMRIGHT:
                $img->rotateImage(new ImagickPixel(), 180);
                break;
              case Imagick::ORIENTATION_BOTTOMLEFT:
                $img->flopImage();
                break;
              case Imagick::ORIENTATION_LEFTTOP:
                $img->transposeImage();
                break;
              case Imagick::ORIENTATION_RIGHTTOP:
                $img->rotateImage(new ImagickPixel(), 90);
                break;
              case Imagick::ORIENTATION_RIGHTBOTTOM:
                $img->transverseImage();
                break;
              case Imagick::ORIENTATION_LEFTBOTTOM:
                $img->rotateImage(new ImagickPixel(), 270);
                break;
            }
          }
          else
          {*/
            list($w, $h) = getimagesize($filePath);
            if ($w > $h) {
              $img->rotateImage(new ImagickPixel('none'), 270);
            //}
          }
        }

        if ($img->getImageColorspace() <> Imagick::COLORSPACE_RGB) {
          $img->setImageColorspace(Imagick::COLORSPACE_RGB);
        }

        $img->thumbnailImage($width, $height, true, false);

        $img->setCompressionQuality(80);
        $img->setImageFormat('jpeg');

        $canvas->compositeImage($img, imagick::COMPOSITE_OVER, ($canvas->getImageWidth() - $img->getImageWidth() ) / 2, ($canvas->getImageHeight() - $img->getImageHeight() ) / 2);
        $canvas->writeImage($tmpFile);

        $img->destroy();
        $canvas->destroy();

        touch($tmpFile, filemtime($filePath));
      }
      catch (ImagickException $e) {}
    }

    $expires = 60*60*24*15;
    header('Pragma: public');
    header('Cache-Control: maxage=' . $expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
    header('Content-Type: image/jpeg');

    readfile($tmpFile);
  }

  public function cacheFullAction()
  {
    $width = (int) $this->_getParam('width');
    $height = (int) $this->_getParam('height');

    if (!$file = $this->_table->findByGuid($this->_getParam('guid'))) {
      return;
    }

    $filePath = $file->getPath();

    $tmpFile = sprintf('%s/../temp/cache/images/%s', APPLICATION_PATH, sha1($this->_request->getRequestUri()));

    if (!is_file($tmpFile) || filemtime($filePath) != filemtime($tmpFile))
    {
      try
      {

        $img = new Imagick($filePath);
        $canvas = new Imagick();
        $canvas->newImage($width, $height, "white");
        $canvas->setFormat("jpg");

        if ($img->getImageColorspace() <> Imagick::COLORSPACE_RGB) {
          $img->setImageColorspace(Imagick::COLORSPACE_RGB);
        }

        $img->thumbnailImage($width, $height, true, false);

        $img->setCompressionQuality(80);
        $img->setImageFormat('jpeg');

        $canvas->compositeImage($img, imagick::COMPOSITE_OVER, ($canvas->getImageWidth() - $img->getImageWidth() ) / 2, ($canvas->getImageHeight() - $img->getImageHeight() ) / 2);
        $canvas->writeImage($tmpFile);

        $img->destroy();
        $canvas->destroy();

        touch($tmpFile, filemtime($filePath));
      }
      catch (ImagickException $e) {}
    }

    $expires = 60*60*24*15;
    header('Pragma: public');
    header('Cache-Control: maxage=' . $expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
    header('Content-Type: image/jpeg');

    readfile($tmpFile);
  }

  public function avatarAction()
  {
    $width = (int) $this->_getParam('width');
    $height = (int) $this->_getParam('height');

    if (!$file = $this->_table->findByGuid($this->_getParam('guid'))) {
      return;
    }

    $filePath = $file->getPath();

    $tmpFile = sprintf('%s/../temp/cache/images/%s', APPLICATION_PATH, sha1($this->_request->getRequestUri()));

    if (!is_file($tmpFile) || filemtime($filePath) != filemtime($tmpFile))
    {
      try
      {

        $img = new Imagick($filePath);
        $canvas = new Imagick();
        $canvas->newImage($width, $height, "white");
        $canvas->setFormat("jpg");

        if ($img->getImageColorspace() <> Imagick::COLORSPACE_RGB) {
          $img->setImageColorspace(Imagick::COLORSPACE_RGB);
        }

        $geo = $img->getImageGeometry();

        if (($geo['width']/$width) < ($geo['height']/$height)) {
            $img->cropImage($geo['width'], floor($height*$geo['width']/$width), 0, (($geo['height']-($height*$geo['width']/$width))/2));
        }
        else {
            $img->cropImage(ceil($width*$geo['height']/$height), $geo['height'], (($geo['width']-($width*$geo['height']/$height))/2), 0);
        }

        $img->thumbnailImage($width, $height, true, false);

        $img->setCompressionQuality(80);
        $img->setImageFormat('jpeg');

        $canvas->compositeImage($img, imagick::COMPOSITE_OVER, ($canvas->getImageWidth() - $img->getImageWidth() ) / 2, ($canvas->getImageHeight() - $img->getImageHeight() ) / 2);
        $canvas->writeImage($tmpFile);

        $img->destroy();
        $canvas->destroy();

        touch($tmpFile, filemtime($filePath));
      }
      catch (ImagickException $e) {}
    }

    $expires = 60*60*24*15;
    header('Pragma: public');
    header('Cache-Control: maxage=' . $expires);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
    header('Content-Type: image/jpeg');

    readfile($tmpFile);
  }
}
