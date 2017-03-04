<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Dingo\Api\Contract\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    /**
     * Получаем все посты
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPosts()
    {
        try {
            $models = Post::all();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }

        return response()->json($models, 200);
    }

    /**
     * Добавление нового поста
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        return response()->json([
            'data' => $data
        ]);
        DB::beginTransaction();
        try {
            if (!isset($data['posts'])) {
                $model = Post::create($data);
                return response()->json([
                    'message' => 'Пост #' . $model->id . ' был успешно добален!',
                ], 200);
            } else {
                $count = count($data['posts']);
                Post::insert($data['posts']);
                return response()->json([
                    'message' => 'Добавлено ' . $count . ' постов!',
                ], 200);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Обновление тега
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $model = Post::find($id);
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
            'message' => 'Пост #' . $model->id . ' был успешно обновлен!',
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
            $model = Post::find($id);
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
            'message' => 'Пост #' . $id . ' был успешно удален!',
        ], 200);
    }

}
