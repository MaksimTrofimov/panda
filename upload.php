<?php

if ($file = $_FILES['file']) {

    $saveDir = [
        'urnOriginal' => 'original',
        'urnModified' => 'modified'
    ];

    $pathRoot = $_SERVER['DOCUMENT_ROOT'];
    $basename = basename($file['name']);

    foreach ($saveDir  as $key => $dir) {
        $$key = '/img/' . $dir . '/' . $basename;
    }

    $pathOriginal = $pathRoot . $urnOriginal;
    $pathModified = $pathRoot . $urnModified;

    $uploaded = uploaded($file, $pathOriginal, $pathModified);

    $type = substr(strrchr($file['type'], "/"), 1);

    if ($uploaded) {
        gd($pathModified, $type);
    }


    $response = [
        'status' => $uploaded ? 'success' : 'fail',
        'urlOriginal' => $urnOriginal,
        'urlModified' => $urnModified,
    ];
    echo json_encode($response);
}

function uploaded($file, $pathOriginal, $pathModified) {

    if (move_uploaded_file($file['tmp_name'], $pathOriginal)) {
        copy($pathOriginal, $pathModified);
        return true;
    }
}


function gd($pathModified, $type) {

    if ($type == 'jpeg' || $type == 'png') {
        if ($type == 'jpeg') {
            $im = imagecreatefromjpeg($pathModified);
        } else {
            $im = imagecreatefrompng($pathModified);
        }

        imagefilter($im, IMG_FILTER_COLORIZE, 0, 255, 0);
        // Сначала создаем наше изображение штампа вручную с помощью GD
        $stamp = imagecreatetruecolor(100, 70);
        imagefilledrectangle($stamp, 0, 0, 99, 69, 0x0000FF);
        imagefilledrectangle($stamp, 9, 9, 90, 60, 0xFFFFFF);
        imagestring($stamp, 5, 20, 20, 'PANDA', 0x0000FF);
        imagestring($stamp, 3, 20, 40, '(c) 2020', 0x0000FF);

        // Установка полей для штампа и получение высоты/ширины штампа
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

        // Слияние штампа с фотографией. Прозрачность 50%
        imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 50);

        // Сохранение фотографии в файл и освобождение памяти
        imagepng($im, $pathModified);
        imagedestroy($im);
    }
}