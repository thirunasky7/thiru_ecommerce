<?php

namespace App\Models;

/**
 * Seller is an alias of Vendor for admin CRUD compatibility.
 * Multivendor sellers live in the vendors table.
 */
class Seller extends Vendor
{
    protected $table = 'vendors';
}
