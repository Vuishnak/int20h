<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Dingo\Api\Contract\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{

    /**
     * Получаем все теги
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTags()
    {
        try {
            $models = Tag::all();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }

        return response()->json($models, 200);
    }

    /**
     * Добавление нового тега
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $model = Tag::create($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }

        return response()->json([
            'message' => 'Тег "' . $model->title . '" был успешно добален!',
        ], 200);
    }

    /**
     * Удаление тега
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $model = Tag::find($id);
            $tag = $model->title;
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
            'message' => 'Тег "' . $tag . '" был успешно удален!',
        ], 200);
    }

}
