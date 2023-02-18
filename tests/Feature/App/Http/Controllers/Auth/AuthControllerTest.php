<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    //Без этой приписки тест не будет запускаться
    /**
     * @test
     * @return void
     */
    public function it_login_page_success() : void
    {
        $this->get(action([SignInController::class, 'page']))
            //Возвращает статус 200
            ->assertOk()
            //Проверка title
            ->assertSee('Вход в аккаунт')
            //Проверка view
            ->assertViewIs('auth.login');
    }

    /**
     * @test
     * @return void
     */
    public function it_sign_up_page_success() : void
    {
        $this->get(action([SignUpController::class, 'page']))
            //Возвращает статус 200
            ->assertOk()
            //Проверка title
            ->assertSee('Регистрация')
            //Проверка view
            ->assertViewIs('auth.sign-up');
    }

    /**
     * @test
     * @return void
     */
    public function it_forgot_page_success() : void
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
            //Возвращает статус 200
            ->assertOk()
            //Проверка title
            ->assertSee('Забыли пароль')
            //Проверка view
            ->assertViewIs('auth.forgot-password');
    }

    /**
     * @test
     * @return void
     */
    //Проверка аутентификации пользователся
    public function it_sign_in_success() : void
    {
        $password ='12345678';
        $user = UserFactory::new()->create([
            'email' => 'testing@mail.ru',
            'password' =>bcrypt($password),
        ]);
        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->post(action([SignInController::class,'handle']), $request);

        $response
            // Проверка всей валидации
            ->assertValid()
            ->assertRedirect(route('home'));

        //Проверка, что пользователь авторизован
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     * @return void
     */
    //Проверка аутентификации пользователся
    public function it_logout_success() : void
    {
        $user = UserFactory::new()->create([
            'email' => 'testing@mail.ru',
        ]);

        // Пользователь авторизован
        $this->actingAs($user)
            // Экшн на логаут из авторизованного пользователя
            ->delete(action([SignInController::class, 'logOut']));

        //Проверка, что мы теперь гость
        $this->assertGuest();
    }

    /**
     * @test
     * @return void
     */
    public function is_sign_up_success() : void
    {
        // Проверка отработки событий
        Notification::fake();
        Event::fake();

        // Проверка создания пользователя
        $request = SignUpFormRequest::factory()->create([
            'email' => 'testing@mail.ru',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        // Проверка, что в бд нету такого емаил
        $this->assertDatabaseMissing('users', [
            'email' => $request['email'],
        ]);

        // Проверка работы метода
        $response = $this->post(
            action([SignUpController::class,'handle']),
            $request
        );

        // Проверка всей валидации, до создания пользователя
        $response->assertValid();

        // Что в базе есть пользователь
        $this->assertDatabaseHas('users', [
            'email' => $request['email'],
        ]);

        //Проверка, что эвент выполняется
        Event::assertDispatched(Registered::class);
        //Проверка вызова Listener у эвента
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        //Проверка отправки уведомлений (сначала вытаскиваем из бд пользователя, которому идут уведомления)
        $user = User::query()->where('email', $request['email'])->first();

        //Т.к. у нас уведомления в очередях, для тестов вызываем их вручную
        $event = new Registered($user);
        $listener = new SendEmailNewUserListener();
        $listener->handle($event);

        Notification::assertSentTo($user, NewUserNotification::class);

        //Проверка, что пользователь авторизован
        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('home'));
    }
}
