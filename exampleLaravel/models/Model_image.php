<?php

namespace App;

use App\MyVideoStreamer\MyVideoStreamer;
use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Image;

class Model_image extends Model
{
    use SoftDeletes;

    /**
     *
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function getImgUrl($image)
    {
        return !empty($image->name)
            ? url('/api'). '/' .self::getImgPath($image->model_data_id, $image->type) . $image->name
            : 'statics/avatars/model_avatar_default.png';
    }

    public static function getFullImgUrl($image)
    {
        return !empty($image->full_name)
            ? url('/api'). '/' .self::getImgPath($image->model_data_id, $image->type) . $image->full_name
            : $image->name;
    }

    public static function getImgPath($modelID, $type)
    {
        return 'images/models/' . $modelID . '/' . $type .'/';
    }

    public static function getImgStoragePath($modelID, $type)
    {
        $file_path = self::getImgPath($modelID, $type);
        if(!Storage::exists($file_path)) {
            Storage::makeDirectory($file_path);
        }
        return storage_path('app/'.$file_path);
    }

    public function saveImage($file)
    {
        $file_path = Model_image::getImgStoragePath($this->model_data_id, $this->type);
        $file_name = md5($this->model_data_id.'_'.now().'_'.rand()).'.'.$file->getClientOriginalExtension();
        $file_full_name = 'full__'.$file_name;
        $this->name = $file_name;
        $this->full_name = $file_full_name;
        if($this->orientation === 'vertical') {
            Image::make($file)
                ->resize(null, Constants::SMALL_IMAGE_VERTICAL_HEIGHT, function ($constraint) {
                    $constraint->upsize();
                    $constraint->aspectRatio();
                })
                ->save($file_path . $file_name);

            Image::make($file)
                ->resize(null, Constants::FULL_IMAGE_HEIGHT, function ($constraint) {
                    $constraint->upsize();
                    $constraint->aspectRatio();
                })
                ->save($file_path . $file_full_name);
        }
        if($this->orientation === 'horizontal') {
            Image::make($file)
                ->resize(Constants::SMALL_IMAGE_HORIZONTAL_WIDTH, null, function ($constraint) {
                    $constraint->upsize();
                    $constraint->aspectRatio();
                })
                ->save($file_path . $file_name);
            Image::make($file)
                ->resize(Constants::FULL_IMAGE_WIDTH, null, function ($constraint) {
                    $constraint->upsize();
                    $constraint->aspectRatio();
                })
                ->save($file_path . $file_full_name);
        }

        return true;
    }

    public function saveVideo($file)
    {
        $file_path = Model_image::getImgPath($this->model_data_id, $this->type);
        $file_name = md5($this->model_data_id.'_'.now().'_'.rand()).'.'.$file->getClientOriginalExtension();
        $file_full_name = 'full__'.$file_name;
        $this->name = $file_name;
        $this->full_name = $file_full_name;
        Storage::putFileAs($file_path, $file, $file_name);

        return true;
    }

    public static function getImageByName($modelID, $type, $fileName)
    {
        $file_path = Model_image::getImgStoragePath($modelID, $type);
        return Image::make($file_path. $fileName);
    }

    public static function getVideoByName($modelID, $type, $fileName)
    {
        $file_path = Model_image::getImgStoragePath($modelID, $type);
        return  MyVideoStreamer::streamFile($file_path. $fileName);
    }

    public function deleteImage()
    {
        $file_path = Model_image::getImgPath($this->model_data_id, $this->type);
        Storage::disk('local')->delete($file_path . $this->name);
        Storage::disk('local')->delete($file_path . $this->full_name);
        return true;
    }

}
