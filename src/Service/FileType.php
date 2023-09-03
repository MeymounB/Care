<?php

namespace App\Service;

enum FileType: string
{
    case CERTIFICATE = 'certificates';
    case PHOTO = 'photos';
}
