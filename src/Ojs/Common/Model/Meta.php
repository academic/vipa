<?php

namespace Ojs\Common\Model;

/**
 * @author Hasan Tayyar BESIK <tayyar.besik@okulbilisim.com>
 * @abstract
 *  a lightweight class to stack and manage meta tags
 *  extended from eusonlito/laravel-Meta 
 *  added multiple tag  value assignment to same key
 */
class Meta {

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var array
     */
    private $metas = [];

    /**
     * @var string
     */
    private $title;

    /**
     * @param  array $config
     * @return this
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['title_limit'])) {
            $config['title_limit'] = 70;
        }

        if (!isset($config['description_limit'])) {
            $config['description_limit'] = 200;
        }

        $this->config = $config;
    }

    /**
     * @param  string $title
     * @return string
     */
    public function title($title = null)
    {
        if ($title === null) {
            return $this->title;
        }

        $this->title = null;

        $this->set('title', $title);

        return $this->title = $this->fix($title);
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return string
     */
    public function meta($key, $value = null)
    {
        if ($value === null) {
            return $this->get($key);
        }

        return $this->set($key, $value);
    }

    /**
     * @param  string $key
     * @return string
     */
    public function get($key)
    {
        if (empty($this->metas[$key])) {
            return null;
        }

        return $this->metas[$key];
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return string
     */
    public function set($key, $value = null)
    {
        if (is_array($value)) {
            for ($i = 0; $i < count($value); $i++) {
                $value[$i] = $this->fix($value[$i]);
            }
        } else {
            $value = $this->fix($value);
        }
        $method = 'set' . $key;

        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        return $this->metas[$key] = self::cut($value, $key);
    }

    /**
     * @param  string $value
     * @return string
     */
    private function setTitle($value)
    {
        $title = $this->title;

        if ($title && $this->config['title_limit']) {
            $title = ' - ' . $title;
            $limit = $this->config['title_limit'] - strlen($title);
        } else {
            $limit = 'title';
        }

        return $this->metas['title'] = self::cut($value, $limit) . $title;
    }

    /**
     * @param  string $value
     * @return string
     */
    private function setImage($value)
    {
        if (!isset($this->metas['image'])) {
            $this->metas['image'] = [];
        }

        return $this->metas['image'][] = $value;
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return string
     */
    public function tag($key, $value = null)
    {
        if (($value === null) && empty($this->metas[$key])) {
            return '';
        }

        $method = 'tag' . ucfirst($key);

        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        return $this->tagDefault($key, $value);
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return string
     */
    private function tagDefault($key, $value = null)
    {
        return $this->tagMetaName($key, $value) . $this->tagString('property', $key, $value);
    }

    /**
     * @param  string $key
     * @param  mixed $images
     * @return string
     */
    public function tagImage($images = null)
    {
        $html = '';

        foreach ((array) ($images ? : $this->metas['image']) as $image) {
            $html .= $this->tagDefault('image', $image)
                    . '<link rel="image_src" href="' . $image . '" />';
        }

        return $html;
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return string
     */
    public function tagMetaName($key, $value = null)
    {
        $metaString = '';
        if (is_array($value)) {
            foreach ($value as $item) {
                $metaString.=$this->tagString('name', $key, $item);
            }
        } else {
            $metaString = $this->tagString('name', $key, $value);
        }
    }

    /**
     * @param  string $name
     * @param  string $key
     * @param  string $value
     * @return string
     */
    private function tagString($name, $key, $value = null)
    {
        $metaStr = '';
        if (isset($this->metas[$key]) && is_array($this->metas[$key])) {
            foreach ($this->metas[$key] as $item) {
                $metaStr.= '<meta ' . $name . '="' . $key . '" content="' . $item . '" />';
            }
        } else {
            $metaStr = '<meta ' . $name . '="' . $key . '" content="' . ($value ? : $this->metas[$key]) . '" />';
        }
        return $metaStr;
    }

    /**
     * @param  string $text
     * @return string
     */
    private function fix($text)
    {
        $text = preg_replace('/<[^>]+>/', ' ', $text);
        $text = preg_replace('/[\r\n\s]+/', ' ', $text);
        return trim(str_replace('"', '&quot;', $text));
    }

    /**
     * @param  string $text
     * @param  string $key
     * @return string
     */
    private function cut($text, $key)
    {
        if (is_string($key) && isset($this->config[$key . '_limit'])) {
            $limit = $this->config[$key . '_limit'];
        } elseif (is_integer($key)) {
            $limit = $key;
        } else {
            return $text;
        }

        $length = strlen($text);

        if ($length <= (int) $limit) {
            return $text;
        }

        $text = substr($text, 0, ($limit -= 3));

        if ($space = strrpos($text, ' ')) {
            $text = substr($text, 0, $space);
        }

        return $text . '...';
    }

}
