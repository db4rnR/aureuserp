<?php

declare(strict_types=1);

namespace Webkul\Website\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Webkul\Website\Filament\Customer\Pages\Homepage;

class LogoutResponse implements \Filament\Auth\Http\Responses\Contracts\LogoutResponse
{
    public function toResponse($request): RedirectResponse
    {
        if ($request->route()->getName() === 'filament.customer.auth.logout') {
            return redirect()->route(Homepage::getRouteName());
        }

        return redirect()->route('filament.admin..');

    }
}
