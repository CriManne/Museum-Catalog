<?php

namespace App\Service;

use App\Models\IArtifact;

interface IArtifactService extends IService
{
    /**
     * Parse the request and return the instantiated model
     * @param array $request
     *
     * @return IArtifact
     */
    public function fromRequest(array $request): IArtifact;
}