<?php

namespace Modules\Rbac\Services\Actions;

use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

abstract class ActionsService
{
    public function handle()
    {
        // start transaction
        DB::beginTransaction();

        try {
            // handle register new applicant
            $saved = $this->save();

            // commit
            DB::commit();

            return $saved;
        } catch (Throwable $exception) {
            // rollback
            DB::rollBack();

            // throw error
            throw new Exception($exception->getMessage());
        }
    }

    public abstract function save();
}
