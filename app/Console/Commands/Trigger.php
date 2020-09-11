<?php

namespace App\Console\Commands;

use App\Services\Trigger\ConditionService;
use Illuminate\Console\Command;

class Trigger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trigger:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ckeck and update all triggers';

    protected ConditionService $conditionService;

    /**
     * Campaign constructor.
     *
     * @param ConditionService $conditionService
     */
    public function __construct(ConditionService $conditionService)
    {
        parent::__construct();

        $this->conditionService = $conditionService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \App\Exceptions\MessageException
     */
    public function handle()
    {
        /*
         * У нас имеется иерархия из 3х сущностей Map <- 1:M -- Group <- 1:M -- Condition
         * Проверяем значение каждого состояния, на основании этого проводим
         * логические операции для группы и для проекта в целом. Именно в таком порядке,
         * из конца в начало
         */

        $this->conditionService->updateAllStatuses();
    }
}
