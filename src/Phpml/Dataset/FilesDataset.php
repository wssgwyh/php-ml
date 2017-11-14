<?php

declare(strict_types=1);

namespace Phpml\Dataset;

use Phpml\Exception\DatasetException;

class FilesDataset extends ArrayDataset
{
    public function __construct(string $rootPath)
    {
        if (!is_dir($rootPath)) {
            throw DatasetException::missingFolder($rootPath);
        }

        $this->scanRootPath($rootPath);
    }

    private function scanRootPath(string $rootPath)
    {
        foreach (glob($rootPath.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) as $dir) {
            $this->scanDir($dir);
        }
    }

    private function scanDir(string $dir)
    {
        $target = basename($dir);

        foreach (array_filter(glob($dir.DIRECTORY_SEPARATOR.'*'), 'is_file') as $file) {
            $this->samples[] = [file_get_contents($file)];
            $this->targets[] = $target;
        }
    }
}
