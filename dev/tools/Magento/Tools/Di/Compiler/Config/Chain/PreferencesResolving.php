<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Tools\Di\Compiler\Config\Chain;

use Magento\Tools\Di\Compiler\Config\ModificationInterface;

class PreferencesResolving implements ModificationInterface
{
    /**
     * Modifies input config
     *
     * @param array $config
     * @return array
     */
    public function modify(array $config)
    {
        if (!isset($config['arguments']) || !isset($config['preferences'])) {
            return $config;
        }

        $this->resolvePreferences($config['arguments'], $config['preferences']);

        return $config;
    }

    /**
     * Replaces interfaces to their concrete implementations in scope of current config
     *
     * @param array $argument
     * @param array $preferences
     * @return array
     */
    private function resolvePreferences(&$argument, &$preferences)
    {
        if (!is_array($argument)) {
            return;
        }

        foreach ($argument as $key => &$value) {
            if (in_array($key, ['_i_', '_ins_'])) {
                $value = isset($preferences[$value]) ? $preferences[$value] : $value;
                continue;
            }

            if (is_array($value)) {
                $this->resolvePreferences($value, $preferences);
            }
        }
        return;
    }
}
