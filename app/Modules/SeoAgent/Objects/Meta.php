<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 7/12/18
 * Time: 10:57 AM
 */

namespace App\Modules\SeoAgent\Objects;


class Meta
{

    /**
     * @var string $title
     */
    private $title;
    /**
     * @var string $canonical
     */
    private $canonical;
    /**
     * @var string $description
     */
    private $description;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCanonical(): string
    {
        return $this->canonical;
    }

    /**
     * @param string $canonical
     */
    public function setCanonical(string $canonical): void
    {
        $this->canonical = $canonical;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        return [
            'meta' => [
                'defaults' => [
                    'title' => $this->title,
                    'description' => $this->description,
                    'canonical' => $this->canonical
                ]
            ]
        ];
    }
}