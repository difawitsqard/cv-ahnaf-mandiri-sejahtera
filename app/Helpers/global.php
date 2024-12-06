<?php

use App\Models\CompanyInfo;

if (!function_exists('formatRupiah')) {
  function formatRupiah($amount)
  {
    return number_format($amount, 0, ',', '.');
  }
}

if (!function_exists('getCompanyInfo')) {
  function getCompanyInfo()
  {
    $companyInfo = CompanyInfo::first();
    return $companyInfo;
  }
}

if (! function_exists('roleBasedRoute')) {
  function roleBasedRoute($name, $parameters = [])
  {
    /** @var \App\Models\User */
    $user = auth()->user();
    $prefix = '';

    if ($user) {
      if ($user->hasRole('superadmin')) {
        $prefix = 'outlet.';
      } elseif ($user->hasRole('admin')) {
        $prefix = 'admin.';
      } elseif ($user->hasRole('staff')) {
        $prefix = 'staff.';
      }
    }

    $routeName = $prefix . $name;
    $route = \Route::getRoutes()->getByName($routeName);

    if ($route && $route->parameterNames()) {
      if ($user && $user->hasRole('admin|staff')) {
        $parameters = array_filter($parameters, function ($key) {
          return $key !== 'outlet';
        }, ARRAY_FILTER_USE_KEY);
      }
      return route($routeName, $parameters);
    } else {
      return route($routeName);
    }
  }
}

if (! function_exists('importOnce')) {
  function importOnce($asset)
  {
    static $imported = [];

    if (!in_array($asset, $imported)) {
      $imported[] = $asset;
      return true;
    }

    return false;
  }
}
