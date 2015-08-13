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
use TYPO3Incubator\AppEngine\Domain\Model\Configuration;

class ConfigurationService implements SingletonInterface {

	/**
	 * @return ConfigurationService
	 */
	static public function create() {
		return GeneralUtility::makeInstance(static::class);
	}

	/**
	 * @var array|Configuration[]
	 */
	protected $configurations = array();

	/**
	 * @param string $file
	 * @throws \Exception
	 */
	public function addConfiguration($file) {
		$fileName = GeneralUtility::getFileAbsFileName($file);
		if (!file_exists($fileName)) {
			return;
		}

		$configuration = Configuration::parse($fileName);
		if (!isset($this->configurations[$configuration->getIdentifier()])) {
			try {
				$this->configurations[$configuration->getIdentifier()] = $configuration;
				ModuleService::create()->assignModule($configuration);
			} catch(\Exception $exception) {
				// @todo Handle and output failed parsing process
				throw $exception;
			}
		}
	}

	/**
	 * @return array|Configuration[]
	 */
	public function getConfigurations() {
		return $this->configurations;
	}

	/**
	 * @param string $identifier
	 * @return NULL|Configuration
	 */
	public function getConfiguration($identifier) {
		if (empty($this->configurations[$identifier])) {
			return NULL;
		}

		return $this->configurations[$identifier];
	}

}