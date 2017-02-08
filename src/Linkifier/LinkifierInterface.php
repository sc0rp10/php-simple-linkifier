<?php

/**
 * This file is part of sc/php-simple-linkify.
 *
 * © Konstantin Zamyakin <dev@weblab.pro>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sc\Linkifier;

interface LinkifierInterface
{
    public function linkifyText(string $text, array $attrs = []): string;
}
