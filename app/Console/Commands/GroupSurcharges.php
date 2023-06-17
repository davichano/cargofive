<?php

namespace App\Console\Commands;

use App\Structure\Abstract\Services\SurchargesServiceInterface;
use Illuminate\Console\Command;

class GroupSurcharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'surcharges:group';
    protected SurchargesServiceInterface $surchargeService;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @param SurchargesServiceInterface $surchargeService
     */
    public function __construct(SurchargesServiceInterface $surchargeService)
    {
        parent::__construct();
        $this->surchargeService = $surchargeService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->surchargeService->group();
    }
}
