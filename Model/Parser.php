<?php

declare(strict_types=1);

namespace Erybkin\RouteList\Model;

/**
 * Class Parser
 */
class Parser
{
    /**
     * Controller path separator
     *
     * @var string
     */
    private const PATH_SEPARATOR = '\\';

    /**
     * Adminhtml area label
     *
     * @var string
     */
    private const AREA_ADMINHTML = 'adminhtml';

    /**
     * Frontend area label
     *
     * @var string
     */
    private const AREA_FRONTEND  = 'frontend';

    /**
     * Parses controller path string
     *
     * @param  string $controllerPath
     * @param  string $separator
     *
     * @return array
     */
    public function parseControllerPath(string $controllerPath, string $separator = self::PATH_SEPARATOR): array
    {
        $controllerArray = explode($separator, $controllerPath);

        $moduleName = $this->getControllerModuleName($controllerArray);
        $area       = $this->getControllerArea($controllerArray);
        $namespace  = $this->getControllerNamespace($controllerArray, $area);
        $actionName = $this->getControllerActionName($controllerArray, $area);

        return compact(
            'moduleName',
            'area',
            'namespace',
            'actionName',
            'controllerPath'
        );
    }

    /**
     * Resolves controller's module name
     *
     * @param  array  $controllerArray
     *
     * @return string
     */
    private function getControllerModuleName(array $controllerArray): string
    {
        $moduleName = sprintf(
            '%s_%s',
            $controllerArray[0],
            $controllerArray[1]
        );

        return $moduleName;
    }

    /**
     * Resolves controller's area
     *
     * @param  array  $controllerArray
     *
     * @return string
     */
    private function getControllerArea(array $controllerArray): string
    {
        $area = $controllerArray[3] === 'Adminhtml'
            ? self::AREA_ADMINHTML
            : self::AREA_FRONTEND;

        return $area;
    }

    /**
     * Resolves controller's namespace
     *
     * @param  array  $controllerArray
     * @param  string $area
     *
     * @return string
     */
    private function getControllerNamespace(array $controllerArray, string $area): string
    {
        $namespace = $area == self::AREA_ADMINHTML
            ? $controllerArray[4]
            : $controllerArray[3];

        return $namespace;
    }

    /**
     * Resolves controller's action name
     *
     * @param  array  $controllerArray
     * @param  string $area
     *
     * @return string
     */
    private function getControllerActionName(array $controllerArray, string $area): string
    {
        // TODO: implemet abstract controller identifying
        if (!isset($controllerArray[4])) {
            return 'abstract?';
        }

        if (!isset($controllerArray[5]) && $area == self::AREA_ADMINHTML) {
            return 'abstract?';
        }

        $actionName = $area == self::AREA_ADMINHTML
            ? $controllerArray[5]
            : $controllerArray[4];

        return $actionName;
    }
}
