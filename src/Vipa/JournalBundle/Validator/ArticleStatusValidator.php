<?php

namespace Vipa\JournalBundle\Validator;

use Vipa\CoreBundle\Params\ArticleStatuses;
use Vipa\JournalBundle\Entity\Article;
use Vipa\UserBundle\Validator\Constraints\UniqueMultipleEmails;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class ArticleStatusValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint|UniqueMultipleEmails $constraint
     * @return mixed
     */
    public function validate($value, Constraint $constraint)
    {
        $article = $this->context->getRoot();

        if (!$article instanceof Article) {
            $article = $article->getData();
        }

        if($article->getId() == null){
            return;
        }
        if($article->getStatus() !== ArticleStatuses::STATUS_PUBLISHED){
            return;
        }
        if($article->getArticleAuthors()->count()<1){
            $this->context->addViolation($constraint->message);
        }
    }
}
