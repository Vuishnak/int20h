<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Dingo\Api\Contract\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{

    /**
     * Обновление настроек
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $configs = Config::all();
        $data = $request->all();

        DB::beginTransaction();
        try {
            foreach ($configs as $config) {
                if ($config->value != $data['config'][$config->id]) {
                    $config->value = $data['config'][$config->id];
                    $config->save();
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Настройки успешно сохранены!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], $e->getCode());
        }
    }

}
