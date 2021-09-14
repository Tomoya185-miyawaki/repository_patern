<?php

/**
 * コントローラ
 */
class UserController extends Controller
{
    public function show(UserService $userService)
    {
        $user = $userService->getUserByToken('token');
        // viewを返す
    }
}

/**
 * サービスクラス（ビジネスロジックを書くところ）
 */
class UserService
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function getUserByToken(string $token)
    {
        return $this->userRepository->findUserByToken($token);
    }
}

/**
 * インターフェース（抽象）
 */
interface UserRepository
{
    public function findUserByToken(string $token): User;
}

/**
 * 具象クラス（開発用）
 */
class DummyUserRepository implements UserRepository
{
    public function findUserByToken(string $token): User
    {
        // ダミーのユーザーを返す
    }
}

/**
 * 具象クラス（本番用）
 */
class UserAPIRepository implements UserRepository
{
    public function findUserByToken(string $token): User
    {
        // 外部APIに接続して、ユーザオブジェクトを返す
    }
}

/**
 * サービスプロパイダー
 */
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // 開発環境の場合はダミーデータ用のRepositoryに向ける
        if (App::environment('development')) {
            $this->app->bind(UserRepository::class, DummyUserRepository::class);
        } else {
            $this->app->bind(UserRepository::class, UserAPIRepository::class);
        }
    }
}