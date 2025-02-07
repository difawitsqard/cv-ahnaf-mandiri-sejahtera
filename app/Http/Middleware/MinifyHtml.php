<?php

namespace App\Http\Middleware;

use Closure;
use voku\helper\HtmlMin;

class MinifyHtml
{
  protected $htmlMin;

  public function __construct()
  {
    $this->htmlMin = new HtmlMin();
  }

  public function handle($request, Closure $next)
  {
    $response = $next($request);

    if ($response->isSuccessful() && $response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
      $output = $response->getContent();
      $output = $this->htmlMin->minify($output);
      $response->setContent($output);
    }

    return $response;
  }
}
