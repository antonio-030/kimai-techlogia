<?php

/*
 * This file is part of the "Custom Content Bundle" for Kimai.
 * All rights reserved by Kevin Papst (www.kevinpapst.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\CustomContentBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CustomContent extends Constraint
{
    public const TAGS_DISALLOWED = 'kimai-custom-content-01';
    public const SPECIAL_TAGS_DISALLOWED = 'kimai-custom-content-02';

    /**
     * @var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::TAGS_DISALLOWED => 'HTML_TAGS',
        self::SPECIAL_TAGS_DISALLOWED => 'SPECIAL_TAGS',
    ];

    public string $message = 'Your custom content settings are invalid.';

    public function getTargets(): string|array
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
