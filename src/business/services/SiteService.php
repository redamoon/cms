<?php

/**
 *
 */
class SiteService extends CApplicationComponent
{
	private $_currentSite = null;
	private $_licenseKeyStatus = null;

	/**
	 * @access public
	 *
	 * @return array|null
	 */
	public function getLicenseKeys()
	{
		$keysArr = array();
		$licenseKeys = LicenseKeys::model()->findAll();

		foreach ($licenseKeys as $licenseKey)
			$keysArr[] = $licenseKey->key;

		if (count($keysArr) > 0)
			return $keysArr;

		return null;
	}

	/**
	 * @access public
	 *
	 * @return string|null
	 */
	public function getSiteName()
	{
		if (isset(Blocks::app()->params['config']['siteName']))
			return Blocks::app()->params['config']['siteName'];

		return null;
	}

	/**
	 * @access public
	 *
	 * @return string|null
	 */
	public function getSiteLanguage()
	{
		if (isset(Blocks::app()->params['config']['language']))
			return Blocks::app()->params['config']['language'];

		return null;
	}

	/**
	 * @access public
	 *
	 * @return string|null
	 */
	public function getSiteUrl()
	{
		if (isset(Blocks::app()->params['config']['siteUrl']))
			return Blocks::app()->params['config']['siteUrl'];

		return null;
	}

	/**
	 * @access public
	 *
	 * @return Sites
	 */
	public function getCurrentSiteByUrl()
	{
		if ($this->_currentSite == null)
		{
			$serverName = Blocks::app()->request->serverName;
			$httpServerName = 'http://'.$serverName;
			$httpsServerName = 'https://'.$serverName;

			$site = Sites::model()->find(
				'url=:url OR url=:httpUrl OR url=:httpsUrl', array(':url' => $serverName, ':httpUrl' => $httpServerName, ':httpsUrl' => $httpsServerName)
			);

			$this->_currentSite = $site;
		}

		return $this->_currentSite;
	}

	/**
	 * @access public
	 *
	 * @param $url
	 *
	 * @return Sites
	 */
	public function getSiteByUrl($url)
	{
		$url = ltrim('http://', $url);
		$url = ltrim('https://', $url);

		$httpServerName = 'http://'.$url;
		$httpsServerName = 'https://'.$url;

		$site = Sites::model()->find(
			'url=:url OR url=:httpUrl OR url=:httpsUrl', array(':url' => $url, ':httpUrl' => $httpServerName, ':httpsUrl' => $httpsServerName)
		);

		return $site;
	}

	/**
	 * @access public
	 *
	 * @param $id
	 *
	 * @return Sites
	 */
	public function getSiteById($id)
	{
		$site = Sites::model()->findByPk($id);
		return $site;
	}

	/**
	 * @access public
	 *
	 * @param $handle
	 *
	 * @return Sites
	 */
	public function getSiteByHandle($handle)
	{
		$site = Sites::model()->findByAttributes(array(
			'handle' => $handle,
		));

		return $site;
	}

	/**
	 * @access public
	 *
	 * @return array
	 */
	public function getAllowedTemplateFileExtensions()
	{
		return array('html', 'php');
	}

	/**
	 * @access public
	 *
	 * @param $templatePath
	 * @param string $srcLanguage
	 *
	 * @return null|string
	 */
	public function matchTemplatePathWithAllowedFileExtensions($templatePath, $srcLanguage = 'en-us')
	{
		foreach ($this->allowedTemplateFileExtensions as $allowedExtension)
		{
			$templateFile = Blocks::app()->findLocalizedFile($templatePath.'.'.$allowedExtension, $srcLanguage);
			if (is_file($templateFile))
				return realpath($templateFile);
		}

		return null;
	}

	/**
	 * @access public
	 *
	 * @return string
	 */
	public function getLicenseKeyStatus()
	{
		$licenseKeyStatus = Blocks::app()->fileCache->get('licenseKeyStatus');
		if ($licenseKeyStatus == false)
			$licenseKeyStatus = $this->_getLicenseKeyStatus();

		return $licenseKeyStatus;

	}

	/**
	 * @access public
	 *
	 * @param $licenseKeyStatus
	 */
	public function setLicenseKeyStatus($licenseKeyStatus)
	{
		// cache it and set it to expire according to config
		Blocks::app()->fileCache->set('licenseKeyStatus', $licenseKeyStatus, Blocks::app()->config('cacheTimeSeconds'));
	}

	/**
	 * @access private
	 *
	 * @return string
	 */
	private function _getLicenseKeyStatus()
	{
		$licenseKeys = Blocks::app()->site->licenseKeys;

		if (!$licenseKeys)
			return LicenseKeyStatus::MissingKey;

		$package = Blocks::app()->et->ping();
		$licenseKeyStatus = $package->licenseKeyStatus;
		$this->setLicenseKeyStatus($licenseKeyStatus);
		return $licenseKeyStatus;
	}
}
