<?php

namespace App\Http\Cart;
use Illuminate\Database\Eloquent\Collection;


/**
 * Class Cart
 * @package App\Http\Cart
 */
class Cart extends AbstractCart implements ItemInterface, \IteratorAggregate, \Countable{

    /**
     * @var array
     */
    protected $products;

    /**
     * @var array
     */
    protected $total;

    /**
     * @var array
     */
    protected $promotions;

    /**
     * Constructor
     */
    public function __construct(Array $products = []){
        $this->products = $products;
        $this->promotions = [];
        $this->total = 0;
    }


    /**
     * @param ItemInterface $item
     */
    public function add(ItemInterface $item){
        $this->products[] = $item;

        return $this;
    }
    /**
     * @param ItemInterface $item
     */
    public function addItems(Collection $collection){
        foreach($collection as $item){
            $this->products[] = $item;
        }
        return $this->products;
    }

    /**
     * @param ItemInterface $item
     */
    public function remove(ItemInterface $item){
        $this->products[array_search($item,$this->products)] = $item;

        return $this;
    }


    /**
     * @return array
     */
    public function clear()
    {
        $this->products = [];

        return $this->products;
    }

    /**
     * @return array
     */
    public function all(){

        return $this->products;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->products);
    }

    /**
     * @return \ArrayIterator
     */
    public function count()
    {
        return count($this->products);
    }


    /**
     * Total TTC
     * @param int $taxe
     * @return int
     */
    public function total($taxe = 1)
    {
        foreach($this as $movie){
            $this->total += $movie->getMovie()->prix;
        }

        return $this->total * $taxe;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param array $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return array
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param array $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return array
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

    /**
     * @param array $promotions
     */
    public function setPromotions($promotions)
    {
        $this->promotions = $promotions;
    }


    /**
     * @param array $total
     */
    public function emptycart()
    {
        return empty($this->products) ? true : false;

    }




}

