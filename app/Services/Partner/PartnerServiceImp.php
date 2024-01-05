<?php

namespace App\Services\Partner;

use App\Models\Partner;
use App\Repositories\Partner\PartnerRepository;
use App\Services\Base\BaseServiceImp;

class PartnerServiceImp extends BaseServiceImp implements PartnerService {

    protected $_repository;

    public function __construct(PartnerRepository $partnerRepository)
    {
        $this->_repository = $partnerRepository;
    }

    public function all(): array
    {
        return $this->_repository->select(['id', 'name', 'youtube_link', 'description', 'doc_link', 'rick_text', 'back_link', 'description', 'app_logo', 'app_link'])->where('status', 'approved')->get()->toArray();
    }
}
