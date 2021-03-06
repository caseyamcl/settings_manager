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

namespace SettingsManager\Provider;

use SettingsManager\Model\SettingValue;
use SettingsManager\Behavior\SettingProviderTrait;
use SettingsManager\Contract\SettingProvider;
use SettingsManager\Exception\ImmutableSettingOverrideException;

/**
 * Cascading setting provider
 *
 * Reads settings from multiple providers.  This is an immutable object, but it is clone-able (via the `with()` method)
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class CascadingSettingProvider implements SettingProvider
{
    use SettingProviderTrait;

    /**
     * @var array|SettingProvider[]
     */
    private $providers;

    /**
     * @var array|SettingValue[]
     */
    private $valuesCache = [];

    /**
     * Alternate constructor
     *
     * @param SettingProvider[] $provider
     * @return CascadingSettingProvider
     */
    public static function build(SettingProvider ...$provider): self
    {
        return new static($provider);
    }

    /**
     * CascadeProvider constructor.
     * @param iterable $providers
     */
    public function __construct(iterable $providers)
    {
        foreach ($providers as $provider) {
            $this->add($provider);
        }
    }

    /**
     * Add a setting provider
     *
     * @param SettingProvider $provider
     */
    private function add(SettingProvider $provider): void
    {
        $this->providers[$provider->getName()] = $provider;

        // Initialize setting values
        foreach ($provider->getSettingValues() as $value) {
            $valueCollision = array_key_exists($value->getName(), $this->valuesCache)
                && (! $this->valuesCache[$value->getName()]->isMutable());

            // If the setting is already in the out array and is immutable, throw exception.
            if ($valueCollision) {
                throw ImmutableSettingOverrideException::build(
                    $value->getName(),
                    $provider->getName(),
                    $this->valuesCache[$value->getName()]->getProviderName()
                );
            }

            $this->valuesCache[$value->getName()] = $value;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'cascade';
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return 'Cascading provider interface';
    }

    /**
     * Return a key/value set of setting names/values
     *
     * @return iterable|SettingValue[]
     */
    public function getSettingValues(): iterable
    {
        return $this->valuesCache;
    }

    /**
     * @param string $name
     * @return SettingProvider|null
     */
    public function findValueInstance(string $name): ?SettingValue
    {
        return $this->getSettingValues()[$name] ?? null;
    }

    /**
     * Clone this, adding a provider to the cloned instance
     *
     * @param SettingProvider $provider
     * @return $this
     */
    public function withProvider(SettingProvider $provider): self
    {
        $that = clone $this;
        $that->add($provider);
        return $that;
    }
}
