<?php

namespace App\Traits;

trait UploadTrait
{
    /**
     * Upload the imagens into folder.
     *
     * @param  string  $images
     * @param  string  $imageColumn
     * @return Array
     */
    private function imageUpload($images, $imageColumn = null)
    {
        $uploadImages = [];

        if(is_array($images)) {
            foreach ($images as $key => $image) {
                if(!is_null($imageColumn)) {
                    $uploadImages[] = [$imageColumn => $image->store('products', 'public')];
                }
            }
        } else {
            $uploadImages = $images->store('stores', 'public');
        }

        return $uploadImages;
    }
}
