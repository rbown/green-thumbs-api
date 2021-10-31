<?php

namespace App\Services;

class PlantService {

    public function handleFileUpload($file = null){
        if ($file){
            return $file->store('uploads', 'public');
        }

        return null;
    }

}
