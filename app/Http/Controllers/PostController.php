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
        DB::beginTransaction();
        try {
            if (!isset($data['posts'])) {
                $model = Post::create($data);
                DB::commit();
                return response()->json([
                    'message' => 'Пост #' . $model->id . ' был успешно добален!',
                ], 200);
            } else {
                if (sizeof($data['posts']) > 0) {
                    $count = count($data['posts']);
                    Post::insert($data['posts']);
                    DB::commit();
                    return response()->json([
                        'message' => 'Добавлено постов: ' . $count,
                    ], 200);
                }

                return response()->json([
                    'message' => 'Нет записей!',
                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], 500);
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
