<?php

namespace Xima\XmTools\ViewHelpers\Focuspoint;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
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

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class BackgroundPositionViewHelper
 */
class BackgroundPositionViewHelper extends AbstractViewHelper
{

    public function initializeArguments()
    {
        $this->registerArgument('focus_point_x', 'string', 'Focuspoint X', false, '0');
        $this->registerArgument('focus_point_y', 'string', 'Focuspoint Y', false, '0');
        $this->registerArgument('orientation', 'string', 'One of x, y or both', false, 'both');
    }

    /**
     * @return mixed|string
     */
    public function render()
    {
        $focus_point_x = $this->arguments['focus_point_x'];
        $focus_point_y = $this->arguments['focus_point_y'];
        $orientation = $this->arguments['orientation'];

        return static::renderStatic(
            [
                'focus_point_x' => $focus_point_x,
                'focus_point_y' => $focus_point_y,
                'orientation' => $orientation,
            ],
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $focus_point_x = $arguments['focus_point_x'];
        $focus_point_y = $arguments['focus_point_y'];
        $orientation = $arguments['orientation'];

        $xPercent = (100 + (int)$focus_point_x)/2;
        $yPercent = (100 + (int)$focus_point_y)/2;

        switch ($orientation) {
            case 'x':
                $style = 'style="background-position-x: ' . $xPercent . '%"';
                break;
            case 'y':
                $style = 'style="background-position-y: ' . $yPercent . '%"';
                break;
            case 'both':
            default:
                $style = 'style="background-position: ' . $xPercent . '% ' . $yPercent . '%"';
        }

        return $style;
    }
}
