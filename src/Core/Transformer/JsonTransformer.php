<?php

namespace App\Core\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Handles transforming json to array and backward
 */
class JsonTransformer implements DataTransformerInterface
{

    /**
     * @inheritDoc
     * @return mixed
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return [];
        }
        return json_decode($value);
    }

    /**
     * @inheritDoc
     * @return mixed
     */
    public function transform($value)
    {
        if (empty($value)) {
            return json_encode([]);
        }
        return json_encode($value);
    }
}