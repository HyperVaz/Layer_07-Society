<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StatRequest;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Models\LikedPost;
use App\Models\Post;
use App\Models\SubscriberFollowing;
use App\Models\User;
use http\Client\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNot('id', auth()->id())->get();
        $followingIds = SubscriberFollowing::where('subscriber_id', auth()->id())
            ->get('following_id')->pluck('following_id')->toArray();

        foreach ($users as $user) {
            if (in_array($user->id, $followingIds)) {
                $user->is_followed = true;
            }
        }

        return UserResource::collection($users);
    }

    public function post(User $user)
    {
        $posts = $user->posts()->withCount('repostedByPosts')->latest()->get();
        $posts = $this->prepareLikedPosts($posts);
        return \App\Http\Resources\Post\PostResource::collection($user->posts);
    }

    public function toggleFollowing(User $user)
    {
        $res = auth()->user()->followings()->toggle($user->id);
        $data['is_followed'] = count($res['attached']) > 0;
        return $data;

    }

    public function followingPosts()
    {
        $followedIds = auth()->user()->followings()->latest()->get()->pluck('id')
                ->toArray();
        $likedPostIds = LikedPost::where('user_id', auth()->id())->get('post_id')->pluck('post_id')->toArray();
        $posts = Post::whereIn('user_id', $followedIds)->withCount('repostedByPosts')->whereNotIn('id', $likedPostIds)->get();

        return PostResource::collection($posts);

    }

    private function prepareLikedPosts($posts)
    {
        $likedPostIds = LikedPost::where('user_id', auth()->id())->pluck('post_id')->toArray();
        foreach ($posts as $post) {
            if (in_array($post->id, $likedPostIds)) {
                $post->is_liked = true;
            }
        }
            return $posts;

    }

    public function stat(StatRequest $request)
    {
        $data = $request->validated();
        $userId = isset($data['user_id']) ? $data['user_id'] : auth()->id();

        $result = [];
        $result['subscribers_count'] = SubscriberFollowing::where('following_id', $userId)->count();
        $result['followings_count'] = SubscriberFollowing::where('subscriber_id', $userId)->count();
        $result['likes_count'] = LikedPost::where('user_id', $userId)->count();

        $postIds = Post::where('user_id', $userId)->get('id')->pluck('id')->toArray();
        $result['likes_count'] = LikedPost::whereIn('post_id', $postIds)->count();
        $result['posts_count'] = count($postIds);

        return response()->json(['data' => $result]);


    }

    public function uploadAvatar(Request $request, $userId)
    {
        $request->validateImage([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Проверьте тип и размер
        ]);

        $user = User::findOrFail($userId);

        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = 'avatars/' . $user->id;
            $disk = 'public';


            // Сохраняем изображение с использованием Storage::disk
            Storage::disk($disk)->putFileAs($path, $image, $filename);

            // Удаляем предыдущий аватар, если он существует
            if ($user->avatar) {
                Storage::disk($user->avatar->disk)->delete($user->avatar->path . '/' . $user->avatar->filename);
                $user->avatar()->delete();
            }


            //  Создание аватара
            $avatar = new \App\Models\Avatar([
                'filename' => $filename,
                'path' => $path,
                'disk' => $disk,
            ]);

            $user->avatar()->save($avatar);

            // Возвращаем URL нового аватара (для обновления в frontend)
            return response()->json(['avatar_url' => Storage::disk($disk)->url($path . '/' . $filename)], 200);
        }

        return response()->json(['message' => 'Ошибка загрузки аватара'], 400);
    }
}

