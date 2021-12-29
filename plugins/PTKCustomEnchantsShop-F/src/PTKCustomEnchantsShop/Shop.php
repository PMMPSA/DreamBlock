<?php

namespace PTKCustomEnchantsShop;

/**
 * Class Shop
 * @package PTKCustomEnchantsShop
 */
class Shop
{
    private $x;
    private $y;
    private $z;
    private $enchantment;
    private $level;
    private $price;

    /**
     * Shop constructor.
     * @param int $x
     * @param int $y
     * @param int $z
     * @param string $enchantment
     * @param int $level
     * @param int $price
     */
    public function __construct($x,  $y,  $z,  $enchantment,  $level,  $price)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->enchantment = $enchantment;
        $this->level = $level;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getZ()
    {
        return $this->z;
    }

    /**
     * @return string
     */
    public function getEnchantment()
    {
        return $this->enchantment;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }
}