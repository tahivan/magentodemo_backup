<?php
	class SD5Qsoft_ImgGallery_IndexController extends Mage_Core_Controller_Front_Action
	{
	    public function indexAction()
	    {
	     $this->loadLayout(array('default'));
	     $this->renderLayout();
	    }
	}