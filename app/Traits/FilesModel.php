<?php


namespace App\Traits;
use App\Models\File;

trait FilesModel
{

    public function getFilesAttribute(){
        return $this->files();
    }

    public function files(){
        return File::where(['model' => $this->getModelAttribute(),  'model_id' => $this->id ])->get();
    }

    public function getModelAttribute(){
        return str_replace('\\', '/', get_class($this));
    }

}
