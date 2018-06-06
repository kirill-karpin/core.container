<?php


namespace Toolbox\Core\Result;


interface ResultInterface
{
    const SUCCESS = 'SUCCESS';
    const ERROR = 'ERROR';

    public function getStatus();
    public function getMessage();
    public function getData();
}