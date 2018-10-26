<?php
/**
 * Created by https://github.com/Wheiss
 * Date: 26.10.2018
 * Time: 23:26
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NullableImage extends Constraint
{
    public $message = '{{ image }} must be null or image file.';
}