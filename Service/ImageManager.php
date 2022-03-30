<?php
declare(strict_types=1);
/**
 * @author    Trellis Team
 * @copyright Copyright © 2021 Trellis (https://www.trellis.co)
 */

namespace Trellis\Compliance\Service;

use Magento\Framework\Module\Dir;

class ImageManager
{
    const IMAGE_NAME = 'prop-65-warning.png';

    private Dir $directory;

    public function __construct(Dir $directory)
    {
        $this->directory = $directory;
    }

    public function getImageTag(): string
    {
        return "<img class=\"warning-message\" src=\"{$this->getBase64ImageSrc()}\">";
    }

    private function getBase64ImageSrc(): string
    {
        $path = $this->getModuleEtcDir() . '/src/' . self::IMAGE_NAME;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    public function getModuleEtcDir(): string
    {
        return $this->directory->getDir(
            'Trellis_Compliance', Dir::MODULE_ETC_DIR
        );
    }
}
