<?php

namespace ChinmoyBiswas\CBPortfolio\Hooks\Handlers;

use ChinmoyBiswas\CBPortfolio\Database\Migration;

if (! defined('ABSPATH')) {
    exit;
}
class ActivationHandler
{
    public function handle()
    {
        $this->createTables();
    }

    private function createTables()
    {
        Migration::createPortfolioTable();
        Migration::createExperienceTable();
        Migration::createProjectsTable();
    }
}
