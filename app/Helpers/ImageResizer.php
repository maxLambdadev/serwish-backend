<?php

namespace App\Helpers;

use Gumlet\ImageResize;

class ImageResizer
{

    protected ImageResize $imageResize;
    protected string $filePath;

    protected bool $rawPath;

    protected float $resizeMultiplier = 1.5;
    protected $thumbs = [
        'thumb610x510'  => [
            "width" => 610,
            "height" => 510
        ],
        'thumb366x228' => [
            "width" => 366,
            "height" => 228
        ],
        'thumb228x228' => [
            "width" => 228,
            "height" => 228
        ],
        'thumb228x176' => [
            "width" => 228,
            "height" => 176
        ],
        'thumb106x106' => [
            "width" => 106,
            "height" => 106
        ],
        'thumb98x98' => [
            "width" => 98,
            "height" => 98
        ]
    ];

    public function __construct(string $filePath, bool $rawPath = false){

        $this->filePath = $filePath;
        $this->rawPath = $rawPath;
        $this->initImageResizer();
    }

    public static function cropper(string $filePath, bool $rawPath = false){
        $resizer = new self($filePath, $rawPath);
        $resizer->cropThumbs();
    }

    public function cropThumbs(){
        $this->imageResize->quality_jpg = 100;
        $this->imageResize->quality_webp = 100;
        $this->imageResize->quality_png = 9;

        [$width, $height] = $this->getDimensions();


        foreach ($this->thumbs as $for => $value){
            $this->createThumb($for, $width > $height);
        }

    }

    private function createThumb($for = 'thumb610x510', $toWidth = true){
        if (!isset($this->thumbs[$for])) dd('thumb size not exists');

        if (!$toWidth){
            $height =  $this->thumbs[$for]['height'] * $this->resizeMultiplier;
            $this->imageResize->resizeToHeight($height);
        }else{
            $width =  $this->thumbs[$for]['width'] * $this->resizeMultiplier;
            $this->imageResize->resizeToWidth($width);
        }

        $this->crop($for);
    }

    private function crop($for = 'thumb610x510'){

        $this->imageResize->freecrop(
            $this->thumbs[$for]['width'],
            $this->thumbs[$for]['height']
        );

        if ($this->rawPath){
            $this->imageResize->save($this->createThumbName($for));
        }else{
            $this->imageResize->save('storage/'.$this->createThumbName($for));
        }
    }


    private function createThumbName($for = 'thumb610x510'){
        $thumbName = str_replace('thumb','',$for);

        return $this->getNamePart().'-'.$thumbName.'.'.$this->getExtension();
    }

    private function getExtension(): string{
        $extArr =  explode('.',$this->filePath);
        return $extArr[count($extArr) - 1];
    }

    private function getNamePart(): string{
        $extArr =  explode('.',$this->filePath);
        return $extArr[0];
    }



    private function getDimensions(): array{
        return [
            $this->imageResize->getSourceWidth(),
            $this->imageResize->getSourceHeight(),
        ];
    }

    private function initImageResizer(){

        if ($this->rawPath){
            $this->imageResize = new ImageResize($this->filePath);
        }else{
            $this->imageResize = new ImageResize('storage/'.$this->filePath);
        }

    }
}
