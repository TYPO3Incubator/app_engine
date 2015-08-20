<?php
namespace TYPO3Incubator\AppEngine\Domain\Model;

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
use TYPO3\CMS\Extbase\Utility\ArrayUtility;
use Symfony\Component\Yaml;

class Configuration {

	/**
	 * @return Configuration
	 */
	static public function create() {
		return GeneralUtility::makeInstance(static::class);
	}

	/**
	 * @param string $fileName
	 * @return Configuration
	 */
	static public function parse($fileName) {
		$identifier = sha1($fileName);
		$yamlParser = new Yaml\Parser();
		$data = $yamlParser->parse(
			file_get_contents($fileName)
		);

		$configuration = static::create();
		$configuration->setFileName($fileName);
		$configuration->setIdentifier($identifier);
		$configuration->setData($data);

		return $configuration;
	}

	/**
	 * @var string
	 */
	protected $fileName;

	/**
	 * @var string
	 */
	protected $identifier;

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @param string $fileName
	 */
	public function setFileName($fileName) {
		$this->fileName = $fileName;
	}

	/**
	 * @return string
	 */
	public function getFileName() {
		return $this->fileName;
	}

	/**
	 * @param string $identifier
	 */
	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}

	/**
	 * @return string
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * @param array $data
	 */
	public function setData(array $data = NULL) {
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @param string $path
	 * @return NULL|bool|int|string|array
	 */
	public function getValue($path) {
		try {
			return ArrayUtility::getValueByPath($this->data, $path);
		} catch(\InvalidArgumentException $exception) {
			return NULL;
		}
	}

}