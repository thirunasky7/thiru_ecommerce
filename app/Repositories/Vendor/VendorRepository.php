<?php

namespace App\Repositories\Vendor;

use App\Repositories\Vendor\VendorRepositoryInterface;
use App\Models\Vendor;

class VendorRepository implements VendorRepositoryInterface
{
    public function findByEmail(string $email)
    {
        return Vendor::where('email', $email)->first();
    }
}
