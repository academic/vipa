<?php

namespace Ojstr\JournalBundle\Entity;

/**
 * Author
 */
class Author extends Model\AuthorModel {

    /**
     * @var integer
     */
    public  $id;

    /**
     * @var string
     */
    public  $firstName;

    /**
     * @var string
     */
    public  $middleName;

    /**
     * @var string
     */
    public  $lastName;

    /**
     * @var string
     */
    public  $email;

    /**
     * @var string
     */
    public  $firstNameTransliterated;

    /**
     * @var string
     */
    public  $middleNameTransliterated;

    /**
     * @var string
     */
    public  $lastNameTransliterated;

    /**
     * @var string
     */
    public  $initials;

    /**
     * @var string
     */
    public  $address;

    /**
     * @var integer
     */
    public  $institutionId;

    /**
     * @var integer
     */
    public  $country;

    /**
     * @var string
     */
    public  $summary;

    /**
     * @var integer
     */
    public  $userId;
    
    /**
     * @var \Ojstr\UserBundle/Entity/User
     */
    public  $user;


}
