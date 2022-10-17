<?php

namespace Encoda\Organization\DTOs;

use Encoda\Dashboard\DTOs\BaseDTO;

class OrganizationSettingDTO extends BaseDTO
{

    public string $name;
    public string $uid;
    public string $tenantId;
    public bool $landlord;
    public string $domain;
    public string $logo;
    public bool $isActive;
    public string $dateFormat;
    public string $dateTimeFormat;
    public array $modules = [];
    public array $configs = [];
}
