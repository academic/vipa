<?php

namespace Ojstr\AnalyticsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * This collections keeps page information and like count *without user info*
 * @MongoDb\Document(collection="analytics_likesum_article") 
 */
class ArticleLike extends ArticleView {
    
}
