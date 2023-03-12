<?php

namespace App\Services;

use App\Helpers\Params\GetListParams;
use App\Helpers\Params\PagenavigationParams;
use App\Repositories\EventsIblockRepository;
use App\Repositories\Shared\RepositoryResult;
use App\Services\Shared\AbstractServiceWithRepository;
use App\Traits\SingletonTrait;

/**
 * @method EventsIblockService getRepository()
 */
class EventsIblockService extends AbstractServiceWithRepository
{
    use SingletonTrait;

    public function repositoryClass(): string
    {
        return EventsIblockRepository::class;
    }

    public function getListPaginated(GetListParams $getListParams, PagenavigationParams $pagenavigationParams): RepositoryResult
    {
        return $this->getRepository()->getListPaginated($getListParams, $pagenavigationParams);
    }
}
