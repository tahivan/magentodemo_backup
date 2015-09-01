<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 8/25/2015
 * Time: 10:09 AM
 */
class Qsoft_Cms_Block_Block extends Mage_Cms_Block_Block
{
    /**
     * If this block has a block id, use that as the cache key.
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        if ($this->getBlockId()) {
            return array(
                Mage_Cms_Model_Block::CACHE_TAG,
                Mage::app()->getStore()->getId(),
                $this->getBlockId(),
                (int)Mage::app()->getStore()->isCurrentlySecure()
            );
        } else {
            return parent::getCacheKeyInfo();
        }
    }
}