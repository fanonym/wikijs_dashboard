<?php

declare(strict_types=1);

namespace OCA\WikiJsDashboard\AppInfo;

use OCA\WikiJsDashboard\Dashboard\WikiJsWidget;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Bootstrap\IBootContext;

class Application extends App implements IBootstrap {
	public const APP_ID = 'wikijs_dashboard';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		// Register Dashboard widget (API v2)
		$context->registerDashboardWidget(WikiJsWidget::class);
	}

	public function boot(IBootContext $context): void {
		// nothing
	}
}
