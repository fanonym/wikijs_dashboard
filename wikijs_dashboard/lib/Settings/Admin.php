<?php

declare(strict_types=1);

namespace OCA\WikiJsDashboard\Settings;

use OCA\WikiJsDashboard\AppInfo\Application;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\Settings\ISettings;

class Admin implements ISettings {
	public function __construct(
		private IConfig $config,
	) {
	}

	public function getForm(): TemplateResponse {
		$app = Application::APP_ID;
		$params = [
			'wikijs_url' => $this->config->getAppValue($app, 'wikijs_url', ''),
			'wikijs_token' => $this->config->getAppValue($app, 'wikijs_token', ''),
			'wikijs_public_url' => $this->config->getAppValue($app, 'wikijs_public_url', ''),
			'wikijs_locale' => $this->config->getAppValue($app, 'wikijs_locale', 'cs'),
			'limit' => $this->config->getAppValue($app, 'limit', '7'),		];

		return new TemplateResponse($app, 'admin', $params, '');
	}

	public function getSection(): string {
		return 'additional';
	}

	public function getPriority(): int {
		return 50;
	}
}
