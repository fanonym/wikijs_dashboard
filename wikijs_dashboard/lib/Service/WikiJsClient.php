<?php
declare(strict_types=1);

namespace OCA\WikiJsDashboard\Service;

use OCP\Http\Client\IClientService;
use OCP\IConfig;
use Psr\Log\LoggerInterface;

class WikiJsClient {
  public function __construct(
    private IClientService $clientService,
    private IConfig $config,
    private LoggerInterface $logger
  ) {}

  private function postGraphQL(string $endpoint, string $token, string $payload): array {
    $client = $this->clientService->newClient();
    $resp = $client->post($endpoint, [
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
      ],
      'body' => $payload,
      'timeout' => 15,
      'connect_timeout' => 5,
      // Hard-disable proxy for this call; in some deployments a global proxy causes 403/HTML interstitials.
      'proxy' => false,
    ]);

    $body = (string)$resp->getBody();

    try {
      $json = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) {
      $snippet = trim(preg_replace('/\s+/', ' ', strip_tags(substr($body, 0, 400))));
      throw new \RuntimeException(
        'Wiki.js API returned non-JSON response (possible proxy/firewall). HTTP '
          . $resp->getStatusCode() . ' | ' . $snippet,
        0,
        $e
      );
    }

    if (!empty($json['errors'])) {
      $msg = is_array($json['errors']) && isset($json['errors'][0]['message'])
        ? (string)$json['errors'][0]['message']
        : 'Wiki.js API returned errors';
      throw new \RuntimeException($msg);
    }

    return $json;
  }

  public function getRecentChanges(?int $limitOverride = null): array {
    $url = rtrim((string)$this->config->getAppValue('wikijs_dashboard', 'wikijs_url', ''), '/');
    if ($url === '') {
      throw new \RuntimeException('Missing config: wikijs_url');
    }

    $publicBase = rtrim((string)$this->config->getAppValue('wikijs_dashboard', 'wikijs_public_url', ''), '/');
    if ($publicBase === '') {
      $publicBase = $url;
    }

    $defaultLocale = trim((string)$this->config->getAppValue('wikijs_dashboard', 'wikijs_locale', ''));

    $token = (string)$this->config->getAppValue('wikijs_dashboard', 'wikijs_token', '');
    if ($token === '') {
      throw new \RuntimeException('Missing config: wikijs_token');
    }

    $limit = $limitOverride ?? (int)$this->config->getAppValue('wikijs_dashboard', 'limit', '10');
    if ($limit < 1 || $limit > 50) {
      $limit = 10;
    }

    $endpoint = $url . '/graphql';

    $query1 = <<<'GQL'
query RecentPages($limit: Int!) {
  pages {
    list(orderBy: UPDATED, orderByDirection: DESC, limit: $limit) {
      title
      updatedAt
      path
      locale
    }
  }
}
GQL;

    $payload1 = json_encode([
      'query' => $query1,
      'variables' => ['limit' => $limit],
    ], JSON_THROW_ON_ERROR);

    try {
      $json = $this->postGraphQL($endpoint, $token, $payload1);
      $list = $json['data']['pages']['list'] ?? [];
      if (!is_array($list)) {
        $list = [];
      }
    } catch (\Throwable $e) {
      $this->logger->warning('WikiJsDashboard: primary GraphQL query failed, using fallback. ' . $e->getMessage(), ['app' => 'wikijs_dashboard']);

      $query2 = <<<'GQL'
query PagesFallback($limit: Int!) {
  pages {
    list(limit: $limit) {
      title
      path
    }
  }
}
GQL;

      $payload2 = json_encode([
        'query' => $query2,
        'variables' => ['limit' => $limit],
      ], JSON_THROW_ON_ERROR);

      $json2 = $this->postGraphQL($endpoint, $token, $payload2);
      $list = $json2['data']['pages']['list'] ?? [];
      if (!is_array($list)) {
        $list = [];
      }
    }

    $items = [];
    foreach ($list as $row) {
      if (!is_array($row)) { continue; }
      $title = (string)($row['title'] ?? '');
      $updatedAt = (string)($row['updatedAt'] ?? '');
      $path = (string)($row['path'] ?? '');
      $locale = (string)($row['locale'] ?? '');

      if ($locale === '' && $defaultLocale !== '') {
        $locale = $defaultLocale;
      }

      $link = $publicBase;
      if ($locale !== '') {
        $link .= '/' . trim($locale, '/');
      }
      $link .= '/' . ltrim($path, '/');

      $items[] = [
        'title' => $title,
        'updatedAt' => $updatedAt,
        'url' => $link,
      ];
    }

    return $items;
  }

  /**
   * Compatibility alias used by some widget implementations.
   */
  public function getChanges(?int $limitOverride = null): array {
    return $this->getRecentChanges($limitOverride);
  }
}
