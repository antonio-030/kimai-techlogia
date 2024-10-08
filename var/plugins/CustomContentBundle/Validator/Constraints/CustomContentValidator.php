<?php

/*
 * This file is part of the "Custom Content Bundle" for Kimai.
 * All rights reserved by Kevin Papst (www.kevinpapst.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomContentBundle\Validator\Constraints;

use KimaiPlugin\CustomContentBundle\Entity\CustomContent;
use KimaiPlugin\CustomContentBundle\Validator\Constraints\CustomContent as CustomContentConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CustomContentValidator extends ConstraintValidator
{
    /**
     * @param string $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!($constraint instanceof CustomContentConstraint)) {
            throw new UnexpectedTypeException($constraint, CustomContentConstraint::class);
        }

        if (!($value instanceof CustomContent)) {
            return;
        }

        if ($value->getAlert() !== null) {
            $this->validateNoSpecialTag($value->getAlert(), 'alert');
            $this->validateNoTag($value->getAlert(), 'alert');
        }

        if ($value->getJavascript() !== null) {
            $this->validateNoSpecialTag($value->getJavascript(), 'javascript');
        }

        if ($value->getStylesheet() !== null) {
            $this->validateNoSpecialTag($value->getStylesheet(), 'stylesheet');
        }
    }

    private function validateNoTag(string $value, string $path): bool
    {
        $value = strtolower($value);

        if (strip_tags($value) !== $value) {
            $this->context->buildViolation(CustomContentConstraint::getErrorName(CustomContentConstraint::TAGS_DISALLOWED))
                ->atPath($path)
                ->setTranslationDomain('validators')
                ->setCode(CustomContentConstraint::TAGS_DISALLOWED)
                ->addViolation();

            return false;
        }

        return true;
    }

    private function validateNoSpecialTag(string $value, string $path): bool
    {
        $value = strtolower($value);

        if (stripos($value, '<style') !== false || stripos($value, '<script') !== false || stripos($value, '</style') !== false || stripos($value, '</script') !== false) {
            $this->context->buildViolation(CustomContentConstraint::getErrorName(CustomContentConstraint::SPECIAL_TAGS_DISALLOWED))
                ->atPath($path)
                ->setTranslationDomain('validators')
                ->setCode(CustomContentConstraint::SPECIAL_TAGS_DISALLOWED)
                ->addViolation();

            return false;
        }

        return true;
    }
}
