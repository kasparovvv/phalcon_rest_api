<?php

class Product extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $product_name;

    /**
     *
     * @var integer
     */
    protected $product_status;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field product_name
     *
     * @param string $product_name
     * @return $this
     */
    public function setProductName($product_name)
    {
        $this->product_name = $product_name;

        return $this;
    }

    /**
     * Method to set the value of field product_status
     *
     * @param integer $product_status
     * @return $this
     */
    public function setProductStatus($product_status)
    {
        $this->product_status = $product_status;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field product_name
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Returns the value of field product_status
     *
     * @return integer
     */
    public function getProductStatus()
    {
        return $this->product_status;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("PHALCON_REST_API");
        $this->setSource("product");


        $this->hasManyToMany('id', OrderHasProduct::class, 'productId', 'orderId', Orders::class, 'id', 
        [
            'alias' => 'orders',
            'params'   => [
                'conditions' => 'ohp_status = :ohp_status:',
                'bind'       => [
                    'ohp_status' => 1,
                ]
            ]
            
        ]);


    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product[]|Product|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
