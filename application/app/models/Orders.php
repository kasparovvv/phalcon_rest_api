<?php

class Orders extends \Phalcon\Mvc\Model
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
    protected $orderCode;

    /**
     *
     * @var integer
     */
    protected $productId;

    /**
     *
     * @var string
     */
    protected $address;

    /**
     *
     * @var string
     */
    protected $shippingDate;

    /**
     *
     * @var integer
     */
    protected $customerId;

    /**
     *
     * @var integer
     */
    protected $order_status;

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
     * Method to set the value of field orderCode
     *
     * @param string $orderCode
     * @return $this
     */
    public function setOrderCode($orderCode)
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    /**
     * Method to set the value of field productId
     *
     * @param integer $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Method to set the value of field address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Method to set the value of field shippingDate
     *
     * @param string $shippingDate
     * @return $this
     */
    public function setShippingDate($shippingDate)
    {
        $this->shippingDate = $shippingDate;

        return $this;
    }

    /**
     * Method to set the value of field customerId
     *
     * @param integer $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Method to set the value of field order_status
     *
     * @param integer $order_status
     * @return $this
     */
    public function setOrderStatus($order_status)
    {
        $this->order_status = $order_status;

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
     * Returns the value of field orderCode
     *
     * @return string
     */
    public function getOrderCode()
    {
        return $this->orderCode;
    }

    /**
     * Returns the value of field productId
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Returns the value of field address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Returns the value of field shippingDate
     *
     * @return string
     */
    public function getShippingDate()
    {
        return $this->shippingDate;
    }

    /**
     * Returns the value of field customerId
     *
     * @return integer
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Returns the value of field order_status
     *
     * @return integer
     */
    public function getOrderStatus()
    {
        return $this->order_status;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("PHALCON_REST_API");
        $this->setSource("orders");

        // $this->hasManyToMany(
        //     'id',
        //     OrderHasProduct::class,
        //     'orderId',
        //     'productID',
        //     Product::class,
        //     'id',
        //     [
        //         'reusable' => true,
        //         'alias'    => 'OrderProducts'
        //     ]
        // );

        $this->hasManyToMany('id', OrderHasProduct::class, 'orderId', 'productID', Product::class, 'id', 
        [
            'alias' => 'products',
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
     * @return Order[]|Order|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Order|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
