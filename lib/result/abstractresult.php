<?php


namespace Toolbox\Core\Result;


abstract class AbstractResult implements ResultInterface
{
    protected $status;
    protected $message;
    protected $data;

    /**
     * AbstractResult constructor.
     * @param $status
     * @param $message
     * @param $data
     */
    public function __construct($status = '', $message = '', array $data = [])
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }


    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }



    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}