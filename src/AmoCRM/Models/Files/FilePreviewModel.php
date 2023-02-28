<?php

declare(strict_types=1);

namespace AmoCRM\Models;

/**
 * @since Release Spring 2022
 */
class FilePreviewModel extends BaseApiModel
{
    /**
     * @var string ссылка для загрузки превью
     */
    protected $downloadLink;

    /**
     * @var int ширина
     */
    protected $width;

    /**
     * @var int высота
     */
    protected $height;

    /**
     * @param string $downloadLink
     */
    public function setDownloadLink(string $downloadLink): void
    {
        $this->downloadLink = $downloadLink;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getDownloadLink(): string
    {
        return $this->downloadLink;
    }

    /**
     * @return int
     */
    public function getWidth(): int 
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int 
    {
        return $this->height;
    }

    /**
     * @param array $previews
     *
     * @return static
     */
    public static function fromArray(array $previews): self
    {
        $preview = new self();
        $preview->setDownloadLink($previews[0]['download_link']);
        $preview->setWidth($previews[0]['width']);
        $preview->setHeight($previews[0]['height']);

        return $preview;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            [
                'download_link' => $this->getDownloadLink(),
                'width' => $this->getWidth(),
                'height' => $this->getHeight(),
            ]
        ];
    }

    /**
     * @param string|null $requestId
     *
     * @return array
     */
    public function toApi(string $requestId = null): array
    {
        return $this->toArray();
    }
}
