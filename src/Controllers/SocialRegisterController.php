<?php
namespace Properos\Users\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Properos\Users\Classes\CSocialAccounts;

class SocialRegisterController extends Controller
{

    public function redirectToFacebookProvider()
    {
	$socialite = Socialite::driver('facebook');
    $socialite->redirectUrl($this->URL('/auth/facebook/callback'));
	return  $socialite->redirect();
    }

    public function redirectToGoogleProvider()
    {
	$socialite = Socialite::driver('google');
    $socialite->redirectUrl($this->URL('/auth/google/callback'));
	return  $socialite->redirect();
    }

    public function handleFacebookProviderCallback()
    {
        $provider = 'facebook';
        try {
            $socialite = Socialite::driver('facebook');
            $socialite->redirectUrl($this->URL('/auth/facebook/callback'));
            $cSocialAccount = new CSocialAccounts();
            $user = $cSocialAccount->GetOrCreateUser($socialite->user(), $provider);
            auth()->login($user);
            return redirect()->intended($this->redirectPath());
        } catch (Exception $e) {
            return redirect('/');
        }
    }

    public function handleGoogleProviderCallback()
    {
        $provider = 'google';
        try {
            $socialite = Socialite::driver('google');
            $socialite->redirectUrl($this->URL('/auth/google/callback'));
            $cSocialAccount = new CSocialAccounts();
            $user = $cSocialAccount->GetOrCreateUser($socialite->user(), $provider);
            auth()->login($user);
            return redirect()->intended($this->redirectPath());
        } catch (Exception $e) {
            return redirect('/');
        }
    }
    
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }

    private function URL($path) 
    {
       return sprintf( "%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'], $path );
    }
}
