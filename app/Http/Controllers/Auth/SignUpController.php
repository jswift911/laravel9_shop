<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;


class SignUpController extends Controller
{
    public function page(): Factory|View|Application|RedirectResponse
    {
        return view('auth.sign-up');
    }

    public function handle(SignUpFormRequest $request, RegisterNewUserContract $action): RedirectResponse
    {

        $dto = new NewUserDTO(
            $request->get('name'),
            $request->get('email'),
            $request->get('password'),
        );

        //NewUserDTO::make('name','email','password');

        $action(NewUserDTO::fromRequest($request));

        //Перегенерация сессии
        //$request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
