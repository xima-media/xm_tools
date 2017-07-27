<?php
namespace Xima\XmTools\ViewHelpers\Focuspoint;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class BackgroundPositionViewHelper
 *
 */
class BackgroundPositionViewHelper extends AbstractViewHelper
{
    /**
     * @param string $focus_point_x
     * @param string $focus_point_y
     * @return mixed|string
     */
    public function render($focus_point_x = '0', $focus_point_y = '0')
    {
        return static::renderStatic(
            [
                'focus_point_x' => $focus_point_x,
                'focus_point_y' => $focus_point_y
            ],
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext)
    {
        $focus_point_x = $arguments['focus_point_x'];
        $focus_point_y = $arguments['focus_point_y'];

        $xPercent = (100 + intval($focus_point_x))/2;
        $yPercent = (100 + intval($focus_point_y))/2;

        return 'style="background-position: left ' . $xPercent . '% top ' . $yPercent .'%"';
    }
}
