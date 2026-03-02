<?php

declare(strict_types=1);

namespace OCA\WikiJsDashboard\Controller;

use OCA\WikiJsDashboard\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IConfig;
use OCP\IRequest;

class SettingsController extends Controller {
	public function __construct(
		IRequest $request,
		private IConfig $config,
	) {
		parent::__construct(Application::APP_ID, $request);
	}

	/**
	 * @AdminRequired
	 */
	public function saveAdmin(): DataResponse {
		$app = Application::APP_ID;

		$wikijsUrl = trim((string)$this->request->getParam('wikijs_url', ''));
		$wikijsToken = trim((string)$this->request->getParam('wikijs_token', ''));
		$publicUrl = trim((string)$this->request->getParam('wikijs_public_url', ''));
		$locale = trim((string)$this->request->getParam('wikijs_locale', 'cs'));
		$limit = trim((string)$this->request->getParam('limit', '7'));

		$this->config->setAppValue($app, 'wikijs_url', $wikijsUrl);
		$this->config->setAppValue($app, 'wikijs_token', $wikijsToken);
		$this->config->setAppValue($app, 'wikijs_public_url', $publicUrl);
		$this->config->setAppValue($app, 'wikijs_locale', $locale);
		$this->config->setAppValue($app, 'limit', $limit);

		return new DataResponse(['status' => 'ok']);
	}
}
