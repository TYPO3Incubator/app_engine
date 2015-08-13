<?php
namespace TYPO3Incubator\AppEngine\Service;

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

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3Incubator\AppEngine\Domain\Model\Configuration;

class ModuleService implements SingletonInterface {

	/**
	 * @return ModuleService
	 */
	static public function create() {
		return GeneralUtility::makeInstance(static::class);
	}

	/**
	 * @var bool
	 */
	protected $hasMainModule = FALSE;

	/**
	 * Assigns App configurations to backend modules.
	 *
	 * @param Configuration $configuration
	 */
	public function assignModule(Configuration $configuration) {
		if (!$this->hasMainModule) {
			$this->registerMainModule();
			$this->hasMainModule = TRUE;
		}
		$this->registerSubModule($configuration);
	}

	/**
	 * Registers the main "Apps" module.
	 */
	protected function registerMainModule() {
		ExtensionUtility::registerModule(
			'TYPO3Incubator.AppEngine',
			'App',
			'',
			'',
			array(
				'App' => 'index'
			),
			array(
				'access' => 'user,group',
				'icon' => 'module-app',
				'labels' => 'LLL:EXT:app_engine/Resources/Private/Language/Apps.xlf',
			)
		);

		$this->moveMainModuleToTop();
	}

	/**
	 * Moves the main module to be the first menu in the list
	 * by re-arranging the array items of $GLOBALS['TBE_MODULES'].
	 */
	protected function moveMainModuleToTop() {
		$modules = array('AppEngineApp' => $GLOBALS['TBE_MODULES']['AppEngineApp']);
		unset($GLOBALS['TBE_MODULES']['AppEngineApp']);
		$GLOBALS['TBE_MODULES'] = array_merge($modules, $GLOBALS['TBE_MODULES']);
	}

	/**
	 * Registers any sub-module below the main "Apps" module.
	 *
	 * @param Configuration $configuration
	 */
	protected function registerSubModule(Configuration $configuration) {
		ExtensionUtility::registerModule(
			'TYPO3Incubator.AppEngine',
			'App',
			'ID' . $configuration->getIdentifier(),
			'',
			array(
				'App' => 'dispatch,invalid'
			),
			array(
				'access' => 'user,group',
				'icon' => $configuration->getValue('app.iconFile'),
				'labels' => $configuration->getValue('app.labelFile'),
			)
		);
	}

}