<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 8/31/2015
 * Time: 11:23 AM
 */
class Qsoft_LandingImage_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}