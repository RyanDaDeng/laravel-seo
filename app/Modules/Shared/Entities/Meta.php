<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 7/12/18
 * Time: 10:57 AM
 */

namespace App\Modules\Shared\Entities;


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
     * @return array
     */
    public static function rules()
    {
        return [
            'title' => 'string|required',
            'description' => 'string|required',
            'canonical' => 'string|required'
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Meta
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
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
     * @return Meta
     */
    public function setCanonical(string $canonical): self
    {
        $this->canonical = $canonical;
        return $this;
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
     * @return Meta
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
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