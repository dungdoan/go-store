<?php

namespace App\EventListener;

class ProductListener
{
    const PRODUCT_ADDED_EVENT = 'product.added';
    const PRODUCT_UPDATED_EVENT = 'product.updated';

    public function onProductAdded(): void
    {
    }

    public function onProductUpdated(): void
    {
    }
}