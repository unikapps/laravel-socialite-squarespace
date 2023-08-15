<?php

namespace Unikapps\LaravelSocialiteSquarespace;

use SocialiteProviders\Manager\SocialiteWasCalled;

class SquarespaceExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('squarespace', Provider::class);
    }
}
