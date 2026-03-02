<?php
declare(strict_types=1);

namespace OCA\WikiJsDashboard\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;
use OCA\WikiJsDashboard\Service\WikiJsClient;

class ApiController extends Controller {
  public function __construct(
    string $appName,
    IRequest $request,
    private WikiJsClient $client
  ) {
    parent::__construct($appName, $request);
  }

  /**
   * @NoAdminRequired
   * @NoCSRFRequired
   */
  public function changes(): DataResponse {
    try {
      $items = $this->client->getRecentChanges();
      return new DataResponse([
        'ok' => true,
        'items' => $items,
      ]);
    } catch (\Throwable $e) {
      return new DataResponse([
        'ok' => false,
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
