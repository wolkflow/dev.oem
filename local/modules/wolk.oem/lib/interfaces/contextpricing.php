<?php

namespace Wolk\OEM\Interfaces;

interface ContextPricing
{
    public function getContextPrice(\Wolk\OEM\Context $context);
}