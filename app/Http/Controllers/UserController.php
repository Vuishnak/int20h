<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dingo\Api\Contract\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * Получаем всех пользователей
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers()
    {
        try {
            $models = User::all();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }

        return response()->json($models, 200);
    }

    /**
     * Добавление нового модератора
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $model = User::create($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }

        return response()->json([
            'message' => 'Пользователь #' . $model->id . ' был успешно добален!',
        ], 200);
    }

    /**
     * Обновление данных пользователя
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $model = User::find($id);
            $model->fill($data)->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }

        return response()->json([
            'message' => 'Данные пользователя #' . $id . ' были успешно обновлены!',
        ], 200);
    }

    /**
     * Удаление пользователя
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $model = User::find($id);
            $model->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }

        return response()->json([
            'message' => 'Пользователь #' . $id . ' был успешно удален!',
        ], 200);
    }

}
