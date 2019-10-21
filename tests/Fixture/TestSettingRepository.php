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

namespace SettingsManager\Fixture;

use SettingsManager\Contract\SettingRepositoryInterface;
use SettingsManager\Contract\SettingValueInterface;

class TestSettingRepository implements SettingRepositoryInterface
{
    private $values = [];

    /**
     * @param SettingValueInterface $settingValue
     */
    public function addValue(SettingValueInterface $settingValue)
    {
        $this->values[$settingValue->getName()] = $settingValue->getValue();
    }

    /**
     * Find a setting value by its name
     *
     * @param string $settingName
     * @return mixed|null
     */
    public function findValue(string $settingName)
    {
        return (array_key_exists($settingName, $this->values)) ? $this->values[$settingName] : null;
    }

    /**
     * List values
     *
     * @return iterable|mixed[]
     */
    public function listValues(): iterable
    {
        return $this->values;
    }
}
