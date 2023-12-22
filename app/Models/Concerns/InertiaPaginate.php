<?php

namespace App\Models\Concerns;

use Illuminate\Pagination\LengthAwarePaginator;

trait InertiaPaginate
{
    public function inertiaPaginate(...$args) {
        /**
         * Type hinting to workaround ide-helper bug
         * Can't change chain the methods without false positive errors thrown errors
         *
         * @var LengthAwarePaginator
         */
        $paginate = $this->paginate($args);
        $paginate = $paginate->toArray();
        $data = $paginate['data'];
        unset($paginate['data']);
        return [$data, $paginate];
    }

}
