<?php

namespace Webkul\Website\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Webkul\Website\Filament\Customer\Pages\Homepage;

class LogoutResponse implements Responsable
{
    public function toResponse($request): RedirectResponse
    {
        if ($request->route()->getName() == 'filament.customer.auth.logout') {
            return redirect()->route(Homepage::getRouteName());
        } else {
            return redirect()->route('filament.admin..');
        }
    }
}
