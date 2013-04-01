<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2013
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class UrlHelper extends AppHelper {
	protected $urlParams = null;

	protected $namedParams = null;

	public $url = '/';

	/**
	 * Retreives the current URL
	 *
	 * @return string returns the the current URL in string form with queryString or pagination params
	 */
	public function currentUrl() {
		$this->urlParams = $this->params['url'];
		$this->namedParams = $this->params['named'];

		$this->url .= $this->urlParams['url'];
		unset($this->urlParams['url']);

		// if $this->named is empty, no pagination is being used
		if (!empty($this->namedParams)) {
		}

		if (!empty($this->urlParams)) {
			$i = 0;
			foreach ($this->urlParams as $key => $value) {
				if ($i === 0) {
					$this->url .= "?$key=$value";
				} else {
					$this->url .= "&$key=$value";
				}
				$i++;
			}
		}

		return $this->url;
	}
}
