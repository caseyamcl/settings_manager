<?php

/**
 * Settings Manager
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/caseyamcl/settings-manager
 * @package caseyamcl/settings-manager
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *  ------------------------------------------------------------------
 */

declare(strict_types=1);

namespace SettingsManager\Contract;

/**
 * Setting Interface defines the attributes for a setting
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
interface SettingDefinition
{
    /**
     * The canonical name for this setting (machine-friendly)
     * @return string
     */
    public function getName(): string;

    /**
     * Get a human-friendly display name for this setting
     * @return string
     */
    public function getDisplayName(): string;

    /**
     * Get notes/documentation for this setting
     *
     * @return string
     */
    public function getNotes(): string;

    /**
     * Return the default value for this setting
     *
     * If no default, return NULL
     *
     * @return mixed
     */
    public function getDefault();

    /**
     * Should this setting value be exposed in the API as-is, or masked/hidden?
     *
     * @return bool
     */
    public function isSensitive(): bool;

    /**
     * Process, validate, and store a new value
     *
     * @param mixed $value  The raw value
     * @return mixed  The processed value to store
     */
    public function processValue($value);
}
