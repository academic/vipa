<?php

namespace Ojs\WorkflowBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Step invitation
 * In any step if user assigned that step to a user(s) new Invitation document created
 * @MongoDb\Document(collection="invitation")
 */
class Invitation
{

    /**
     * @MongoDb\Id
     */
    protected $id;

    /**
     * @MongoDb\Int
     */
    protected $userId;

    /**
     * @MongoDb\String
     */
    protected $userEmail;

    /**
     * @MongoDb\ReferenceOne(targetDocument="ArticleReviewStep")
     */
    protected $step;

    /**
     * @MongoDb\Date(nullable=true)
     */
    protected $accept;

    /**
     * @MongoDb\Date(nullable=true)
     */
    protected $reject;

    /**
     * Set id
     *
     * @param  $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set step
     *
     * @param  ArticleReviewStep $step
     * @return self
     */
    public function setStep(ArticleReviewStep $step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get step
     *
     * @return ArticleReviewStep $step
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Set userId
     *
     * @param  int  $userId
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userEmail
     *
     * @param  string $userEmail
     * @return $this
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get userEmail
     *
     * @return string $userEmail
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set accept
     *
     * @param  \DateTime $accept
     * @return self
     */
    public function setAccept(\DateTime $accept)
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * Get accept
     *
     * @return \DateTime $accept
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Set reject
     *
     * @param  \DateTime $reject
     * @return self
     */
    public function setReject(\DateTime $reject)
    {
        $this->reject = $reject;

        return $this;
    }

    /**
     * Get reject
     *
     * @return \DateTime $reject
     */
    public function getReject()
    {
        return $this->reject;
    }
}
