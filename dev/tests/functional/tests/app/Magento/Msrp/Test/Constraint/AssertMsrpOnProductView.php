<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Msrp\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\InjectableFixture;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;

/**
 * Assert product MAP related data on product view page.
 */
class AssertMsrpOnProductView extends AbstractConstraint
{
    /**
     * Assert product MAP related data on product view page.
     *
     * @param CmsIndex $cmsIndex
     * @param CatalogCategoryView $catalogCategoryView
     * @param CatalogProductView $catalogProductView
     * @param InjectableFixture $product
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CatalogCategoryView $catalogCategoryView,
        CatalogProductView $catalogProductView,
        InjectableFixture $product
    ) {
        /** @var CatalogProductSimple $product */
        $cmsIndex->open();
        $cmsIndex->getTopmenu()->selectCategoryByName($product->getCategoryIds()[0]);
        $catalogCategoryView->getListProductBlock()->getProductItem($product)->open();

        $priceBlock = $catalogProductView->getViewBlock()->getPriceBlock();
        \PHPUnit_Framework_Assert::assertEquals(
            $product->getMsrp(),
            $priceBlock->getOldPrice(),
            'Displayed on Product view page MAP is incorrect'
        );
        \PHPUnit_Framework_Assert::assertFalse(
            $priceBlock->isRegularPriceVisible(),
            'Regular price on Product view page is visible and not expected.'
        );

        $catalogProductView->getMapViewBlock()->openMapBlock();
        $mapBlock = $catalogProductView->getMapViewBlock()->getMapBlock();
        \PHPUnit_Framework_Assert::assertContains(
            $product->getMsrp(),
            $mapBlock->getOldPrice(),
            'Displayed on Product view page MAP is incorrect.'
        );
        \PHPUnit_Framework_Assert::assertEquals(
            $product->getPrice(),
            $mapBlock->getActualPrice(),
            'Displayed on Product view page price is incorrect.'
        );
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return "Displayed Product MAP data on product view page is correct.";
    }
}
