<?php

namespace ChinmoyBiswasPortfolio\Hooks\Handlers;

use ChinmoyBiswasPortfolio\Database\Migration;

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
