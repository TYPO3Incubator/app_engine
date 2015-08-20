<?php
namespace TYPO3Incubator\AppEngine\Controller;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Incubator\AppEngine\Service\ConfigurationService;

class AppController extends ActionController {

	protected function initializeAction() {
		$this->settings['configuration'] = $this->getCurrentConfiguration();
	}

	public function indexAction() {

	}

	public function dispatchAction() {
		$configuration = $this->getCurrentConfiguration();

		if ($configuration === NULL) {
			$this->redirect('invalid');
		}
	}

	public function invalidAction() {

	}

	protected function getCurrentConfiguration() {
		$identifier = $this->getCurrentIdentifier();
		return ConfigurationService::create()->getConfiguration($identifier);
	}

	protected function getCurrentIdentifier() {
		$moduleName = GeneralUtility::_GET('M');
		$prefix = 'AppEngineApp_AppEngineId';

		if (strpos($moduleName, $prefix) === 0) {
			return substr($moduleName, strlen($prefix));
		}

		return NULL;
	}

}