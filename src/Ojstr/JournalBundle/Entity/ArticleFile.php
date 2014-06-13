<?php

namespace Ojstr\JournalBundle\Entity;

/**
 * ArticleFile
 */
class ArticleFile extends \Ojstr\Common\Entity\GenericExtendedEntity {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $size;

    /**
     * @var integer
     */
    private $articleId;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return ArticleFile
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ArticleFile
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return ArticleFile
     */
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType() {
        return $this->mimeType;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return ArticleFile
     */
    public function setSize($size) {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Set articleId
     *
     * @param integer $articleId
     * @return ArticleFile
     */
    public function setArticleId($articleId) {
        $this->articleId = $articleId;

        return $this;
    }

    /**
     * Get articleId
     *
     * @return integer 
     */
    public function getArticleId() {
        return $this->articleId;
    }

    /**
     * callback - This option allows you to set a method name. 
     * If this option is set, the method will be called after the file is moved. Default value: "". 
     * As first argument, this method can receive an array with information about the uploaded file, 
     * which includes the following keys:
     * 
     * - fileName: The filename.
     * - fileExtension: The extension of the file (including the dot). Example: .jpg
     * - fileWithoutExt: The filename without the extension.
     * - filePath: The file path. Example: /my/path/filename.jpg
     * - fileMimeType: The mime-type of the file. Example: text/plain.
     * - fileSize: Size of the file in bytes. Example: 140000.
     * 
     * @param array $info
     */
    public function articleFileCallback(array $info) {
        // Do some stuff with the file..
    }

}
