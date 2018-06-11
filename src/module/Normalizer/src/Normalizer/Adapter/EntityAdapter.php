<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Normalizer\Adapter;

use DateTime;
use Entity\Entity\EntityInterface;

class EntityAdapter extends AbstractAdapter
{
    /**
     * @return EntityInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    public function isValid($object)
    {
        return $object instanceof EntityInterface;
    }

    protected function getContent()
    {
        return $this->getField('content');
    }

    protected function getCreationDate()
    {
        $head = $this->getObject()->getHead();
        if ($head) {
            return $head->getTimestamp();
        }
        return new DateTime();
    }

    /**
     * @return string
     */
    protected function getDescription()
    {
        return $this->getField(['summary', 'description', 'content']);
    }

    protected function getField($field, $default = '')
    {
        $entity = $this->getObject();
        $id     = $entity->getId();

        if (is_array($field)) {
            $fields = $field;
            $value  = '';
            foreach ($fields as $field) {
                $value = $this->getField((string)$field);
                if ($value && $value != $id) {
                    break;
                }
            }

            return $value ? : $id;
        }


        $revision = $entity->hasCurrentRevision() ? $entity->getCurrentRevision() : $entity->getHead();

        if (!$revision) {
            return $default;
        }

        $value = $revision->get($field);

        return $value ? : $id;
    }

    protected function getId()
    {
        return $this->getObject()->getId();
    }

    protected function getKeywords()
    {
        $entity   = $this->getObject();
        $keywords = [];
        $terms = $entity->getTaxonomyTerms();
        if (!$terms->count()) {
            $parents = $entity->getParents('link');
            if($parents->count()) {
                $terms = $parents->first()->getTaxonomyTerms();
            }
        }
        foreach ($terms as $term) {
            while ($term->hasParent()) {
                $keywords[] = $term->getName();
                $term       = $term->getParent();
            }
        }
        return array_unique($keywords);
    }

    /**
     * @return DateTime
     */
    protected function getLastModified()
    {
        $head = $this->getObject()->getHead();
        if ($head) {
            return $head->getTimestamp();
        }
        return new DateTime();
    }

    protected function getPreview()
    {
        return $this->getField(['summary', 'description', 'content']);
    }

    protected function getRouteName()
    {
        return 'entity/page';
    }

    protected function getRouteParams()
    {
        return [
            'entity' => $this->getObject()->getId()
        ];
    }

    protected function getTitle()
    {
        return $this->getField(['title', 'id'], $this->getId());
    }

    protected function getType()
    {
        return $this->getObject()->getType()->getName();
    }

    protected function isTrashed()
    {
        return $this->getObject()->isTrashed();
    }
}
