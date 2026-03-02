<?php
declare(strict_types=1);

return [
  'routes' => [
    [
      'name' => 'api#changes',
      'url' => '/api/changes',
      'verb' => 'GET',
    ],
    [
      'name' => 'settings#saveAdmin',
      'url' => '/settings/admin',
      'verb' => 'POST',
    ],
  ],
];
