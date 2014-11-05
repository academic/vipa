<?php

namespace Ojs\Common\Helper;

class ImageResizeHelper
{

    protected $options;
    protected $imagePath;
    protected $error_messages = array();
    protected $image_objects = array();

    public function __construct($options = null)
    {
        $this->options = array(
            'image_name' => '',
            'upload_dir' => 'web/uploads/',
            'upload_url' => '/uploads/',
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'accept_file_types' => '/.+$/i',
            'image_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Image resolution restrictions:
            'max_width' => 4000,
            'max_height' => 4000,
            'min_width' => 10,
            'min_height' => 10,
            // Set to 0 to use the GD library to scale and orient images,
            // set to 1 to use imagick (if installed, falls back to GD),
            'image_library' => 1,
            // Uncomment the following to define an array of resource limits
            // for imagick:
            /*
              'imagick_resource_limits' => array(
              imagick::RESOURCETYPE_MAP => 32,
              imagick::RESOURCETYPE_MEMORY => 32
              ),
             */
            'image_versions' => array(
                // The empty image version key defines options for the original image:
                'big' => array(
                    // Automatically rotate images based on EXIF meta data:
                    'max_width' => 1024,
                    'max_height' => 768,
                    'auto_orient' => true
                ),
                'medium' => array(
                    'crop' => true,
                    'max_width' => 600,
                    'max_height' => 600
                ),
                'thumbnail2' => array(
                    'crop' => true,
                    'max_width' => 240,
                    'max_height' => 240
                ),
                'thumbnail' => array(
                    'crop' => true,
                    'max_width' => 80,
                    'max_height' => 80
                )
            )
        );
        if ($options) {
            $this->options = array_merge($this->options, $options);
        }
        $this->image_name = $this->options['image_name'];
    }

    public function resize()
    {
        $failed_versions = array();
        foreach ($this->options['image_versions'] as $version => $options) {
            if (!$this->create_scaled_image($this->image_name, $version, $options)) {
                $failed_versions[] = $version ? $version : 'original';
            }
        }
        if (count($failed_versions)) {
            $this->error_messages[] = $this->get_error_message('image_resize')
                . ' (' . implode($failed_versions, ', ') . ')';
        }
        $this->destroy_image_object($this->image_name);
    }

    // Fix for overflowing signed 32 bit integers,
    // works for sizes up to 2^32-1 bytes (4 GiB - 1):
    protected function fix_integer_overflow($size)
    {
        return $size < 0 ? ($size + (2.0 * (PHP_INT_MAX + 1))) : $size;
    }

    protected function get_file_size($file_path, $clear_stat_cache = false)
    {
        if ($clear_stat_cache) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                clearstatcache(true, $file_path);
            } else {
                clearstatcache();
            }
        }

        return $this->fix_integer_overflow(filesize($file_path));
    }

    protected function is_valid_file_object($file_name)
    {
        $file_path = $this->get_upload_path($file_name);
        if (is_file($file_path) && $file_name[0] !== '.') {
            return true;
        }

        return false;
    }

    protected function get_error_message($error)
    {
        return array_key_exists($error, $this->error_messages) ?
            $this->error_messages[$error] : $error;
    }

    protected function upcount_name_callback($matches)
    {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';

        return ' (' . $index . ')' . $ext;
    }

    protected function upcount_name($name)
    {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/', array($this, 'upcount_name_callback'), $name, 1
        );
    }

    protected function get_upload_path($file_name = null, $version = null)
    {
        $file_name = $file_name ? $file_name : '';
        if (empty($version)) {
            $version_path = '';
        } else {
            $version_dir = @$this->options['image_versions'][$version]['upload_dir'];
            if ($version_dir) {
                return $version_dir . $this->get_user_path() . $file_name;
            }
            $version_path = $version . '/';
        }

        return $this->options['upload_dir'] . $version_path . $file_name;
    }

    protected function get_scaled_image_file_paths($file_name, $version)
    {
        $file_path = $this->get_upload_path($file_name);
        if (!empty($version)) {
            $version_dir = $this->get_upload_path(null, $version);
            if (!is_dir($version_dir)) {
                mkdir($version_dir, $this->options['mkdir_mode'], true);
            }
            $new_file_path = $version_dir . '/' . $file_name;
        } else {
            $new_file_path = $file_path;
        }

        return array($file_path, $new_file_path);
    }

    protected function gd_get_image_object($file_path, $func, $no_cache = false)
    {
        if (empty($this->image_objects[$file_path]) || $no_cache) {
            $this->gd_destroy_image_object($file_path);
            $this->image_objects[$file_path] = $func($file_path);
        }

        return $this->image_objects[$file_path];
    }

    protected function gd_set_image_object($file_path, $image)
    {
        $this->gd_destroy_image_object($file_path);
        $this->image_objects[$file_path] = $image;
    }

    protected function gd_destroy_image_object($file_path)
    {
        $image = @$this->image_objects[$file_path];

        return $image && imagedestroy($image);
    }

    protected function gd_imageflip($image, $mode)
    {
        if (function_exists('imageflip')) {
            return imageflip($image, $mode);
        }
        $new_width = $src_width = imagesx($image);
        $new_height = $src_height = imagesy($image);
        $new_img = imagecreatetruecolor($new_width, $new_height);
        $src_x = 0;
        $src_y = 0;
        switch ($mode) {
            case '1': // flip on the horizontal axis
                $src_y = $new_height - 1;
                $src_height = -$new_height;
                break;
            case '2': // flip on the vertical axis
                $src_x = $new_width - 1;
                $src_width = -$new_width;
                break;
            case '3': // flip on both axes
                $src_y = $new_height - 1;
                $src_height = -$new_height;
                $src_x = $new_width - 1;
                $src_width = -$new_width;
                break;
            default:
                return $image;
        }
        imagecopyresampled(
            $new_img, $image, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_width, $src_height
        );

        return $new_img;
    }

    protected function gd_orient_image($file_path, $src_img)
    {
        if (!function_exists('exif_read_data')) {
            return false;
        }
        $exif = @exif_read_data($file_path);
        if ($exif === false) {
            return false;
        }
        $orientation = intval(@$exif['Orientation']);
        if ($orientation < 2 || $orientation > 8) {
            return false;
        }
        switch ($orientation) {
            case 2:
                $new_img = $this->gd_imageflip(
                    $src_img, defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
                );
                break;
            case 3:
                $new_img = imagerotate($src_img, 180, 0);
                break;
            case 4:
                $new_img = $this->gd_imageflip(
                    $src_img, defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
                );
                break;
            case 5:
                $tmp_img = $this->gd_imageflip(
                    $src_img, defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
                );
                $new_img = imagerotate($tmp_img, 270, 0);
                imagedestroy($tmp_img);
                break;
            case 6:
                $new_img = imagerotate($src_img, 270, 0);
                break;
            case 7:
                $tmp_img = $this->gd_imageflip(
                    $src_img, defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
                );
                $new_img = imagerotate($tmp_img, 270, 0);
                imagedestroy($tmp_img);
                break;
            case 8:
                $new_img = imagerotate($src_img, 90, 0);
                break;
            default:
                return false;
        }
        $this->gd_set_image_object($file_path, $new_img);

        return true;
    }

    protected function gd_create_scaled_image($file_name, $version, $options)
    {
        if (!function_exists('imagecreatetruecolor')) {
            error_log('Function not found: imagecreatetruecolor');

            return false;
        }
        list($file_path, $new_file_path) = $this->get_scaled_image_file_paths($file_name, $version);
        $type = strtolower(substr(strrchr($file_name, '.'), 1));
        switch ($type) {
            case 'jpg':
            case 'jpeg':
                $src_func = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                    $options['jpeg_quality'] : 75;
            break;
            case 'gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
            case 'png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                    $options['png_quality'] : 9;
                break;
            default:
                return false;
        }
        $src_img = $this->gd_get_image_object(
            $file_path, $src_func, !empty($options['no_cache'])
        );
        $image_oriented = false;
        if (!empty($options['auto_orient']) && $this->gd_orient_image(
                $file_path, $src_img
            )
        ) {
            $image_oriented = true;
            $src_img = $this->gd_get_image_object(
                $file_path, $src_func
            );
        }
        $img_width = imagesx($src_img);
        $img_height = imagesy($src_img);
        $max_height = !empty($options['max_height']) ? $options['max_height'] : $img_height;
        $max_width = !empty($options['max_width']) ? $options['max_width'] : $img_width;

        $scale = min(
            $max_width / $img_width, $max_height / $img_height
        );
        if ($scale >= 1) {
            if ($image_oriented) {
                return $write_func($src_img, $new_file_path, $image_quality);
            }
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            }

            return true;
        }
        if (empty($options['crop'])) {
            $new_width = $img_width * $scale;
            $new_height = $img_height * $scale;
            $dst_x = 0;
            $dst_y = 0;
            $new_img = imagecreatetruecolor($new_width, $new_height);
        } else {
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = $img_width / ($img_height / $max_height);
                $new_height = $max_height;
            } else {
                $new_width = $max_width;
                $new_height = $img_height / ($img_width / $max_width);
            }
            $dst_x = 0 - ($new_width - $max_width) / 2;
            $dst_y = 0 - ($new_height - $max_height) / 2;
            $new_img = imagecreatetruecolor($max_width, $max_height);
        }
        // Handle transparency in GIF and PNG images:
        switch ($type) {
            case 'gif':
            case 'png':
                imagecolortransparent($new_img, imagecolorallocate($new_img, 0, 0, 0));
            case 'png':
                imagealphablending($new_img, false);
                imagesavealpha($new_img, true);
                break;
        }
        $success = imagecopyresampled(
                $new_img, $src_img, $dst_x, $dst_y, 0, 0, $new_width, $new_height, $img_width, $img_height
            ) && $write_func($new_img, $new_file_path, $image_quality);
        $this->gd_set_image_object($file_path, $new_img);

        return $success;
    }

    protected function imagick_get_image_object($file_path, $no_cache = false)
    {
        if (empty($this->image_objects[$file_path]) || $no_cache) {
            $this->imagick_destroy_image_object($file_path);
            $image = new \Imagick();
            if (!empty($this->options['imagick_resource_limits'])) {
                foreach ($this->options['imagick_resource_limits'] as $type => $limit) {
                    $image->setResourceLimit($type, $limit);
                }
            }
            $image->readImage($file_path);
            $this->image_objects[$file_path] = $image;
        }

        return $this->image_objects[$file_path];
    }

    protected function imagick_set_image_object($file_path, $image)
    {
        $this->imagick_destroy_image_object($file_path);
        $this->image_objects[$file_path] = $image;
    }

    protected function imagick_destroy_image_object($file_path)
    {
        $image = @$this->image_objects[$file_path];

        return $image && $image->destroy();
    }

    protected function imagick_orient_image($image)
    {
        $orientation = $image->getImageOrientation();
        $background = new \ImagickPixel('none');
        switch ($orientation) {
            case \imagick::ORIENTATION_TOPRIGHT: // 2
                $image->flopImage(); // horizontal flop around y-axis
                break;
            case \imagick::ORIENTATION_BOTTOMRIGHT: // 3
                $image->rotateImage($background, 180);
                break;
            case \imagick::ORIENTATION_BOTTOMLEFT: // 4
                $image->flipImage(); // vertical flip around x-axis
                break;
            case \imagick::ORIENTATION_LEFTTOP: // 5
                $image->flopImage(); // horizontal flop around y-axis
                $image->rotateImage($background, 270);
                break;
            case \imagick::ORIENTATION_RIGHTTOP: // 6
                $image->rotateImage($background, 90);
                break;
            case \imagick::ORIENTATION_RIGHTBOTTOM: // 7
                $image->flipImage(); // vertical flip around x-axis
                $image->rotateImage($background, 270);
                break;
            case \imagick::ORIENTATION_LEFTBOTTOM: // 8
                $image->rotateImage($background, 270);
                break;
            default:
                return false;
        }
        $image->setImageOrientation(\imagick::ORIENTATION_TOPLEFT); // 1

        return true;
    }

    protected function imagick_create_scaled_image($file_name, $version, $options)
    {
        list($file_path, $new_file_path) = $this->get_scaled_image_file_paths($file_name, $version);
        $image = $this->imagick_get_image_object(
            $file_path, !empty($options['no_cache'])
        );
        if ($image->getImageFormat() === 'GIF') {
            // Handle animated GIFs:
            $images = $image->coalesceImages();
            foreach ($images as $frame) {
                $image = $frame;
                $this->imagick_set_image_object($file_name, $image);
                break;
            }
        }
        $image_oriented = false;
        if (!empty($options['auto_orient'])) {
            $image_oriented = $this->imagick_orient_image($image);
        }
        $img_width = $image->getImageWidth();
        $img_height = $image->getImageHeight();

        $new_width = $max_width = !empty($options['max_width']) ? $options['max_width'] : $img_width;
        $new_height = $max_height = !empty($options['max_height']) ? $options['max_height'] : $img_height;
        if (!($image_oriented || $max_width < $img_width || $max_height < $img_height)) {
            return ($file_path !== $new_file_path) ? copy($file_path, $new_file_path) : true;
        }
        $crop = !empty($options['crop']);
        if ($crop) {
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = 0; // Enables proportional scaling based on max_height
                $x = ($img_width / ($img_height / $max_height) - $max_width) / 2;
            } else {
                $new_height = 0; // Enables proportional scaling based on max_width
                $y = ($img_height / ($img_width / $max_width) - $max_height) / 2;
            }
        }
        $success = $image->resizeImage(
            $new_width, $new_height, isset($options['filter']) ? $options['filter'] : \imagick:: FILTER_LANCZOS, isset($options['blur']) ? $options['blur'] : 1, $new_width && $new_height // fit image into constraints if not to be cropped
        );
        if ($success && $crop) {
            $success = $image->cropImage(
                $max_width, $max_height, isset($x) ? $x : 0, isset($y) ? $y : 0
            );
            if ($success) {
                $success = $image->setImagePage($max_width, $max_height, 0, 0);
            }
        }
        $type = strtolower(substr(strrchr($file_name, '.'), 1));
        if (($type === 'jpeg' || $type === 'jpg') && !empty($options['jpeg_quality'])) {
            $image->setImageCompression(\imagick::COMPRESSION_JPEG);
            $image->setImageCompressionQuality($options['jpeg_quality']);
        }
        if (!empty($options['strip'])) {
            $image->stripImage();
        }

        return $success && $image->writeImage($new_file_path);
    }

    protected function get_image_size($file_path)
    {
        if ($this->options['image_library']) {
            if (extension_loaded('imagick')) {
                $image = new \Imagick();
                try {
                    if (@$image->pingImage($file_path)) {
                        $dimensions = array($image->getImageWidth(), $image->getImageHeight());
                        $image->destroy();

                        return $dimensions;
                    }

                    return false;
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }
        }
        if (!function_exists('getimagesize')) {
            error_log('Function not found: getimagesize');

            return false;
        }

        return @getimagesize($file_path);
    }

    protected function create_scaled_image($file_name, $version, $options)
    {
        if ($this->options['image_library'] && extension_loaded('imagick')) {
            return $this->imagick_create_scaled_image($file_name, $version, $options);
        }

        return $this->gd_create_scaled_image($file_name, $version, $options);
    }

    protected function destroy_image_object($file_path)
    {
        if ($this->options['image_library'] && extension_loaded('imagick')) {
            return $this->imagick_destroy_image_object($file_path);
        }
    }

    protected function is_valid_image_file($file_path)
    {
        if (!preg_match($this->options['image_file_types'], $file_path)) {
            return false;
        }
        if (function_exists('exif_imagetype')) {
            return @exif_imagetype($file_path);
        }
        $image_info = $this->get_image_size($file_path);

        return $image_info && $image_info[0] && $image_info[1];
    }

}
