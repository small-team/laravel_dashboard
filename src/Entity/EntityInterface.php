<?php namespace SmallTeam\Dashboard\Entity;

/**
 * EntityInterface
 *
 * @author Max Kovpak <max_kovpak@hotmail.com>
 * @url www.max-kovpak.com
 * @date 27.05.2015
 * */
interface EntityInterface
{
    /**
     * Get entity name
     *
     * @return string|null
     * */
    public function getName();

    /**
     * Get model name
     *
     * @return string|null
     * */
    public function getModel();

    /**
     * Get controller name
     *
     * @return string|null
     * */
    public function getController();

    /**
     * Get fields list
     *
     * @return array|null
     * */
    public function getFields();

}