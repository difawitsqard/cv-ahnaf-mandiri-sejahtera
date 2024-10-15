<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

class ImageUploadService
{
  /**
   * Upload image to the specified directory.
   *
   * @param UploadedFile $image
   * @param string $directory
   * @return string
   */
  public function uploadImage(UploadedFile $image, $directory = '')
  {
    $baseDirectory = 'uploads/images/';
    $fullDirectory = $baseDirectory . $directory;

    $imageName = md5(time() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
    $destinationPath = public_path($fullDirectory);

    // Periksa apakah folder tujuan ada, jika tidak buat foldernya
    if (!File::exists($destinationPath)) {
      File::makeDirectory($destinationPath, 0755, true);
    }

    $image->move($destinationPath, $imageName);

    return 'images/' . ($directory ? $directory . '/' : '') . $imageName;
  }


  /**
   * Delete image from the specified directory.
   *
   * @param string $imagePath
   * @return void
   */
  public function deleteImage($imagePath)
  {
    $fullPath = public_path('uploads/' . $imagePath);

    if (File::exists($fullPath)) {
      File::delete($fullPath);
    }
  }
}
