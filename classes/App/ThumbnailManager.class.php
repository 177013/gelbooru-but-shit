<?php

namespace App;

use image;

final class ThumbnailManager {
    /** @var string */
    private $root;
    /** @var string */
    private $thumbnailFolder;

    /**
     * ThumbnailManager constructor.
     * @param string $root
     * @param string $thumbnailFolder
     */
    public function __construct(string $root, string $thumbnailFolder) {
        $this->root = $root;
        $this->thumbnailFolder = $thumbnailFolder;
    }

    /**
     * @param string $imagePath absolute or relative-to-the-project-root path to the image file
     * @return string relative path to the (possibly) newly generated thumbnail
     */
    public function makeIfNeeded(string $imagePath): string {
        return $this->getThumbnail($imagePath) ?: $this->makeThumbnail($imagePath);
    }

    public function getThumbnail(string $imagePath): ?string {
        $relativeToRoot = $this->makeRelative($imagePath);

        $relativeToImageFolder = str_replace('images/1/', '', $relativeToRoot);

        $filename = basename($relativeToImageFolder);
        $folder = rtrim($relativeToImageFolder, $filename);

        $thumbnail = $this->thumbnailFolder . '/' . $folder . '/thumbnail_' . $filename;
        $absoluteThumbnail = $this->root . '/' . $thumbnail;

        return file_exists($absoluteThumbnail) && !is_dir($absoluteThumbnail)
            ? $thumbnail
            : null;
    }

    private function makeRelative(string $path): string {
        return ltrim(str_replace($this->root, '', $path), '/');
    }

    public function makeThumbnail(string $imagePath): string {
        $relative = str_replace(PUBLIC_ROOT, '', $imagePath);
        $thumbnailFolder = THUMBNAIL_PATH;

        ['dirname' => $folder] = pathinfo($this->root . '/' . $relative);
        $relativeFolder = str_replace($this->root, '', $folder);

        $image = new image();
        if (!is_dir($thumbnailFolder . '/' . $relativeFolder)) {
            $image->makethumbnailfolder($relativeFolder);
        }

        return $this->makeRelative($image->thumbnail($relative));
    }
}
