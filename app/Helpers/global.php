<?php

if (!function_exists('formatRupiah')) {
  function formatRupiah($amount)
  {
    return number_format($amount, 0, ',', '.');
  }
}
